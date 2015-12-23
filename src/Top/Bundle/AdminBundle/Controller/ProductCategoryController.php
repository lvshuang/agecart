<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;

class ProductCategoryController extends BaseController
{
    public function indexAction()
    {
        $topCategories = $this->getCategoryService()->makeCategoryTree();
        return $this->render('AdminBundle:ProductCategory:index.html.twig', array(
            'categoryTree' => $topCategories
        ));
    }
    
    public function categorySelectAction()
    {
        
    }
    
    public function addCaregory()
    {
        
    }
    
}