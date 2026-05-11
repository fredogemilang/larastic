<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Services\GitDeployService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Settings')]
class SiteSettings extends Component
{
    // General
    public string $site_name = '';
    public string $site_tagline = '';
    public string $site_url = '';
    public string $footer_text = '';

    // Analytics
    public string $gtm_id = '';
    public string $ga_id = '';
    public string $clarity_id = '';

    public string $ahrefs_key = '';

    // Social
    public string $social_facebook = '';
    public string $social_twitter = '';
    public string $social_instagram = '';
    public string $social_linkedin = '';
    public string $social_youtube = '';

    // Export
    public string $csp_mode = 'warning';

    // Deploy
    public string $github_deploy_repo = '';
    public string $github_deploy_token = '';
    public string $github_deploy_branch = 'main';
    public string $firebase_site_id = '';
    public ?array $connectionTestResult = null;

    public string $activeTab = 'general';

    public function mount(): void
    {
        if (!auth()->user()->hasRole('super_admin')) {
            abort(403);
        }

        $this->site_name = Setting::get('site_name', config('app.name')) ?? '';
        $this->site_tagline = Setting::get('site_tagline', '') ?? '';
        $this->site_url = Setting::get('site_url', config('app.url')) ?? '';
        $this->footer_text = Setting::get('footer_text', '') ?? '';

        $this->gtm_id = Setting::get('gtm_id', '') ?? '';
        $this->ga_id = Setting::get('ga_id', '') ?? '';
        $this->clarity_id = Setting::get('clarity_id', '') ?? '';

        $this->ahrefs_key = Setting::get('ahrefs_key', '') ?? '';

        $this->social_facebook = Setting::get('social_facebook', '') ?? '';
        $this->social_twitter = Setting::get('social_twitter', '') ?? '';
        $this->social_instagram = Setting::get('social_instagram', '') ?? '';
        $this->social_linkedin = Setting::get('social_linkedin', '') ?? '';
        $this->social_youtube = Setting::get('social_youtube', '') ?? '';

        $this->csp_mode = Setting::get('csp_mode', 'warning') ?? 'warning';

        $this->github_deploy_repo = Setting::get('github_deploy_repo', '') ?? '';
        $this->github_deploy_token = Setting::get('github_deploy_token', '') ?? '';
        $this->github_deploy_branch = Setting::get('github_deploy_branch', 'main') ?? 'main';
        $this->firebase_site_id = Setting::get('firebase_site_id', 'defenxor-com') ?? 'defenxor-com';
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_url' => 'required|url|max:255',
            'footer_text' => 'nullable|string|max:1000',
        ]);

        Setting::set('site_name', $this->site_name, 'general');
        Setting::set('site_tagline', $this->site_tagline, 'general');
        Setting::set('site_url', rtrim($this->site_url, '/'), 'general');
        Setting::set('footer_text', $this->footer_text, 'general');
        $this->dispatch('notify', type: 'success', message: 'General settings saved.');
    }

    public function saveAnalytics(): void
    {
        $this->validate([
            'gtm_id' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-_]*$/',
            'ga_id' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-]*$/',
            'clarity_id' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9]*$/',

            'ahrefs_key' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9]*$/',
        ]);

        Setting::set('gtm_id', $this->gtm_id, 'analytics');
        Setting::set('ga_id', $this->ga_id, 'analytics');
        Setting::set('clarity_id', $this->clarity_id, 'analytics');

        Setting::set('ahrefs_key', $this->ahrefs_key, 'analytics');
        $this->dispatch('notify', type: 'success', message: 'Analytics settings saved.');
    }

    public function saveSocial(): void
    {
        $this->validate([
            'social_facebook' => 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_linkedin' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
        ]);

        Setting::set('social_facebook', $this->social_facebook, 'social');
        Setting::set('social_twitter', $this->social_twitter, 'social');
        Setting::set('social_instagram', $this->social_instagram, 'social');
        Setting::set('social_linkedin', $this->social_linkedin, 'social');
        Setting::set('social_youtube', $this->social_youtube, 'social');
        $this->dispatch('notify', type: 'success', message: 'Social links saved.');
    }

    public function saveExport(): void
    {
        $this->validate([
            'csp_mode' => 'required|in:warning,strict',
        ]);

        Setting::set('csp_mode', $this->csp_mode, 'export');
        $this->dispatch('notify', type: 'success', message: 'Export settings saved.');
    }

    public function saveDeploy(): void
    {
        $this->validate([
            'github_deploy_repo' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9._-]+\/[a-zA-Z0-9._-]+$/'],
            'github_deploy_token' => 'nullable|string|max:500',
            'github_deploy_branch' => 'required|string|max:100',
            'firebase_site_id' => 'nullable|string|max:100',
        ]);

        Setting::set('github_deploy_repo', $this->github_deploy_repo, 'deploy');
        Setting::set('github_deploy_token', $this->github_deploy_token, 'deploy');
        Setting::set('github_deploy_branch', $this->github_deploy_branch, 'deploy');
        Setting::set('firebase_site_id', $this->firebase_site_id, 'deploy');

        $this->connectionTestResult = null;
        $this->dispatch('notify', type: 'success', message: 'Deploy settings saved.');
    }

    public function testGithubConnection(): void
    {
        // Save first so the service reads latest values
        $this->saveDeploy();

        $this->connectionTestResult = GitDeployService::testConnection();

        if ($this->connectionTestResult['success']) {
            $this->dispatch('notify', type: 'success', message: $this->connectionTestResult['message']);
        } else {
            $this->dispatch('notify', type: 'error', message: $this->connectionTestResult['message']);
        }
    }

    public function render(): View
    {
        return view('livewire.settings.site-settings');
    }
}
