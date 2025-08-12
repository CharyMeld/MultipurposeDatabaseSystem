<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'route_name')) {
                $table->string('route_name')->nullable();
            }

            if (!Schema::hasColumn('menus', 'path')) {
                $table->string('path')->nullable();
            }

            if (!Schema::hasColumn('menus', 'url')) {
                $table->string('url')->nullable();
            }

            if (!Schema::hasColumn('menus', 'order')) {
                $table->integer('order')->default(0);
            }

            if (!Schema::hasColumn('menus', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable();
            }

            if (!Schema::hasColumn('menus', 'status')) {
                $table->string('status')->default('active');
            }
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Optional: Only drop if exists
            if (Schema::hasColumn('menus', 'route_name')) {
                $table->dropColumn('route_name');
            }

            if (Schema::hasColumn('menus', 'path')) {
                $table->dropColumn('path');
            }

            if (Schema::hasColumn('menus', 'url')) {
                $table->dropColumn('url');
            }

            if (Schema::hasColumn('menus', 'order')) {
                $table->dropColumn('order');
            }

            if (Schema::hasColumn('menus', 'parent_id')) {
                $table->dropColumn('parent_id');
            }

            if (Schema::hasColumn('menus', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

