<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    public function showAction($sku)
    {
        $skuInfo = $this->getProductService()->getSkuInfo($sku);
        if (!$skuInfo) {
            return $this->createNotFoundException('你所查看的商品不存在或者已下架');
        }

        $product = $this->getProductService()->getProductById($skuInfo['product_id']);
        if (!$product) {
            return $this->createNotFoundException('你所查看的商品不存在或者已下架');
        }
        
        $skus = $this->getProductService()->getProductSkus($product['id']);
        
        return $this->render('AppBundle:Product:show.html.twig', array(
            'product' => $product,
            'currentSku' => $skuInfo,
            'skus' => $skus,
        ));
    }
    
}