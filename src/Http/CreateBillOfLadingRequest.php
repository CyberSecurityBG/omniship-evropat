<?php


namespace Omniship\Evropat\Http;

use Carbon\Carbon;
use Omniship\Evropat\Client;
use Omniship\Evropat\Http\ShippingQuoteResponse;

class CreateBillOfLadingRequest extends AbstractRequest
{
    public function getData()
    {
        $cash_on_delivery = 0;
        if($this->getCashOnDeliveryAmount() > 0){
            $cash_on_delivery = $this->getCashOnDeliveryAmount();
        }
        if(!empty($this->getSenderAddress()->getOffice())){
            $from = 'office';
        } else {
            $from = 'address';
        }
        if(!empty($this->getReceiverAddress()->getOffice())){
            $to = 'office';
        } else {
            $to = 'address';
        }

        $shipmentType = null;
        if($from == 'office' && $to == 'office'){
            $shipmentType = 1;
        } elseif($from == 'office' && $to == 'address'){
            $shipmentType = 2;
        } elseif ($from == 'address' && $to == 'office'){
            $shipmentType = 3;
        } elseif ($from == 'address' && $to == 'address'){
            $shipmentType = 4;
        }

        $paymentWay = null;
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
        if($paymentWay == 1 && !empty($this->getOtherParameters('client_number'))){
            $paymentWay = 3;
        }
        if($paymentWay == 2 && !empty($this->getOtherParameters('client_number'))){
            $paymentWay = 4;
        }
        $data['method'] = 2;
        if (!empty($this->getOtherParameters('client_number'))) {
            $data['clientNumber'] = $this->getOtherParameters('client_number');
            $data['method'] = 1;
        }
        // $data
        $data['clientKey'] = $this->getParameter('api_key');
        $data['senderDestID'] = $this->getSenderAddress()->getCity()->getId();
        if($this->getOtherParameters()->get('send_type') == 'office') {
            $data['senderOfficeID'] = $this->getSenderAddress()->getOffice()->getId();
        }
        $data['senderAddress'] =  $this->getSenderAddress()->getCity()->getName().', '.$this->getSenderAddress()->getAddress1();
        $data['senderName'] = $this->getSenderAddress()->getFullName();
        $data['senderPhone'] = $this->getSenderAddress()->getPhone();
        $data['senderFirm'] = $this->getSenderAddress()->getCompanyName();
        $data['recipientDestID'] = $this->getReceiverAddress()->getCity()->getId();

        if(!empty($this->getReceiverAddress()->getOffice())) {
            $data['recipientOfficeID'] = $this->getReceiverAddress()->getOffice()->getId();
            $data['recipientAddress'] = $this->getReceiverAddress()->getOffice()->getName();
        } else {
            $data['recipientAddress'] =  $this->getReceiverAddress()->getStreet()->getName();
            $data['recipientAddressNumber'] = $this->getReceiverAddress()->getStreetNumber();
            $data['recipientAddressAddition'] = $this->getReceiverAddress()->getAddress1();
        }

        if($this->getOtherParameters('number_package') > 0){
            $number_package = $this->getOtherParameters('number_package');
        } else {
            $number_package = $this->getItems()->count();
        }

        $data['recipientName'] = $this->getReceiverAddress()->getFullName();
        $data['recipientPhone'] = $this->getReceiverPhone();
        $data['deliveryType'] = $shipmentType;
        $data['shipmentType'] = 2;
        $data['paymentWay'] = $paymentWay;
        $data['shipmentDescription'] = $this->getContent();
        $data['twoWayShipment'] = null;
        $data['twoWayShipmentPaymentWay'] = null;
        $data['twoWayShipmentClientNumber'] = null;
        $data['twoWayShipmentExpirationDays'] = null;
        $data['verification'] = $this->getOtherParameters('verification') == 1 ? true : false;
        $data['notification'] = $this->getOtherParameters('notification') == 1 ? true : false;;
        $data['recipientEmail'] = $this->getReceiverEmail();
        $data['parcelCount'] = $number_package;
        $data['shipmentWeight'] = $this->getWeight();
        if(!empty($this->getOtherParameters('breakable'))) {
            $data['breakable'] = true;
        }
        $data['returnReceipt'] = !empty($this->getBackReceipt()) ? true : false;
        if(!empty($this->getOtherParameters('accompanyingDocuments'))) {
            $data['accompanyingDocuments'] = true;
        }
        if(!empty($this->getCashOnDeliveryAmount())) {
            $data['cashOnDelivery'] = $this->getCashOnDeliveryAmount();
            $data['cashOnDeliveryDirection'] = 0;
        }
        $data['postalMoneyOrder'] = null;
//        $total_volume = 0;
//        foreach($this->getItems() as $item){
//            $total_volume += ($item->width*$item->height*$item->depth)*$item->quantity;
//        }
//
//        $data['length'] = $total_volume**(1/3);
        if($this->getMoneyTransfer() == 1 && !empty($this->getOtherParameters('client_number'))){
            $data['postalMoneyOrder'] = (float)$this->getCashOnDeliveryAmount();
            $data['cashOnDelivery'] = null;
            unset($data['cashOnDeliveryDirection']);
        }
        $data['height'] = null;
        $data['width'] = null;
        $data['allowShipmentCheck'] = $this->getOtherParameters('allowShipmentCheck');
        $data['returnShipmentForExpense'] = $this->getOtherParameters('returnShipmentForExpense');
        $data['deliveryDay'] = null;
        $data['extraDay'] = null;
        $data['shipmentValue'] = null;
        $data['shipmentValueDocumentType'] = null;
        $data['shipmentValueDocumentNumber'] = null;
        $data['shipmentValueDocumentDate'] = null;
        $data['priorityHour'] = null;
        $data['dailyExpress'] = null;
        $data['coolingBag'] = null;
        $data['coolingBagNumber'] = null;
        $data['envelopeNumber'] = null;
        $data['shipmentMoreInfo'] = null;
        $data['clientNumber'] = $this->getOtherParameters('client_number');
        return array_filter($data);
    }

    public function sendData($data)
    {
        $services = (new Client($data['clientKey']));
        return $this->createResponse($services->SendRequest('POST', 'createshipment',$data));
    }

    protected function createResponse($data)
    {
        return $this->response = new CreateBillOfLadingResponse($this, $data);
    }

}
