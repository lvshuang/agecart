<?php
/**
 * 分类数据层接口.
 * 
 * @author Lvh <lvshuang1201.com>
 */
namespace Top\Service\Product\Dao;

interface CategoryDao
{
    const ENABLED = 1;
    const DISABLED = 0;
    
    public function addCategory(array $category);
    
    public function getCategory($id);
    
    public function updateById($id, array $updateFields);
    
    public function deleteById($id);
    
    public function countByCondition(array $condition);
    
    public function getByCondition(array $condition, $orderBy = null, $start = 0, $limit = null);
    
    public function getAll();
    
}

