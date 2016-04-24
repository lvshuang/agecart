<?php
/**
 * 产品服务.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;
use Top\Common\BusinessException;

class ProductImpl extends BaseService
{
    
    public function addProduct(array $product)
    {
        if (empty($product)) {
            throw new BusinessException('product empty');
        }
        try {
            $this->validateProduct($product);
            $productInfo = array(
                'product_name' => $product['name'],
                'category_id' => $product['category_id'],
                'description' => $product['descript'],
                'create_time' => time()
            );
            return $this->getProductDao()->add($productInfo);
        } catch (\Exception $ex) {
            $this->getLogger()->error('创建商品信息失败:' . $ex->getMessage());
            throw $ex;
        }
    }
    
    public function getProductById($id, $field = '*')
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new BusinessException('product id error');
        }
        
    }
    
    protected function validateProduct($product)
    {
        if (!filter_var($product['category_id'], FILTER_VALIDATE_INT) ||
            !$this->getCategoryService()->isCategoryExist($product['category_id'])
        ) {
            throw new BusinessException('分类不正确或者分类不存在');
        }
        if (empty($product['name']) || 
            mb_strlen($product['name']) < 2 || 
            mb_strlen($product['name']) > 64
        ) {
            throw new BusinessException('商品名称不满足要求');
        }
        return true;
    }
    
}
