<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store all trades for Trades page / core journaling functionality
        Schema::create('wealthsimple_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('account_id')->constrained('wealthsimple_accounts');
            $table->string('symbol');
            $table->enum('asset_type', ['stock', 'option', 'crypto'])->default('stock');
            $table->timestamp('entry_at');
            $table->decimal('entry_price', 15, 2);
            $table->timestamp('exit_at')->nullable();
            $table->decimal('exit_price', 15, 2)->nullable();
            $table->decimal('pnl', 15, 2);
            $table->decimal('transaction_fee', 15, 2)->nullable();
            $table->string('currency');
            $table->decimal('strike_price', 15, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('call_put', ['call', 'put'])->notNullable()->default('call');
            $table->boolean('assigned')->nullable()->default(false);
            $table->boolean('expired')->nullable()->default(false);
            $table->timestamps();
            $table->index(['user_id', 'account_id', 'symbol', 'entry_at', 'exit_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wealthsimple_trades');
    }
};