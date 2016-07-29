<?php
namespace Top\Service\Product\Dao;

class ProductSkuDaoImpl extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\ProductSkuDao
{
    const TABLE_NAME = 'product_sku';
    
    public function add(array $skuInfo) 
    {
        return $this->insert(self::TABLE_NAME, $skuInfo);
    }
    
    public function getByProductId($productId, $fields) 
    {
        return $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where(array('product_id' => $productId))
            ->fetchAll();
    }
    
    public function getBySku($sku, $fields) 
    {
        return $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where(array('sku' => $sku))
            ->fetchRow();
    }
    
    public function updateBySku($sku, array $updateData)
    {
        return $this->update(self::TABLE_NAME, array('sku' => $sku), $updateData);
    }
    
}
