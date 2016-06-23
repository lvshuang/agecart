<?php
namespace Top\Service\Product\Dao;

use Top\Service\Common\BaseDao;
use Top\Service\Product\Dao\ProductDao;

class ProductDaoImpl extends BaseDao implements ProductDao
{
    
    const TABLE_NAME = 'product';
    
    
    /**
     * 新增一产品信息.
     * 
     * @param array $product 产品信息.
     * 
     * @return integer 成功返回最后一次写入的产品id.
     */
    public function add(array $product) 
    {
        return $this->insert(self::TABLE_NAME, $product);
    }
    
    /**
     * 通过产品id获取产品信息.
     * 
     * @param integer $id     产品id.
     * @param string  $fields 返回的字段，多个字段使用英文逗号隔开.
     * 
     * @return array|boolean 产品信息或者false.
     */
    public function getById($id, $fields = '*') 
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(array('id' => $id))
                ->fetchRow();
    }
    
    
    public function getBySkuNo($skuNo, $fields = '*') 
    {
        
    }
    
    /**
     * 通过分类id获取产品信息.
     * 
     * @param integer $categoryId 分类id.
     * @param string  $fields     返回的字段.
     * @param string  $orderBy    排序方式.
     * @param integer $start      起始记录.
     * @param integer $limit      每次获取限制数量.
     * 
     * @return array
     */
    public function getByCategoryId($categoryId, $fields = '*', $orderBy = null, $start = 0, $limit = null)
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(array('category_id' => $categoryId))
                ->orderBy($orderBy)
                ->limit($start, $limit)
                ->fetchAll();
    }
    
    /**
     * 通过品牌id获取产品信息.
     * 
     * @param integer $brandId 分类id.
     * @param string  $fields  返回的字段.
     * @param string  $orderBy 排序方式.
     * @param integer $start   起始记录.
     * @param integer $limit   每次获取限制数量.
     * 
     * @return array
     */
    public function getByBrandId($brandId, $fields = '*', $orderBy = null, $start = 0, $limit = null)
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(array('brand_id' => $brandId))
                ->orderBy($orderBy)
                ->limit($start, $limit)
                ->fetchAll();
    }
    
    /**
     * 修改产品信息.
     * 
     * @param integer $id         产品id.
     * @param array   $updateData 需要修改的数据.
     * 
     * @return integer 响应条数.
     */
    public function upateById($id, array $updateData) 
    {
        return $this->update(self::TABLE_NAME, array('id' => $id), $updateData);
    }
    
    /**
     * 删除产品信息.
     * 
     * @param integer $id 产品id.
     * 
     * @return integer 响应条数.
     */
    public function deleteById($id) 
    {
        return $this->delete(self::TABLE_NAME, array('id' => $id));
    }
    
    /**
     * 根据条件获取所有的数据量.
     * 
     * @param array $condition 条件.
     * 
     * @return integer
     */
    public function getCountByCondition(array $condition)
    {
        $result = $this->select('COUNT(1) AS total')
                ->from(self::TABLE_NAME)
                ->where($condition)
                ->fetchRow();
        return (int) $result['total'];
    }
    
    /**
     * 根据条件获取产品.
     * 
     * @param array   $condition 条件.
     * @param string  $fields    返回的字段.
     * @param integer $start     开始位置.
     * @param integer $limit     结束位置.
     * @param string  $orderBy   排序.
     * 
     * @return array
     */
    public function getByCondition(array $condition, $fields="*", $start = 0, $limit = null, $orderBy =null)
    {
        $stat = $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where($condition);
        if ($orderBy) {
            $stat = $stat->orderBy($orderBy);
        }
        if ($start || $limit) {
            $stat = $stat->limit($start, $limit);
        }
        return $stat->fetchAll();
    }
    
}
