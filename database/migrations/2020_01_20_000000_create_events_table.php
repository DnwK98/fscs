<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string('name');
            $table->integer("int_index")->nullable();
            $table->integer('int_1')->nullable();
            $table->integer('int_2')->nullable();
            $table->string('string_index', 128)->nullable();
            $table->string('string_1', 254)->nullable();
            $table->string('content', 1024 * 4);
            $table->dateTime('created');

            $table->index('int_index');
            $table->index('string_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
