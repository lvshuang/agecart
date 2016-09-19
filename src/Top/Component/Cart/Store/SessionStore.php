<?php
namespace Top\Component\Cart\Store;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionStore implements \Top\Component\Cart\Store\StoreInterface
{
    protected $session;


    public function __construct() 
    {
        $this->session = new Session();
//        $this->session->start();
    }
    
    public function flush($id) 
    {
        $this->session->remove($id);
    }

    public function get($id) 
    {
        return $this->session->get($id, null);
    }

    public function save($id, $data) 
    {
        return $this->session->set($id, $data);
    }

}
