<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProductSkuController extends BaseController
{
    public function addAction(Request $request, $productId)
    {
        $product = $this->getProductService()->getProductById($productId);
        if (!$product) {
            $this->addFlash('error', '商品不存在');
            goto render;
        }
        
        render:
        return $this->render('AdminBundle:ProductSku:add.html.twig');
    }
}