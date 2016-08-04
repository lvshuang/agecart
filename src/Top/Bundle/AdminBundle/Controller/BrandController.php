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
        $form = $this->buildSearchForm();
        $form->handleRequest($request);
        $data = $form->getData();

        $condition = array();
        if (isset($data['name'])) {
            $condition['name LIKE'] = '%' . $data['name'] . '%';
        }
        if (isset($data['short_name'])) {
            $condition['short_name LIKE'] = '%' . $data['short_name'] . '%';
        }

        $data['sort'] = isset($data['sort']) ? $data['sort'] : 'name';
        switch ($data['sort']) {
            case 'short':
                $orderBy = 'short_name ASC';
                break;
            case 'time':
                $orderBy = 'create_time DESC';
                break;
            case 'name':
            default:
                $orderBy = 'name ASC';
                break;
        }

        $perPage = 20;
        $total = $this->getBrandService()->getBrandsCountByCondition($condition);
        $paginator = new \Top\Common\Paginator($request, $total, $perPage);
        
        $brands = $this->getBrandService()->getBrandsByCondition(
            $condition,
            '*',
            $orderBy,
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
            'paginator' => $paginator,
            'categories' => ArrayToolkit::index('id', $categories),
            'searchForm' => $form->createView()
        ));
    }
    
    public function addAction(Request $request)
    {
        $form = $this->buildForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $brandId = $this->getBrandService()->addBrand($data);
                return  $this->redirectToRoute('admin_brand_detail', ['id' => $brandId]);
            } catch (BusinessException $be) {
                $this->addFlash('error', '保存品牌出错');
                goto render;
            } catch (\Exception $ex) {
                $this->addFlash('error', '系统异常');
                goto render;
            }
        }

        render: 
        return $this->render('AdminBundle:Brand:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function nameCheckAction(Request $request)
    {
        $name = $request->query->get('brandname');
        $isExist = $this->getBrandService()->isBrandNameExist($name);
        return $this->createJsonResponse(!$isExist);
    }

    public function editNameCheckAction(Request $request, $id)
    {
        $name = $request->query->get('brandname');
        $brand = $this->getBrandService()->getBrandById($id);
        if ($brand && $brand['name'] === $name) {
            return $this->createJsonResponse(true);
        }
        $isExist = $this->getBrandService()->isBrandNameExist($name);
        return $this->createJsonResponse(!$isExist);
    }

    public function logoUploadAction(Request $request)
    {
        $file = $request->files->get('file');
        $imageUploadHelper = new \Top\Common\ImageUploadHelper($this->container);
        list($success, $msg) = $imageUploadHelper->validate($file);
        if (!$success) {
            return $this->createJsonResponse(['status' => 'error', 'message' => $msg]);
        }
        list($uri, ) = $imageUploadHelper->thumbnail($file, 'category', 102, 36);
        
        return $this->createJsonResponse(['status' => 'ok', 'uri' => $uri]);
    }

    public function editAction(Request $request, $id)
    {
        $brand = $this->getBrandService()->getBrandById($id);
        if (!$brand) {
            return $this->createNotFoundException('品牌不存在');
        }
        $categoryName = '';
        if ($brand['category_id']) {
            $categoryName = $this->getCategoryService()->getNamesById($brand['category_id']);
        }
        $form = $this->buildForm($brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $this->getBrandService()->updateBrand($id, $data);
            } catch (BusinessException $ex) {
                $this->addFlash('error', '保存失败');
                goto render;
            } catch (\Exception $ex) {
                $this->addFlash('error', '系统异常');
                goto render;
            }
            return $this->redirectToRoute('admin_brand_detail', ['id' => $id]);
        }
        render:
        return $this->render('AdminBundle:Brand:edit.html.twig', array(
            'form' => $form->createView(),
            'brand' => $brand,
            'categoryName' => $categoryName
        ));
    }

    public function detailAction($id)
    {
        $brand = $this->getBrandService()->getBrandById($id);
        if (!$brand) {
            return $this->createNotFoundException('品牌不存在');
        }
        $categoryName = '';
        if ($brand['category_id']) {
            $categoryName = $this->getCategoryService()->getNamesById($brand['category_id']);
        }
        return $this->render('AdminBundle:Brand:detail.html.twig', array(
            'brand' => $brand,
            'categoryName' => $categoryName
        ));
    }

    public function switchAction($id, $type)
    {
        $method = $type === 'disable' ? 'disable' : 'enable';
        try {
            $result = $this->getBrandService()->$method($id);
        } catch(\BusinessException $ex) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '操作失败']);
        } catch(\Exception $ex) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '系统异常']);
        }
        
        if (!$result) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '操作失败']);
        }

        $brand = $this->getBrandService()->getBrandById($id);
        $category = array();
        if ($brand['category_id']) {
            $category = $this->getCategoryService()->getCategory($brand['category_id']);
        }
        $html = $this->renderView('AdminBundle:Brand:list-item.html.twig', array('brand' => $brand, 'category' => $category));
        return $this->createJsonResponse(['status' => 'ok', 'html' => $html, 'message' => '操作成功']);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            return $this->createJsonResponse(['status' => 'error', 'message' => 'ID错误']);
        }

        try {
            $result = $this->getBrandService()->delete($id);
        } catch(\BusinessException $ex) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '删除失败']);
        } catch(\Exception $ex) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '系统异常']);
        }

        if (!$result) {
            return $this->createJsonResponse(['status' => 'error', 'message' => '删除失败']);
        }

        return $this->createJsonResponse(['status' => 'ok', 'message' => '删除成功']);
    }

    public function loadAction(Request $request)
    {
        $name = $request->query->get('keyword');
        $condition = $name ? ['name' => $name] : [];
        $brands = $this->getBrandService()->getBrandsByCondition($condition, 'id, name', 'name ASC');

        $return = [];
        foreach ($brands as $brand) {
            $return[$brand['id']] = $brand['name'];
        }

        return $this->createJsonResponse($return);
    }
    
    protected function buildForm(array $data = array())
    {
        return $this->createFormBuilder($data)
            ->setMethod('POST')
            ->add('name', 'text', array('required' => true))
            ->add('short_name', 'text', array('required' => true))
            ->add('logo', 'hidden')
            ->add('weight', 'text')
            ->add('enable', 'choice', array(
                    'choices' => array(
                        '是' => '1',
                        '否' => '0',
                    ),
                    'data' => 1,
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'empty_data' => 1
                ))
            ->add('category_id', 'hidden', array('required' => true))
            ->getForm();
    }

    protected function buildSearchForm(array $data = array())
    {
        return $this->createFormBuilder($data)
            ->add('name', 'text')
            ->add('short_name', 'text')
            ->add('sort', 'choice', array(
                    'choices' => array(
                        '名称' => 'name',
                        '缩写' => 'short',
                        '创建时间' => 'time',
                    ),
                    'expanded' => false,
                    'multiple' => false,
                    'choices_as_values' => true,
                ))
            ->getForm();
    }

}