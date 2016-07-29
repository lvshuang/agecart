<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProductSkuController extends BaseController
{
    public function detailAction($id)
    {
        $product = $this->getProductService()->getProductById($id);
        if (!$product) {
            throw new \Exception('商品不存在');
        }
        $categoryNames = null;
        if ($product['category_id']) {
            $categoryNames = $this->getCategoryService()->getNamesById($product['category_id']);
        }
        $skus = $this->getProductService()->getProductSkus($id);
        $data = array(
            'product' => $product,
            'categoryNames' => $categoryNames,
            'skus' => $skus
        );
        return $this->render('AdminBundle:ProductSku:detail.html.twig', $data);
    }

    public function addAction(Request $request, $productId)
    {
        $form = $this->buildForm();
        try {
            $product = array();
            $product = $this->getProductService()->getProductById($productId);
        } catch (\Exception $ex) {
            throw new \Exception('获取商品信息失败');
        }
        if (!$product) {
            throw new \Exception('商品不存在');
        }
        $categoryNames = null;
        if ($product['category_id']) {
            $categoryNames = $this->getCategoryService()->getNamesById($product['category_id']);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $skuInfo = array(
                'short_name' => $data['short_name'],
                'price' => $data['price'],
                'discount_price' => $data['discount_price'],
                'inventory' => $data['inventory'],
                'meta_keyword' => $data['meta_keyword'],
                'meta_description' => $data['meta_description'],
                'attributes' => $data['attributes']
            );
            try {
                $sku = $this->getProductService()->addProductSku($product['id'], $skuInfo);
                if ($sku) {
                    return $this->redirectToRoute('admin_product_detail', array('id' => $product['id']), 301);
                }
            } catch (\Top\Common\BusinessException $ex) {
                $this->addFlash('error', $ex->getMessage());
            } catch (\Exception $ex) {
                $this->getLogger()->error('创建SKU失败:' . $ex->getMessage());
                $this->addFlash('error', '系统异常');
                goto render;
            }
        }
        render:
        return $this->render('AdminBundle:ProductSku:add.html.twig', array(
            'product' => $product,
            'categoryNames' => $categoryNames,
            'form' => $form->createView()
        ));
    }

    protected function buildForm($data = array())
    {
        return $this->createFormBuilder($data)
                ->setMethod('POST')
                ->add('short_name', 'text', array('required' => true))
                ->add('attributes', 'hidden')
                ->add('price', 'text', array('required' => true))
                ->add('discount_price', 'text', array('required' => false))
                ->add('inventory', 'text', array('required' => true))
                ->add('meta_keyword', 'text')
                ->add('meta_description', 'textarea')
                ->getForm();
    }
    
}