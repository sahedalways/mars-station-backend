<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'icon' => 'globe',
                'title' => 'Web Development',
                'type' => 'web',
                'description' => 'Custom, high-performance websites and web applications built to scale with your business.',
                'bullets' => [
                    'Custom website development',
                    'E-commerce solutions',
                    'Progressive web apps',
                    'API development & integration',
                    'Performance optimisation',
                ],
            ],
            [
                'icon' => 'smartphone',
                'title' => 'Mobile App Development',
                'type' => 'mobile',
                'description' => 'Native and cross-platform mobile applications engineered for iOS and Android.',
                'bullets' => [
                    'iOS & Android apps',
                    'Cross-platform development',
                    'UI/UX for mobile',
                    'App store deployment',
                    'Ongoing maintenance',
                ],
            ],
            [
                'icon' => 'palette',
                'title' => 'UI/UX Design',
                'type' => 'uiux',
                'description' => 'User-centred design that turns complex problems into intuitive, delightful experiences.',
                'bullets' => [
                    'User research & personas',
                    'Wireframes & prototypes',
                    'Design systems',
                    'Usability testing',
                    'Interaction design',
                ],
            ],
            [
                'icon' => 'briefcase',
                'title' => 'Branding & Identity',
                'type' => 'branding',
                'description' => 'Distinctive brand identities that communicate who you are and connect with your audience.',
                'bullets' => [
                    'Logo & visual identity',
                    'Brand strategy & messaging',
                    'Brand guidelines',
                    'Print & digital collateral',
                    'Rebranding',
                ],
            ],
            [
                'icon' => 'megaphone',
                'title' => 'Digital Marketing',
                'type' => 'marketing',
                'description' => 'Data-driven marketing campaigns that generate leads, engagement and measurable growth.',
                'bullets' => [
                    'Social media management',
                    'Paid advertising (PPC)',
                    'Email marketing',
                    'Content marketing',
                    'Analytics & reporting',
                ],
            ],
            [
                'icon' => 'search',
                'title' => 'SEO & Content',
                'type' => 'seo',
                'description' => 'Search engine optimisation and content strategies that put your brand in front of the right people.',
                'bullets' => [
                    'Technical SEO audits',
                    'Keyword research',
                    'On-page & off-page SEO',
                    'Content strategy & copywriting',
                    'Local SEO',
                ],
            ],
            [
                'icon' => 'video',
                'title' => 'Video Production',
                'type' => 'video',
                'description' => 'Professional video content from concept to final cut for websites, social media and advertising.',
                'bullets' => [
                    'Promotional videos',
                    'Product demos & explainers',
                    'Motion graphics',
                    'Social media video',
                    'Post-production',
                ],
            ],
            [
                'icon' => 'cart',
                'title' => 'E-commerce Solutions',
                'type' => 'ecommerce',
                'description' => 'End-to-end online store development with secure payments, subscriptions and checkout flows.',
                'bullets' => [
                    'Store design & build',
                    'Payment gateway integration',
                    'Subscriptions & billing',
                    'Inventory management',
                    'Conversion optimisation',
                ],
            ],
            [
                'icon' => 'wrench',
                'title' => 'Maintenance & Support',
                'type' => 'support',
                'description' => 'Reliable ongoing support and maintenance that keeps your digital products secure and current.',
                'bullets' => [
                    'Security updates',
                    'Performance monitoring',
                    'Bug fixes & patches',
                    'Backups & recovery',
                    'Priority support',
                ],
            ],
        ];

        $index = 1;

        foreach ($services as $serviceData) {
            $bullets = $serviceData['bullets'];
            unset($serviceData['bullets']);

            $service = Service::query()->updateOrCreate(
                ['title' => $serviceData['title']],
                [...$serviceData, 'order_index' => $index++]
            );

            $bulletOrder = 1;
            foreach ($bullets as $bullet) {
                $service->bulletPoints()->updateOrCreate(
                    ['text' => $bullet],
                    ['order_index' => $bulletOrder++]
                );
            }
        }
    }
}
