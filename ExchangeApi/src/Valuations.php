<?php

namespace ExchangeApi;

class Valuations{

  static public function fetch(){
    foreach(Exchanges::get_exchange_list() as $exchange){
      $name = "\\ExchangeApi\\{$exchange}\\Valuation";
      $valuation = $name::fetch();
      var_dump($valuation);
      exit;
    }
  }
}