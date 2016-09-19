<?php
namespace Top\Component\Cart\Store;

interface StoreInterface
{
    
    public function save($id, $data);
    
    public function get($id);
    
    public function flush($id);
    
}
