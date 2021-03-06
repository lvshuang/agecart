<?php
/**
 * 商品SKU DAO接口类.
 * 
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product\Dao;


interface ProductSkuDao
{
    
    public function add(array $skuInfo);
    
    public function getBySku($sku, $fields);
    
    public function getByProductId($productId, $fields);
    
    public function updateBySku($sku, array $updateData);

    public function getAll($fields, $orderBy = null, $start = 0, $limit = null);
    
}
