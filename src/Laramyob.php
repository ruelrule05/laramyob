<?php

namespace Creativecurtis\Laramyob;

use Carbon\Carbon;
use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Authentication\MyobAuthenticate;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfiguration;

class Laramyob
{
    public $authenticate; 

    public function __construct() 
    {
       $this->authenticate = new MyobAuthenticate(new MyobRequest);
    }
    
    public function authenticate() 
    {
        return $this->authenticate;
    }

    public function load($model) 
    {
        if($this->preflight()) {
            return app()->make($model)->load();
        }
    }

    private function preflight() 
    {
        if(MyobConfiguration::first() && Carbon::now() > MyobConfiguration::first()->expires_at) {
            return $this->authenticate->getRefreshToken();
        } else {
            return true;
        }
    }
}
