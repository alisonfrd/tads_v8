<?php

class AuthenticationAdapter 
{
    private $authenticationMethod;

    public function __construct(AuthenticationInterface $authenticationMethod) 
    {
        $this->authenticationMethod = $authenticationMethod;
    }

    public function authenticate($credentials) 
    {
        return $this->authenticationMethod->authenticate($credentials);
    }
}
