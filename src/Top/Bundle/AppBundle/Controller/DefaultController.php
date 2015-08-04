<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Top\Component\Curl\Curl;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$name = 'ok';
    	
    	$accessToken = $this->get('weixin.auth.token');
    	$token = $accessToken->getAccessToken();
    	// TODO: cache token 
    	var_dump($token);

        return $this->render('AppBundle:Default:index.html.twig', array('name' => $name));
    }
}
