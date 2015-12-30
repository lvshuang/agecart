<?php
/**
 * 分类服务接口.
 * 
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

interface CategoryInterface 
{
    
    public function addCategory(array $category);
    
    public function getCategory($id);
    
    public function updateCategory($id, array $updateFields);
    
    public function deleteCategory($id);
    
    public function isCategoryExist($id);
    
    public function makeCategoryTree();
    
    public function getChildren($id);
    
}
