<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class WebPaymentRequest extends AbstractRequest
{

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }
    public function getSourceId()
    {
        return $this->getParameter('source_id');
    }

    public function setSourceId($value)
    {
        return $this->setParameter('source_id', $value);
    }
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }    
    public function getHost()
    {
        return $this->getParameter('host');
    }
    public function setHost($value)
    {
        return $this->setParameter('host', $value);
    }     

    public function setSandBoxHost($value)
    {
        return $this->setParameter('sandboxHost', $value);
    }      
    public function getSandBoxHost()
    {
        return $this->getParameter('sandboxHost');
    }
    public function getCustomerId()
    {
        return $this->getParameter('customer_id');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customer_id', $value);
    }       

     
    public function getData()
    {
        $data_array = array(
            'idempotency_key' => uniqid(),
            'source_id' => $this->getSourceId(),
            'amount_money' => [
                'amount' => intval($this->getAmount()*100),
                'currency' => $this->getCurrency()
            ],
            'customer_id' => $this->getCustomerId(),
        );
        $data = new \SquareConnect\Model\CreatePaymentRequest($data_array);

        return $data;
    }

    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        if( $this->getTestMode() ){
            SquareConnect\Configuration::getDefaultConfiguration()->setHost($this->getSandBoxHost());
        } 
        $api_instance = new SquareConnect\Api\PaymentsApi();

        try {
            $result = $api_instance->createPayment($data);
            $result = $result->getPayment();
           
            return $this->createResponse($result);
        } catch (Exception $e) {
            echo 'Exception when calling LocationsApi->listLocations: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createResponse($response)
    {
        return $this->response = new WebPaymentResponse($this, $response);
    }
}
