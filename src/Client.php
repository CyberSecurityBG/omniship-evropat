<?php

namespace Omniship\Evropat;

use Carbon\Carbon;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use http\Client\Response;

class Client
{

    protected $api_key;
    protected $error;
    const SERVICE_PRODUCTION_URL = 'https://api.evropat.com/';
    const SERVICE_TEST_URL = 'https://devapi.evropat.com/';

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }


    public function getError()
    {
        return $this->error;
    }


    /**
     * @param $method
     * @param $endpoint
     * @param $data
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function SendRequest($method, $endpoint, $data = [])
    {
        try {
            $client = new HttpClient(['base_uri' => self::SERVICE_PRODUCTION_URL]);
            $response = $client->request($method, $endpoint, [
                'debug'  => fopen('php://stderr', 'w'),
                'form_params' => $data,
            ]);


            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getMessage()
            ];
        }

    }

    /**
     * @param $api_key
     * @return \Illuminate\Support\Collection|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getClientAddresses($api_key){
        $get =  $this->SendRequest('POST', 'getclientaddresses', ['clientKey' => $api_key]);
        if(empty($get->error)){
            return collect((array)$get->response)->unique('destinationID');
        }
    }

    /**
     * @return false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOffices(){
        $get =  $this->SendRequest('POST', 'getoffices', ['clientKey' => $this->api_key, 'limit' => -1]);
        if(empty($get->error)){
            return $get->response;
        }
        return false;
    }

    /**
     * @return false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCities(){
        $get =  $this->SendRequest('POST', 'getdestinations?extendedResponse=true', ['clientKey' => $this->api_key, 'limit' => -1]);
        if(empty($get->error)){
            return $get->response;
        }
        return false;
    }

    /**
     * @param $number
     * @param $type
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPdf($number, $type){
        return $this->SendRequest('POST', 'printshipment', ['clientKey' => $this->api_key, 'shipmentBarCode' => $number, 'printoutType' => $type]);
    }

    /**
     * @param $number
     * @param $type
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getParcels($number, $type){
        $response =  $this->SendRequest('POST', 'printparcels', ['clientKey' => $this->api_key, 'shipmentBarCode' => $number, 'printoutType' => $type]);
        if(empty($response->error)){
            return file_get_contents($response->response);
        }
        return false;
    }

    /**
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAddress(){
        $response =  $this->SendRequest('POST', 'getclientaddresses', ['clientKey' => $this->api_key]);
        if(empty($response->error)){
            return $response->response;
        }
        return false;
    }

}
