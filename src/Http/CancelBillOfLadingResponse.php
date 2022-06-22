<?php
namespace Omniship\Evropat\Http;

class CancelBillOfLadingResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if(!empty($this->getMessage())){
            return null;
        }
        return $this->data;
    }

}
