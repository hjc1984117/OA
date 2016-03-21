<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class AppResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AppResponse Attributes
  public $errorcode;
  public $data;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setErrorcode($aErrorcode)
  {
    $wasSet = false;
    $this->errorcode = $aErrorcode;
    $wasSet = true;
    return $wasSet;
  }

  public function setData($aData)
  {
    $wasSet = false;
    $this->data = $aData;
    $wasSet = true;
    return $wasSet;
  }

  public function getErrorcode()
  {
    return $this->errorcode;
  }

  public function getData()
  {
    return $this->data;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}


/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class SubmitAppStatusResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //SubmitAppStatusResponse Attributes
  public $data;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setData($adata) {
       $this->data = $adata;
   }

  public function addData($aData)
  {
    $wasAdded = false;
    $this->data[] = $aData;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeData($aData)
  {
    $wasRemoved = false;
    unset($this->data[$this->indexOfData($aData)]);
    $this->data = array_values($this->data);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getData()
  {
    $newData = $this->data;
    return $newData;
  }

  public function numberOfData()
  {
    $number = count($this->data);
    return $number;
  }

  public function indexOfData($aData)
  {
    $rawAnswer = array_search($aData,$this->data);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}


/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class SubmitAppStatusRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //SubmitAppStatusRequest Attributes
  public $event;
  public $app;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setEvent($aEvent)
  {
    $wasSet = false;
    $this->event = $aEvent;
    $wasSet = true;
    return $wasSet;
  }

  public function setApp($aApp)
  {
    $wasSet = false;
    $this->app = $aApp;
    $wasSet = true;
    return $wasSet;
  }

  public function getEvent()
  {
    return $this->event;
  }

  public function getApp()
  {
    return $this->app;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}


/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class sms_service_AppService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'AppService' );
    }

  // ABSTRACT METHODS 

 public function submitAppStatus ($submitAppStatusRequest){
 return $this->execute ( 'submitAppStatus', $submitAppStatusRequest );
}
  
}


?>