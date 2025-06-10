<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('admin_email')->unique();
            $table->string('admin_security_code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
