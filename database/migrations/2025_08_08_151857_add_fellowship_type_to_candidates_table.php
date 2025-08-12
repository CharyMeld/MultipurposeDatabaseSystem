<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFellowshipTypeToCandidatesTable extends Migration
{
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('fellowship_type')->nullable()->after('nationality');
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('fellowship_type');
        });
    }
}

