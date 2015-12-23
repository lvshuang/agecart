<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{

    protected function getService($name) 
    {
        list($service, $serviceName) = explode('.', $name);
        $class = '\\Top\\Service\\' . $service . '\\' . $serviceName . 'Impl';
        if (!class_exists($class)) {
            throw new \Top\Common\BusinessException($class . ' NOT FIND');
        }
        $service = $class::instance();
        $service->setContainer($this->container);
        return $service;
    }

    protected function getAccessTokenService() 
    {
        return $this->getService('Auth.AccessToken');
    }
    
    protected function getCategoryService()
    {
        return $this->getService('Product.Category');
    }

}
