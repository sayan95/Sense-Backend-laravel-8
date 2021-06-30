<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTherapistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapists', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('user_type')->default('therapist');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_published')->default(false);
            $table->boolean('profile_created')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('therapists');
    }
}
