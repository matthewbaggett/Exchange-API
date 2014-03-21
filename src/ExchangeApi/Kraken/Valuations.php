<?php

namespace ExchangeApi\Kraken;

class Valuations{


  static public function fetch(){
    $client = new \Guzzle\Http\Client('https://api.kraken.com/0/public/');
    $request = $client->get('AssetPairs');
    $response = $request->send();
    $data = $response->json();
    krumo($data['result']);exit;
    foreach($data['result'] as $name => $tradable_pair){

    }
    $valuations = array();

    return $valuations;
  }
}