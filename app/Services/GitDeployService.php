<?php

namespace App\Services;

use App\Models\ContentRevision;
use App\Models\Export;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GitDeployService
{
    protected string $repoDir;
    protected string $token;
    protected string $repo; // "owner/repo"
    protected string $baseBranch;

    public function __construct()
    {
        $this->repoDir = storage_path('app/deploy-repo');
        $this->token = Setting::get('github_deploy_token', '');
        $this->repo = Setting::get('github_deploy_repo', '');
        $this->baseBranch = Setting::get('github_deploy_branch', 'main');
    }

    /**
     * Check if deploy is configured.
     */
    public static function isConfigured(): bool
    {
        return !empty(Setting::get('github_deploy_token'))
            && !empty(Setting::get('github_deploy_repo'));
    }

    /**
     * Test GitHub connection by fetching repo info.
     *
     * @return array{success: bool, message: string}
     */
    public static function testConnection(): array
    {
        $token = Setting::get('github_deploy_token', '');
        $repo = Setting::get('github_deploy_repo', '');

        if (empty($token) || empty($repo)) {
            return ['success' => false, 'message' => 'GitHub token and repository are required.'];
        }

        try {
            $response = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/vnd.github+json'])
                ->get("https://api.github.com/repos/{$repo}");

            if ($response->successful()) {
                $data = $response->json();
                $permissions = $data['permissions'] ?? [];
                $canPush = $permissions['push'] ?? false;

                if (!$canPush) {
                    return ['success' => false, 'message' => "Connected to {$data['full_name']}, but you don't have write access."];
                }

                return [
                    'success' => true,
                    'message' => "Connected to {$data['full_name']} ({$data['visibility']}). Write access confirmed.",
                ];
            }

            if ($response->status() === 401) {
                return ['success' => false, 'message' => 'Invalid GitHub token. Please check your Personal Access Token.'];
            }

            if ($response->status() === 404) {
                return ['success' => false, 'message' => "Repository '{$repo}' not found. Check the owner/repo format and your token's access."];
            }

            return ['success' => false, 'message' => "GitHub API error: {$response->status()} — {$response->body()}"];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => "Connection failed: {$e->getMessage()}"];
        }
    }

    /**
     * Deploy an export to GitHub via branch + PR + auto-merge.
     *
     * @return array{pr_url: string, merged: bool}
     */
    public function deploy(Export $export): array
    {
        if (empty($this->token) || empty($this->repo)) {
            throw new \Exception('GitHub deploy is not configured. Go to Settings → Deploy to set up.');
        }

        if (!$export->output_path || !Storage::exists($export->output_path)) {
            throw new \Exception('Export file not found. Cannot deploy.');
        }

        $branch = 'deploy-' . now()->format('Ymd-His');

        Log::info("GitDeploy: Starting deploy for Export #{$export->id} → branch {$branch}");

        // Build change log from content revisions
        $changeLog = $this->buildChangeLog($export);

        // 1. Ensure local repo clone exists
        $this->ensureRepo();

        // 2. Sync build files from export ZIP to repo
        $this->syncBuildFiles(Storage::path($export->output_path));

        // 3. Generate firebase.json with latest CSP hash
        $this->generateFirebaseJson();

        // 4. Check if there are actual changes
        $statusResult = Process::path($this->repoDir)->run('git status --porcelain');
        if (empty(trim($statusResult->output()))) {
            Log::info("GitDeploy: No changes detected, skipping deploy.");
            return ['pr_url' => '', 'merged' => false, 'skipped' => true];
        }

        // 5. Create branch, commit, push
        $this->pushBranch($branch, $export, $changeLog);

        // 6. Create Pull Request
        $pr = $this->createPullRequest($branch, $changeLog);

        // 7. Auto-merge the PR
        $merged = $this->mergePullRequest($pr['number'], $changeLog['title']);

        // 8. Cleanup: delete remote branch after merge
        if ($merged) {
            $this->deleteRemoteBranch($branch);
        }

        return [
            'pr_url' => $pr['html_url'],
            'merged' => $merged,
        ];
    }

    /**
     * Clone the repo if it doesn't exist, or pull latest changes.
     */
    protected function ensureRepo(): void
    {
        $repoUrl = "https://x-access-token:{$this->token}@github.com/{$this->repo}.git";

        if (!File::isDirectory($this->repoDir . '/.git')) {
            // Fresh clone
            File::ensureDirectoryExists(dirname($this->repoDir));

            $result = Process::path(dirname($this->repoDir))
                ->timeout(120)
                ->run("git clone {$repoUrl} deploy-repo");

            if ($result->failed()) {
                throw new \Exception("Git clone failed: {$result->errorOutput()}");
            }

            // Configure git user
            Process::path($this->repoDir)->run('git config user.name "Defenxor CMS"');

            Log::info("GitDeploy: Cloned repo to {$this->repoDir}");
        } else {
            // Pull latest from base branch
            Process::path($this->repoDir)->run("git checkout {$this->baseBranch}");
            
            $result = Process::path($this->repoDir)
                ->timeout(60)
                ->run("git pull origin {$this->baseBranch}");

            if ($result->failed()) {
                // If pull fails, try a fresh clone
                Log::warning("GitDeploy: Pull failed, re-cloning. Error: {$result->errorOutput()}");
                File::deleteDirectory($this->repoDir);
                $this->ensureRepo();
                return;
            }

            // Update remote URL in case token changed
            Process::path($this->repoDir)->run("git remote set-url origin {$repoUrl}");

            Log::info("GitDeploy: Pulled latest from {$this->baseBranch}");
        }
    }

    /**
     * Extract export ZIP and sync all files to the repo directory.
     * Deletes old files from repo (except .git, firebase.json, .github).
     */
    protected function syncBuildFiles(string $zipPath): void
    {
        // Extract ZIP to temp directory
        $tempDir = storage_path('app/deploy-temp-' . uniqid());
        File::ensureDirectoryExists($tempDir);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Cannot open export ZIP file.');
        }
        $zip->extractTo($tempDir);
        $zip->close();

        // Remove all existing files from repo except protected dirs/files
        $protectedItems = ['.git', '.github', 'firebase.json', '.firebaserc', '.firebase', 'README.md'];

        foreach (File::files($this->repoDir) as $file) {
            if (!in_array($file->getFilename(), $protectedItems)) {
                File::delete($file->getPathname());
            }
        }

        foreach (File::directories($this->repoDir) as $dir) {
            if (!in_array(basename($dir), $protectedItems)) {
                File::deleteDirectory($dir);
            }
        }

        // Copy all extracted files to repo
        $this->copyDirectoryContents($tempDir, $this->repoDir);

        // Cleanup temp
        File::deleteDirectory($tempDir);

        Log::info("GitDeploy: Synced build files to repo directory.");
    }

    /**
     * Copy all contents from source directory to destination.
     */
    protected function copyDirectoryContents(string $source, string $destination): void
    {
        foreach (File::files($source) as $file) {
            File::copy($file->getPathname(), $destination . '/' . $file->getFilename());
        }

        foreach (File::directories($source) as $dir) {
            $dirName = basename($dir);
            $destDir = $destination . '/' . $dirName;
            File::ensureDirectoryExists($destDir);
            File::copyDirectory($dir, $destDir);
        }
    }

    /**
     * Generate firebase.json with the latest CSP hash from AnalyticsService.
     */
    protected function generateFirebaseJson(): void
    {
        $siteId = Setting::get('firebase_site_id', 'defenxor-com');
        $cspHash = AnalyticsService::getHash();

        $config = [
            'hosting' => [
                'site' => $siteId,
                'public' => '.',
                'ignore' => [
                    'firebase.json',
                    '.firebase',
                ],
                'headers' => [
                    [
                        'source' => '**',
                        'headers' => [
                            ['key' => 'X-Frame-Options', 'value' => 'SAMEORIGIN'],
                            ['key' => 'X-Content-Type-Options', 'value' => 'nosniff'],
                            ['key' => 'X-XSS-Protection', 'value' => '1; mode=block'],
                            ['key' => 'Referrer-Policy', 'value' => 'strict-origin-when-cross-origin'],
                            ['key' => 'Strict-Transport-Security', 'value' => 'max-age=63072000; includeSubDomains; preload'],
                            [
                                'key' => 'Content-Security-Policy',
                                'value' => "default-src 'self'; script-src '{$cspHash}' 'strict-dynamic' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://www.clarity.ms https://analytics.ahrefs.com https://static.cloudflareinsights.com https://challenges.cloudflare.com https://ajax.cloudflare.com https: http:; style-src 'self' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com https://*.clarity.ms https://analytics.ahrefs.com https://cloudflareinsights.com; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; frame-src 'self' https://challenges.cloudflare.com; upgrade-insecure-requests;",
                            ],
                        ],
                    ],
                ],
            ],
        ];

        File::put(
            $this->repoDir . '/firebase.json',
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        Log::info("GitDeploy: Generated firebase.json with CSP hash {$cspHash}");
    }

    /**
     * Build a change log from ContentRevision records since last deploy.
     *
     * @return array{title: string, commit_message: string, pr_body: string}
     */
    protected function buildChangeLog(Export $export): array
    {
        // Find the last deployed export to get revisions since then
        $lastDeployed = Export::where('deploy_status', 'deployed')
            ->latest('deployed_at')
            ->first();

        $since = $lastDeployed?->deployed_at ?? $export->created_at->subDays(30);

        $revisions = ContentRevision::where('created_at', '>', $since)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Build summary lines from revisions
        $lines = [];
        foreach ($revisions as $rev) {
            $lines[] = "- [{$rev->action}] {$rev->summary}";
        }

        // Collect unique item names for the title
        $itemNames = $revisions->map(function ($rev) {
            $model = $rev->revisionable;
            return $model?->title ?? $model?->name ?? null;
        })->filter()->unique()->values();

        // Also grab names from scope_details (partial exports have this)
        $scopeItems = $export->scope_details['items'] ?? [];
        if (!empty($scopeItems) && $itemNames->isEmpty()) {
            $itemNames = collect($scopeItems)->pluck('title')->filter()->unique()->values();
        }

        // Build title with actual names
        $exportType = ucfirst($export->type);

        if ($itemNames->count() <= 5 && $itemNames->isNotEmpty()) {
            // Show names directly: "Update: About, Contact, Services"
            $title = 'Update: ' . $itemNames->implode(', ');
        } else {
            // Too many — use counts
            $postCount = $revisions->where('revisionable_type', 'App\\Models\\Post')->count();
            $pageCount = $revisions->where('revisionable_type', 'App\\Models\\Page')->count();

            $parts = [];
            if ($postCount > 0) $parts[] = "{$postCount} post" . ($postCount > 1 ? 's' : '');
            if ($pageCount > 0) $parts[] = "{$pageCount} page" . ($pageCount > 1 ? 's' : '');

            $title = !empty($parts)
                ? 'Update ' . implode(', ', $parts)
                : 'Content update';
        }

        $commitMessage = "{$title} ({$exportType} Export #{$export->id})";

        // Build PR body with change details
        $prBody = "## 🚀 CMS Deploy — {$exportType} Export #{$export->id}\n\n";
        $prBody .= "**Generated at:** " . now()->format('M d, Y H:i:s') . "\n";
        $prBody .= "**Export type:** {$exportType}\n";
        $prBody .= "**Changes:** " . ($revisions->count() ?: 'N/A') . " revision(s)\n\n";

        // Items summary
        if ($itemNames->isNotEmpty()) {
            $prBody .= "### Items\n\n";
            foreach ($itemNames as $name) {
                $prBody .= "- {$name}\n";
            }
            $prBody .= "\n";
        }

        // Detailed change log
        if (!empty($lines)) {
            $prBody .= "### Change Log\n\n";
            $prBody .= implode("\n", array_slice($lines, 0, 30)) . "\n";
            if (count($lines) > 30) {
                $prBody .= "\n_...and " . (count($lines) - 30) . " more changes._\n";
            }
        }

        return [
            'title' => $commitMessage,
            'commit_message' => $commitMessage,
            'pr_body' => $prBody,
        ];
    }

    /**
     * Create a new branch, stage all changes, commit, and push.
     */
    protected function pushBranch(string $branch, Export $export, array $changeLog): void
    {
        $repoDir = $this->repoDir;

        // Create and switch to new branch
        $result = Process::path($repoDir)->run("git checkout -b {$branch}");
        if ($result->failed()) {
            throw new \Exception("Failed to create branch: {$result->errorOutput()}");
        }

        // Stage all changes
        Process::path($repoDir)->run('git add -A');

        // Commit with descriptive message
        $message = str_replace('"', '\\"', $changeLog['commit_message']);
        $result = Process::path($repoDir)->run("git commit -m \"{$message}\"");
        if ($result->failed()) {
            throw new \Exception("Git commit failed: {$result->errorOutput()}");
        }

        // Push
        $result = Process::path($repoDir)
            ->timeout(120)
            ->run("git push origin {$branch}");
        if ($result->failed()) {
            throw new \Exception("Git push failed: {$result->errorOutput()}");
        }

        Log::info("GitDeploy: Pushed branch {$branch}");
    }

    /**
     * Create a Pull Request via GitHub API.
     *
     * @return array{number: int, html_url: string}
     */
    protected function createPullRequest(string $branch, array $changeLog): array
    {
        $response = Http::withToken($this->token)
            ->withHeaders(['Accept' => 'application/vnd.github+json'])
            ->post("https://api.github.com/repos/{$this->repo}/pulls", [
                'title' => $changeLog['title'],
                'head' => $branch,
                'base' => $this->baseBranch,
                'body' => $changeLog['pr_body'],
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to create PR: {$response->status()} — {$response->body()}");
        }

        $data = $response->json();
        Log::info("GitDeploy: Created PR #{$data['number']} — {$data['html_url']}");

        return [
            'number' => $data['number'],
            'html_url' => $data['html_url'],
        ];
    }

    /**
     * Merge a Pull Request via GitHub API.
     */
    protected function mergePullRequest(int $prNumber, string $commitTitle): bool
    {
        $response = Http::withToken($this->token)
            ->withHeaders(['Accept' => 'application/vnd.github+json'])
            ->put("https://api.github.com/repos/{$this->repo}/pulls/{$prNumber}/merge", [
                'merge_method' => 'squash',
                'commit_title' => $commitTitle,
            ]);

        if ($response->successful()) {
            Log::info("GitDeploy: Merged PR #{$prNumber}");
            return true;
        }

        Log::warning("GitDeploy: Could not auto-merge PR #{$prNumber}: {$response->status()} — {$response->body()}");
        return false;
    }

    /**
     * Delete a remote branch after successful merge.
     */
    protected function deleteRemoteBranch(string $branch): void
    {
        $response = Http::withToken($this->token)
            ->withHeaders(['Accept' => 'application/vnd.github+json'])
            ->delete("https://api.github.com/repos/{$this->repo}/git/refs/heads/{$branch}");

        if ($response->successful()) {
            Log::info("GitDeploy: Deleted remote branch {$branch}");
        }

        // Also switch local repo back to base branch
        Process::path($this->repoDir)->run("git checkout {$this->baseBranch}");
        Process::path($this->repoDir)->run("git branch -D {$branch}");
    }
}
