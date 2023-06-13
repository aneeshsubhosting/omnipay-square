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
        $errors = @$this->data->errors;

        return empty( $errors ) || "BAD_REQUEST" == @$errors[0]->code ? false : true;
    }

    public function getMessage()
    {
        $errors = @$this->data->errors;
        return empty( $errors ) || "" != @$errors[0]->detail ? @$errors[0]->detail : "Unknown Error";
    }    

    public function getRedirectData()
    {
        return $this->getData();
    }    

    public function getTransaction_reference()
    {
        return null;
    }
}
