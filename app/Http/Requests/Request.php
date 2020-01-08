<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class Request extends FormRequest
{

    public function validateResolved()
    {
        $this->castUserInput();
        parent::validateResolved();
    }

    public function rules()
    {
        return [];
    }

    private function castUserInput()
    {
        $rulesTable = $this->rules();

        foreach ($rulesTable as $name => $rulesString){
            $rules = explode("|", $rulesString);

            foreach (['query', 'request'] as $parameterBag){
                if(empty($this->$parameterBag)){
                    continue;
                }

                if($this->$parameterBag->get($name) !== null){
                    try {
                        if (in_array('int', $rules)) {
                            $this->$parameterBag->set($name, (int)$this->$parameterBag->get($name));
                        } elseif (in_array('integer', $rules)) {
                            $this->$parameterBag->set($name, (int)$this->$parameterBag->get($name));
                        } elseif (in_array('numeric', $rules)) {
                            $this->$parameterBag->set($name, (float)$this->$parameterBag->get($name));
                        } elseif (in_array('string', $rules)) {
                            $this->$parameterBag->set($name, (string)$this->$parameterBag->get($name));
                        } elseif (in_array('array', $rules)) {
                            $this->$parameterBag->set($name, (array)$this->$parameterBag->get($name));
                        }
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([$name => "The $name has invalid data type"]);
                    }
                }
            }
        }
    }
}
