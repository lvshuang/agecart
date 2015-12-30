<?php
/**
 * 分类服务接口实现.
 * 
 * @author lvhshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Service\Common\BaseService;
use Top\Common\BusinessException;

class CategoryImpl extends \Top\Service\Common\BaseService implements \Top\Service\Product\CategoryInterface
{
    
    public function addCategory(array $category) 
    {
        $this->validateCategory($category);
        $category['name'] = trim($category['name']);
        
        $categoryId = $this->getCategoryDao()->addCategory($category);
        return $this->getCategory($categoryId);
    }
    
    public function getCategory($id) 
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        return $this->getCategoryDao()->getCategory($id);
    }
    
    public function updateCategory($id, array $updateFields) 
    {
        if (empty($updateFields)) {
            throw new BusinessException('UPDATE_FIELDS_EMPTY');
        }
        return $this->getCategoryDao()->updateById($id, $updateFields);
    }
    
    public function isCategoryExist($id) 
    {
        $category = $this->getCategory($id);
        return $category ? true : false;
    }
    
    public function deleteCategory($id) 
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        
        return $this->getCategoryDao()->deleteById($id);
    }
    
    public function makeCategoryTree() 
    {
        $categorys = $this->getCategoryDao()->getAll();
        $topCategories = array();
        $level1Categories = array();
        $level2Categories = array();
        foreach ($categorys as $category) {
            if ($category['parent_id'] == 0) {
                $topCategories[$category['id']] = $category;
            }
        }
        $level1CategoryIds = array();
        foreach ($categorys as $category) {
            if (in_array($category['parent_id'], array_keys($topCategories))) {
                $level1CategoryIds[] = $category['id'];
                $level1Categories[$category['parent_id']][$category['id']] = $category;
            }
        }
        foreach ($categorys as $category) {
            if (in_array($category['parent_id'], $level1CategoryIds)) {
                $level2Categories[$category['parent_id']][$category['id']] = $category;
            }
        }
        
        return array('top' => $topCategories, 'level1' => $level1Categories, 'level2' => $level2Categories);
    }
    
    public function getTopCategory() 
    {
        $conditon = array(
            'parent_id' => 0,
        );
        return $this->getCategoryDao()->getByCondition($conditon);
    }
    
    public function getChildren($id)
    {
        $condition = array(
            'parent_id' => (int) $id,
        );
        return $this->getCategoryDao()->getByCondition($condition);
    }
    
    protected function validateCategory(array $category)
    {
        if (!isset($category['parent_id'])) {
            throw new BusinessException('PARENT_ID_NOT_SET');
        }
        if ($category['parent_id'] > 0 && !$this->isCategoryExist($category['parent_id'])) {
            throw new BusinessException('PARENT_CATEGORY_NOT_EXIST');
        } 
        $name = trim($category['name']);
        if (empty($name) || mb_strlen($name) > 50) {
            throw new BusinessException('CATEGORE_NAME_ILLEGAL');
        }
    }
    
}
