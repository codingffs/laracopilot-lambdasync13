<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crm_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('assigned_to');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->date('due_date');
            $table->timestamp('completed_at')->nullable();
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('crm_users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('crm_users')->onDelete('set null');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('due_date');
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('crm_tasks');
    }
};