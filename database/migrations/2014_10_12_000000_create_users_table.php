<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account')->unique();
            $table->string('gender')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->integer('ago')->nullable();
            $table->string('Login_method')->nullable();
            $table->string('email');
            $table->string('secret')->nullable();
            $table->string('code')->nullable();
            $table->string('client_id')->nullable(369);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
};
