<?php

namespace ExchangeApi\Cryptsy;

class Valuations{

  static public function fetch(){
    $client = new GuzzleHttp\Client();
    $response = $client->get('http://pubapi.cryptsy.com/api.php?method=marketdatav2');
    var_dump($response);exit;
  }
}