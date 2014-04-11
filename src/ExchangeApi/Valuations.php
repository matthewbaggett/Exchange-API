<?php

namespace ExchangeApi;

class Valuations{
  static $valuations = array();
  const APC_KEY = 'ExchangeApiCache';

  static public function fetch(){
    if(function_exists('apc_exists') && \apc_exists(self::APC_KEY)){
      self::$valuations = \apc_fetch(self::APC_KEY);
      return false;
    }
    foreach(Exchanges::get_exchange_list() as $exchange){
      $name = "\\ExchangeApi\\{$exchange}\\Valuations";
      self::$valuations[$exchange] = $name::fetch();
    }
    $averages = array();
    $average_datapoints = array();
    foreach(self::$valuations as $exchange => $valuations){
      foreach($valuations as $primary => $options){
        foreach($options as $secondary => $datapoints){
          $average_datapoints[$primary . "-" . $secondary][] = $datapoints['price'];
        }
      }
    }
    foreach($average_datapoints as $key => $average_datapoint_group){
      $averages[$key] = array(
        'avg' => number_format(array_sum($average_datapoint_group) / count($average_datapoint_group),10),
        'source_count' => count($average_datapoint_group)
      );
    }
    foreach($averages as $key => $average){
      $key = explode("-", $key, 2);
      self::$valuations['Average'][$key[0]][$key[1]] = array(
        'price' => $average['avg'],
        'source_count' => $average['source_count']
      );
    }
    if(function_exists('apc_add')){
      \apc_add(self::APC_KEY,self::$valuations,60);
    }
    return true;
  }

  static public function get_data(){
    return self::$valuations;
  }

  static public function get_price($from, $to, $amount){
    $from = strtoupper($from);
    $to = strtoupper($to);
    //echo "get_price($from, $to, $amount): ";
    $rate = self::get_rate($from, $to);
    $result = $amount * $rate;
    //echo "{$amount} @ {$rate} = {$result} <br />";
    return $result;
  }

  static public function get_rate($from, $to){
    $from = strtoupper($from);
    $to = strtoupper($to);

    if(count(self::$valuations) == 0){
      self::fetch();
    }

    //echo "[{$from}][{$to}] ";
    if($from == $to){
      //echo "A";
      $valuation = 1;
    }elseif(isset(self::$valuations['Average'][$from][$to]['price'])){
      //echo "B";
      $valuation = self::$valuations['Average'][$from][$to]['price'];
    }elseif(isset(self::$valuations['Average'][$to][$from]['price'])){
      //echo "C";
      $valuation = self::$valuations['Average'][$to][$from]['price'];
    }else{
      //echo "D";
      // Where a direct conversion is unavailable, attempt via BTC
      if(isset(self::$valuations['Average'][$from]['BTC']['price']) && isset(self::$valuations['Average'][$to]['BTC']['price'])){
        $from_btc = self::$valuations['Average'][$from]['BTC']['price'];
        $to_btc = self::$valuations['Average'][$to]['BTC']['price'];
        $to_btc_flip = 1/$to_btc;
        //echo "1 {$from} in BTC = {$from_btc} <br />";
        //echo "1 BTC in {$to} = {$to_btc} <br />";
        //echo "1 {$to} in BTC = {$to_btc_flip} <br />";
        $rate = $from_btc * $to_btc;
        //echo "1 {$from} in {$to} = {$rate} <br />";
        $valuation = $rate;
      }
    }
    //echo $valuation . "<br />";
    return $valuation;

  }
}