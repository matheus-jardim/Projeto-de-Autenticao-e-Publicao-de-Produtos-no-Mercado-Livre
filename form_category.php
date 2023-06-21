<?php
if(!empty($_POST['title'])) {

    $title = $_POST['title'];
    $url = "https://api.mercadolibre.com/sites/MLB/domain_discovery/search?q=$title";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    
    if($response) {
        print_r($response);
    }    
} 
?>