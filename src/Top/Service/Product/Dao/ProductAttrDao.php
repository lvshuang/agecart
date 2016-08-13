<?php
namespace Top\Service\Product\Dao;

class ProductAttrDao extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\ProductDaoAttrInterface
{
    const TABLE_NAME = 'product_attr';

    public function add(array $attr)
    {
        return $this->insert(self::TABLE_NAME, $attr);
    }

    public function getAllByProductId($productId, $fields = '*')
    {
        return $this->select($fields)->from(self::TABLE_NAME)
            ->where(array('product_id' => $productId))
            ->fetchAll();
    }   

    public function addMutil(array $attrs)
    {
        if (empty($attrs)) {
            return false;
        }
        $keys = array_keys(current($attrs));
        $sql = 'INSERT INTO `' . self::TABLE_NAME . '`';
        $sql .= '(' . implode(', ', $keys) . ') VALUES ';
        $valuesInstring = array();
        $placeholders = array();
        for ($i = 0; $i < count($keys); $i++) {
            $placeholders[] = '?';
        }
        $params = array();
        foreach ($attrs as $attr) {
            $params = array_merge($params, array_values($attr));
            $valuesInstring[] = '(' . implode(', ', $placeholders) . ')';
        }

        $sql .= implode(', ' , $valuesInstring);

        return $this->getDbConnection()->executeQuery($sql, $params);
    }

}