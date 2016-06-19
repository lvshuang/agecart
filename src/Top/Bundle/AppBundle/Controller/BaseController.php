<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{

    protected function getAccessTokenService() 
    {
        return \Top\Service\Auth\AccessTokenImpl::instance($this->container);
    }
    
    protected function getCategoryService()
    {
        return \Top\Service\Product\CategoryImpl::instance($this->container);
    }
    
    protected function getProductService()
    {
        return \Top\Service\Product\ProductImpl::instance($this->container);
    }


    protected function createJsonResponse($data)
    {
        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }
    
    protected function getLogger()
    {
        return $this->container->get('logger');
    }
    
}
