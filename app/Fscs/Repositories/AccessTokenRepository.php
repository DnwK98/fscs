<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 19.09.2019
 * Time: 20:39
 */

namespace App\Fscs\Repositories;


use Illuminate\Support\Facades\DB;

class AccessTokenRepository
{
    public function removeExpiredAccessTokensForUser($userId)
    {
        // Remove tokens which expired more than 10 minutes ago

        $dateTime = (new \DateTime())
            ->modify('-10 minutes');

        DB::table('oauth_access_tokens')
            ->where('user_id', '=', $userId)
            ->where('expires_at', '<', $dateTime)
            ->delete();
    }
}