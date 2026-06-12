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
        Schema::table('transactions', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('ticket_quantity', 'quantity');
            $table->renameColumn('payment_status', 'status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Add new columns
            $table->decimal('subtotal', 10, 2)->default(0)->after('promo_id');
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->renameColumn('total_price', 'total');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->string('payment_proof')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount', 'paid_at', 'payment_proof']);
        });
    }
};
