<?php


namespace CoreBundle\Core;


use CoreBundle\Helper\SessionHelper;
use CoreBundle\Interfaces\ContainerInterface;
use CoreBundle\Lib\LeaTranslator;
use CoreBundle\Lib\TestRouter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

abstract class Container implements ContainerInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;
    /**
     * @var YamlFileLoader
     */
    private $loader;

    /**
     * @var Request
     */
    private $request;
    /**
     * Container constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new ContainerBuilder();
        $this->loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ .'/../Resources/Config'));
        $this->request = Request::createFromGlobals();

        $this->loader->load('config.yml');
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return SessionHelper
     */
    public function getSession()
    {
        return $this->getServices('app.session_helper');
    }

    /**
     * @param $service
     * @return object
     */
    public function getServices($service)
    {
        return $this->container->get($service);
    }

    /**
     * @param $service
     * @return object
     */
    public function getModel($model)
    {
        return $this->container->get($model);
    }

    /**
     * @return Request|static
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function getYmlLoader()
    {
        return $this->loader;
    }

    /**
     * @return LeaTranslator
     */
    public function getTranslator()
    {
        return $this->getServices('app.translator');
    }

    /**
     * @return bool
     */
    public function isLoggedUser()
    {
        $user = $this->getSession()->getUser();

        return is_array($user) && isset($user['is_authenticated']) && true == $user['is_authenticated'];
    }

    public function getRouter()
    {
        return $this->getServices('app.router_service');
    }
}