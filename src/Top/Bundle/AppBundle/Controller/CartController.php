<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class CartController extends BaseController
{

    public function myAction(Request $request)
    {
        $cartStore = new \Top\Component\Cart\Store\SessionStore();
        $cartId = $request->cookies->get('cart_id');

        $cart = null;
        if ($cartId) {
            $cart = unserialize($cartStore->get($cartId));
        } 

        if (empty($cart)) {
            exit("你的购物车空空如也");
        }

        // var_dump($cart->getItems());
        return $this->render('AppBundle:Cart:index.html.twig', array(
            'cart' => $cart
        ));
    }

    public function confirmationAction()
    {
        $cartStore = new \Top\Component\Cart\Store\SessionStore();
        $cartId = $request->cookies->get('cart_id');

        $cart = null;
        if ($cartId) {
            $cart = unserialize($cartStore->get($cartId));
        } 
        if (empty($cart) || empty($cart->getItems())) {
            exit("你的购物车空空如也");
        }
        
    }
    
    public function addToCartAction(Request $request)
    {
        $sku = $request->request->get('sku', null);
        $quantity = $request->request->get('quantity', 0);
        if (empty($sku) || empty($quantity)) {
            return $this->createJsonResponse(['status' => 'error', 'msg' => '数据错误']);
        }

        if (!$skuInfo = $this->getProductService()->getSkuInfo($sku)) {
            return $this->createJsonResponse(['status' => 'error', 'msg' => 'SKU不存在']);
        }
        
        $cartStore = new \Top\Component\Cart\Store\SessionStore();
        $cartId = $request->cookies->get('cart_id');
        if ($cartId) {
            $cart = unserialize($cartStore->get($cartId));
        } 
        
        if (!$cart) {
            $cartId = uniqid('cart_');
            $cart = new \Top\Component\Cart\Cart($cartId, $cartStore);
        }
        
        if ($cart->hasItem($sku)) {
            $cart->addItemQuantity($sku, $quantity);
        } else {
            $cartItem = new \Top\Component\Cart\CartItem($sku, $skuInfo['short_name'], $quantity, $skuInfo['price']);
            $cart->addItem($cartItem);
        }
        
        $cartStore->save($cartId, serialize($cart));
        
        $response = $this->createJsonResponse(['status' => 'ok', 'msg' => '添加购物车成功']);
        $response->headers->setCookie(new Cookie('cart_id', $cartId, time() + 2592000));
        return $response;
    }

}