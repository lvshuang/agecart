<?php
namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Top\Common\BusinessException;
use Top\Common\ArrayToolkit;

class BrandController extends BaseController
{

    public function indexAction(Request $request)
    {
        $condition = array();
        $perPage = 20;
        $total = $this->getBrandService()->getBrandsCountByCondition($condition);
        $paginator = new \Top\Common\Paginator($request, $total, $perPage);
        
        $brands = $this->getBrandService()->getBrandsByCondition(
            $condition,
            '*',
            'name ASC',
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );
        
        $categoryIds = ArrayToolkit::column($brands, 'category_id');
        $categories = array();
        if ($categoryIds) {
            $categories = $this->getCategoryService()->getCategoryByIds($categoryIds, 'id, name');
        }
        
        return $this->render('AdminBundle:Brand:index.html.twig', array(
            'brands' => $brands,
            'categories' => ArrayToolkit::index('id', $categories)
        ));
    }
    
    public function addAction(Request $request)
    {
        $form = $this->buildForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $productId = $this->getBrandService()->addBrand($data);
                return $this->redirectToRoute('admin_product_sku_add', array('productId' => $productId));
            } catch (\Exception $ex) {
                var_dump($ex->getMessage());
                $this->addFlash('error', '保存品牌出错');
                goto render;
            }
        }

        render: 
        return $this->render('AdminBundle:Brand:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    protected function buildForm(array $data = array())
    {
        return $this->createFormBuilder($data)
            ->setMethod('POST')
            ->add('name', 'text', array('required' => true))
            ->add('logo', 'hidden')
            ->add('weight', 'text')
            ->add('is_top', 'choice', array(
                    'choices' => array(
                        '否' => '0',
                        '是' => '1',
                    ),
                    'data' => 0,
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'empty_data' => 0
                ))
            ->add('description', 'textarea')
            ->add('category_id', 'hidden', array('required' => true))
            ->getForm();
    }

}