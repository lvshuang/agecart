<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Top\Common\ArrayToolkit;

class ProductController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->buildSearchFrom();
        $form->handleRequest($request);
        $data = $form->getData();
        $condition = array();
        if (isset($data['name'])) {
            $condition['product_name LIKE'] = '%' . $data['name'] . '%';
        }
        $sort = 'create_time DESC';
        if (isset($data['sort'])) {
            $sort = $data['sort'] . ' DESC';
        }
        
        $total = $this->getProductService()->getProductCount($condition);
        $perPage = 20;
        $paginator = new \Top\Common\Paginator($request, $total, $perPage);
        $products = $this->getProductService()->getProductList(
            $condition,
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount(),
            $sort
        );
        
        $categoryIds = ArrayToolkit::column($products, 'category_id');
        $categories = array();
        if ($categoryIds) {
            $categories = $this->getCategoryService()->getCategoryByIds($categoryIds, 'id, name');
        }
        
        // TODO: 获取品牌
        $renderData = array(
            'products' => $products,
            'paginator' => $paginator,
            'categories' => ArrayToolkit::index('id', $categories),
            'form' => $form->createView()
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
    
    public function editAction(Request $request, $id)
    {
        $product = $this->getProductService()->getProductById($id);
        if (!$product) {
            $this->addFlash('error', '商品不存在');
            $product = array();
            goto render;
        }
        $product['name'] = $product['product_name'];
        $form = $this->buildForm($product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productInfo = array(
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'descript' => $data['description']
            );
            try {
                $this->getProductService()->updateProduct($id, $productInfo);
                return $this->redirectToRoute('admin_product');
            } catch (\Exception $ex) {
                $this->addFlash('error', '添加商品出错');
                goto render;
            }
        }
        render:
        return $this->render('AdminBundle:Product:edit.html.twig', array(
            'form' => $form->createView()
        ));
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
    
    protected function buildSearchFrom($data = null)
    {
        return $this->createFormBuilder($data)
            ->setMethod('GET')
            ->add('name', 'text')
            ->add('sort', 'choice', array(
                'choices' => array(
                    'create_time' => '创建时间',
                    'product_name' => '商品名称'
                ),
                'expanded' => false,
                'multiple' => false,
            ))
            ->getForm();
    }
    
}