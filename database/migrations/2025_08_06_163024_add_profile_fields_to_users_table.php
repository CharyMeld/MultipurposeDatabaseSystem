<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_name')->unique()->after('id');
            $table->string('firstname')->nullable()->after('login_name');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('middlename')->nullable()->after('lastname');
            $table->string('gender')->nullable()->after('middlename');
            $table->string('marital_status')->nullable()->after('gender');
            $table->tinyInteger('status')->default(1)->after('marital_status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'login_name', 'firstname', 'lastname', 'middlename', 'gender', 'marital_status', 'status'
            ]);
        });
    }

};
