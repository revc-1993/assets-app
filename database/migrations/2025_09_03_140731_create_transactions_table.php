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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedInteger('sequence_number');
            $table->string('request');
            $table->date('transaction_date');

            $table->unsignedBigInteger('verification_id')->nullable();
            $table->unsignedBigInteger('custodian_id')->nullable();
            $table->unsignedBigInteger('responsible_giza_id')->nullable();
            $table->unsignedBigInteger('responsible_gafyb_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->unsignedBigInteger('receive_id')->nullable();

            $table->text('comments')->nullable();
            $table->unsignedBigInteger('department_id');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->enum('action', ['created', 'edited'])->default('created');
            $table->timestamps();

            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('verification_id')->references('id')->on('employees');
            $table->foreign('custodian_id')->references('id')->on('employees');
            $table->foreign('responsible_giza_id')->references('id')->on('employees');
            $table->foreign('responsible_gafyb_id')->references('id')->on('employees');
            $table->foreign('delivery_id')->references('id')->on('employees');
            $table->foreign('receive_id')->references('id')->on('employees');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
