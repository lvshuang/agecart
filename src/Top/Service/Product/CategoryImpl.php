<?php
/**
 * 分类服务接口实现.
 * 
 * @author lvhshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product;

use Top\Common\BusinessException;

class CategoryImpl extends \Top\Service\Common\BaseService implements \Top\Service\Product\CategoryInterface
{

    protected $allowUpdateFields = array(
        'name',
        'enabled',
        'parent_id',
        'sort_order',
        'meta_keyword',
        'meta_description'
    );
    
    public function addCategory(array $category) 
    {
        $this->validateCategory($category);

        $newCategory = array();
        $newCategory['name'] = trim($category['name']);
        $newCategory['parent_id'] = $category['parent_id'] ? (int) $category['parent_id'] : 0;;
        $newCategory['sort_order'] = $category['sort_order'];
        $newCategory['meta_description'] = $category['meta_description'];
        $newCategory['meta_keyword'] = $category['meta_keyword'];
        $newCategory['update_time'] = time();
        $newCategory['create_time'] = time();
        
        $categoryId = $this->getCategoryDao()->addCategory($newCategory);
        return $this->getCategory($categoryId);
    }
    
    public function getCategory($id) 
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        return $this->getCategoryDao()->getCategory($id);
    }
    
    public function getCategoryByIds(array $ids, $fields = "*")
    {
        if (!$ids) {
            return array();
        }
        return $this->getCategoryDao()->getCategoryByIds(array_unique($ids), $fields);
    }
    
    public function updateCategory($id, array $updateFields) 
    {
        if (empty($updateFields)) {
            throw new BusinessException('UPDATE_FIELDS_EMPTY');
        }

        $updateFields = (array_filter($updateFields, function($key) {
            return in_array($key, $this->allowUpdateFields);
        }, ARRAY_FILTER_USE_KEY)); // ARRAY_FILTER_USE_KEY NEED PHP5.6
        $updateFields['update_time'] = time();

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

    public function makeCategoryTreeV2()
    {
        $categories = $this->getCategoryDao()->getAll('parent_id ASC');
        $tree = array();
        foreach ($categories as $category) {
            $tmp = $category;
            $tmp['name'] = $this->getNamesFromAllCategory($category, $categories, ' > ');
            $tree[$tmp['id']] = $tmp;
        }
        return $tree;
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
        if ($id < 0) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        $condition = array(
            'parent_id' => (int) $id,
        );
        return $this->getCategoryDao()->getByCondition($condition);
    }

    public function enableById($id)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        $category = $this->getCategory($id);
        if (!$category) {
            throw new BusinessException('CATEGORY_NOT_EXIST');
        }
        if (\Top\Service\Product\Dao\CategoryDao::ENABLED == $category['enabled']) {
            return true;
        }

        return $this->updateCategory($id, array('enabled' => 1, 'update_time' => time()));
    }

    public function disableById($id)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new BusinessException('CATEGORY_ID_ERROR');
        }
        $category = $this->getCategory($id);
        if (!$category) {
            throw new BusinessException('CATEGORY_NOT_EXIST');
        }
        if (\Top\Service\Product\Dao\CategoryDao::DISABLED == $category['enabled']) {
            return true;
        }

        return $this->updateCategory($id, array('enabled' => 0, 'update_time' => time()));
    }
    
    public function loadForSelect()
    {
        $allCategories = $this->getCategoryDao()->getAll('parent_id ASC, name ASC');
        
        $return = array();
        foreach ($allCategories as $category) {
            $return[$category['id']] = $this->getNamesFromAllCategory($category, $allCategories, ' > ');
        }
        return $return;
    }

    public function getAllowUpdateFields()
    {
        return $this->allowUpdateFields;
    }

    public function getNamesById($id, $connector = ' > ')
    {
        $currentCategory = $this->getCategory($id);
        if (!$currentCategory) {
            throw new BusinessException('分类不存在');
        }
        $categories = $this->getCategoryDao()->getAll();
        
        return $this->getNamesFromAllCategory($currentCategory, $categories, $connector);
    }

    protected function getNamesFromAllCategory($currentCategory, array $categories, $connector = ' > ')
    {
        $names = array();
        $names[] = $currentCategory['name'];
        $parentId = $currentCategory['parent_id'];
        while (true) {
            if (0 == $parentId) {
                break;
            }
            foreach ($categories as $category) {
                if ($parentId == $category['id']) {
                    $names[] = $category['name'];
                    $parentId = $category['parent_id'];
                    break;
                }
            }
        }

        return implode($connector, array_reverse($names));
    }

    protected function validateCategory(array $category)
    {
        $parentId = $category['parent_id'] ? (int) $category['parent_id'] : 0;
        if ($parentId && !$this->getCategory($parentId)) {
            throw new BusinessException('PAREND_CATEGORY_NOT_EXIST');
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
