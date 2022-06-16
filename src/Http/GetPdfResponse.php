<?php

namespace Omniship\Evropat\Http;

class GetPdfResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData(){
        if(!empty($this->getMessage())) {
            return null;
        }
        return file_get_contents($this->data->response);
    }

}
