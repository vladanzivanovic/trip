<?php

namespace CoreBundle\Factory\Api;

use CoreBundle\Components\Api\registrationService;
use CoreBundle\Components\Api\signUpParser;
use CoreBundle\Core\Container;
use CoreBundle\Lib\TestResponse;
use CoreBundle\Validators\AuthenticationValidator;
use Symfony\Component\HttpFoundation\Request;

class SignUpFactory extends Container
{
    private static $instance = null;
    /** @var RegistrationService $registrationService */
    private $registrationService;
    /** @var SignUpParser $signUpParser */
    private $signUpParser;

    /**
     * SignInFactory constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->registrationService = $this->getServices('app.registration_service');
        $this->signUpParser = $this->getServices('app.sign_up_parser');
    }

    /**
     * @return SignUpFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof SignUpFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Request $request
     *
     * @return TestResponse
     */
    public function signUp(Request $request)
    {
        try {
            $data = $this->signUpParser->parse($request);

            /** @var AuthenticationValidator $validator */
            $validator = $this->getServices('app.registration_validator');
            $validator->setValidationRules();
            $errors = $validator->validate($data);

            if (null != $errors) {
                return new TestResponse(['errors' => $errors], TestResponse::HTTP_BAD_REQUEST);
            }

            $this->registrationService->signUp($data);

            return new TestResponse();
        }catch (\Throwable $throwable) {
            return new TestResponse($throwable->getMessage(), TestResponse::HTTP_BAD_REQUEST);
        }
    }
}