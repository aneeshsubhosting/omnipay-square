<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Refund Response
 */
class WebPaymentRefundResponse extends AbstractResponse
{

    public function isSuccessful()
    {  
        if( "" == $this->data->getErrors() && "PENDING" == $this->data->getRefund()->getStatus()  ){
            return $this->data->getRefund();
        } else {
            return false;
        }
    }

    public function getMessage()
    {
        $errors = $this->data->getErrors();
        return empty( $errors ) || "" != @$errors[0]->detail ? @$errors[0]->detail : "Unknown Error";
    }    

    public function getData()
    {
        return $this->data->getRefund()->getId();
    }  
}
