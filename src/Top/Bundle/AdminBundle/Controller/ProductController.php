<?php

namespace Top\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Product:index.html.twig');
    }
}