<?php

namespace Omniship\Evropat;

use Omniship\Common\AbstractGateway;
use Omniship\Evropat\Client;
use Omniship\Evropat\Http\CancelBillOfLadingRequest;
use Omniship\Evropat\Http\CreateBillOfLadingRequest;
use Omniship\Evropat\Http\GetPdfRequest;
use Omniship\Evropat\Http\ShippingQuoteRequest;
use Omniship\Evropat\Http\ValidateCredentialsRequest;

class Gateway extends AbstractGateway
{

    private $name = 'Evropat';
    protected $client;
    const TRACKING_URL = 'https://evropat.bg/track/';

    /**
     * @return stringc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'api_key' => '',
        );
    }

    public function getApiKey() {
        return $this->getParameter('api_key');
    }

    public function setApiKey($value) {
        return $this->setParameter('api_key', $value);
    }


    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }

    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client($this->getApiKey());
        }

        return $this->client;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }

    public function supportsValidateCredentials(){
        return true;
    }


    public function validateCredentials(array $parameters = [])
    {
        return $this->createRequest(ValidateCredentialsRequest::class, $parameters);
    }


    public function getQuotes($parameters = [])
    {
        if($parameters instanceof ShippingQuoteRequest) {
            return $parameters;
        }
        if(!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(ShippingQuoteRequest::class, $this->getParameters() + $parameters);
    }
    public function supportsCashOnDelivery()
    {
        return true;
    }

    public function supportsCreateBillOfLading(){
        return true;
    }

    public function createBillOfLading($parameters = [])
    {
        if ($parameters instanceof CreateBillOfLadingRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(CreateBillOfLadingRequest::class, $this->getParameters() + $parameters);
    }

    public function getPdf($bol_id)
    {
        return $this->createRequest(GetPdfRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function trackingUrl($parcel_id)
    {
        return sprintf(static::TRACKING_URL, $parcel_id);
    }
    public function cancelBillOfLading($bol_id, $cancelComment = null)
    {
        $this->setBolId($bol_id);
        return $this->createRequest(CancelBillOfLadingRequest::class, $this->getParameters());
    }
}
