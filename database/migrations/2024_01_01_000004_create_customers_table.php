<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('source')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Churned'])->default('Active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('set null');
            $table->index('status');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};