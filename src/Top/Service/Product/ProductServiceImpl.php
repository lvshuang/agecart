<?php
/**
 * 产品服务.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;

class ProductServiceImpl extends BaseService
{
    
    public function addProduct(array $product)
    {
        if (empty($product)) {
            throw new \Top\Service\Common\ServiceException('product empty');
        }
        
        try {
            
        } catch (\Exception $ex) {
            
        }
    }
    
    protected function validateProduct($product)
    {
        
    }
    
}
