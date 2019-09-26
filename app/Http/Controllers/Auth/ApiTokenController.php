<?php

namespace App\Http\Controllers\Auth;

use App\Fscs\Repositories\AccessTokenRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\TokenAuthRequest;
use App\Http\RequestValidators\TokenAuthRequestValidator;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\SingleElementResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Responses\UnauthorizedResponse;

class ApiTokenController extends Controller
{
    /** @var TokenAuthRequestValidator  */
    private $tokenAuthRequestValidator;

    /** @var AccessTokenRepository  */
    private $accessTokenRepository;

    public function __construct(TokenAuthRequestValidator $tokenAuthRequestValidator, AccessTokenRepository $accessTokenRepository)
    {
        $this->tokenAuthRequestValidator = $tokenAuthRequestValidator;
        $this->accessTokenRepository = $accessTokenRepository;

        $this->middleware('auth:api')
            ->only('me');
    }

    public function token(Request $request)
    {
        $tokenAuthRequest = TokenAuthRequest::createFromRequest($request);

        $this->tokenAuthRequestValidator->validate($tokenAuthRequest);
        if(!$this->tokenAuthRequestValidator->validationPassed()){
            return new BadRequestResponse($this->tokenAuthRequestValidator->getValidationErrors());
        }

        if(!\Auth::attempt([
            'name' => $tokenAuthRequest->username,
            'password' => $tokenAuthRequest->password
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

        return new SingleElementResponse([
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }

    public function me(Request $request)
    {
        if($user = $request->user()) {
            return new SingleElementResponse($user);
        } else {
            return new UnauthorizedResponse();
        }
    }
}
