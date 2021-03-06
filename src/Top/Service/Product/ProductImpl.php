<?php
/**
 * 产品服务.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;
use Top\Common\BusinessException;

class ProductImpl extends ProductBaseService implements ProductInterface
{
    
    public function addProduct(array $product, array $attrs, array $images)
    {
        if (empty($product)) {
            throw new BusinessException('product empty');
        }
        $this->validateProduct($product);
        $this->getProductDao()->beginTransaction();
        try {
            $productInfo = array(
                'product_name' => $product['name'],
                'category_id' => $product['category_id'],
                'brand_id' => $product['brand_id'],
                'description' => $product['descript'],
                'create_time' => time()
            );
            $productId = $this->getProductDao()->add($productInfo);
            if (!$productId) {
                throw new \Exception('添加商品失败');
            }
            foreach ($attrs as &$attr) {
                $attr['product_id'] = $productId;
                $attr['create_time'] = time();
            }
            if ($attrs) {
                $insertAttr = $this->getProductAttrDao()->addMutil($attrs);
                if (!$insertAttr) {
                    throw new \Exception('增加属性失败');
                }
            }
            
            $productImgs = [];
            foreach ($images as $image) {
                $tmpImg = [];
                $tmpImg['product_id'] = $productId;
                $tmpImg['uri'] = $image;
                $tmpImg['size'] = 1;
                $productImgs[] = $tmpImg;
            }
            if ($productImgs) {
                $insertImages = $this->getProductImageDao()->addMutil($productImgs);
                if (!$insertImages) {
                    throw new \Exception('写入商品图片信息失败');
                }
            }

            $this->getProductDao()->commit();
            return $productId;
        } catch (\Exception $ex) {
            $this->getProductDao()->rollBack();
            $this->getLogger()->error('创建商品信息失败:' . $ex->getMessage());
            throw $ex;
        }
    }
    
    /**
     * 根据产品id获取产品信息.
     * 
     * @param integer $id    产品id.
     * @param string  $field 返回的字段.
     * 
     * @return array
     * 
     * @throws BusinessException
     */
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
        $saveData['update_time'] = $currentTime = time();

        $this->getProductDao()->beginTransaction();

        try {
            if (isset($updateData['attrs']) && is_array($updateData['attrs'])) {
                $productAttrs = $this->getProductAttrDao()->getAllByProductId($id);
                $productAttrs = \Top\Common\ArrayToolkit::index('name', $productAttrs);

                $needInsert = [];
                $needDeleteNames = [];

                $nameIndexUpdateAttr = \Top\Common\ArrayToolkit::index('name', $updateData['attrs']);
                foreach ($updateData['attrs'] as $attr) {
                    if (isset($productAttrs[$attr['name']]) && $productAttrs[$attr['name']] != $attr['value']) {
                        $updateData = ['value' => $attr['value'], 'update_time' => $currentTime];
                        $updateAttr = $this->getProductAttrDao()->updateByProductIdAndName($id, $attr['name'], $updateData);
                        $updateData = true;
                        if (!$updateAttr) {
                            throw new BusinessException('修改商品属性失败');
                        }
                    }
                    if (!isset($productAttrs[$attr['name']])) {
                        $needInsert[] = [
                            'name' => $attr['name'],
                            'value' => $attr['value'],
                            'product_id' => $id,
                            'update_time' => $currentTime,
                            'create_time' => $currentTime
                        ];
                    }
                }
                
                foreach ($productAttrs as $name => $value) {
                    if (!isset($nameIndexUpdateAttr[$name])) {
                        $needDeleteNames[] = $name;
                    }
                }

            }
            if ($needInsert) {
                $insertNewAttr = $this->getProductAttrDao()->addMutil($needInsert);
                if (!$insertNewAttr) {
                    throw new BusinessException('写入新商品信息失败');
                }
            }
            if ($needDeleteNames) {
                $deleteCond = [
                    'name' => $needDeleteNames,
                    'product_id' => $id,
                ];
                $deleteAttr = $this->getProductAttrDao()->deleteByCond($deleteCond);
                if (!$deleteAttr) {
                    throw new BusinessException('删除属性异常');
                }
            }
            $updateProductInfo = $this->getProductDao()->upateById($id, $saveData);
            if (!$updateProductInfo) {
                throw new BusinessException('修改商品信息失败');
            }
            return $this->getProductDao()->commit();
        } catch (\Exception $e) {
            $this->getProductDao()->rollBack();
            throw $e;
        }
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
    
    public function addProductSku($productId, array $skuInfo, $attrs = array(), $images = array())
    {
        $product = $this->getProductById($productId);
        if (!$product) {
            throw new BusinessException('商品不存在');
        }
        $this->validateProductSku($skuInfo);
        
        $newSkuData = [
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
        ];
        foreach ($attrs as &$attr) {
            $attr['sku'] = $newSkuData['sku'];
            $attr['create_time'] = time();
        }
        $this->getProductSkuDao()->beginTransaction();
        try {
            if (!$this->getProductSkuDao()->add($newSkuData)) {
                throw new \Exception('添加SKU信息失败');
            } 
            if (!$this->getProductSkuAttrDao()->addMutil($attrs)) {
                throw new \Exception('添加SKU属性失败');
            }
            $productImgs = [];
            foreach ($images as $image) {
                $tmpImg = [];
                $tmpImg['product_id'] = $productId;
                $tmpImg['uri'] = $image;
                $tmpImg['size'] = 1;
                $tmpImg['sku'] = $newSkuData['sku'];
                $productImgs[] = $tmpImg;
            }
            if ($productImgs) {
                $insertImages = $this->getProductImageDao()->addMutil($productImgs);
                if (!$insertImages) {
                    throw new \Exception('写入商品图片信息失败');
                }
            }
            $this->getProductSkuDao()->commit();

            return $newSkuData['sku'];
        } catch (\Exception $ex) {
            $this->getProductSkuDao()->rollBack();
            $this->getLogger()->error('创建SKU失败:' . $ex->getMessage());
            throw $ex;
        }
        
    }
    
    public function getProductCount(array $condition)
    {
        return $this->getProductDao()->getCountByCondition($condition);
    }
    
    public function getProductList(array $condition, $start, $limit = null, $orderBy = null)
    {
        return $this->getProductDao()->getByCondition($condition, '*', $start, $limit, $orderBy);
    }
    
    /**
     * 根据sku返回sku信息.
     * 
     * @param string $sku sku.
     * 
     * @return boolean|array
     */
    public function getSkuInfo($sku)
    {
        if (empty($sku)) {
            return false;
        }
        return $this->getProductSkuDao()->getBySku($sku, '*');
    }
    
    /**
     * 返回产品下的所有sku详情.
     * 
     * @param integer $productId 产品id.
     * @param string  $fields    返回的字段.
     * 
     * @return array
     */
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

    public function getProductAttrs($productId)
    {
        $productId = (int) $productId;
        if (!$productId) {
            throw new BusinessException('商品ID错误');
        }
        return $this->getProductAttrDao()->getAllByProductId($productId);
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
