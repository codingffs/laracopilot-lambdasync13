<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crm_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->enum('role', ['Super Admin', 'Admin', 'Manager', 'Executive'])->default('Executive');
            $table->string('department')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_login')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('email');
            $table->index('role');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crm_users');
    }
};