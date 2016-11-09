<?php
namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Top\Common\BusinessException;
use Top\Common\ArrayToolkit;

class FileManagerController extends BaseController
{

    public function modalAction()
    {
        return $this->render('AdminBundle:FileManager:modal.html.twig');
    }

}