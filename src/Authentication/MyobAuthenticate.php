<?php

namespace Creativecurtis\Laramyob\Authentication;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfiguration;

class MyobAuthenticate {

    protected $client_id;
    protected $client_secret;
    protected $scope_type;
    protected $redirect_uri;
    protected $myobRequest;

    public function __construct(MyobRequest $myobRequest) 
    {
        $this->client_id     = config('laramyob.client_id');
        $this->client_secret = config('laramyob.client_secret');
        $this->scope_type    = config('laramyob.scope_type');
        $this->redirect_uri  = config('app.url').'/'.config('laramyob.redirect_uri');
        $this->myobRequest   = $myobRequest;
    }

    public function getCode() 
    {
        return redirect('https://secure.myob.com/oauth2/account/authorize?client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri).'&response_type=code&scope='.$this->scope_type);
    }

    public function getToken(Request $request = null, $grant_type = 'authorization_code', $refresh_token = null)
    {
        $http_attributes = [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'code'          => $request ? $request['code'] : null,
                'grant_type'    => $grant_type, 
                'refresh_token' => $refresh_token,
                'redirect_uri'  => $this->redirect_uri, 
                'scope'         => $this->scope_type, 
                'client_id'     => $this->client_id, 
                'client_secret' => $this->client_secret
            ],
        ];
        $response = $this->myobRequest->sendPostRequest('https://secure.myob.com/oauth2/v1/authorize', $http_attributes);

        $response = json_decode($response->getBody()->getContents(), true);

        $myobConfiguration = MyobConfiguration::updateOrCreate(['id' => 1], [
            'access_token'  => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
            'scope'         => $response['scope'],
            'expires_at'    => Carbon::now()->addSeconds($response['expires_in']),
        ]);
            
        return $myobConfiguration;
    }
    
    public function getRefreshToken() 
    {
        return $this->getToken(null, 'refresh_token', MyobConfiguration::first()->refresh_token);
    }
}
