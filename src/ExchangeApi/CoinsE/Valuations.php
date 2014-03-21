<?php

namespace ExchangeApi\CoinsE;

class Valuations{


  static public function fetch(){
    $client = new \Guzzle\Http\Client('https://www.coins-e.com/api/');
    $request = $client->get('v2/markets/data/');
    $response = $request->send();
    $data = $response->json();
    $valuations = array();

    foreach($data['markets'] as $pair_name => $pair){
      $valuations[$pair['c1']][$pair['c2']] = array(
        'volume' => $pair['marketstat']['ltq'],
        'price' => $pair['marketstat']['ltp'],
        'price_time' => date("Y-m-d H:i:s"),
      );
    }


    return $valuations;
  }
}