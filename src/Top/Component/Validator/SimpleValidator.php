<?php
/**
 * 简单验证类.
 * 
 * @author lvh <lvshuang1201@gmail.com>
 */
namespace Top\Component\Validator;

class SimpleValidator
{
    
    public static function isUint($value)
    {
        return filter_var(
            $value, FILTER_VALIDATE_INT,
            array(
                'flags' => FILTER_FLAG_ALLOW_HEX,
                'options' => array('min_range' => 1, 'max_range' => 0xff)
            )
        );
    }
    
    public static function isInt($value)
    {
        return filter_var(
            $value, FILTER_VALIDATE_INT,
            array(
                'flags' => FILTER_FLAG_ALLOW_HEX,
                'options' => array('max_range' => 0xff)
            )
        );
    }
    
    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
    
    public static function isAllChinese($value)
    {
        return preg_match('/$[\x{4e00}-\x{9fa5}]+^/u', $value);
    }
    
    public static function isFloat($value)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
    
    public static function isIpv4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }
    
}
