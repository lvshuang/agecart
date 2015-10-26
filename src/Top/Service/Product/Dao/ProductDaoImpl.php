<?php
namespace Top\Service\Product\Dao;

use Top\Service\Common\BaseDao;
use Top\Service\Product\Dao\ProductDao;

class ProductDaoImpl extends BaseDao implements ProductDao
{
    protected  $tableName = 'product';

    public function add(array $product) 
    {
        return $this->insert($product);
    }
    
    public function getById($id) 
    {
        return $this->fetchRow($id);
    }
    
    public function upateById($id, array $updateData) {
        return $this->update($id, $updateData);
    }
    
    public function deleteById($id) {
//        return $this->deleteById($id);
    }
    
}
