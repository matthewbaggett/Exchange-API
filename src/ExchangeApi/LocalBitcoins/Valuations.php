<?php

namespace ExchangeApi\LocalBitcoins;

class Valuations{


  static public function fetch(){
    $client = new \Guzzle\Http\Client('https://localbitcoins.com/');
    $valuations = array();

    foreach(array('gbp', 'usd', 'eur') as $fiat){
      $request = $client->get("buy-bitcoins-online/" . strtolower($fiat) . "/.json");
      $response = $request->send();
      $data = $response->json();

      foreach($data['data']['ad_list'] as $ad){
        if($ad['data']['min_amount'] > 0){
          continue;
        }
        if(in_array($ad['data']['online_provider'], array('NATIONAL_BANK', 'CASH_DEPOSIT'))){
          continue;
        }

        $valuations[strtoupper($fiat)]['BTC'] = array(
          'volume' => null,
          'price' => $ad['data']['temp_price'],
          'price_time' => date("Y-m-d H:i:s"),
        );
      };
    }


    return $valuations;
  }
}