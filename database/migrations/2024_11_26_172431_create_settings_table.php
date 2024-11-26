<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        // Create the 'settings' table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });

        // Insert the default 'email' setting value
        DB::table('settings')->insert([
            'key' => 'email',
            'value' => 'appchara12@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        // Drop the 'settings' table
        Schema::dropIfExists('settings');
    }
}
