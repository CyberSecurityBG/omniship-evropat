<?php

namespace Omniship\Evropat\Http;

class ValidateCredentialsResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if($this->data){
            return $this->data->response;
        }
        return false;
    }

}
