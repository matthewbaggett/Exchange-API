<?php

namespace ExchangeApi\Cryptsy;

class Valuations{


  static public function fetch(){
    $client = new \Guzzle\Http\Client('Http://pubapi.cryptsy.com/');
    $request = $client->get('api.php?method=marketdatav2');
    $response = $request->send();
    $data = $response->json();
    $valuations = array();
    foreach($data['return']['markets'] as $market){
      $valuations[$market['primarycode']][$market['secondarycode']] = array(
        'volume' => $market['volume'],
        'price' => $market['lasttradeprice'],
        'price_time' => date("Y-m-d H:i:s", strtotime($market['lasttradetime'])),
      );
    }
    return $valuations;
  }
}