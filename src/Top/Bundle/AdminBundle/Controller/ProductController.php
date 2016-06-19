<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    public function indexAction()
    {
        $renderData = array(
            'products' => array(),
        );
        return $this->render('AdminBundle:Product:index.html.twig', $renderData);
    }
    
    public function addAction(Request $request)
    {
        $form = $this->buildForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productInfo = array(
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'descript' => $data['description']
            );
            try {
                $productId = $this->getProductService()->addProduct($productInfo);
                return $this->redirectToRoute('admin_product_sku_add', array('productId' => $productId));
            } catch (\Exception $ex) {
                $this->addFlash('error', '添加商品出错');
                goto render;
            }
        }
        render:
        return $this->render('AdminBundle:Product:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function editAction(Request $request, $productId)
    {
        $product = $this->getProductService()->getProductById($productId);
        if (!$product) {
            $this->addFlash('error', '商品不存在');
            goto render;
        }
        $form = $this->buildForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
        }
        
        render:
        return $this->render('AdminBundle:Product:edit.html.twig', array());
    }

    public function deleteAction($id)
    {
        
    }
    
    protected function buildForm($data = null)
    {
        return $this->createFormBuilder($data)
            ->setMethod('POST')
            ->add('name', 'text', array('required' => true))
            ->add('description', 'textarea')
            ->add('category_id', 'hidden', array('required' => true))
            ->getForm();
    }
    
}