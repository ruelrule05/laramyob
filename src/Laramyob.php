<?php

namespace Creativecurtis\Laramyob;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
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
    
    /**
     * Return the MYOB authentication class for usage
     *
     * @return MyobAuthenticate
     */
    public function authenticate() 
    {
        return $this->authenticate;
    }

    /**
     * Take a model that extends the base model.
     * Create that model, and then load the defaults
     *
     * @return MyobConfiguration || bool,
     */
    public function of($model) 
    {
        if($this->preflight()) {
            return App::make($model);
        }
    }

   /**
     * Preflight check for any request to see if we need to refresh the token
     *
     * @return MyobConfiguration || bool,
     */
    private function preflight() 
    {
        if(MyobConfiguration::first() && Carbon::now() > MyobConfiguration::first()->expires_at) {
            return $this->authenticate->getRefreshToken();
        } else {
            return true;
        }
    }
}
