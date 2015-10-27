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
        $token = $accessTokenDao->getByAppId('wxc925eaf829a9a04b');
        if (!$token) { // token 不存在
            $newToken = $weixinAuth->getAccessToken();
            return $this->saveAccessToke($appId, $newToken);
        } elseif (time() >= $token['expire_time']) { // token 已过期
            $newToken = $weixinAuth->getAccessToken();
            $token['token'] = $newToken['access_token'];
            $token['expire_time'] = time() + $newToken['expires_in'] - 5; // 提前5秒过期
            if ($this->updateAccessToken($token['id'], $token)) {
                return $token;
            }
            return false;
        }
        return $token;
    }

    public function saveAccessToke($appId, array $token)
    {
        $data = array();
        $data['appid'] = $appId;
        $data['token'] = $token['access_token'];
        $data['expire_time'] = time() + $token['expires_in'] - 5; // 提前5秒过期
        $id = $this->getAccessTokenDao()->add($data);
        if ($id) {
            $data['id'] = $id;
            return $data;
        } 
        return false;
    }
	
    public function updateAccessToken($id, array $updateFields)
    {
        return $this->getAccessTokenDao()->updateById($id, $updateFields);
    }

}
