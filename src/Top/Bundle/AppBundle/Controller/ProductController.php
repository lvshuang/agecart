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
        $skuNos = \Top\Common\ArrayToolkit::column($skus, 'sku');

        $skuAttrs = $this->getProductFontendService()->getSkusAttrsInNameAndValue($skuNos);

        $groupBySkuAttrs = $this->getProductFontendService()->getSkusAttrsGroupBySku($skuNos);
        $currentSkuAttr = isset($groupBySkuAttrs[$sku]) ? $groupBySkuAttrs[$sku] : [];

        foreach ($skuAttrs as $key => $value) {
            if(!in_array($key, array_keys($currentSkuAttr))) {
                unset($skuAttrs[$key]);
            }
        }
        
        return $this->render('AppBundle:Product:show.html.twig', array(
            'product' => $product,
            'currentSku' => $skuInfo,
            'skus' => $skus,
            'skuAttrs' => $skuAttrs,
            'groupBySkuAttrs' => json_encode($groupBySkuAttrs),
            'currentSkuAttr' => $currentSkuAttr,
        ));
    }

    public function loadSkuAction($productId, Request $request)
    {
        $attrs = $request->query->get('attrs', null);

        
    }

    
}