<?php
class ServiceBootstrap 
{
    private $status = [];
    private $errors = [];
    
    public function __construct ()
    {
        require('src/Autoload.php');
    }

    public function getStatus ()
    {
        return $this->status;
    }

    public function getErrors ()
    {
        return $this->errors;
    }

    public function addStatus ($status)
    {
        return $this->status [] = $status;
    }

    public function addError ($error)
    {
        return $this->errors [] = $error;
    }

    public function handleAuthenticationRequest() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
            throw new Exception('Only POST requests are allowed.');

        if (isset ($_POST['token'])) 
        {
            $authAdapter = new AuthenticationAdapter (new TokenAuthentication());
            
            $authenticated = $authAdapter->authenticate($_POST['token']);
            
            $authenticated ?  
                $this->addStatus ('Token authentication successful!') : 
                $this->addError ('Token authentication failed.');
        } 
        elseif (isset ($_POST['username'], $_POST['password'])) 
        {
            $authAdapter = new AuthenticationAdapter (new UserPassAuthentication());
            
            $credentials = ['username' => $_POST['username'], 'password' => $_POST['password']];
            
            $authenticated = $authAdapter->authenticate($credentials);
            
            $authenticated ? 
                $this->addStatus ('Username and password authentication successful!') : 
                $this->addError ('Username and password authentication failed.');
        } 
        else 
            $this->addError ('Authentication data missing.');

    }

    public function execute()
    {
        $this->handleAuthenticationRequest();
        $this->processPayment();


        if ($this->getErrors ())
            throw new Exception ();
        
        return TRUE;
    }

    private function processPayment() 
    {
        $creditCardNumber = $_POST['creditCardNumber'];
        
        $amount = $_POST['amount'];

        $this->addStatus ('Payment completed successfully by <b> Credit Card:  </b> ' . $creditCardNumber . ' <br> | <b>R$ '.$amount.'</b>');

        TransactionLog::write ($creditCardNumber);
    }
}
