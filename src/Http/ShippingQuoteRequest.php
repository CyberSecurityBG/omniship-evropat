<?php

namespace Omniship\Evropat\Http;

use Doctrine\Common\Collections\ArrayCollection;
use Omniship\Evropat\Client;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {
        $cash_on_delivery = 0;
        if ($this->getCashOnDeliveryAmount() > 0) {
            $cash_on_delivery = $this->getCashOnDeliveryAmount();
        }
        if (!empty($this->getSenderAddress()->getOffice())) {
            $from = 'office';
        } else {
            $from = 'address';
        }
        if (!empty($this->getReceiverAddress()->getOffice())) {
            $to = 'office';
        } else {
            $to = 'address';
        }

        $shipmentType = null;
        if ($from == 'office' && $to == 'office') {
            $shipmentType = 1;
        } elseif ($from == 'office' && $to == 'address') {
            $shipmentType = 2;
        } elseif ($from == 'address' && $to == 'office') {
            $shipmentType = 3;
        } elseif ($from == 'address' && $to == 'address') {
            $shipmentType = 4;
        }

        $paymentWay = $this->getPayer();
        if(!is_numeric($this->getPayer())) {
            switch ($this->getPayer()) {
                case 'SENDER':
                    $paymentWay = 1;
                    break;
                case 'RECEIVER':
                    $paymentWay = 2;
                    break;
                case 'SENDER_CN':
                    $paymentWay = 3;
                    break;
                case 'RECEIVER_CN':
                    $paymentWay = 4;
                    break;
                case 'ThirdSide_CN':
                    $paymentWay = 5;
                    break;
            }
        }

        $data['clientKey'] = $this->getParameter('api_key');
        $data['fromDestID'] = $this->getSenderAddress()->getCity()->getId();
        $data['toDestID'] = $this->getReceiverAddress()->getCity()->getId();
        $data['shipmentType'] = 2;
        $data['payer'] = $paymentWay;
        $data['method'] = 2;
        if (!empty($this->getOtherParameters('clientNumber'))) {
            $data['clientNumber'] = $this->getOtherParameters('clientNumber');
            $data['method'] = 1;
        }
        if($this->getOtherParameters('returnReceipt')){
            $data['hasReturnReceipt'] = true;
        }
        $data['deliveryType'] = $shipmentType;
        if ($this->getOtherParameters('cd') == 1) {
            $data['cashOnDelivery'] = (float)$this->getCashOnDeliveryAmount();
        }
        if($this->getMoneyTransfer() == 1){
            $data['postalMoneyOrder'] = (float)$this->getCashOnDeliveryAmount();
            $data['cashOnDelivery'] = null;
        }
        if($this->getOtherParameters('accompanyingDocuments')){
            $data['accompanyingDocuments'] = true;
        }
        if($this->getOtherParameters('allowShipmentCheck')){
            $data['allowShipmentCheck'] = true;
        }
        $data['insurance'] = 0;
        if ($this->getOtherParameters('insurance') == 1) {
            $data['insurance'] = (float)$this->getInsuranceAmount();
        }
        if($this->getOtherParameters('client_number')){
            $data['client_number'] = $this->getOtherParameters('client_number');
        }
        $data['parcelCount'] = $this->getItems()->count();
//        $total_volume = 0;
//        foreach ($this->getItems() as $item) {
//            $total_volume += ($item->width * $item->height * $item->depth) * $item->quantity;
//        }

        $data['weight'] = $this->getWeight();
        //$data['sizeL'] = number_format($total_volume ** (1 / 3), 2, '.', '');
        $data['verification'] = $this->getOtherParameters('verification') == 1 ? true : false;
        $data['notification'] = $this->getOtherParameters('notification') == 1 ? true : false;
        return array_filter($data);
    }

    public function sendData($data)
    {
        $services = (new Client($data['clientKey']));
        return $this->createResponse($services->SendRequest('POST', 'calculateprice', $data));
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
