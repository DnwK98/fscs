<?php

namespace App\Http\Requests;


class ServerCreateRequest extends Request
{
    public function customRules()
    {
        $jsonString = $this->getContent();
        $body = json_decode($jsonString, true);

        if(!is_array($body)){
            return ['body' => "Is not valid json object"];
        }
        if(isset($body['id']) && !is_int($body['id'])){
            return ['id' => "Must be integer or null"];
        }
        if(!isset($body['port']) || !is_int($body['port'])){
            return ['port' => "Must be integer"];
        }
        if(!isset($body['map']) || !is_string($body['map'])){
            return ['map' => "Must be string"];
        }
        if(!isset($body['teams']) || !is_array($body['teams'])){
            return ['teams' => "Must be array"];
        }
        if(count($body['teams']) !== 2){
            return ['teams' => "Must have 2 elements"];
        }

        foreach ($body['teams'] as $team){
            if(!is_array($team)){
                return ['teams.team' => "Must be object"];
            }
            if(!isset($team['name']) || !is_string($team['name'])){
                return ['teams.team.name' => "Must be string"];
            }
            if(strlen($team['name']) > 50){
                return ['teams.team.name' => "Must be shorter than 50 characters"];
            }
            if(!isset($team['tag']) || !is_string($team['tag'])){
                return ['teams.team.tag' => "Must be string"];
            }
            if(strlen($team['tag']) > 6){
                return ['teams.team.tag' => "Must be shorter than 6 characters"];
            }
            if(!isset($team['players']) || !is_array($team['players'])){
                return ['teams.team.players' => "Must be array"];
            }

            foreach ($team['players'] as $player){
                if(!is_array($player)){
                    return ['teams.team.players.player' => "Must be object"];
                }
                if(!isset($player['steamId64']) || !is_numeric($player['steamId64'])){
                    return ['teams.team.players.player.steamId64' => "Must be numeric"];
                }
                if(!isset($player['name']) || !is_string($player['name'])){
                    return ['teams.team.players.player.name' => "Must be string"];
                }
                if(strlen($player['name']) > 50){
                    return ['teams.team.players.player.name' => "Must be shorter than 50 characters"];
                }
            }
        }

        if(count($body['teams'][0]['players']) !== count($body['teams'][1]['players'])){
            return ['teams.team.players' => "Players count must be equal in both teams"];
        }

        return [];
    }
}