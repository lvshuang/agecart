<?php
namespace Top\Service\Product\Dao;

class BrandDao extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\BrandDaoInterface
{

    const TABLE_NAME = 'brand';

    public function add(array $brand)
    {
        return $this->insert(self::TABLE_NAME, $brand);
    }

    public function getById($id, $fields = '*')
    {
        return $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where(array('id' => $id))
            ->fetchRow();
    }

    public function getBrandByName($name, $fields = '*')
    {
        return $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where(array('name' => $name))
            ->fetchRow();
    }

    public function getByCategoryId($categoryId, $fields = '*')
    {
        return $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where(array('category_id' => $categoryId))
            ->fetchAll();
    }

    public function getByCondition(array $condition, $fields = '*', $orderBy = null, $start = 0, $limit = null)
    {
        $stat = $this->select($fields)
            ->from(self::TABLE_NAME)
            ->where($condition)
            ->orderBy($orderBy);
        if ($start && $limit) {
            $stat = $stat->limit($start, $limit);
        }
            
        return $stat->fetchAll();
    }

    public function getCountByCondition(array $condition)
    {
        return $this->select('count(1)')
            ->from(self::TABLE_NAME)
            ->where($condition)
            ->count();
    }

    public function updateById($id, array $updateData)
    {
        return $this->update(self::TABLE_NAME, array('id' => $id), $updateData);
    }

    public function deleteById($id)
    {
        return $this->delete(self::TABLE_NAME, array('id' => $id));
    }

}
