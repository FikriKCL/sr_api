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
      Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('payment_method', 50); 
            // contoh: cash, transfer, e_wallet, credit_card

            $table->integer('amount');

            $table->enum('status', [
                'unpaid',
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded'
            ])->default('unpaid');

            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
