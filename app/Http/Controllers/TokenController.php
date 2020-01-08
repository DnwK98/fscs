<?php

namespace App\Http\Controllers;

use App\Repositories\AccessTokenRepository;
use App\Http\Requests\TokenAuthRequest;
use App\Http\Responses\ObjectResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Responses\UnauthorizedResponse;

class TokenController extends Controller
{
    /** @var AccessTokenRepository  */
    private $accessTokenRepository;

    public function __construct(AccessTokenRepository $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function token(TokenAuthRequest $request)
    {
        if(!\Auth::attempt([
            'name' => $request->username,
            'password' => $request->password
        ])){
            return new UnauthorizedResponse();
        }

        /** @var User $user */
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addHour();
        $token->save();

        $this->accessTokenRepository->removeExpiredAccessTokensForUser($user->id);

        return new ObjectResponse([
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }
}
