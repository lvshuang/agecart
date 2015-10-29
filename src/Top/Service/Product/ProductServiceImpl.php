<?php
/**
 * 产品服务.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;
use Top\Common\BusinessException;

class ProductServiceImpl extends BaseService
{
    
    public function addProduct(array $product)
    {
        if (empty($product)) {
            throw new BusinessException('product empty');
        }
        
        try {
            return $this->getProductDao()->add($product);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    protected function validateProduct($product)
    {
    }
    
}
