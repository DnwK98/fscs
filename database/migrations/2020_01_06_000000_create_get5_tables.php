<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGet5Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('get5_stats_matches', function (Blueprint $table) {
            $table->unsignedInteger('matchid')->autoIncrement();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('winner', 16)->default(0);
            $table->string('series_type', 64)->default("");
            $table->string('team1_name', 64)->default("");
            $table->integer('team1_score')->default(0);
            $table->string('team2_name', 64)->default("");
            $table->integer('team2_score')->default(0);
        });

        Schema::create('get5_stats_maps', function (Blueprint $table) {
            $table->unsignedInteger('matchid');
            $table->smallInteger('mapnumber');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('winner', 16)->default("");
            $table->string('mapname', 64)->default("");
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);

            $table->primary(['matchid', 'mapnumber']);
        });

        Schema::create('get5_stats_players', function (Blueprint $table) {
            $table->unsignedInteger('matchid');
            $table->smallInteger('mapnumber');
            $table->string('steamid64', 32);
            $table->string('team', 16)->default("");
            $table->smallInteger('rounds_played');
            $table->string('name', 64);
            $table->integer('kills');
            $table->integer('deaths');
            $table->integer('assists');
            $table->unsignedInteger('flashbang_assists');
            $table->unsignedInteger('teamkills');
            $table->unsignedInteger('headshot_kills');
            $table->unsignedInteger('damage');
            $table->unsignedInteger('bomb_plants');
            $table->unsignedInteger('bomb_defuses');
            $table->unsignedInteger('v1');
            $table->unsignedInteger('v2');
            $table->unsignedInteger('v3');
            $table->unsignedInteger('v4');
            $table->unsignedInteger('v5');
            $table->unsignedInteger('2k');
            $table->unsignedInteger('3k');
            $table->unsignedInteger('4k');
            $table->unsignedInteger('5k');
            $table->unsignedInteger('firstkill_t');
            $table->unsignedInteger('firstkill_ct');
            $table->unsignedInteger('firstdeath_t');
            $table->unsignedInteger('firstdeath_ct');

            $table->primary(['matchid', 'mapnumber', 'steamid64']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get5_stats_matches');
        Schema::dropIfExists('get5_stats_maps');
        Schema::dropIfExists('get5_stats_players');
    }
}
