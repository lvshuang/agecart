<?php
namespace Top\Component\WeixinAuth;

use Top\Component\Curl\Curl;

class AccessToken
{

	protected $container;
	protected $appId;
	protected $appSecret;
	protected $accessTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token';

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function getAccessToken()
	{
		$this->initConfig();
		$params = array('grant_type' => 'client_credential');
		$params['appid'] = $this->appId;
		$params['secret'] = $this->appSecret;
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

	protected function initConfig()
	{
		$settings = $this->container->getParameter('weixin_keys');
		if (empty($settings)) {
			throw new \Top\Common\BusinessException('weixin auth config empty');
		}
		if (!isset($settings['app_id'])) {
			throw new \Top\Common\BusinessException('weixin auth app_id not config');
		}

		if (!isset($settings['app_secret'])) {
			throw new \Top\Common\BusinessException('weixin auth app_secret not config');
		}

		$this->appId = $settings['app_id'];
		$this->appSecret = $settings['app_secret'];
	}

}
