<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // primary key
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('application_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location'); // file path to uploaded document
            $table->boolean('status')->default(true);
            $table->timestamp('time_created')->useCurrent();
            $table->timestamps(); // Laravel's created_at and updated_at

            // Composite unique constraint as per your PHP model (title + application_id)
            $table->unique(['title', 'application_id']);

            // Foreign keys (optional, require these tables to exist)
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}

