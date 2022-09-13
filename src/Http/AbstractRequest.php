<?php

namespace Omniship\Evropat\Http;

use Omniship\Interfaces\RequestInterface;
use Omniship\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest implements RequestInterface
{
    /**
     * @return mixed
     */
    public function getBaseUrl(){

        return $this->getParameter('base_url');
    }

    public function setBaseUrl($value){
        return $this->setParameter('base_url', $value);
    }


    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->getParameter('api_key');
    }


    public function setMoneyTransfer($value)
    {
        return $this->setParameter('money_transfer', $value);
    }

    public function getMoneyTransfer()
    {
        return $this->getParameter('money_transfer');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('api_key', $value);
    }

    abstract protected function createResponse($data);

}
