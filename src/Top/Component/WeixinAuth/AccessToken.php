<?php

namespace Top\Component\WeixinAuth;

use Top\Component\Curl\Curl;

class AccessToken 
{

    protected $container;
    protected $appId;
    protected $appSecret;
    protected $accessTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token';
    protected $grantType = 'client_credential';

    public function __construct($container) {
        $this->container = $container;
        $config = $this->container->getParameter('weixin_keys');
        $this->initConfig($config);
    }

    public function getAppId() {
        return $this->appId;
    }

    public function getAppSecret() {
        return $this->appSecret;
    }

    public function getAccessToken() {
        $params = $this->buildGetAccessTokenParams();
        $response = Curl::get($this->accessTokenUrl, $params);

        if (!$response) {
            throw new \Exception('network error');
        }
        $response = json_decode($response, true);
        if (isset($response['errcode'])) {
            throw new \Top\Common\BusinessException($response['errmsg']);
        }
        return $response;
    }
    
    protected function buildGetAccessTokenParams()
    {
        return array(
            'grant_type' => $this->grantType,
            'appid' => $this->appId,
            'secret' => $this->appSecret,
        );
    }

    protected function initConfig($config) {
        
        if (empty($config)) {
            throw new \Top\Common\BusinessException('weixin auth config empty');
        }
        if (!isset($config['app_id'])) {
            throw new \Top\Common\BusinessException('weixin auth app_id not config');
        }

        if (!isset($config['app_secret'])) {
            throw new \Top\Common\BusinessException('weixin auth app_secret not config');
        }

        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
    }

}
