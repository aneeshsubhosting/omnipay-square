<?php

namespace Omnipay\Square\Message;
use \SquareConnect\ApiClient;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class WebPaymentRefundRequest extends AbstractRequest
{

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }
   
    public function getHost()
    {
         if( $this->getTestMode() || "on" == $this->getTestMode() ){
             return 'https://connect.squareupsandbox.com';
         } else {
              return 'https://connect.squareup.com';
         }
       
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
        return 'https://connect.squareupsandbox.com';
    }
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }  
    public function getTransactionReference()
    {
        return $this->getParameter('transactionReference');
    }

    public function setTransactionReference($value)
    {
        return $this->setParameter('transactionReference', $value);
    }  
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }         

  
    public function getData()
    {
    
        $data = [
            'amount' => (int) $this->getAmount() * 100,
            'currency' => $this->getParameter('currency')
        ];
        $moneyModel = new SquareConnect\Model\Money($data);

        $data_array = [
            'amount_money' => $moneyModel,
            'payment_id' => $this->getParameter('transactionReference'),
            'reason' => 'Refund Requested',
            'idempotency_key' => uniqid()
        ];
        $data = new \SquareConnect\Model\RefundPaymentRequest($data_array);  

        return $data;
    }

    public function sendData($data)
    {

        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());
       
        $apiClient = new ApiClient();
        $apiClient->getConfig()->setHost($this->getHost());
        $apiClient->getConfig()->setAccessToken($this->getAccessToken());
        
        $api_instance = new SquareConnect\Api\RefundsApi($apiClient);

        try {
            $result = $api_instance->refundPayment($data);
            return $this->createResponse($result);
        } catch (Exception $e) {
            echo 'Exception when calling RefundsApi->refundPayment: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createResponse($response)
    {
        return $this->response = new WebPaymentRefundResponse($this, $response);
    }
}
