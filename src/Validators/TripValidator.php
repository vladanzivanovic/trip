<?php

namespace CoreBundle\Validators;

use CoreBundle\Interfaces\ValidationInterface;
use Sirius\Validation\Validator;

class TripValidator implements ValidationInterface
{
    private $validator;
    private $parser;

    /**
     * @param Validator       $validator
     * @param ValidatorParser $parser
     */
    public function __construct(
        Validator $validator,
        ValidatorParser $parser
    ) {
        $this->validator = $validator;
        $this->parser = $parser;
    }

    public function setValidationRules()
    {
        $this->validator->add([
            'name:Trip Name' => 'required',
            'type:Trip type' => 'required',
            'gpx:Gpx' => 'required',
        ]);
    }

    /**
     * @param array $data
     *
     * @return array|bool|mixed
     */
    public function validate(array $data)
    {
        $isValid = $this->validator->validate($data);

        if (false === $isValid) {
            return $this->parser->parseErrors($this->validator->getMessages());
        }

        return null;
    }
}