<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CrmLead;
use App\Models\CrmFollowup;
use App\Models\CrmNote;
use App\Models\SubscriptionPlan;
use App\Models\Agency;
use App\Models\AgencyAgent;
use App\Models\AgentWallet;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ErpAndCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles & Permissions setup
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $agentRole = Role::firstOrCreate(['name' => 'agent']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create Admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@homelist.co.in'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('Admin@12345'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole($adminRole);

        // Create Agents
        $agentUser1 = User::firstOrCreate(
            ['email' => 'agent1@homelist.co.in'],
            [
                'name' => 'Vikram Sharma',
                'password' => bcrypt('Agent@12345'),
                'role' => 'agent',
            ]
        );
        $agentUser1->assignRole($agentRole);

        $agentUser2 = User::firstOrCreate(
            ['email' => 'agent2@homelist.co.in'],
            [
                'name' => 'Priya Patel',
                'password' => bcrypt('Agent@12345'),
                'role' => 'agent',
            ]
        );
        $agentUser2->assignRole($agentRole);

        // Make Agent profile objects
        Agent::firstOrCreate(
            ['user_id' => $agentUser1->id],
            [
                'phone' => '+91 98765 43210',
                'about' => 'Senior real estate agent specializing in residential properties in Central India.',
                'kyc_status' => 'verified',
                'is_verified' => true,
                'rating' => 4.8,
                'rating_count' => 15,
            ]
        );

        Agent::firstOrCreate(
            ['user_id' => $agentUser2->id],
            [
                'phone' => '+91 87654 32109',
                'about' => 'Expert in commercial property leasing and agriculture land transactions.',
                'kyc_status' => 'verified',
                'is_verified' => true,
                'rating' => 4.6,
                'rating_count' => 8,
            ]
        );

        // Create Wallets for Agents
        AgentWallet::firstOrCreate(
            ['user_id' => $agentUser1->id],
            ['balance' => 5000.00, 'commission_rate' => 2.50]
        );
        AgentWallet::firstOrCreate(
            ['user_id' => $agentUser2->id],
            ['balance' => 12000.00, 'commission_rate' => 3.00]
        );

        // 2. Subscription Plans seeding
        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free-trial',
                'price' => 0.00,
                'billing_period' => 'monthly',
                'property_limit' => 5,
                'featured_limit' => 0,
                'image_limit' => 5,
                'video_limit' => 0,
                'trial_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Silver Plan',
                'slug' => 'silver-plan',
                'price' => 999.00,
                'billing_period' => 'monthly',
                'property_limit' => 15,
                'featured_limit' => 2,
                'image_limit' => 10,
                'video_limit' => 1,
                'trial_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Gold Plan',
                'slug' => 'gold-plan',
                'price' => 2499.00,
                'billing_period' => 'quarterly',
                'property_limit' => 50,
                'featured_limit' => 10,
                'image_limit' => 25,
                'video_limit' => 5,
                'trial_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Platinum Plan',
                'slug' => 'platinum-plan',
                'price' => 7999.00,
                'billing_period' => 'yearly',
                'property_limit' => 150,
                'featured_limit' => 40,
                'image_limit' => 50,
                'video_limit' => 15,
                'trial_days' => 0,
                'is_active' => true,
            ]
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }

        // 3. Agencies seeding
        $agency = Agency::firstOrCreate(
            ['slug' => 'apex-realty-group'],
            [
                'name' => 'Apex Realty Group',
                'email' => 'contact@apexrealty.co.in',
                'phone' => '+91 731 4223333',
                'website' => 'https://apexrealty.co.in',
                'address' => '501, Orbit Mall, Vijay Nagar, Indore, MP',
                'about' => 'Apex Realty is the leading real estate consultancy in Central India.',
                'owner_id' => $admin->id,
                'is_verified' => true,
            ]
        );

        // Assign agent1 to agency
        AgencyAgent::firstOrCreate(
            [
                'agency_id' => $agency->id,
                'user_id' => $agentUser1->id,
            ],
            [
                'role' => 'manager',
                'status' => 'active',
            ]
        );

        // 4. CRM Leads & Followups
        $lead1 = CrmLead::firstOrCreate(
            ['email' => 'amit.verma@example.com', 'agent_id' => $agentUser1->id],
            [
                'name' => 'Amit Verma',
                'phone' => '+91 99988 87766',
                'source' => 'website',
                'status' => 'new',
            ]
        );

        $lead2 = CrmLead::firstOrCreate(
            ['email' => 'sneha.singh@example.com', 'agent_id' => $agentUser1->id],
            [
                'name' => 'Sneha Singh',
                'phone' => '+91 98877 66554',
                'source' => 'portal',
                'status' => 'contacted',
            ]
        );

        CrmFollowup::firstOrCreate(
            ['lead_id' => $lead2->id, 'type' => 'call'],
            [
                'followup_date' => now()->addDays(2),
                'notes' => 'Follow up on 3BHK flat budget and preferred locations in Vijay Nagar.',
                'status' => 'scheduled',
            ]
        );

        CrmNote::firstOrCreate(
            ['lead_id' => $lead2->id, 'note_type' => 'call_note'],
            [
                'content' => 'Lead called inquiring about premium residential properties. Budget is 80-90 Lakhs.',
            ]
        );

        // 5. Blog Categories & Blog Posts
        $cat1 = BlogCategory::firstOrCreate(['slug' => 'real-estate-trends'], ['name' => 'Real Estate Trends']);
        $cat2 = BlogCategory::firstOrCreate(['slug' => 'home-buying-tips'], ['name' => 'Home Buying Tips']);

        BlogPost::firstOrCreate(
            ['slug' => 'indore-real-estate-boom-2026'],
            [
                'title' => 'Indore Real Estate Boom in 2026: What Buyers Need to Know',
                'content' => 'Indore has seen significant growth in real estate sector over the last few years due to superb IT park expansion, clean city branding, and metro development. Property values in bypass road and super corridor have appreciated by 25% year on year.',
                'blog_category_id' => $cat1->id,
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        BlogPost::firstOrCreate(
            ['slug' => 'tips-for-first-time-home-buyers-in-india'],
            [
                'title' => '5 Essential Tips for First-Time Home Buyers in India',
                'content' => 'Buying a home for the first time is a milestone. Ensure you check RERA registration, calculate additional costs like registration and stamp duty, choose proper home loan options, perform clean title search, and inspect the locality amenities.',
                'blog_category_id' => $cat2->id,
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
