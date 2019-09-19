<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 20:14
 */

namespace App\Http\RequestValidators;


use App\Http\RequestValidators\Dto\ValidationError;

class Validator
{
    protected $validationErrors = [];

    public function validationPassed()
    {
        return empty($this->validationErrors);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    protected function addError(string $field, string $message)
    {
        $this->validationErrors[] = new ValidationError($field, $message);
    }

    /**
     * @param $var
     * @param string $name
     * @return bool
     */
    public function int($var, $name = "variable")
    {
        if(!preg_match('/^([0-9]|-?[1-9][0-9]*)$/', print_r($var, true))){
            $this->addError($name, 'Must be integer');
            return false;
        }
        return true;
    }

    public function positiveInt($var, $name = "variable")
    {
        if(!preg_match('/^[1-9][0-9]*$/', print_r($var, true))){
            $this->addError($name, 'Must be positive integer');
            return false;
        }
        return true;
    }

    public function float($var, $name = "variable")
    {
        if(!preg_match('/^([0-9]|-?[1-9][0-9]*)[.]?[0-9]*$/', print_r($var, true))){
            $this->addError($name, 'Must be float');
            return false;
        }
        return true;
    }

    public function alphanumericSting($var, $name = "variable")
    {
        if(!preg_match('/^[a-zA-Z0-9 _-]*+$/', print_r($var, true))){
            $this->addError($name, 'Must contains only letters, digits, spaces, -, _ characters');
            return false;
        }
        return true;
    }

    public function notEmpty($var, $name = "variable")
    {
        if(empty(print_r($var, true))){
            $this->addError($name, 'Can not be empty');
            return false;
        }
        return true;
    }

    public function string($var, $name = "variable")
    {
        if(!is_string($var)){
            $this->addError($name, 'Must be string');
            return false;
        }
        return true;
    }


}