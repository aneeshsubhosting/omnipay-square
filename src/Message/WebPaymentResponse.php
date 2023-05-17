<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class WebPaymentResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return "COMPLETED" === $this->data->getStatus() ? true : false;
    }
    public function getRedirectData()
    {
        return $this->getData();
    }    

    public function getTransaction_reference()
    {
        return $this->getData()->getId();
    }
}
