<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Seller Profiles Table
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('business_name');
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('business_type', 100)->default('SME');
            $table->string('lga');
            $table->text('address')->nullable();
            $table->string('seller_program_status', 20)->default('pending');
            $table->string('seller_brand_name')->nullable();
            $table->string('fulfillment_model', 30)->default('seller_direct');
            $table->string('verification_status', 20)->default('submitted');
            $table->integer('readiness_score')->default(60);
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        // 3. Buyer Profiles Table
        Schema::create('buyer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('phone_number')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        // 4. Logistics Profiles Table
        Schema::create('logistics_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->text('coverage_regions')->nullable();
            $table->string('transport_modes')->nullable();
            $table->string('base_location')->nullable();
            $table->string('fleet_capacity')->nullable();
            $table->string('verification_status', 20)->default('approved');
            $table->timestamps();
        });

        // 5. Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_profile_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('hs_code', 100)->nullable();
            $table->string('moq', 100);
            $table->string('available_quantity', 100)->nullable();
            $table->string('unit_price', 100)->nullable();
            $table->text('image_path')->nullable();
            $table->string('seller_sku', 100)->nullable();
            $table->string('brand_name')->nullable();
            $table->string('fulfillment_model', 30)->default('seller_direct');
            $table->boolean('fulfillment_eligible')->default(false);
            $table->boolean('quote_required')->default(true);
            $table->string('packaging')->nullable();
            $table->string('origin_lga');
            $table->integer('readiness_score')->default(70);
            $table->string('status', 30)->default('pending_review');
            $table->timestamps();
        });

        // 6. Documents Table
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type', 50);
            $table->unsignedInteger('owner_id');
            $table->string('document_type', 100);
            $table->string('title');
            $table->text('path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->date('expiry_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_comment')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['owner_type', 'owner_id']);
        });

        // 7. Quote Requests Table
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_profile_id')->constrained('buyer_profiles')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('quantity', 100);
            $table->string('destination_country');
            $table->string('destination_port')->nullable();
            $table->string('incoterm', 20)->default('FOB');
            $table->text('message')->nullable();
            $table->string('response_amount', 100)->nullable();
            $table->text('response_message')->nullable();
            $table->dateTime('responded_at')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamps();
        });

        // 8. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 100)->unique();
            $table->foreignId('quote_request_id')->nullable()->constrained('quote_requests')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('buyer_profile_id')->constrained('buyer_profiles');
            $table->foreignId('seller_profile_id')->constrained('seller_profiles');
            $table->string('order_quantity', 100)->nullable();
            $table->string('destination_location')->nullable();
            $table->string('total_amount', 100)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('fulfillment_mode', 30)->default('seller_direct');
            $table->string('fulfillment_status', 30)->default('pending');
            $table->decimal('commission_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('net_payout_amount', 12, 2)->default(0.00);
            $table->string('settlement_status', 30)->default('pending');
            $table->string('payment_status', 30)->default('pending');
            $table->string('shipment_status', 30)->default('pending');
            $table->string('status', 30)->default('created');
            $table->timestamps();
        });

        // 9. Fulfillment Inventory Table
        Schema::create('fulfillment_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_profile_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('brand_name')->nullable();
            $table->string('seller_sku', 100)->nullable();
            $table->integer('quantity_received')->default(0);
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('quantity_fulfilled')->default(0);
            $table->string('unit_label', 50)->default('units');
            $table->string('storage_location')->nullable();
            $table->string('receipt_status', 30)->default('pending_receipt');
            $table->text('notes')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->timestamps();
        });

        // 10. Settlements Table
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            $table->foreignId('seller_profile_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->decimal('gross_amount', 12, 2)->default(0.00);
            $table->decimal('commission_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('net_payout_amount', 12, 2)->default(0.00);
            $table->string('status', 30)->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('credited_at')->nullable();
            $table->timestamps();
        });

        // 11. Shipments Table
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('logistics_profile_id')->nullable()->constrained('logistics_profiles')->onDelete('set null');
            $table->string('tracking_number', 100)->nullable();
            $table->string('origin_location')->nullable();
            $table->string('destination_location')->nullable();
            $table->string('status', 30)->default('pending_assignment');
            $table->text('notes')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });

        // 12. Atex Audit Logs Table
        Schema::create('atex_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 100);
            $table->string('auditable_type', 100);
            $table->unsignedInteger('auditable_id');
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atex_audit_logs');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('settlements');
        Schema::dropIfExists('fulfillment_inventory');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('products');
        Schema::dropIfExists('logistics_profiles');
        Schema::dropIfExists('buyer_profiles');
        Schema::dropIfExists('seller_profiles');
    }
};
