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
        Schema::create('todo', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description');
            //$table->integer('user_id');
            $table->timestamps();  

            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');         
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo');
    }
};
