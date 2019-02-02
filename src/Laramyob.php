<?php

namespace Creativecurtis\Laramyob;

use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Authentication\MyobAuthenticate;

class Laramyob
{
    // Build your next great package.
    public $authenticate; 

    public function __construct() {
       $this->authenticate = new MyobAuthenticate(new MyobRequest);
    }
    
    public function authenticate() {
        return $this->authenticate;
    }
}
