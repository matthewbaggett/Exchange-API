<?php

namespace ExchangeApi\Cryptsy;

class Valuations{

  static public function fetch(){
    $client = new \Guzzle\Http\Client();
    $response = $client->get('http://pubapi.cryptsy.com/api.php?method=marketdatav2');
    $json = $response->getResponseBody();
    var_dump($json);
    krumo(json_decode($json));exit;
  }
}