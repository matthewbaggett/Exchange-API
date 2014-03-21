<?php

namespace ExchangeApi;

class Exchanges{
  static public function get_exchange_list(){
    return array(
      "Cryptsy",
      //"Kraken",
      "CoinsE",
      "LocalBitcoins"
    );
  }
}