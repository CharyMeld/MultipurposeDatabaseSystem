<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->string('grade')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps(); // includes created_at and updated_at columns
            
            // Foreign keys - make sure these tables exist
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('set null');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}

