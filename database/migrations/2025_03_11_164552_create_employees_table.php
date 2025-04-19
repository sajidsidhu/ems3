<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->enum('position', ['Mill Manager', 'Production Supervisor', 'Quality Control Officer', 'Machine Operator', 'Maintenance Technician', 'Logistics Coordinator']);
            $table->decimal('salary', 10, 2);
            $table->date('hire_date');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->date('resignation_date')->nullable();
            $table->string('resignation_reason', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
