<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('cert_number')->nullable();
            $table->string('surname');
            $table->string('other_name');
            $table->string('maiden_name')->nullable();
            $table->string('change_of_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_address')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->string('sub_speciality')->nullable();
            $table->date('full_registration_date')->nullable();
            $table->string('entry_mode')->nullable();
            $table->string('nysc_discharge_or_exemption')->nullable();
            $table->string('prefered_exam_center')->nullable();
            $table->string('accredited_training_program')->nullable();
            $table->string('post_registration_appointment')->nullable();
            $table->timestamps();

            // Optional foreign key
            // $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};

