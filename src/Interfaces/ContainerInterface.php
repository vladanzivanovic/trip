<?php


namespace CoreBundle\Interfaces;


use CoreBundle\Helper\SessionHelper;
use CoreBundle\Lib\LeaTranslator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

interface ContainerInterface
{
    /**
     * @return ContainerBuilder
     */
    public function getContainer();

    /**
     * @return SessionHelper
     */
    public function getSession();

    /**
     * @param $service
     * @return object
     */
    public function getServices($service);

    /**
     * @param $model
     *
     * @return object
     */
    public function getModel($model);

    /**
     * @return Request|static
     */
    public function getRequest();

    /**
     * @return YamlFileLoader
     */
    public function getYmlLoader();

    /**
     * @return LeaTranslator
     */
    public function getTranslator();
}