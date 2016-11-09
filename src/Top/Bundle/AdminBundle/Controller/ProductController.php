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

            $attrs = array();
            $attrsInStrings = explode('$', $data['attrs']);
            foreach ($attrsInStrings as $attrsInString) {
                $nameAndValue = explode('^', $attrsInString);
                if ($nameAndValue) {
                    $tmp = ['name' => $nameAndValue[0], 'value' => $nameAndValue[1]];
                    $attrs[] = $tmp;
                }
            }
            $images = explode('||', $data['images']);
            $productInfo = [
                'name' => $data['name'],
                'brand_id' => $data['brand_id'],
                'category_id' => $data['category_id'],
                'descript' => $data['description'],
            ];
            
            try {
                $productId = $this->getProductService()->addProduct($productInfo, $attrs, $images);
                $this->addFlash('success', '添加商品成功');
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
            throw $this->createNotFoundException('商品不存在');
        }

        $categoryName = null;
        if (isset($product['category_id'])) {
            $categoryName = $this->getCategoryService()->getNamesById($product['category_id']);
        }
        $brandName = null;
        if (isset($product['brand_id'])) {
            $brand = $this->getBrandService()->getBrandById($product['brand_id'], 'name');
            $brandName = $brand['name'];
        }

        $productAttrs = $this->getProductService()->getProductAttrs($id);

        $product['name'] = $product['product_name'];
        $form = $this->buildForm($product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $attrs = array();
            $attrsInStrings = explode('$', $data['attrs']);
            foreach ($attrsInStrings as $attrsInString) {
                $nameAndValue = explode('^', $attrsInString);
                if ($nameAndValue) {
                    $tmp = ['name' => $nameAndValue[0], 'value' => $nameAndValue[1]];
                    $attrs[] = $tmp;
                }
            }
           
            $productInfo = array(
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'descript' => $data['description'],
                'attrs' => $attrs,
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
            'form' => $form->createView(),
            'product' => $product,
            'brandName' => $brandName,
            'categoryName' => $categoryName,
            'productAttrs' => $productAttrs,
        ));
    }

    public function deleteAction()
    {
        $id = $this->get('request')->request->get('id');
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->createJsonResponse(array('status' => 'error', 'message' => '商品ID错误'));
        }

        try {
            $isDeleted = $this->getProductService()->deleteById($id);
            if (!$isDeleted) {
                throw new \Exception('删除失败');
            }
        } catch (\Exception $e) {
            return $this->createJsonResponse(array('status' => 'error', 'message' => '删除失败'));
        }

        return $this->createJsonResponse(array('status' => 'ok', 'message' => '删除成功'));
    }

    protected function buildForm($data = null)
    {
        return $this->createFormBuilder($data)
            ->setMethod('POST')
            ->add('name', 'text', array('required' => true))
            ->add('brand_id', 'hidden')
            ->add('description', 'textarea')
            ->add('category_id', 'hidden', array('required' => true))
            ->add('attrs', 'hidden')
            ->add('images', 'hidden')
            ->getForm();
    }
    
    protected function buildSearchFrom($data = null)
    {
        return $this->createFormBuilder($data)
            ->setMethod('GET')
            ->add('name', 'text')
            ->add('sort', 'choice', array(
                'choices' => array(
                    '创建时间' => 'create_time',
                    '商品名称' => 'product_name'
                ),
                'choices_as_values' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->getForm();
    }
    
}