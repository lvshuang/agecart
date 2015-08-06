<?php

namespace Top\Bundle\AppBundle\Controller;

use Top\Component\Curl\Curl;

class DefaultController extends BaseController
{
    public function indexAction()
    {

        $token = $this->getAccessTokenService()->getAccessToken();
        return $this->render('AppBundle:Default:index.html.twig', array('token' => $token));
    }
}
