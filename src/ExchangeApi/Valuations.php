<?php

namespace ExchangeApi;

class Valuations{
  static $valuations = array();

  static public function fetch(){
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
      $averages[$key] = number_format(array_sum($average_datapoint_group) / count($average_datapoint_group),10);
    }
    foreach($averages as $key => $average){
      $key = explode("-", $key, 2);
      self::$valuations['Average'][$key[0]][$key[1]] = array('price' => $average);
    }
  }

  static public function get_price($from, $to, $amount){
    $rate = self::get_rate($from, $to);
    return $amount * $rate;
  }

  static public function get_rate($from, $to){
    if(count(self::$valuations) == 0){
      self::fetch();
    }

    if(isset(self::$valuations['Average'][$from][$to]['price'])){
      return self::$valuations['Average'][$from][$to]['price'];
    }else{
      throw new Exception("Cannot exchange {$from} to {$to}, unsupported");
    }

  }
}