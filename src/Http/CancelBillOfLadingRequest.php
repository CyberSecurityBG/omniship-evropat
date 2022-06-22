<?php
namespace Omniship\Evropat\Http;


use Omniship\Evropat\Client;

class CancelBillOfLadingRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData() {
        return $this->getBolId();
    }

    /**
     * @param mixed $data
     * @return CancelBillOfLadingResponse
     */
    public function sendData($data)
    {
        $params = $this->parameters->all();
        $services = (new Client($params['api_key']));
        return $this->createResponse($services->SendRequest('POST', 'cancelshipment', ['clientKey' => $params['api_key'], 'shipmentBarcode' => $params['bol_id']]));
    }


        /**
     * @param $data
     * @return CancelBillOfLadingResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CancelBillOfLadingResponse($this, $data);
    }

}
