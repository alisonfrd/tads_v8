<?php

class TokenAuthentication implements AuthenticationInterface 
{
    public function authenticate($token) 
    {
        return !empty($token);
    }
}