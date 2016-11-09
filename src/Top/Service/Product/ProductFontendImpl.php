<?php
namespace Top\Service\Product;

class ProductFontendImpl extends ProductBaseService
{

    public function getNewProduct($limit = 6)
    {
        if (!filter_var($limit, FILTER_VALIDATE_INT)) {
            throw new \Top\Common\BusinessException('参数错误');
        }
        $products = $this->getProductDao()->getByCondition(array(), '*', 0, $limit, 'id DESC');
        $productIds = \Top\Common\ArrayToolkit::column($products, 'id');
        $productImages = $this->getProductImageDao()->getByProductIds($productIds, '*', 'product_id DESC, id DESC');
        $indexedProductImages = \Top\Common\ArrayToolkit::index('product_id', $productImages);
        
        $productSkus = $this->getSelleSkusByProductIds($productIds);
        $indexedProductSkus = \Top\Common\ArrayToolkit::index('product_id', $productSkus);
        
        foreach ($products as $key => &$product) {
            $product['image'] = isset($indexedProductImages[$product['id']]) ? $indexedProductImages[$product['id']] : array();

            if (!isset($indexedProductSkus[$product['id']])) {
                unset($products[$key]);
                continue;
            }
            $product['sku'] = $indexedProductSkus[$product['id']];

        }

        return $products;
    }

    public function getSelleSkusByProductIds(array $productIds)
    {
        if (empty($productIds)) {
            throw new \Top\Common\BusinessException('参数错误');
        }
        $condition = [
            'product_id' => $productIds,
            'is_sellable' => 1
        ];
        return $this->getProductSkuDao()->getByCondition($condition);
    }

    public function getNewProductSku($limit = 6)
    {
        $newSkus = $this->getProductSkuDao()->getAll('*', 'id DESC', 0, $limit);
        return $newSkus;
    }
    
    public function getSkusAttrs(array $skus)
    {
        if (empty($skus)) {
            return [];
        }
        return $this->getProductSkuAttrDao()->getAllBySkus($skus);
    }
    
    public function getSkusAttrsInNameAndValue(array $skus)
    {
        $skuAttrs = $this->getSkusAttrs($skus);
        $groupedAttrs = [];
        foreach ($skuAttrs as $skuAttr) {
            if (isset($groupedAttrs[$skuAttr['name']]) && in_array($skuAttr['value'], $groupedAttrs[$skuAttr['name']])) {
                continue;
            }
            $groupedAttrs[$skuAttr['name']][] = $skuAttr['value'];
        }
        return $groupedAttrs;
    }

    public function getSkusAttrsGroupBySku(array $skus)
    {
        $skuAttrs = $this->getSkusAttrs($skus);
        $groupedAttrs = [];
        foreach ($skuAttrs as $skuAttr) {
            $groupedAttrs[$skuAttr['sku']][$skuAttr['name']] = $skuAttr['value'];
        }
        return $groupedAttrs;
    }

}