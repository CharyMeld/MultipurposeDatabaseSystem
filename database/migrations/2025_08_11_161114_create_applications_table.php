<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->unsignedBigInteger('candidate_id');
            $table->string('code')->nullable();
            $table->string('application_type');
            $table->string('exam_number');
            $table->text('previous_attempts')->nullable();
            $table->date('exam_date')->nullable();
            $table->text('other_details')->nullable();
            $table->boolean('status')->default(false);
            $table->date('application_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->boolean('passed')->default(false);
            $table->unsignedBigInteger('exam_center_id')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');

            // Index for composite fields if you want
            $table->index(['application_type', 'exam_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}

