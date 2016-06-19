<?php

namespace Top\Bundle\AdminBundle\Controller;

use Top\Bundle\AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Top\Common\BusinessException;

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
        $form = $this->buildForm();
        $form->handleRequest($request);

        if ($form->isValid() && $request->isMethod('POST')) {
            $data = $form->getData();
            try {
                $data['sort_order'] = $data['weight'];
                $data['meta_keyword'] = $data['keyword'];
                $data['meta_description'] = $data['description'];
                $addResult = $this->getCategoryService()->addCategory($data);
            } catch (BusinessException $bex) {
                return $this->createJsonResponse(array('status' => 'error', 'message' => $bex->getMessage()));
            } catch (\Exception $ex) {
                return $this->createJsonResponse(array('status' => 'error', 'message' => $ex->getMessage()));
            }
            if ($addResult) {
                return $this->createJsonResponse(array('status' => 'ok'));
            }
            return $this->createJsonResponse(array('status' => 'error', 'message' => '写入数据库失败'));
        }
        
        return $this->render('AdminBundle:ProductCategory:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction(Request $request, $id)
    {
        try {
            $category = $this->getCategoryService()->getCategory($id);
            $category['weight'] = $category['sort_order'];
            $category['keyword'] = $category['meta_keyword'];
            $category['description'] = $category['meta_description'];
            $form = $this->buildForm($category);
            $form->handleRequest($request);
            if ($form->isValid() && $request->isMethod('POST')) {
                $data = $form->getData();
                $data['sort_order'] = $data['weight'];
                $data['meta_keyword'] = $data['keyword'];
                $data['meta_description'] = $data['description'];
                $updateResult = $this->getCategoryService()->updateCategory($id, $data);
                if (!$updateResult) {
                    return $this->createJsonResponse(array('status' => 'error', 'message' => '保存失败'));
                }
                return $this->createJsonResponse(array('status' => 'ok'));
            }
        } catch (BusinessException $bex) {
            return $this->createJsonResponse(array('status' => 'error', 'message' => $bex->getMessage()));
        } catch (\Exception $ex) {
            return $this->createJsonResponse(array('status' => 'error', 'message' => $ex->getMessage()));
        }

        render:

        return $this->render('AdminBundle:ProductCategory:edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
        
    }
    
    public function loadAction(Request $request)
    {
        $parentId = $request->query->get('parent_id');
        $children = $this->getCategoryService()->getChildren($parentId);
        
        return $this->createJsonResponse($children);
    }

    public function selectLoadAction($id)
    {
        $result = $this->getCategoryService()->loadForSelect($id);
        return $this->createJsonResponse($result);
    }

    public function switchAction($id, $type)
    {
        $enableOrderDisableApi = $type == 'enable' ? 'enableById' : 'disableById';
        try {
            $result = $this->getCategoryService()->$enableOrderDisableApi($id);
            if (!$result) {
                throw new \Exception($type . ' category failed: return false');
            }
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
            return $this->createJsonResponse(array('status' => 'error', 'message' => '操作失败'));
        }
        $category = $this->getCategoryService()->getCategory($id);
        $html = $this->renderView('AdminBundle:ProductCategory:list-item.html.twig', array('category' => $category));
        return $this->createJsonResponse(array('status' => 'ok', 'message' => '成功', 'html' => $html));
    }

    public function buildForm(array $data = array())
    {
        return $this->createFormBuilder($data)
                ->add('parent_id', 'hidden')
                ->add('name', 'text', array('required' => true, 'max_length' => 40))
                ->add('weight', 'text')
                ->add('keyword', 'text')
                ->add('description', 'textarea')
                ->getForm();
    }
    
}