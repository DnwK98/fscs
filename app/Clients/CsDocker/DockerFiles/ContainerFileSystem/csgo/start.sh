#!/bin/bash

# Get gateway ip
export GATEWAY_IP=$(ip route | awk '/^default/ { print $3 }')

# Declare variables
export SERVER_HOSTNAME="${SERVER_HOSTNAME:-FSCS CSGO Server}"
export SERVER_PASSWORD="${SERVER_PASSWORD:-}"
export RCON_PASSWORD="${RCON_PASSWORD:-nd72mskf63ngx0lnx82jd83}"
export GAME_TYPE="${GAME_TYPE:-0}"
export GAME_MODE="${GAME_MODE:-1}"
export MAP="${MAP:-de_dust}"
export MAPGROUP="${MAPGROUP:-mg_active}"
export MAXPLAYERS="${MAXPLAYERS:-12}"
export TICKRATE="${TICKRATE:-128}"

export MATCH_ID="${MATCH_ID:-}"
export STEAM_ACCOUNT="${STEAM_ACCOUNT:-}"
export JSON_MATCH_CONFIGURATION="${JSON_MATCH_CONFIGURATION:-}"
export DB_NAME="${DB_NAME:-}"
export DB_USER="${DB_USER:-}"
export DB_PASSWORD="${DB_PASSWORD:-}"
export DB_PORT="${DB_PORT:-}"

[[ -z "$MATCH_ID" ]] && echo "Empty MATCH_ID" && exit 1;
[[ -z "$STEAM_ACCOUNT" ]] && echo "Empty STEAM_ACCOUNT" && exit 1;
[[ -z "$JSON_MATCH_CONFIGURATION" ]] && echo "Empty JSON_MATCH_CONFIGURATION" && exit 1;
[[ -z "$DB_NAME" ]] && echo "Empty DB_NAME" && exit 1;
[[ -z "$DB_USER" ]] && echo "Empty DB_USER" && exit 1;
[[ -z "$DB_PASSWORD" ]] && echo "Empty DB_PASSWORD" && exit 1;
[[ -z "$DB_PORT" ]] && echo "Empty DB_PORT" && exit 1;

cd /csgo

### Append dynamic server config
cat << SERVERCFG >> /csgo/cfg/server.cfg
hostname "${SERVER_HOSTNAME}"
rcon_password "${RCON_PASSWORD}"
sv_password "${SERVER_PASSWORD}"
SERVERCFG

cat /csgo/cfg/server.cfg

### Add server administrators
cat << ADMINS > /csgo/csgo/addons/sourcemod/configs/admins_simple.ini
"STEAM_0:0:111111111" "99:z"
ADMINS

### Set database configuration for get5 plugin
cat << DATABASE > /csgo/csgo/addons/sourcemod/configs/databases.cfg
"Databases"
{
	"driver_default"		"mysql"

	"default"
	{
		"driver"			"default"
		"host"				"localhost"
		"database"			"sourcemod"
		"user"				"root"
		"pass"				""
		//"timeout"			"0"
		//"port"			"0"
	}

	"get5"
	{
		"driver"			"default"
		"host"				"${GATEWAY_IP}"
		"database"			"${DB_NAME}"
		"user"				"${DB_USER}"
		"pass"				"${DB_PASSWORD}"
		//"timeout"			"0"
		//"port"			"0"
	}

	"storage-local"
	{
		"driver"			"sqlite"
		"database"			"sourcemod-local"
	}

	"clientprefs"
	{
		"driver"			"sqlite"
		"host"				"localhost"
		"database"			"clientprefs-sqlite"
		"user"				"root"
		"pass"				""
		//"timeout"			"0"
		//"port"			"0"
	}
}
DATABASE

### Append dynamically get5 cvars
cat << GET5CFG > /csgo/csgo/cfg/sourcemod/get5.cfg
get5_allow_technical_pause "1"
get5_autoload_config "addons/sourcemod/configs/get5/match.json"
get5_backup_system_enabled "1"
get5_check_auths "1"
get5_damageprint_format "--> ({DMG_TO} dmg / {HITS_TO} hits) to ({DMG_FROM} dmg / {HITS_FROM} hits) from {NAME} ({HEALTH} HP)"
get5_demo_name_format "{MATCHID}_map{MAPNUMBER}_{MAPNAME}"
get5_display_gotv_veto "0"
get5_end_match_on_empty_server "0"
get5_event_log_format ""
get5_fixed_pause_time "0"
get5_hostname_format "FSCS: {TEAM1} vs {TEAM2}"
get5_kick_immunity "1"
get5_kick_when_no_match_loaded "1"
get5_live_cfg "get5/live.cfg"
get5_live_countdown_time "10"
get5_max_backup_age "160000"
get5_max_pause_time "300"
get5_max_pauses "0"
get5_message_prefix ""
get5_pausing_enabled "1"
get5_print_damage "0"
get5_reset_pauses_each_half "1"
get5_server_id "0"
get5_set_client_clan_tags "1"
get5_stats_path_format "get5_matchstats_{MATCHID}.cfg"
get5_stop_command_enabled "1"
get5_time_format "%Y-%m-%d_%H"
get5_time_to_make_knife_decision "60"
get5_time_to_start "0"
get5_veto_confirmation_time "2.0"
get5_veto_countdown "5"
get5_warmup_cfg "get5/warmup.cfg"
get5_mysql_force_matchid "${MATCH_ID}"
GET5CFG

### Set match config for get5 plugin
cat << MATCHCFG >  /csgo/csgo/addons/sourcemod/configs/get5/match.json
${JSON_MATCH_CONFIGURATION}
MATCHCFG

cat /csgo/csgo/addons/sourcemod/configs/get5/match.json

### Run counter-strike server instance
./srcds_run \
    -console \
    -usercon \
    -game csgo \
    -tickrate ${TICKRATE} \
    -port 27015 \
    -maxplayers_override ${MAXPLAYERS} \
    +game_type ${GAME_TYPE} \
    +game_mode ${GAME_MODE} \
    +mapgroup ${MAPGROUP} \
    +map ${MAP} \
    +ip 0.0.0.0 \
    +sv_setsteamaccount ${STEAM_ACCOUNT} \
    -nowatchdog \
    +host_timer_spin_ms 0 \
    -pingbost 2
