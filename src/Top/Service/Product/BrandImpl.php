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
        $brand = array(
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'logo' => isset($data['logo']) ? $data['logo'] : '',
            'is_top' => isset($data['is_top']) && $data['is_top'] ? 1 : 0,
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
        if (mb_strlen($brand['name'] > 64)) {
            throw new \Top\Common\BusinessException('品牌名称过长');
        }
        if ($brand['category_id'] && !$this->getCategoryService()->isCategoryExist($brand['category_id'])) {
            throw new \Top\Common\BusinessException('选择的分类不存在');
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

}