<?php

namespace Omniship\Evropat\Http;
use Omniship\Evropat\Client;


class ValidateCredentialsRequest extends AbstractRequest
{


    public function getData()
    {
    }

    /**
     * @param $data
     * @return ValidateCredentialsResponse|\Omniship\Interfaces\ResponseInterface
     */
    public function sendData($data)
    {
        $params = $this->parameters->all();
        $services = (new Client($params['api_key']));
        $services = $services->SendRequest('POST', 'testclientkey', ['clientKey' => $params['api_key']]);
        return $this->createResponse($services);
    }

    /**
     * @param $data
     * @return ValidateCredentialsResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
