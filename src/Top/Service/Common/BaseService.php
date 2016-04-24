<?php

namespace Top\Service\Common;

abstract class BaseService {

    protected static $instance = array();
    protected $container;

    public static function instance($container) 
    {
        $className = get_called_class();
        if (!isset(self::$instance[$className])) {
            self::$instance[$className] = new $className;
            self::$instance[$className]->setContainer($container);
        }
        return self::$instance[$className];
    }

    public function setContainer($container) 
    {
        $this->container = $container;
    }
    
    public function getContainer()
    {
        return $this->container;
    }
    
    protected function getLogger()
    {
        return $this->getContainer()->get('logger');
    }

    protected function createDao($name) 
    {
        list($service, $daoName) = explode('.', $name);
        $class = '\\Top\\Service\\' . $service . '\\Dao\\' . $daoName . 'Impl';
        if (!class_exists($class)) {
            throw new \Top\Common\BusinessException($class . ' NOT FIND');
        }
        $dao = $class::instance($this->container->get('database_connection'));
        return $dao;
    }

    protected function getAccessTokenDao() 
    {
        return \Top\Service\Auth\Dao\AccessTokenDaoImpl::instance($this->container->get('database_connection'));
    }

    protected function getProductDao() 
    {
        return \Top\Service\Product\Dao\ProductDaoImpl::instance($this->container->get('database_connection'));
    }

    protected function getCategoryDao() 
    {
        return \Top\Service\Product\Dao\CategoryDaoImpl::instance($this->container->get('database_connection'));
    }

    protected function getCategoryService() 
    {
        return \Top\Service\Product\CategoryImpl::instance($this->container);
    }

}
