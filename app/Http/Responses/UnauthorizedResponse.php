<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

namespace App\Http\Responses;


use App\Http\RequestValidators\Dto\ValidationError;

class UnauthorizedResponse extends BaseResponse
{
    protected $status = 401;
    protected $statusMessage = 'Unauthorized';

    /**
     * BadRequestResponse constructor.
     * @param ValidationError[] $validationErrors
     */
    public function __construct()
    {
        parent::__construct(null);
    }
}