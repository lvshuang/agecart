<?php
/**
 * 产品数据库接口.
 * 
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product\Dao;

interface ProductDao
{
    public function add(array $product);
    
    public function getById($id, $fields = '*');
    
    public function getBySkuNo($skuNo, $fields = '*');
    
    public function getByCategoryId($categoryId, $fields = '*', $orderBy = null, $start = 0, $limit = null);
    
    public function upateById($id, array $updateData);
    
    public function deleteById($id);
    
    public function getByBrandId($brandId, $fields = '*', $orderBy = null, $start = 0, $limit = null);
    
}

