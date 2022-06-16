<?php


namespace Omniship\Evropat\Http;

use Omniship\Evropat\Client;
use Omniship\Message\AbstractResponse AS BaseAbstractResponse;

class AbstractResponse extends BaseAbstractResponse
{

    protected $error;

    protected $errorCode;

    protected $client;


    /**
     * Get the initiating request object.
     *
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return  $this->request;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        $message = null;
        $data = $this->data;
        if(!empty($data->error)){
            $message = $data->errorMessage;
        }
        return $message;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        if($this->getMessage() != null){
            return 400;
        }
        return null;
    }

    /**
     * @return null|Client
     */
    public function getClient()
    {
        return $this->getRequest()->getClient();
    }

    /**
     * @param mixed $client
     * @return AbstractResponse
     */


}
