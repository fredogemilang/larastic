<?php

namespace App\Services;

class CspValidator
{
    protected array $violations = [];
    protected string $mode;

    public function __construct()
    {
        $this->mode = config('static-cms.csp.mode', 'warning');
    }

    /**
     * Validate HTML content for CSP compliance.
     * Returns array of violations found.
     */
    public function validate(string $html, string $pageUrl = ''): array
    {
        $this->violations = [];

        $this->checkInlineScripts($html, $pageUrl);
        $this->checkInlineStyles($html, $pageUrl);
        $this->checkEventHandlers($html, $pageUrl);
        $this->checkJavascriptUrls($html, $pageUrl);
        $this->checkUnsafePatterns($html, $pageUrl);

        return $this->violations;
    }

    /**
     * Validate all rendered pages.
     * Returns ['passed' => bool, 'violations' => array, 'summary' => array]
     */
    public function validateAll(array $manifest): array
    {
        $allViolations = [];
        $perPage = [];

        foreach ($manifest as $item) {
            if (!in_array($item['type'], ['page', 'post', 'blog-index'])) {
                continue; // Skip XML, TXT files
            }

            $violations = $this->validate($item['html'], $item['url']);
            if (!empty($violations)) {
                $allViolations = array_merge($allViolations, $violations);
                $perPage[$item['url']] = $violations;
            }
        }

        $passed = empty($allViolations);

        return [
            'passed' => $passed,
            'mode' => $this->mode,
            'total_violations' => count($allViolations),
            'violations' => $allViolations,
            'per_page' => $perPage,
            'summary' => [
                'inline_scripts' => count(array_filter($allViolations, fn($v) => $v['type'] === 'inline_script')),
                'inline_styles' => count(array_filter($allViolations, fn($v) => $v['type'] === 'inline_style')),
                'event_handlers' => count(array_filter($allViolations, fn($v) => $v['type'] === 'event_handler')),
                'javascript_urls' => count(array_filter($allViolations, fn($v) => $v['type'] === 'javascript_url')),
                'unsafe_patterns' => count(array_filter($allViolations, fn($v) => $v['type'] === 'unsafe_pattern')),
            ],
        ];
    }

    /**
     * Check if export should fail based on mode and violations.
     */
    public function shouldAbort(array $report): bool
    {
        return $this->mode === 'strict' && !$report['passed'];
    }

    protected function checkInlineScripts(string $html, string $url): void
    {
        $validInlineScript = \App\Services\AnalyticsService::getInlineScript();

        // Match <script> tags without src attribute (inline scripts)
        if (preg_match_all('/<script(?![^>]*\bsrc\b)[^>]*>(.+?)<\/script>/is', $html, $matches)) {
            foreach ($matches[1] as $index => $content) {
                if (trim($content) === trim($validInlineScript)) {
                    continue; // Whitelist the analytics inline script
                }
                $this->addViolation('inline_script', 'Inline <script> tag detected', $url, $matches[0][$index]);
            }
        }
    }

    protected function checkInlineStyles(string $html, string $url): void
    {
        // Match style="" attributes
        if (preg_match_all('/\bstyle\s*=\s*"[^"]+"/i', $html, $matches)) {
            foreach ($matches[0] as $match) {
                $this->addViolation('inline_style', 'Inline style attribute detected', $url, $match);
            }
        }

        // Match <style> tags
        if (preg_match_all('/<style[^>]*>.+?<\/style>/is', $html, $matches)) {
            foreach ($matches[0] as $match) {
                $this->addViolation('inline_style', 'Inline <style> tag detected', $url, substr($match, 0, 100));
            }
        }
    }

    protected function checkEventHandlers(string $html, string $url): void
    {
        $handlers = ['onclick', 'onload', 'onerror', 'onmouseover', 'onmouseout', 'onsubmit', 'onchange', 'onfocus', 'onblur', 'onkeydown', 'onkeyup'];
        $pattern = '/\b(' . implode('|', $handlers) . ')\s*=\s*"/i';

        if (preg_match_all($pattern, $html, $matches)) {
            foreach ($matches[0] as $match) {
                $this->addViolation('event_handler', 'Inline event handler detected', $url, $match);
            }
        }
    }

    protected function checkJavascriptUrls(string $html, string $url): void
    {
        if (preg_match_all('/href\s*=\s*"javascript:/i', $html, $matches)) {
            foreach ($matches[0] as $match) {
                $this->addViolation('javascript_url', 'javascript: URL detected', $url, $match);
            }
        }
    }

    protected function checkUnsafePatterns(string $html, string $url): void
    {
        // First, extract all script tag contents
        if (preg_match_all('/<script[^>]*>(.*?)<\/script>/is', $html, $scriptMatches)) {
            foreach ($scriptMatches[1] as $scriptContent) {
                // Check for eval() inside script content
                if (preg_match_all('/\beval\s*\(/i', $scriptContent, $matches)) {
                    foreach ($matches[0] as $match) {
                        $this->addViolation('unsafe_pattern', 'eval() pattern detected', $url, $match);
                    }
                }

                // Check for new Function() inside script content
                if (preg_match_all('/new\s+Function\s*\(/i', $scriptContent, $matches)) {
                    foreach ($matches[0] as $match) {
                        $this->addViolation('unsafe_pattern', 'new Function() pattern detected', $url, $match);
                    }
                }
            }
        }
    }

    protected function addViolation(string $type, string $message, string $url, string $snippet = ''): void
    {
        $this->violations[] = [
            'type' => $type,
            'message' => $message,
            'url' => $url,
            'snippet' => mb_substr(trim($snippet), 0, 150),
        ];
    }
}
