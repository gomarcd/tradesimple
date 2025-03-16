<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store open positions for use on the Holdings page
        Schema::create('wealthsimple_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('account_id')->constrained('wealthsimple_accounts');
            $table->string('symbol');
            $table->enum('asset_type', ['stock', 'option', 'crypto'])->default('stock');
            $table->decimal('qty', 15, 4);
            $table->decimal('avg_price', 15, 2);
            $table->decimal('current_price', 15, 2);
            $table->decimal('book_value', 15, 2);
            $table->decimal('market_value', 15, 2);
            $table->decimal('pnl', 15, 2);
            $table->string('currency');
            $table->decimal('strike_price', 15, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('call_put', ['call', 'put'])->notNullable()->default('call');
            $table->boolean('assigned')->nullable()->default(false);
            $table->boolean('expired')->nullable()->default(false);
            $table->timestamps();
            $table->index(['user_id', 'account_id', 'symbol']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wealthsimple_positions');
    }
};