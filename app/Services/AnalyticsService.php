<?php

namespace App\Services;

use App\Models\Setting;

class AnalyticsService
{
    /**
     * Generate the inline script for analytics and main.js loading.
     */
    public static function getInlineScript(): string
    {
        // Sanitize: allow only valid GTM/GA/Clarity ID characters
        $gtmId = preg_replace('/[^a-zA-Z0-9\-_]/', '', Setting::get('gtm_id') ?? '');
        $gaId = preg_replace('/[^a-zA-Z0-9]/', '', Setting::get('ga_id') ?? '');
        $clarityId = preg_replace('/[^a-zA-Z0-9]/', '', Setting::get('clarity_id') ?? '');

        $ahrefsKey = preg_replace('/[^a-zA-Z0-9]/', '', Setting::get('ahrefs_key') ?? '');

        // IMPORTANT: No leading whitespace on lines! HtmlMin strips leading
        // spaces from script content during export, which would cause a SHA-256
        // hash mismatch with the CSP header (the hash is computed pre-minification
        // but the browser hashes the post-minification content).
        $js = "(function(){\n";

        if ($gtmId) {
            $js .= "window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});\n";
            $js .= "var g=document.createElement('script');g.async=true;g.src='https://www.googletagmanager.com/gtm.js?id={$gtmId}';document.head.appendChild(g);\n";
        }

        if ($gaId) {
            $js .= "var a=document.createElement('script');a.async=true;a.src='https://www.googletagmanager.com/gtag/js?id={$gaId}';document.head.appendChild(a);\n";
            $js .= "a.onload=function(){window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$gaId}');};\n";
        }

        if ($clarityId) {
            $js .= "var c=document.createElement('script');c.async=true;c.src='https://www.clarity.ms/tag/{$clarityId}';document.head.appendChild(c);\n";
        }

        if ($ahrefsKey) {
            $js .= "var h=document.createElement('script');h.async=true;h.setAttribute('data-key','{$ahrefsKey}');h.src='https://analytics.ahrefs.com/analytics.js';document.head.appendChild(h);\n";
        }

        // Dynamically load main.js locally so strict-dynamic covers it
        $js .= "var m=document.createElement('script');m.src='/assets/js/main.js';document.body.appendChild(m);\n";

        $js .= "})();";

        return $js;
    }

    /**
     * Get the SHA-256 base64 hash of the inline script for CSP.
     */
    public static function getHash(): string
    {
        $script = self::getInlineScript();
        return 'sha256-' . base64_encode(hash('sha256', $script, true));
    }
}
