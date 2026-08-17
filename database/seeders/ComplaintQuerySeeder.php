<?php

namespace Database\Seeders;

use App\Enums\ComplaintStatus;
use App\Enums\QueryStatus;
use App\Models\Complaint;
use App\Models\Query;
use Illuminate\Database\Seeder;

class ComplaintQuerySeeder extends Seeder
{
    public function run(): void
    {
        $complaints = [
            [
                'full_name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'description' => 'My website has been down for 3 hours. I need urgent support to resolve this issue as it is affecting my business.',
                'status' => ComplaintStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'description' => 'The payment integration is not working properly. Customers are getting errors during checkout.',
                'status' => ComplaintStatus::Open,
                'is_read' => true,
                'read_at' => now()->subDay(),
            ],
            [
                'full_name' => 'Emma Williams',
                'email' => 'emma.williams@example.com',
                'description' => 'I was charged twice for the same service. Please refund the extra payment immediately.',
                'status' => ComplaintStatus::Flagged,
                'is_read' => true,
                'read_at' => now()->subDays(2),
            ],
            [
                'full_name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'description' => 'The mobile app crashes every time I try to upload a photo. This has been happening for a week.',
                'status' => ComplaintStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'description' => 'Email notifications are not being sent to my customers. I have verified the email settings are correct.',
                'status' => ComplaintStatus::Resolved,
                'is_read' => true,
                'read_at' => now()->subDays(5),
            ],
            [
                'full_name' => 'James Wilson',
                'email' => 'james.wilson@example.com',
                'description' => 'The admin dashboard is extremely slow. It takes more than 10 seconds to load any page.',
                'status' => ComplaintStatus::Open,
                'is_read' => true,
                'read_at' => now()->subHours(12),
            ],
            [
                'full_name' => 'Olivia Martinez',
                'email' => 'olivia.martinez@example.com',
                'description' => 'I cannot access my account after the recent update. The password reset is also not working.',
                'status' => ComplaintStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Robert Taylor',
                'email' => 'robert.taylor@example.com',
                'description' => 'The search functionality returns wrong results. It shows products that are not related to the search query.',
                'status' => ComplaintStatus::Resolved,
                'is_read' => true,
                'read_at' => now()->subWeek(),
            ],
        ];

        foreach ($complaints as $complaint) {
            Complaint::create($complaint);
        }

        $queries = [
            [
                'full_name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'query' => 'How can I integrate the Stripe payment gateway into my existing website? Do you provide API documentation?',
                'status' => QueryStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Priya Patel',
                'email' => 'priya.patel@example.com',
                'query' => 'What is the process to upgrade my current plan? Will there be any downtime during the migration?',
                'status' => QueryStatus::Open,
                'is_read' => true,
                'read_at' => now()->subDay(),
            ],
            [
                'full_name' => 'Carlos Garcia',
                'email' => 'carlos.garcia@example.com',
                'query' => 'Can you create a custom admin panel for our inventory management system? We need real-time stock tracking.',
                'status' => QueryStatus::Responded,
                'is_read' => true,
                'read_at' => now()->subDays(3),
            ],
            [
                'full_name' => 'Fatima Al-Rashid',
                'email' => 'fatima.alrashid@example.com',
                'query' => 'Do you offer SEO services? I need help improving my website ranking on Google.',
                'status' => QueryStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Tom Harris',
                'email' => 'tom.harris@example.com',
                'query' => 'What technologies do you use for mobile app development? Do you support both iOS and Android?',
                'status' => QueryStatus::Flagged,
                'is_read' => true,
                'read_at' => now()->subHours(6),
            ],
            [
                'full_name' => 'Yuki Tanaka',
                'email' => 'yuki.tanaka@example.com',
                'query' => 'I need a multi-language website. Can you support Japanese and English with RTL layout for Arabic?',
                'status' => QueryStatus::Responded,
                'is_read' => true,
                'read_at' => now()->subDays(4),
            ],
            [
                'full_name' => 'Anna Kowalski',
                'email' => 'anna.kowalski@example.com',
                'query' => 'How long does it typically take to build an eCommerce website with 500+ products?',
                'status' => QueryStatus::New,
                'is_read' => false,
            ],
            [
                'full_name' => 'Omar Diallo',
                'email' => 'omar.diallo@example.com',
                'query' => 'Can you migrate my WordPress site to a custom Laravel application without losing SEO rankings?',
                'status' => QueryStatus::Open,
                'is_read' => true,
                'read_at' => now()->subHours(3),
            ],
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'query' => 'Do you provide ongoing maintenance and support after the project is delivered? What are the pricing plans?',
                'status' => QueryStatus::Responded,
                'is_read' => true,
                'read_at' => now()->subDays(2),
            ],
            [
                'full_name' => 'Daniel Kim',
                'email' => 'daniel.kim@example.com',
                'query' => 'I need API integration with Salesforce CRM. Is this something your team handles?',
                'status' => QueryStatus::New,
                'is_read' => false,
            ],
        ];

        foreach ($queries as $query) {
            Query::create($query);
        }
    }
}
