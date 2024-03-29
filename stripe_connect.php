<?php
require_once 'config.php';
require_once 'utilities.php';
require_once('vendor/autoload.php');
////////

use Parse\ParseClient;
ParseClient::initialize($parsekeys['key1'], $parsekeys['key2'], $parsekeys['key3']);

use Parse\ParseQuery;
use Parse\ParseObject;

$error = get_parameter('error');
$error_description = get_parameter('error_description');
$scope = get_parameter('scope');
$code = get_parameter('code');

if ($scope && $code) {
    $post = array(
        'client_secret' => $secret_key,
        'code' => $code,
        'grant_type' => 'authorization_code'
    );
    
    // We got the scope and code. We'll exchane this with the SPK and AT by running a curl function
    $res = json_decode(postToCurl($stripe_token_url, $post));

    
    if($res->error || $res->error_description) {
        $s = "Error: ".$res->error." - ".$res->error_description;
        //consoleLog($s);
    } else {
        $accessToken = $res->access_token;
        $pubKey = $res->stripe_publishable_key;
        $stripe_user = $res->stripe_user_id;

        // Create Parse query to retrieve all invoices generated by a particular stripe user
        $query = new ParseQuery("Invoice");
        $query->equalTo("su", $stripe_user);
        $results = $query->find();
        
        // Everytime we need to check SPK and AT for all invoices of a user, in case of possible overrides or invokes
        foreach($results as $old_invoice_model){
            if(($old_invoice_model->at != $accessToken) || ($old_invoice_model->spk != $pubKey) ) {
                $old_invoice_model->set("at", $accessToken);
                $old_invoice_model->set("spk", $pubKey);
                $old_invoice_model->save();
            }
        }

        //consoleLog($accessToken);
        //consoleLog($pubKey);
        //$stripe['user_publishable_key'] = $pubKey;
        //$s = "PK: ".$res->stripe_publishable_key." - SK: ".$res->access_token;
        //consoleLog($s);
    }


} else if ($error || $error_description) {
    // Error
    $s = "Error: ".$error." - ".$error_description;
    //consoleLog("error: " . $error);
    //consoleLog("error_description: " . $error_description);
}

require_once 'index.thtml';


?>
