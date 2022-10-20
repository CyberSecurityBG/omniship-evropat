<?php

namespace Omniship\Evropat\Http;

use Carbon\Carbon;
use Omniship\Common\Bill\Create;
use Omniship\Evropat\Client;

class CreateBillOfLadingResponse extends AbstractResponse
{
    /**
     * @var Parcel
     */
    protected $data;
    /**
     * @return Create
     */
    public function getData()
    {
        if(!empty($this->getMessage()) || empty($this->data->response)){
            return null;
        }

        $client = (new Client($this->getRequest()->getParameter('api_key')));
        $pdf = $client->getPdf($this->data->response[0], 'A4');
        $data = $this->data;
        $result = new Create();
        $result->setBolId($this->data->response[0]);
        $result->setBillOfLadingSource($pdf->response);
        $result->setBillOfLadingType($result::PDF);
        return $result;
    }

}
