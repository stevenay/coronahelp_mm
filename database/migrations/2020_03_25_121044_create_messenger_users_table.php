<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessengerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('messenger_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('language')->default("eng");
            $table->string('profile_pic')->nullable();
            $table->string('locale', '10')->nullable();
            $table->string('source');
            $table->integer('sessions');
            $table->string('gender')->nullable();
            $table->dateTime('last_seen_date');
            $table->boolean('subscribed')->default(false);

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
        Schema::dropIfExists('messenger_users');
    }
}
