<?php
namespace Top\Service\Auth;

interface AccessTokenInterface
{
	
    public function getAccessToken();

    public function saveAccessToke($appId, array $token);
	
    public function updateAccessToken($id, array $updateFields);

}