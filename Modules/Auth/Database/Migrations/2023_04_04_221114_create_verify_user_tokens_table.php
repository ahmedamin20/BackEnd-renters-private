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
        Schema::create('verify_user_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('handle')->unique();
            $table->string('code'); // SHA 256 Token
            $table->timestamp('expire_at')
                ->default(now()->addHours(2));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verify_emails_tokens');
    }
};
