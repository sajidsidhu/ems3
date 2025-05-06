<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->decimal('working_hours', 5, 2)->default(0)->after('clock_out');
            $table->integer('late_minutes')->default(0)->after('working_hours');
        });
    }

    public function down()
    {
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropColumn(['working_hours', 'late_minutes']);
        });
    }
}; 