<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\ExporterProfile;
use App\Models\BuyerProfile;
use App\Models\LogisticsProfile;
use App\Models\Product;
use App\Models\Document;
use App\Models\QuoteRequest;
use App\Models\Order;
use App\Models\Settlement;
use App\Models\Shipment;
use App\Models\AtexAuditLog;
use Illuminate\Support\Facades\Hash;

class AtexDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::findOrCreate('super-admin');
        $officerRole = Role::findOrCreate('field-officer');
        $exporterRole = Role::findOrCreate('exporter');
        $buyerRole = Role::findOrCreate('buyer');
        $logisticsRole = Role::findOrCreate('logistics');

        // 2. Create Users
        $password = Hash::make('password');

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'AEM Super Admin', 'password' => $password, 'is_active' => true, 'status' => 'active', 'email_verified_at' => now()]
        );
        $adminUser->assignRole($adminRole);

        $officerUser = User::updateOrCreate(
            ['email' => 'officer@example.com'],
            ['name' => 'Verification Officer', 'password' => $password, 'is_active' => true, 'status' => 'active', 'email_verified_at' => now()]
        );
        $officerUser->assignRole($officerRole);

        $exporterUser = User::updateOrCreate(
            ['email' => 'exporter@example.com'],
            ['name' => 'Ganye Agro Cooperative', 'password' => $password, 'is_active' => true, 'status' => 'active', 'email_verified_at' => now()]
        );
        $exporterUser->assignRole($exporterRole);

        $buyerUser = User::updateOrCreate(
            ['email' => 'buyer@example.com'],
            ['name' => 'Dubai Food Trading', 'password' => $password, 'is_active' => true, 'status' => 'active', 'email_verified_at' => now()]
        );
        $buyerUser->assignRole($buyerRole);

        $logisticsUser = User::updateOrCreate(
            ['email' => 'logistics@example.com'],
            ['name' => 'Sahel Freight Logistics', 'password' => $password, 'is_active' => true, 'status' => 'active', 'email_verified_at' => now()]
        );
        $logisticsUser->assignRole($logisticsRole);

        // 3. Create Categories
        $categories = [
            ['name' => 'Agriculture', 'slug' => 'agriculture', 'status' => 'active'],
            ['name' => 'Food Processing', 'slug' => 'food-processing', 'status' => 'active'],
            ['name' => 'Textiles', 'slug' => 'textiles', 'status' => 'active'],
            ['name' => 'Minerals', 'slug' => 'minerals', 'status' => 'active'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $categoryAgri = Category::where('slug', 'agriculture')->first();
        $categoryFood = Category::where('slug', 'food-processing')->first();

        // 4. Create Exporter Profile
        $exporterProfile = ExporterProfile::updateOrCreate(
            ['user_id' => $exporterUser->id],
            [
                'business_name' => 'Ganye Agro Cooperative',
                'registration_number' => 'AD-EXP-1024',
                'tax_number' => 'TIN-98234',
                'business_type' => 'Cooperative',
                'lga' => 'Ganye',
                'address' => 'Ganye producer cluster office, Adamawa State',
                'verification_status' => 'approved',
                'seller_program_status' => 'approved',
                'seller_brand_name' => 'Ganye Agro Cooperative',
                'fulfillment_model' => 'seller_direct',
                'readiness_score' => 86,
                'approved_at' => now(),
            ]
        );

        // 5. Create Buyer Profile
        $buyerProfile = BuyerProfile::updateOrCreate(
            ['user_id' => $buyerUser->id],
            [
                'company_name' => 'Dubai Food Trading LLC',
                'buyer_type' => 'distributor',
                'country' => 'United Arab Emirates',
                'verification_status' => 'approved',
            ]
        );

        // 6. Create Logistics Profile
        $logisticsProfile = LogisticsProfile::updateOrCreate(
            ['user_id' => $logisticsUser->id],
            [
                'company_name' => 'Sahel Freight Logistics',
                'coverage_regions' => 'Nigeria, UAE, Ghana, Morocco',
                'transport_modes' => 'Road Freight',
                'base_location' => 'Yola',
                'fleet_capacity' => 'Standard export cargo handling',
                'verification_status' => 'approved',
            ]
        );

        // 7. Create Products
        $product1 = Product::updateOrCreate(
            ['exporter_profile_id' => $exporterProfile->id, 'name' => 'Premium Sesame Seed'],
            [
                'category_id' => $categoryAgri->id,
                'description' => 'Cleaned, sorted, export-grade sesame from Adamawa producer clusters.',
                'hs_code' => '120740',
                'moq' => '10 MT',
                'available_quantity' => '120 MT',
                'unit_price' => 'Request quote',
                'quote_required' => true,
                'packaging' => '50kg export sacks',
                'origin_lga' => 'Ganye',
                'readiness_score' => 92,
                'status' => 'approved',
                'seller_sku' => 'AEM-1',
                'brand_name' => 'Ganye Agro Cooperative',
            ]
        );

        $product2 = Product::updateOrCreate(
            ['exporter_profile_id' => $exporterProfile->id, 'name' => 'Dried Hibiscus Flower'],
            [
                'category_id' => $categoryFood->id,
                'description' => 'Naturally dried hibiscus prepared for wholesale export.',
                'hs_code' => '121190',
                'moq' => '5 MT',
                'available_quantity' => '40 MT',
                'unit_price' => '$1,180 / MT',
                'quote_required' => false,
                'packaging' => '25kg cartons',
                'origin_lga' => 'Mubi',
                'readiness_score' => 88,
                'status' => 'pending_review',
                'seller_sku' => 'AEM-2',
                'brand_name' => 'Ganye Agro Cooperative',
            ]
        );

        $product3 = Product::updateOrCreate(
            ['exporter_profile_id' => $exporterProfile->id, 'name' => 'Export Grade Sorghum'],
            [
                'category_id' => $categoryAgri->id,
                'description' => 'Moisture-tested sorghum in bulk export packaging.',
                'hs_code' => '100790',
                'moq' => '25 MT',
                'available_quantity' => '300 MT',
                'unit_price' => '$390 / MT',
                'quote_required' => false,
                'packaging' => 'Bulk sacks',
                'origin_lga' => 'Guyuk',
                'readiness_score' => 84,
                'status' => 'approved',
                'seller_sku' => 'AEM-3',
                'brand_name' => 'Ganye Agro Cooperative',
            ]
        );

        // 8. Create Documents
        Document::updateOrCreate(
            ['owner_type' => 'exporter', 'owner_id' => $exporterProfile->id, 'document_type' => 'business_registration'],
            [
                'title' => 'Business Registration Certificate',
                'status' => 'approved',
                'reviewed_by' => $officerUser->id,
                'review_comment' => 'Registration record verified.',
                'reviewed_at' => now(),
            ]
        );

        Document::updateOrCreate(
            ['owner_type' => 'product', 'owner_id' => $product1->id, 'document_type' => 'phytosanitary'],
            [
                'title' => 'Phytosanitary Certificate',
                'status' => 'pending',
                'expiry_date' => '2026-07-22',
            ]
        );

        Document::updateOrCreate(
            ['owner_type' => 'product', 'owner_id' => $product2->id, 'document_type' => 'quality_report'],
            [
                'title' => 'Quality Inspection Report',
                'status' => 'rejected',
                'expiry_date' => '2026-05-31',
                'reviewed_by' => $officerUser->id,
                'review_comment' => 'Upload clearer lab report scan.',
                'reviewed_at' => now(),
            ]
        );

        // 9. Create Quote Request
        $quoteRequest = QuoteRequest::updateOrCreate(
            ['buyer_profile_id' => $buyerProfile->id, 'product_id' => $product1->id],
            [
                'quantity' => '20 MT',
                'destination_country' => 'United Arab Emirates',
                'destination_port' => 'Jebel Ali Port',
                'incoterm' => 'FOB',
                'message' => 'Please quote with phytosanitary certificate and export packaging.',
                'status' => 'open',
            ]
        );

        // 10. Create Order
        $order = Order::updateOrCreate(
            ['order_number' => 'AEM-ORD-0001'],
            [
                'quote_request_id' => $quoteRequest->id,
                'product_id' => $product1->id,
                'buyer_profile_id' => $buyerProfile->id,
                'exporter_profile_id' => $exporterProfile->id,
                'order_quantity' => '20 MT',
                'destination_location' => 'Jebel Ali Port, United Arab Emirates',
                'total_amount' => '23600',
                'currency' => 'USD',
                'fulfillment_mode' => 'seller_direct',
                'fulfillment_status' => 'pending',
                'commission_amount' => 2360.00,
                'tax_amount' => 1770.00,
                'net_payout_amount' => 19470.00,
                'settlement_status' => 'pending',
                'payment_status' => 'held',
                'shipment_status' => 'departed_origin',
                'status' => 'in_transit',
            ]
        );

        // 11. Create Settlement
        Settlement::updateOrCreate(
            ['order_id' => $order->id],
            [
                'exporter_profile_id' => $exporterProfile->id,
                'gross_amount' => 23600.00,
                'commission_amount' => 2360.00,
                'tax_amount' => 1770.00,
                'net_payout_amount' => 19470.00,
                'status' => 'pending',
            ]
        );

        // 12. Create Shipment
        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'logistics_profile_id' => $logisticsProfile->id,
                'tracking_number' => 'AEM-TRK-0001',
                'origin_location' => 'Adamawa Export Hub',
                'destination_location' => 'Jebel Ali Port, UAE',
                'status' => 'picked_up',
                'notes' => 'Departed origin - Lagos port handoff',
                'assigned_at' => now(),
            ]
        );

        // 13. Create Audit Log
        AtexAuditLog::create([
            'actor_id' => $officerUser->id,
            'action' => 'approved_exporter',
            'auditable_type' => 'exporter_profile',
            'auditable_id' => $exporterProfile->id,
            'new_values' => json_encode(['verification_status' => 'approved']),
            'ip_address' => '127.0.0.1',
        ]);
    }
}
