<?php


namespace App\Services\Token;


interface TokenServiceInterface
{
    public function deleteUserAccessToken($user);
  
    public function createUserAccessToken($user);

    public function getUserAccessToken($user);
   
}
