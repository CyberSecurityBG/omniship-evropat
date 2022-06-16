<?php

namespace Omniship\Evropat\Http;
use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        if(!empty($this->getMessage())){
             return null;
        }

        $result = new ShippingQuoteBag();
        $result->push( [
            'id' => 1,
            'name' => 'Standard delivery',
            'description' => null,
            'price' => $this->data->response->price,
            'pickup_date' => null,
            'pickup_time' => null,
            'delivery_date' => null,
            'delivery_time' => null,
            'currency' => $this->getRequest()->getCurrency(),
            'tax' => null,
            'insurance' => null,
            'exchange_rate' => null,
            'payer' => $this->getRequest()->getPayer(),
            'allowance_fixed_time_delivery' => false,
            'allowance_cash_on_delivery' => true,
            'allowance_insurance' => true,
        ]);
        return $result;
    }
}
