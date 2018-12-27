<?php

namespace CoreBundle\Factory\Api;

use CoreBundle\Components\Api\AuthorizationService;
use CoreBundle\Components\Api\SignInParser;
use CoreBundle\Core\Container;
use CoreBundle\Lib\TestResponse;
use CoreBundle\Validators\AuthenticationValidator;
use Symfony\Component\HttpFoundation\Request;

class SignInFactory extends Container
{
    private static $instance = null;
    /** @var AuthorizationService $authorizationService */
    private $authorizationService;
    /** @var SignInParser $signInParser */
    private $signInParser;

    /**
     * SignInFactory constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->authorizationService = $this->getServices('app.authorization_service');
        $this->signInParser = $this->getServices('app.sign_in_parser');
    }

    /**
     * @return SignInFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof SignInFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Request $request
     *
     * @return TestResponse
     */
    public function signIn(Request $request): TestResponse
    {
        try {
            $data = $this->signInParser->parse($request);

            /** @var AuthenticationValidator $validator */
            $validator = $this->getServices('app.authentication_validator');
            $validator->setValidationRules();
            $errors = $validator->validate($data);

            if (null != $errors) {
                return new TestResponse(['errors' => $errors], TestResponse::HTTP_BAD_REQUEST);
            }

            $isValid = $this->authorizationService->authorizeUser($data['email'], $data['password']);

            if (true === $isValid) {
                return new TestResponse();
            }

            return new TestResponse(null, TestResponse::HTTP_FORBIDDEN);
        } catch (\Throwable $throwable) {
            return new TestResponse(null, TestResponse::HTTP_BAD_REQUEST);
        }
    }
}