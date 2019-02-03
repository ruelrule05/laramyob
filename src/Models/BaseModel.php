<?php

namespace Creativecurtis\Laramyob\Models;

use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Exceptions\MyobConfigurationException;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfiguration;

abstract class BaseModel {

    public $endpoint;
    public $myobRequest;

    public function __construct() 
    {
        if(MyobConfiguration::first()) {
            $myobConfiguration = MyobConfiguration::first();
            $this->myobRequest = new MyobRequest([
                'Authorization' => 'Bearer '.$myobConfiguration->access_token,
                'x-myobapi-version' => 'v2',
                'x-myobapi-key' => config('laramyob.client_id'),
                'x-myobapi-cftoken' => base64_encode($myobConfiguration->company_file_token),
                'Accept' => 'application/json',
                'Content-Type' =>'application/json',
            ]);
        } else {
            throw MyobConfigurationException::myobConfigurationNotFoundException();

        }
        
    }
    public function load()
    {
        //
        $response = $this->myobRequest->sendGetRequest($this->endpoint);
        return json_decode($response->getBody()->getContents(), true);
    }
}