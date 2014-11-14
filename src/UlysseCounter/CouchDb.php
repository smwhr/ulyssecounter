<?php 

namespace UlysseCounter;

class CouchDb{
  
  public function __construct($options = []){
    $this->url = isset($options['url']) && !is_null($options['url']) ? $options['url'] : 'http://127.0.0.1:5984';
    $this->database = isset($options['database']) && !is_null($options['database']) ? $options['database'] : 'ulyssecounter';
  }

  public function getDatabaseUrl(){
    return $this->url."/".$this->database;
  }

  public function getViewUrl($design, $view, $options=array()){
    $viewUrl = $this->getDatabaseUrl()."/_design/".$design."/_view/".$view;
    if(!empty($options)){
      $viewUrl.= "?";
      foreach($options as $key => $val){
        $viewUrl.=$key."=".$val."&";
      }
    }
    return $viewUrl;
  }

  public function addDocument($document){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$this->getDatabaseUrl());
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($document));    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $server_output = curl_exec($ch);

      if(empty($server_output)){
        throw new CouchDbException("Could not connect to ".$this->url);
      }

      $response = json_decode($server_output, true);
      if(isset($response["ok"])){
        return $response["ok"];
      }else{
        var_dump($response);
        throw new CouchDbException($response["error"]." : ".$response["reason"]);
      }
  }

}