<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->integer('total_late_minutes')->default(0)->after('total_late_arrivals');
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('total_late_minutes');
        });
    }
}; 