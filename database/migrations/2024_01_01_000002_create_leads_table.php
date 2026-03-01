<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->nullable();
            $table->string('campaign')->nullable();
            $table->enum('status', ['New', 'Contacted', 'Qualified', 'Lost', 'Converted'])->default('New');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('tags')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('crm_users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('crm_users')->onDelete('set null');
            $table->index('status');
            $table->index('priority');
            $table->index('source');
            $table->index('email');
            $table->index('assigned_to');
            $table->index('follow_up_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};