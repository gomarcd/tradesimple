<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enable audit trail for connected WS accounts
        Schema::create('wealthsimple_connection_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('login_id')->constrained('wealthsimple_logins')->onDelete('cascade')->index();
            $table->string('ip_address');
            $table->text('user_agent'); 
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['login_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wealthsimple_connection_audit');
    }
};