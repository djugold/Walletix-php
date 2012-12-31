<?php

require_once 'walletix-library.php';
define('VENDOR_ID', "YOUR_vendorID_HERE");
define('API_KEY', "YOUR_apiKey_HERE");

/* ************************************ Initialisation ******************************************** */
$walletix= new walletix(VENDOR_ID,API_KEY);


/* ************ Générer un code de paiement : ID de l'opération = 123 /  montant 200 DA / callBack URL: http://www.koutoub-store.com/checkout************ */

	
$reponse= $walletix->generatePaymentCode(123,200,"http://www.koutoub-store.com/checkout");

if (WALLETIX_OK == $reponse->status) {
  echo "Votre code de paiement est {$reponse->code} <br />";
}else{
  echo "Erreur lors de la génération du code de paiement <br /> Code d'erreur: $reponse->status <br />";
  
} 



	
/* *** Vérifier l'état d'une opération de paiement: code de paiement de l'opération = A83A1 *** */


$reponse= $walletix->verifyPayment("A83A1");
if (WALLETIX_OK == $reponse->status) {
  
	if (WALLETIX_OK == $reponse->result) {
		echo "Le paiement a bien été effectué. <br />";
	}else{
		echo "Le paiement n'a pas été encore effectué.<br />";
	} 
}else{
  echo "Erreur lors de la vérification.<br />";
} 


?>