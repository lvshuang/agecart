<?php
/**
* 品牌管理service.
*
* @author lvshuang <lvshuang1201@gmail.com> 
*/
namespace Top\Service\Product;

class BrandImpl extends \Top\Service\Common\BaseService implements \Top\Service\Product\BrandInterface
{
    public function addBrand(array $data)
    {
        $this->validateBrand($data);
        $brandName = trim($data['name']);

        if ($this->isBrandNameExist($brandName)) {
            throw new \Top\Common\BusinessException('品牌' . $brandName . '已存在');
        }
        $brand = array(
            'name' => $brandName,
            'short_name' => trim($data['short_name']),
            'category_id' => $data['category_id'],
            'logo' => $data['logo'],
            'enable' => isset($data['enable']) && $data['enable'] ? 1 : 0,
            'weight' => isset($data['weight']) ? (int) $data['weight'] : '0',
            'create_time' => time()
        );

        return $this->getBrandDao()->add($brand);
    }

    public function validateBrand(array $brand)
    {
        if (empty($brand['name'])) {
            throw new \Top\Common\BusinessException('品牌名称不存在');
        }
        
        if (empty($brand['short_name'])) {
            throw new \Top\Common\BusinessException('品牌名称缩写不存在');
        }
        
        if (mb_strlen($brand['name'] > 64)) {
            throw new \Top\Common\BusinessException('品牌名称过长');
        }
        
        if (mb_strlen($brand['short_name'] > 64)) {
            throw new \Top\Common\BusinessException('品牌名称缩写过长');
        }
        
        $uploadPath = realpath($this->container->getParameter('top.upload.path'));
        if (empty($brand['logo']) || !file_exists($uploadPath . '/' . $brand['logo'])) {
            throw new \Top\Common\BusinessException('品牌LOGO不存在');
        }
        if ($brand['category_id'] && !$this->getCategoryService()->isCategoryExist($brand['category_id'])) {
            throw new \Top\Common\BusinessException('选择的分类不存在');
        }
    }

    public function updateBrand($id, array $updateData)
    {
        $this->validateBrandForUpdate($updateData);
        $brand = $this->getBrandById($id);
        if (!$brand) {
            throw new \Top\Common\BusinessException('品牌不存在');
        }
        if (isset($updateData['name']) && 
            $updateData['name'] !== $brand['name'] &&
            $this->isBrandNameExist($updateData['name'])
        ) {
            throw new \Top\Common\BusinessException('品牌' . $brandName . '已存在');
        }
        return $this->getBrandDao()->updateById($id, $updateData);
    }

    protected function validateBrandForUpdate(array $updateData)
    {

        if (isset($updateData['name']) && (mb_strlen($updateData['name']) > 64 || mb_strlen($updateData['name']) < 0)) {
            throw new \Top\Common\BusinessException('品牌名称不符合要求');
        }
        
        if (isset($updateData['short_name']) && (mb_strlen($updateData['short_name']) > 64 || mb_strlen($updateData['short_name']) < 0)) {
            throw new \Top\Common\BusinessException('品牌名称缩写不符合要求');
        }

        if (isset($updateData['logo'])) {
            $uploadPath = realpath($this->container->getParameter('top.upload.path'));
            if (empty($updateData['logo']) || !file_exists($uploadPath . '/' . $updateData['logo'])) {
                throw new \Top\Common\BusinessException('品牌LOGO不存在');
            }
        }
        if (isset($updateData['category_id'])) {
            if ($updateData['category_id'] && !$this->getCategoryService()->isCategoryExist($updateData['category_id'])) {
                throw new \Top\Common\BusinessException('选择的分类不存在');
            }
        }
        
    }

    public function getBrandById($id, $fields = '*')
    {
        return $this->getBrandDao()->getById((int) $id, $fields);
    }

    public function getBrandsByCategoryId($categoryId, $fields = '*')
    {
        return $this->getBrandDao()->getByCategoryId((int) $categoryId, $fields);
    }

    public function getBrandsCountByCondition(array $condition)
    {
        return $this->getBrandDao()->getCountByCondition($condition);
    }

    public function getBrandsByCondition(array $condition, $fields = '*', $orderBy = null, $start = 0, $limit = null)
    {
        return $this->getBrandDao()->getByCondition(
            $condition,
            $fields,
            $orderBy,
            $start,
            $limit
        );
    }

    public function isBrandExist($id)
    {
        return !!$this->getBrandById($id);
    }

    public function isBrandNameExist($name)
    {
        if (empty($name)) {
            return false;
        }
        return !!$this->getBrandDao()->getBrandByName($name);
    }

    public function enable($id)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new \Top\Common\BusinessException('参数错误');
        }
        $updateData = ['enable' => 1, 'update_time' => time()];
        return $this->getBrandDao()->updateById($id, $updateData);
    }

    public function disable($id)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new \Top\Common\BusinessException('参数错误');
        }
        $updateData = ['enable' => 0, 'update_time' => time()];
        return $this->getBrandDao()->updateById($id, $updateData);
    }

    public function delete($id)
    {
        if (!\Top\Component\Validator\SimpleValidator::isUint($id)) {
            throw new \Top\Common\BusinessException('参数错误');
        }
        return $this->getBrandDao()->deleteById($id);
    }

}