<?php
namespace Top\Service\Product;

abstract  class ProductBaseService extends \Top\Service\Common\BaseService
{

    protected function getProductDao() 
    {
        return \Top\Service\Product\Dao\ProductDaoImpl::instance($this->container->get('database_connection'));
    }

    protected function getProductSkuDao()
    {
        return \Top\Service\Product\Dao\ProductSkuDaoImpl::instance($this->container->get('database_connection'));
    }
    
    protected function getBrandService()
    {
        return \Top\Service\Product\BrandImpl::instance($this->container);
    }

    protected function getProductAttrDao()
    {
        return \Top\Service\Product\Dao\ProductAttrDao::instance($this->container->get('database_connection'));
    }
    
    protected function getProductSkuAttrDao()
    {
        return \Top\Service\Product\Dao\ProductSkuAttrDao::instance($this->container->get('database_connection'));
    }

    protected function getProductImageDao()
    {
        return \Top\Service\Product\Dao\ProductImageDao::instance($this->container->get('database_connection')); 
    }

}