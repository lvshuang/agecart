<?php
/**
 * 获取用户授权信息接口.
 * 
 * @author lvshuang1201@gmail.com
 */
namespace Top\Component\WeixinAuth;

class UserAuth extends AccessToken
{
    
    protected $weixinCodeUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?'; // 获取code地址
    protected $accessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
    protected $callBackUrl; // 微信回调地址
    protected $grantType = 'client_credential';
    protected $code;


    protected function initConfig($config) 
    {
        parent::initConfig($config);
        if (!empty($config['auth_callback'])) {
            throw new \Top\Common\BusinessException('weixin auth callback url not config');
        }
        $this->callBackUrl = $config['auth_callback'];
    }
    
    public function setCode($code)
    {
        if (empty($code)) {
            throw new \Top\Common\BusinessException('weixin auth code empty');
        }
        $this->code = $code;
    }

    /**
     * 返回跳转到微信获取code的地址.
     * 
     * @return string
     */
    public function getCodeRedrict()
    {
        $params = array(
            'appid' => $this->appId,
            'redrict_uri' => $this->callBackUrl,
            'response_type' => 'code',
            'scope' => 'snsapi_userinfo',
            'state' => time(),
        );
        return $this->weixinCodeUrl . urlencode(http_build_query($params)) . '#wechat_redrict';
    }
    
    protected function buildGetAccessTokenParams() 
    {
        if (empty($this->code)) {
            throw new \Top\Common\BusinessException('weixin auth code not set');
        }
        return array(
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'code' => $this->code,
            'grant_type' => $this->grantType,
        );
    }
    
}
