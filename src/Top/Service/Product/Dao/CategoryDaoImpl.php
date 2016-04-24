<?php
/**
 * 分类数据层操作.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product\Dao;

class CategoryDaoImpl extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\CategoryDao
{
    const TABLE_NAME = 'category';

    public function addCategory(array $category) 
    {
        return $this->insert(self::TABLE_NAME, $category);
    }
    
    public function getCategory($id) 
    {
        return $this->select('*')
                ->from(self::TABLE_NAME)
                ->where(array('id' => $id))
                ->fetchRow();
    }
    
    public function updateById($id, array $updateFields) 
    {
        return $this->update(self::TABLE_NAME, array('id' => $id), $updateFields);
    }
    
    public function deleteById($id) 
    {
        return $this->delete(self::TABLE_NAME, array('id' => $id));
    }
    
    public function countByCondition(array $condition) 
    {
        return $this->select('count(1)')
                ->from(self::TABLE_NAME)
                ->where($condition)
                ->count();
    }
    
    public function getByCondition(array $condition, $orderBy = null, $start = 0, $limit = null) 
    {
        $stat = $this->select('*')
                ->from(self::TABLE_NAME)
                ->where($condition);
        if ($orderBy) {
            $stat->orderBy($orderBy);
        }
        if ($limit) {
            $stat->limit($start, $limit);
        }
        return $stat->fetchAll();
    }
    
    public function getAll($orderBy = null) 
    {
        $stat = $this->select('*')
                ->from(self::TABLE_NAME);
        if ($orderBy) {
            $stat->orderBy($orderBy);
        }
        return $stat->fetchAll();
    }
    
}
