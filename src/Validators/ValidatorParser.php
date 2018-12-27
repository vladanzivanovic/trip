<?php

namespace CoreBundle\Validators;

use CoreBundle\Lib\LeaTranslator;
use Sirius\Validation\ErrorMessage;

class ValidatorParser
{
    public function parseErrors(array $errors)
    {
        $parsedErros = [];
        foreach ($errors as $prop => $error) {
            foreach ($error as $object) {
                if (!$object instanceof ErrorMessage) {
                    continue;
                }
                $message = $object->getTemplate();

                $message = preg_replace('/[\{\}]/', '%', $message);

                foreach ($object->getVariables() as $name => $variable) {
                    $message = str_replace($name, $variable,  $message);
                }
                $parsedErros[$prop] = str_replace('%', '', $message);
            }
        }

        return $parsedErros;
    }
}