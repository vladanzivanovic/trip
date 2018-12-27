<?php

namespace CoreBundle\Validators;

use CoreBundle\Interfaces\ValidationInterface;
use Sirius\Validation\Validator;

class AuthenticationValidator implements ValidationInterface
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
            'email:Email' => 'required',
            'password:Password' => 'required',
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