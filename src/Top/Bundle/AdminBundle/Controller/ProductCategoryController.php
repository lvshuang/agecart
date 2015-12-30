<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryController extends BaseController
{
    public function indexAction()
    {
        $topCategories = $this->getCategoryService()->makeCategoryTree();
        return $this->render('AdminBundle:ProductCategory:index.html.twig', array(
            'categoryTree' => $topCategories
        ));
    }
    
    public function addAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
                ->add('parent_id', 'hidden')
                ->add('name', 'text')
                ->add('weight', 'text')
                ->add('keyword', 'text')
                ->add('description', 'textarea')
                ->getForm();
        
        if ($form->isValid() && $request->isMethod('POST')) {
            // process form
        }
        
        return $this->render('AdminBundle:ProductCategory:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function loadAction(Request $request)
    {
        $parentId = $request->query->get('parent_id');
        $children = $this->getCategoryService()->getChildren($parentId);
        
        return $this->createJsonResponse($children);
    }
    
    public function categorySelectAction()
    {
        
    }
    
    public function addCaregory()
    {
        
    }
    
}