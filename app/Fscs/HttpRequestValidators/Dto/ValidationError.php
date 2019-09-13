<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 20:37
 */

namespace App\Fscs\HttpRequestValidators\Dto;


class ValidationError
{
    /** @var string */
    public $field;

    /** @var string */
    public $error;

    public function __construct(string $field, string $error)
    {
        $this->field = $field;
        $this->error = $error;
    }
}