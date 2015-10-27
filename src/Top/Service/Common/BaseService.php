<?php
namespace Top\Service\Common;

abstract class BaseService
{

	protected static $instance = array();
	protected $container;


	public static function instance()
	{
		$className = get_called_class();
		if (!isset(self::$instance[$className])) {
			self::$instance[$className] = new $className;
		}
		return self::$instance[$className];
	}

	public function setContainer($container)
	{
		$this->container = $container;
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
		return $this->createDao('Auth.AccessTokenDao');
	}
        
        protected function getProductDao()
        {
            return $this->createDao('Product.ProductDao');
        }

}