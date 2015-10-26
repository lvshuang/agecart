<?php
namespace Top\Service\Product\Dao;

interface ProductDao
{
    public function add(array $product);
    
    public function getById($id);
    
    public function upateById($id, array $updateData);
    
    public function deleteById($id);
    
}

