<?php

namespace Omniship\Evropat;

use Carbon\Carbon;
use GuzzleHttp\Client AS HttpClient;
use http\Client\Response;

class Client
{

    protected $username;
    protected $password;
    protected $key_primary;
    protected $key_secondary;
    protected $error;
    protected $token;
    const SERVICE_PRODUCTION_URL = 'https://urgentcargus.azure-api.net/api/';
    public function __construct($username, $password, $token = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->key_primary = '2e43b07e28f443559b6c3832c46da64b';
        $this->key_secondary = 'de662014db2240189e7578370b03b975';
        $this->token = $token;
    }


    public function getError()
    {
        return $this->error;
    }


    public function SendRequest($method, $endpoint, $data = []){
        $Token = $this->getToken();
        if(!is_null($Token)) {
            try {
                $client = new HttpClient(['base_uri' => self::SERVICE_PRODUCTION_URL]);
                $response = $client->request($method, $endpoint, [
                    'json' => $data,
                    'headers' => $this->SetHeader($endpoint, $method, $Token)
                ]);
               // dd($response->getBody()->getContents());
                return json_decode($response->getBody()->getContents());
            } catch (\Exception $e) {
                return  $this->error = [
                    'code' => $e->getCode(),
                    'error' => json_decode($e->getResponse()->getBody()->getContents())
                ];
            }
        }
    }

}
