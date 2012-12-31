<?php

/*
 * -Cette classe permet d'utiliser les  fonctions de Walletix:
 * - Générer un code de paiement à partir de l’identifiant de la commande 
 * et du montant à payer. 
 * 
 * -Vérifier l’état d’une opération de paiement (vérifier si une opération de 
 * paiement a bien été effectuée).
 *
 *
 * Copyright (c) 2011-2012 Youghourta Benali
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 **/

//define ('API_PATH','http://localhost/walletix/api/');
define ('API_PATH','https://www.walletix.com/api/');
define ('GENERATE_PAYMENT_CODE','paymentcode');
define ('VERIFY_PAYMENT','paymentverification');

define('WALLETIX_OK',1);

class Walletix{

	var $vendorID;
	var $apiKey;

	function __construct($vendorID, $apiKey){
		$this->vendorID = $vendorID;
		$this->apiKey = $apiKey;
	}
	
	public function generatePaymentCode($purchaseID, $amount,$callbackUrl){
		if ($this->vendorInfoSet()){
		
			$params = array(
				'vendorID'    => $this->vendorID, 
				'apiKey'      => $this->apiKey, 
				'purchaseID'  => $purchaseID, 
				'amount'      => $amount,
				'callbackurl'      => $callbackUrl,
				'format'	  => 'xml'
			);
		
			return $this->post(API_PATH.GENERATE_PAYMENT_CODE, $params);
		}else{
			$this->errorMessage();
			return 0;
		}
	}
  
	public function verifyPayment($paiementCode){
	  
		if ($this->vendorInfoSet()){
			$params = array(
			  'vendorID'          => $this->vendorID, 
			  'apiKey'            => $this->apiKey, 
			  'paiementCode'      => $paiementCode,
			  'format'	  		  => 'xml'
			);

			return $this->post(API_PATH.VERIFY_PAYMENT, $params);	
		}else{
			$this->errorMessage();
			return 0;	
		}
	}

  private function post($url, $params) {

	$result = "";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close($ch);
	return new SimpleXMLElement($result);  
  }

  private function vendorInfoSet(){
    return !(empty($this->vendorID) || empty($this->apiKey));
  }
 
  private function errorMessage(){
    trigger_error('Required parameters($vendorID and or $apiKey) are missing.', E_USER_WARNING);
  }
  
}

?>