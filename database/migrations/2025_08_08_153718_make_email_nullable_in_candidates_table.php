<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeEmailNullableInCandidatesTable extends Migration
{
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
}

