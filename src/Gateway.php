<?php

namespace Omniship\Evropat;

use Omniship\Common\AbstractGateway;
use Omniship\Evropat\Client;

class Gateway extends AbstractGateway
{

    private $name = 'Evropat';
    protected $client;
    const TRACKING_URL = 'https://berry.bg/bg/t/';

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
            'username' => '',
            'password' => '',
            'key_primary' => '',
            'key_secondary' => '',
        );
    }

    public function getUsername() {
        return $this->getParameter('username');
    }

    public function setUsername($value) {
        return $this->setParameter('username', $value);
    }

    public function getPassword() {
        return $this->getParameter('password');
    }

    public function setPassword($value) {
        return $this->setParameter('password', $value);
    }

    public function getKeyPrimary() {
        return $this->getParameter('key_primary');
    }

    public function setKeyPrimary($value) {
        return $this->setParameter('key_primary', $value);
    }

    public function getKeySecondary() {
        return $this->getParameter('key_secondary');
    }

    public function setKeySecondary($value) {
        return $this->setParameter('key_secondary', $value);
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
            $this->client = new Client($this->getUsername(), $this->getPassword(), $this->getKeyPrimary(), $this->getKeySecondary());
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
}
