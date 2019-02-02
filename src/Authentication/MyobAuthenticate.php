<?php

namespace Creativecurtis\Laramyob\Authentication;

use Illuminate\Http\Request;
use Creativecurtis\Laramyob\Request\MyobRequest;

class MyobAuthenticate {

    protected $client_id;
    protected $client_secret;
    protected $grant_type;
    protected $scope_type;
    protected $redirect_uri;
    protected $myobRequest;

    public function __construct(MyobRequest $myobRequest) 
    {
        $this->client_id     = config('laramyob.client_id');
        $this->client_secret = config('laramyob.client_secret');
        $this->grant_type    = config('laramyob.grant_type');
        $this->scope_type    = config('laramyob.scope_type');
        $this->redirect_uri  = config('app.url').'/'.config('laramyob.redirect_uri');
        $this->myobRequest   = $myobRequest;
    }

    public function getCode() 
    {
        return redirect('https://secure.myob.com/oauth2/account/authorize?client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri).'&response_type=code&scope='.$this->scope_type);
    }

    public function getToken(Request $request)
    {
        $http_attributes = [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'code'          => $request['code'],
                'grant_type'    => $this->grant_type, 
                'redirect_uri'  => $this->redirect_uri, 
                'scope'         => $this->scope_type, 
                'client_id'     => $this->client_id, 
                'client_secret' => $this->client_secret
            ],
        ];

        $response = $this->myobRequest->sendPostRequest('https://secure.myob.com/oauth2/v1/authorize', $http_attributes);

        $response = json_decode($response->getBody()->getContents(), true);
        //TODO: do something with the token
    }
    
}
