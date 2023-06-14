<?php

namespace Omnipay\Square\Message;
use \SquareConnect\ApiClient;

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
    public function getCustomerId()
    {
        return $this->getParameter('customer_id');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customer_id', $value);
    } 
    public function getMethod()
    {
        return $this->getParameter('method');
    }

    public function setMethod($value)
    {
        return $this->setParameter('method', $value);
    }            

     
    public function getData()
    {

        if("void" == $this->getMethod()){
            $data_array = [
                'transaction_id' => $this->getParameter('transactionReference'),
                'method' => 'void'
            ];
            $data = new \SquareConnect\Model\CancelPaymentRequest($data_array);   
        } else {
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
        }

        return $data;
    }

    public function sendData($data)
    {
       
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());
       
        $apiClient = new ApiClient();
        $apiClient->getConfig()->setHost($this->getHost());
        $apiClient->getConfig()->setAccessToken($this->getAccessToken());
        
        $api_instance = new SquareConnect\Api\PaymentsApi($apiClient);

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
