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
            throw new BusinessException('参数错误');
        }
        return $this->getProductDao()->getById($id, $field);
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
    
    public function addProductSku($productId, array $skuInfo)
    {
        $product = $this->getProductById($productId);
        if (!$product) {
            throw new BusinessException('商品不存在');
        }
        $this->validateProductSku($skuInfo);
        
        $newSkuData = array(
            'short_name' => trim($skuInfo['short_name']),
            'product_id' => $product['id'],
            'sku' => $this->generateSkuByProduct($product),
            'category_id' => $product['category_id'],
            'price' => sprintf('%.2f', $skuInfo['price']),
            'discount_price' => isset($skuInfo['discount_price']) ? sprintf('%.2f', $skuInfo['discount_price']) : 0.00,
            'inventory' => intval($skuInfo['inventory']),
            'meta_keyword' => trim($skuInfo['meta_keyword']),
            'meta_description' => trim($skuInfo['meta_description']),
            'create_time' => time(),
            'update_time' => time()
        );
        if ($this->getProductSkuDao()->add($newSkuData)) {
            return $newSkuData['sku'];
        }
        return false;
    }
    
    protected function validateProductSku(array $skuInfo)
    {
        $shortName = trim($skuInfo['short_name']);
        if(!$shortName) {
            throw new BusinessException('商品短标题不能为空');
        }
        if (!filter_var($skuInfo['price'], FILTER_VALIDATE_BOOLEAN)) {
            throw new BusinessException('商品价格不正确');
        }
        if (isset($skuInfo['discount_price']) && 
            $skuInfo['discount_price'] && 
            !filter_var($skuInfo['discount_price'], FILTER_VALIDATE_FLOAT)
        ) {
            throw new BusinessException('商品折后价式错误');
        }
        if ($skuInfo['discount_price'] > $skuInfo['price']) {
            throw new BusinessException('商品折后价大于原价');
        }
    }
    
    public function generateSkuByProduct(array $product)
    {
        $prefix = $product['category_id'] . '-' . $product['id'] . '-';
        return $this->generateSku($prefix);
    }
    
    public function generateSku($prefix)
    {
        return uniqid('SKU-' . $prefix);
    }
    
}