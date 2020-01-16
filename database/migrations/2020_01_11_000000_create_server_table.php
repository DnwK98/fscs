<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('team_players');
        Schema::dropIfExists('server_teams');
        Schema::dropIfExists('servers');

        Schema::create('servers', function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->integer("match_id");
            $table->string("map", 32);
            $table->unsignedMediumInteger("port");
            $table->string("status", 16);

            $table->index('status');
            $table->index('match_id');
        });

        Schema::create('server_teams', function (Blueprint $table) {
            // Laravel's eloquent doesn't support multi key foreign
            // so create autoincrement id and set indexes for columns
            $table->integerIncrements("id");
            $table->unsignedInteger("server_id");
            $table->unsignedSmallInteger("team_number");
            $table->string("name", 128);
            $table->string("tag", 32);

            $table->foreign("server_id")->references("id")->on("servers");
        });
        Schema::create('team_players', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger("team_id");
            $table->string("steam_id_64", 64);
            $table->string("name", 128)->nullable();

            $table->index("steam_id_64");
            $table->foreign("team_id")->references("id")->on("server_teams");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_players');
        Schema::dropIfExists('server_teams');
        Schema::dropIfExists('servers');
    }
}
