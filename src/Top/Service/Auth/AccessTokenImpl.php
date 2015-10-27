<?php
namespace Top\Service\Auth;

use Top\Service\Common\BaseService;

class AccessTokenImpl extends BaseService implements \Top\Service\Auth\AccessTokenInterface
{
    
    public function getAccessToken()
    {
	$accessTokenDao = $this->getAccessTokenDao();
        $weixinAuth = $this->container->get('weixin.auth.token');

        $appId = $weixinAuth->getAppId();
        $token = $accessTokenDao->getByAppId(1);
        if (!$token || time() >= $token['expire_time']) {
            $token = $weixinAuth->getAccessToken();
            $data = array();
            $data['appid'] = $appId;
            $data['token'] = $token['access_token'];
            $data['expire_time'] = time() + $token['expires_in'] - 5; // 提前5秒过期
            $id = $accessTokenDao->add($data);
            if ($id) {
                $data['id'] = $id;
            } else {
                throw new \Exception('Can not save token');
            }
            return $data;
        }
        return $token;
    }

    public function saveAccessToke()
    {

    }
	
    public function updateAccessToken()
    {

    }

}
