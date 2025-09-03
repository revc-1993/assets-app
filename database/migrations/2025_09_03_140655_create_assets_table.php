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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->integer('esbye_code')->unique();
            $table->string('description')->size(200);
            $table->string('serie', 80);
            $table->string('model', 70);
            $table->string('condition', 40);
            $table->decimal('book_value', 8, 2);
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->boolean('inactive')->default(false);
            $table->boolean('registered_esbye')->default(false);
            $table->text('comments')->nullable();
            $table->string('origin')->nullable();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('department_id')->references('id')->on('departments');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
