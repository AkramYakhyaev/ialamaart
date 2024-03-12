<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaintingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paintings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link', 48)->unique();
            $table->string('name', 48);
            $table->string('description', 4096);
            $table->integer('price')->default(0)->index();
            $table->integer('availability')->default(1)->index();
            $table->integer('category')->unsigned()->index();
            $table->foreign('category')->references('id')->on('categories');
            $table->string('size', 16)->index();
            $table->string('orientation', 16)->index();
            $table->softDeletes();
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
        Schema::dropIfExists('paintings');
    }
}
