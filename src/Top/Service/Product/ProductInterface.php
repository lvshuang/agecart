<?php
/**
 * 产品服务.
 * 
 * @author helv <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

interface ProductInterface
{
    
    public function getProductCount(array $condition);
    
    public function getProductList(array $condition, $start, $limit = null, $orderBy = null);
    
    public function updateProduct($id, array $updateData);

    public function getProductSkus($productId, $fields = '*');

    public function deleteById($id);

    public function deleteByIds(array $ids);
    
}
