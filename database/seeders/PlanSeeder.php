<?php
namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'stripe_id' => 'price_standard_id_here', 
                'price' => 49.00,
                'analysis_quota' => 1,
                'has_human_validation' => true,
                'support_type' => 'email',
            ],
            [
                'name' => 'Business AI',
                'slug' => 'business-ai',
                'stripe_id' => 'price_business_id_here', 
                'price' => 199.00,
                'analysis_quota' => 15,
                'has_human_validation' => true,
                'support_type' => 'priority',
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'stripe_id' => 'price_enterprise_id_here', 
                'price' => 999.00,
                'analysis_quota' => 999, 
                'has_human_validation' => true,
                'support_type' => 'dedicated',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}