<?php
namespace Omniship\Evropat\Http;
use Omniship\Evropat\Client;


class GetPdfRequest extends AbstractRequest
{
    /**
     * @return integer
     */
    public function getData() {
        return [];
    }

    /**
     * @param mixed $data
     * @return GetPdfResponse
     */
    public function sendData($data) {

        $params = $this->parameters->all();
        $services = (new Client($params['api_key']));
        return $this->createResponse($services->getPdf($params['bol_id'], $this->getOtherParameters('printer_type')));

    }

    /**
     * @param $data
     * @return GetPdfResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new GetPdfResponse($this, $data);
    }

}
