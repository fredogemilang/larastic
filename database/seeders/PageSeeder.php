<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'template' => 'home',
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Welcome to Our Website',
                        'subtitle' => 'Building the future, one page at a time.',
                        'cta_text' => 'Get Started',
                        'cta_url' => '/about',
                    ],
                    'features' => [
                        ['icon' => '⚡', 'title' => 'Fast', 'description' => 'Lightning fast static pages'],
                        ['icon' => '🔒', 'title' => 'Secure', 'description' => 'CSP compliant and secure'],
                        ['icon' => '🎯', 'title' => 'SEO Optimized', 'description' => 'Built for search engines'],
                    ],
                    'about_section' => [
                        'title' => 'About Us',
                        'description' => 'We create beautiful static websites.',
                    ],
                ],
                'status' => 'published',
                'sort_order' => 1,
            ],
            [
                'title' => 'About',
                'slug' => 'about',
                'template' => 'about',
                'content_blocks' => [
                    'hero' => ['title' => 'About Us', 'subtitle' => 'Learn more about our mission'],
                    'body' => '<p>We are a team dedicated to building fast, secure, and beautiful websites.</p>',
                    'team' => [],
                ],
                'status' => 'published',
                'sort_order' => 2,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'template' => 'services',
                'content_blocks' => [
                    'hero' => ['title' => 'Our Services', 'subtitle' => 'What we offer'],
                    'services' => [
                        ['icon' => '🌐', 'title' => 'Web Design', 'description' => 'Beautiful responsive designs', 'link' => ''],
                        ['icon' => '💻', 'title' => 'Development', 'description' => 'Clean, modern code', 'link' => ''],
                    ],
                ],
                'status' => 'published',
                'sort_order' => 3,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'template' => 'contact',
                'content_blocks' => [
                    'hero' => ['title' => 'Contact Us', 'subtitle' => 'Get in touch'],
                    'info' => ['email' => 'hello@example.com', 'phone' => '+1 234 567 890', 'address' => '123 Main St'],
                ],
                'status' => 'published',
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
