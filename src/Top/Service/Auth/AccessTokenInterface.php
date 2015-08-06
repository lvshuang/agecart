<?php
namespace Top\Service\Auth;

interface AccessTokenInterface
{
	
	public function getAccessToken();

	public function saveAccessToke();
	
	public function updateAccessToken();

}