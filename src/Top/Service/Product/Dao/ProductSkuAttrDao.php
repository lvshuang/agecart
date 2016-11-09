<?php
/**
 * sku 属性DAO 类.
 * 
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product\Dao;

/**
 * sku 属性DAO 类.
 */
class ProductSkuAttrDao extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\ProductSkuAttrDaoInterface
{
    
    const TABLE_NAME = 'product_sku_attr';
    
    public function add(array $attr)
    {
        return $this->insert(self::TABLE_NAME, $attr);
    }

    public function getAllBySku($sku, $fields = '*')
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(array('sku' => $sku))
                ->fetchAll();
    }

    public function addMutil(array $attrs)
    {
        return $this->mutilInsert(self::TABLE_NAME, $attrs);
    }

    public function getAllBySkus(array $skus, $fields = '*')
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(array('sku' => $skus))
                ->fetchAll();
    }
    
}
