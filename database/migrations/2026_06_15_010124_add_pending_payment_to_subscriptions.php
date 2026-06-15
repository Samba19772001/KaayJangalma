<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter le statut pending_payment
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending_payment', 'active', 'expired', 'cancelled') NOT NULL DEFAULT 'pending_payment'");

        // Ajouter les colonnes pour le suivi du paiement
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // wave, orange_money, virement
            $table->string('payment_reference')->nullable()->after('payment_method'); // référence du virement
            $table->timestamp('payment_confirmed_at')->nullable()->after('payment_reference');
            $table->foreignId('confirmed_by')->nullable()->after('payment_confirmed_at')
                  ->constrained('users')->nullOnDelete();
            $table->text('payment_note')->nullable()->after('confirmed_by');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_reference',
                'payment_confirmed_at', 'confirmed_by', 'payment_note'
            ]);
        });
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active'");
    }
};