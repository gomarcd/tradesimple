<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wealthsimple_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('login_id')->constrained('wealthsimple_logins')->onDelete('cascade')->index();
            $table->string('display_name')->nullable();            
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->boolean('has_multiple_owners')->default(false);
            $table->string('account_id')->index();
            $table->string('account_type')->nullable();
            $table->string('description')->nullable();
            $table->string('currency');
            $table->decimal('balance', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['login_id', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wealthsimple_accounts');
    }
};