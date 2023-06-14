<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class WebPaymentCancelResponse extends AbstractResponse
{

    public function isSuccessful()
    {  
        if( null == $this->data->getErrors() && "CANCELED" == $this->data->getPayment()->getStatus()  ){
            return true;
        } elseif( empty( $errors ) || "BAD_REQUEST" == @$errors[0]->code ){
            return false;
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
        return $this->data->getPayment();
    }  
}
