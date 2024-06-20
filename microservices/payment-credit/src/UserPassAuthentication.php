<?php

class UserPassAuthentication implements AuthenticationInterface 
{
    public function authenticate($credentials) 
    {      
        return !empty($credentials['username']) && !empty($credentials['password']);
    }
}