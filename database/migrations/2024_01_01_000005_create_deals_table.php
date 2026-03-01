<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('stage', ['Prospecting', 'Qualification', 'Proposal', 'Negotiation', 'Won', 'Lost'])->default('Prospecting');
            $table->decimal('value', 15, 2)->default(0);
            $table->unsignedTinyInteger('probability')->default(0);
            $table->date('expected_close_date')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('crm_users')->onDelete('set null');
            $table->index('stage');
            $table->index('assigned_to');
            $table->index('customer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deals');
    }
};