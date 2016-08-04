<?php
/**
 * 产品服务.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;
use Top\Common\BusinessException;

class ProductImpl extends BaseService implements ProductInterface
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
                'brand_id' => $product['brand_id'],
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
    
    public function updateProduct($id, array $updateData)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new BusinessException('参数错误');
        }
        $saveData = array();
        if (isset($updateData['name'])) {
            $saveData['product_name'] = $updateData['name'];
        }
        if (isset($updateData['category_id'])) {
            $saveData['category_id'] = $updateData['category_id'];
        }
        if (isset($updateData['brand_id'])) {
            $saveData['brand_id'] = $updateData['brand_id'];
        }
        if (isset($updateData['descript'])) {
            $saveData['description'] = $updateData['descript'];
        }
        if (empty($saveData)) {
            return false;
        }
        $saveData['update_time'] = time();
        
        return $this->getProductDao()->upateById($id, $saveData);
    }
    
    protected function validateProduct($product)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($product['category_id']) ||
            !$this->getCategoryService()->isCategoryExist($product['category_id'])
        ) {
            throw new BusinessException('分类不正确或者分类不存在');
        }

        if (!\Top\Component\Validator\SimpleValidator::isUint($product['brand_id']) || 
            !$this->getBrandService()->isBrandExist($product['brand_id'])
        ) {
            throw new BusinessException('品牌不正确或者品牌不存在');
        }

        if (empty($product['name']) || 
            mb_strlen($product['name']) < 2 || 
            mb_strlen($product['name']) > 64
        ) {
            throw new BusinessException('商品名称不满足要求');
        }
        return true;
    }

    public function deleteById($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new BusinessException('商品ID错误');
        }

        return $this->getProductDao()->deleteById($id);
    }

    public function deleteByIds(array $ids)
    {

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
    
    public function getProductCount(array $condition)
    {
        return $this->getProductDao()->getCountByCondition($condition);
    }
    
    public function getProductList(array $condition, $start, $limit = null, $orderBy = null)
    {
        return $this->getProductDao()->getByCondition($condition, '*', $start, $limit, $orderBy);
    }

    public function getProductSkus($productId, $fields = '*')
    {
        return $this->getProductSkuDao()->getByProductId((int) $productId, $fields);
    }
    
    protected function validateProductSku(array $skuInfo)
    {
        $shortName = trim($skuInfo['short_name']);
        if(!$shortName) {
            throw new BusinessException('商品短标题不能为空');
        }
        if (!filter_var($skuInfo['price'], FILTER_VALIDATE_FLOAT)) {
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

    protected function getBrandService()
    {
        return \Top\Service\Product\BrandImpl::instance($this->container);
    }
    
}
