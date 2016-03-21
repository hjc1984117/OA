<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class AddPhoneRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddPhoneRequest Attributes
  public $phoneTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setPhoneTypes($aPhoneTypes)
  {
    $wasSet = false;
    $this->phoneTypes = $aPhoneTypes;
    $wasSet = true;
    return $wasSet;
  }

  public function getPhoneTypes()
  {
    return $this->phoneTypes;
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

class AddPhoneResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddPhoneResponse Attributes
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

class AddSublinkResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddSublinkResponse Attributes
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

class DeleteSublinkResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteSublinkResponse Attributes
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

class UpdateBridgeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateBridgeResponse Attributes
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

class GetBridgeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetBridgeRequest Attributes
  public $ids;
  public $idType;
  public $bridgeFields;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setIds($aids) {
       $this->ids = $aids;
   }

  public function addId($aId)
  {
    $wasAdded = false;
    $this->ids[] = $aId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeId($aId)
  {
    $wasRemoved = false;
    unset($this->ids[$this->indexOfId($aId)]);
    $this->ids = array_values($this->ids);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setIdType($aIdType)
  {
    $wasSet = false;
    $this->idType = $aIdType;
    $wasSet = true;
    return $wasSet;
  }
   public function setBridgeFields($abridgeFields) {
       $this->bridgeFields = $abridgeFields;
   }

  public function addBridgeField($aBridgeField)
  {
    $wasAdded = false;
    $this->bridgeFields[] = $aBridgeField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeBridgeField($aBridgeField)
  {
    $wasRemoved = false;
    unset($this->bridgeFields[$this->indexOfBridgeField($aBridgeField)]);
    $this->bridgeFields = array_values($this->bridgeFields);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getIds()
  {
    $newIds = $this->ids;
    return $newIds;
  }

  public function numberOfIds()
  {
    $number = count($this->ids);
    return $number;
  }

  public function indexOfId($aId)
  {
    $rawAnswer = array_search($aId,$this->ids);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getIdType()
  {
    return $this->idType;
  }


  public function getBridgeFields()
  {
    $newBridgeFields = $this->bridgeFields;
    return $newBridgeFields;
  }

  public function numberOfBridgeFields()
  {
    $number = count($this->bridgeFields);
    return $number;
  }

  public function indexOfBridgeField($aBridgeField)
  {
    $rawAnswer = array_search($aBridgeField,$this->bridgeFields);
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

class AddSublinkRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddSublinkRequest Attributes
  public $sublinkTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setSublinkTypes($aSublinkTypes)
  {
    $wasSet = false;
    $this->sublinkTypes = $aSublinkTypes;
    $wasSet = true;
    return $wasSet;
  }

  public function getSublinkTypes()
  {
    return $this->sublinkTypes;
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

class UpdatePhoneResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdatePhoneResponse Attributes
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

class UpdateBridgeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateBridgeRequest Attributes
  public $bridgeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setBridgeTypes($abridgeTypes) {
       $this->bridgeTypes = $abridgeTypes;
   }

  public function addBridgeType($aBridgeType)
  {
    $wasAdded = false;
    $this->bridgeTypes[] = $aBridgeType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeBridgeType($aBridgeType)
  {
    $wasRemoved = false;
    unset($this->bridgeTypes[$this->indexOfBridgeType($aBridgeType)]);
    $this->bridgeTypes = array_values($this->bridgeTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getBridgeTypes()
  {
    $newBridgeTypes = $this->bridgeTypes;
    return $newBridgeTypes;
  }

  public function numberOfBridgeTypes()
  {
    $number = count($this->bridgeTypes);
    return $number;
  }

  public function indexOfBridgeType($aBridgeType)
  {
    $rawAnswer = array_search($aBridgeType,$this->bridgeTypes);
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

class PhoneType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //PhoneType Attributes
  public $phoneId;
  public $phoneNum;
  public $adgroupId;
  public $pause;
  public $status;
  public $operator;
  public $campaignId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setPhoneId($aPhoneId)
  {
    $wasSet = false;
    $this->phoneId = $aPhoneId;
    $wasSet = true;
    return $wasSet;
  }

  public function setPhoneNum($aPhoneNum)
  {
    $wasSet = false;
    $this->phoneNum = $aPhoneNum;
    $wasSet = true;
    return $wasSet;
  }

  public function setAdgroupId($aAdgroupId)
  {
    $wasSet = false;
    $this->adgroupId = $aAdgroupId;
    $wasSet = true;
    return $wasSet;
  }

  public function setPause($aPause)
  {
    $wasSet = false;
    $this->pause = $aPause;
    $wasSet = true;
    return $wasSet;
  }

  public function setStatus($aStatus)
  {
    $wasSet = false;
    $this->status = $aStatus;
    $wasSet = true;
    return $wasSet;
  }

  public function setOperator($aOperator)
  {
    $wasSet = false;
    $this->operator = $aOperator;
    $wasSet = true;
    return $wasSet;
  }

  public function setCampaignId($aCampaignId)
  {
    $wasSet = false;
    $this->campaignId = $aCampaignId;
    $wasSet = true;
    return $wasSet;
  }

  public function getPhoneId()
  {
    return $this->phoneId;
  }

  public function getPhoneNum()
  {
    return $this->phoneNum;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function getOperator()
  {
    return $this->operator;
  }

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function isPause()
  {
    return $this->pause;
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

class AddBridgeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddBridgeRequest Attributes
  public $bridgeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setBridgeTypes($aBridgeTypes)
  {
    $wasSet = false;
    $this->bridgeTypes = $aBridgeTypes;
    $wasSet = true;
    return $wasSet;
  }

  public function getBridgeTypes()
  {
    return $this->bridgeTypes;
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

class GetSublinkBySublinkIdResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetSublinkBySublinkIdResponse Attributes
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

class UpdatePhoneRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdatePhoneRequest Attributes
  public $phoneTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setPhoneTypes($aphoneTypes) {
       $this->phoneTypes = $aphoneTypes;
   }

  public function addPhoneType($aPhoneType)
  {
    $wasAdded = false;
    $this->phoneTypes[] = $aPhoneType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePhoneType($aPhoneType)
  {
    $wasRemoved = false;
    unset($this->phoneTypes[$this->indexOfPhoneType($aPhoneType)]);
    $this->phoneTypes = array_values($this->phoneTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getPhoneTypes()
  {
    $newPhoneTypes = $this->phoneTypes;
    return $newPhoneTypes;
  }

  public function numberOfPhoneTypes()
  {
    $number = count($this->phoneTypes);
    return $number;
  }

  public function indexOfPhoneType($aPhoneType)
  {
    $rawAnswer = array_search($aPhoneType,$this->phoneTypes);
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

class SublinkType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //SublinkType Attributes
  public $sublinkId;
  public $sublinkInfos;
  public $adgroupId;
  public $pause;
  public $status;
  public $device;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setSublinkId($aSublinkId)
  {
    $wasSet = false;
    $this->sublinkId = $aSublinkId;
    $wasSet = true;
    return $wasSet;
  }
   public function setSublinkInfos($asublinkInfos) {
       $this->sublinkInfos = $asublinkInfos;
   }

  public function addSublinkInfo($aSublinkInfo)
  {
    $wasAdded = false;
    $this->sublinkInfos[] = $aSublinkInfo;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSublinkInfo($aSublinkInfo)
  {
    $wasRemoved = false;
    unset($this->sublinkInfos[$this->indexOfSublinkInfo($aSublinkInfo)]);
    $this->sublinkInfos = array_values($this->sublinkInfos);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setAdgroupId($aAdgroupId)
  {
    $wasSet = false;
    $this->adgroupId = $aAdgroupId;
    $wasSet = true;
    return $wasSet;
  }

  public function setPause($aPause)
  {
    $wasSet = false;
    $this->pause = $aPause;
    $wasSet = true;
    return $wasSet;
  }

  public function setStatus($aStatus)
  {
    $wasSet = false;
    $this->status = $aStatus;
    $wasSet = true;
    return $wasSet;
  }

  public function setDevice($aDevice)
  {
    $wasSet = false;
    $this->device = $aDevice;
    $wasSet = true;
    return $wasSet;
  }

  public function getSublinkId()
  {
    return $this->sublinkId;
  }


  public function getSublinkInfos()
  {
    $newSublinkInfos = $this->sublinkInfos;
    return $newSublinkInfos;
  }

  public function numberOfSublinkInfos()
  {
    $number = count($this->sublinkInfos);
    return $number;
  }

  public function indexOfSublinkInfo($aSublinkInfo)
  {
    $rawAnswer = array_search($aSublinkInfo,$this->sublinkInfos);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function getDevice()
  {
    return $this->device;
  }

  public function isPause()
  {
    return $this->pause;
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

class GetSublinkBySublinkIdRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetSublinkBySublinkIdRequest Attributes
  public $ids;
  public $idType;
  public $sublinkFields;
  public $getTemp;
  public $device;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setIds($aids) {
       $this->ids = $aids;
   }

  public function addId($aId)
  {
    $wasAdded = false;
    $this->ids[] = $aId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeId($aId)
  {
    $wasRemoved = false;
    unset($this->ids[$this->indexOfId($aId)]);
    $this->ids = array_values($this->ids);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setIdType($aIdType)
  {
    $wasSet = false;
    $this->idType = $aIdType;
    $wasSet = true;
    return $wasSet;
  }
   public function setSublinkFields($asublinkFields) {
       $this->sublinkFields = $asublinkFields;
   }

  public function addSublinkField($aSublinkField)
  {
    $wasAdded = false;
    $this->sublinkFields[] = $aSublinkField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSublinkField($aSublinkField)
  {
    $wasRemoved = false;
    unset($this->sublinkFields[$this->indexOfSublinkField($aSublinkField)]);
    $this->sublinkFields = array_values($this->sublinkFields);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setGetTemp($aGetTemp)
  {
    $wasSet = false;
    $this->getTemp = $aGetTemp;
    $wasSet = true;
    return $wasSet;
  }

  public function setDevice($aDevice)
  {
    $wasSet = false;
    $this->device = $aDevice;
    $wasSet = true;
    return $wasSet;
  }


  public function getIds()
  {
    $newIds = $this->ids;
    return $newIds;
  }

  public function numberOfIds()
  {
    $number = count($this->ids);
    return $number;
  }

  public function indexOfId($aId)
  {
    $rawAnswer = array_search($aId,$this->ids);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getIdType()
  {
    return $this->idType;
  }


  public function getSublinkFields()
  {
    $newSublinkFields = $this->sublinkFields;
    return $newSublinkFields;
  }

  public function numberOfSublinkFields()
  {
    $number = count($this->sublinkFields);
    return $number;
  }

  public function indexOfSublinkField($aSublinkField)
  {
    $rawAnswer = array_search($aSublinkField,$this->sublinkFields);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getGetTemp()
  {
    return $this->getTemp;
  }

  public function getDevice()
  {
    return $this->device;
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

class GetPhoneResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetPhoneResponse Attributes
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

class UpdateSublinkResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateSublinkResponse Attributes
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

class BridgeType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //BridgeType Attributes
  public $bridgeId;
  public $adgroupId;
  public $pause;
  public $status;
  public $campaignId;
  public $operator;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setBridgeId($aBridgeId)
  {
    $wasSet = false;
    $this->bridgeId = $aBridgeId;
    $wasSet = true;
    return $wasSet;
  }

  public function setAdgroupId($aAdgroupId)
  {
    $wasSet = false;
    $this->adgroupId = $aAdgroupId;
    $wasSet = true;
    return $wasSet;
  }

  public function setPause($aPause)
  {
    $wasSet = false;
    $this->pause = $aPause;
    $wasSet = true;
    return $wasSet;
  }

  public function setStatus($aStatus)
  {
    $wasSet = false;
    $this->status = $aStatus;
    $wasSet = true;
    return $wasSet;
  }

  public function setCampaignId($aCampaignId)
  {
    $wasSet = false;
    $this->campaignId = $aCampaignId;
    $wasSet = true;
    return $wasSet;
  }

  public function setOperator($aOperator)
  {
    $wasSet = false;
    $this->operator = $aOperator;
    $wasSet = true;
    return $wasSet;
  }

  public function getBridgeId()
  {
    return $this->bridgeId;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function getOperator()
  {
    return $this->operator;
  }

  public function isPause()
  {
    return $this->pause;
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

class UpdateSublinkRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateSublinkRequest Attributes
  public $sublinkTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setSublinkTypes($asublinkTypes) {
       $this->sublinkTypes = $asublinkTypes;
   }

  public function addSublinkType($aSublinkType)
  {
    $wasAdded = false;
    $this->sublinkTypes[] = $aSublinkType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSublinkType($aSublinkType)
  {
    $wasRemoved = false;
    unset($this->sublinkTypes[$this->indexOfSublinkType($aSublinkType)]);
    $this->sublinkTypes = array_values($this->sublinkTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getSublinkTypes()
  {
    $newSublinkTypes = $this->sublinkTypes;
    return $newSublinkTypes;
  }

  public function numberOfSublinkTypes()
  {
    $number = count($this->sublinkTypes);
    return $number;
  }

  public function indexOfSublinkType($aSublinkType)
  {
    $rawAnswer = array_search($aSublinkType,$this->sublinkTypes);
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

class AddBridgeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddBridgeResponse Attributes
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

class GetBridgeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetBridgeResponse Attributes
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

class GetPhoneRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetPhoneRequest Attributes
  public $ids;
  public $idType;
  public $phoneFields;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setIds($aids) {
       $this->ids = $aids;
   }

  public function addId($aId)
  {
    $wasAdded = false;
    $this->ids[] = $aId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeId($aId)
  {
    $wasRemoved = false;
    unset($this->ids[$this->indexOfId($aId)]);
    $this->ids = array_values($this->ids);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setIdType($aIdType)
  {
    $wasSet = false;
    $this->idType = $aIdType;
    $wasSet = true;
    return $wasSet;
  }
   public function setPhoneFields($aphoneFields) {
       $this->phoneFields = $aphoneFields;
   }

  public function addPhoneField($aPhoneField)
  {
    $wasAdded = false;
    $this->phoneFields[] = $aPhoneField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePhoneField($aPhoneField)
  {
    $wasRemoved = false;
    unset($this->phoneFields[$this->indexOfPhoneField($aPhoneField)]);
    $this->phoneFields = array_values($this->phoneFields);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getIds()
  {
    $newIds = $this->ids;
    return $newIds;
  }

  public function numberOfIds()
  {
    $number = count($this->ids);
    return $number;
  }

  public function indexOfId($aId)
  {
    $rawAnswer = array_search($aId,$this->ids);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getIdType()
  {
    return $this->idType;
  }


  public function getPhoneFields()
  {
    $newPhoneFields = $this->phoneFields;
    return $newPhoneFields;
  }

  public function numberOfPhoneFields()
  {
    $number = count($this->phoneFields);
    return $number;
  }

  public function indexOfPhoneField($aPhoneField)
  {
    $rawAnswer = array_search($aPhoneField,$this->phoneFields);
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

class DeleteSublinkRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteSublinkRequest Attributes
  public $sublinkIds;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setSublinkIds($asublinkIds) {
       $this->sublinkIds = $asublinkIds;
   }

  public function addSublinkId($aSublinkId)
  {
    $wasAdded = false;
    $this->sublinkIds[] = $aSublinkId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSublinkId($aSublinkId)
  {
    $wasRemoved = false;
    unset($this->sublinkIds[$this->indexOfSublinkId($aSublinkId)]);
    $this->sublinkIds = array_values($this->sublinkIds);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getSublinkIds()
  {
    $newSublinkIds = $this->sublinkIds;
    return $newSublinkIds;
  }

  public function numberOfSublinkIds()
  {
    $number = count($this->sublinkIds);
    return $number;
  }

  public function indexOfSublinkId($aSublinkId)
  {
    $rawAnswer = array_search($aSublinkId,$this->sublinkIds);
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

class SublinkInfo
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //SublinkInfo Attributes
  public $description;
  public $destinationUrl;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setDescription($aDescription)
  {
    $wasSet = false;
    $this->description = $aDescription;
    $wasSet = true;
    return $wasSet;
  }

  public function setDestinationUrl($aDestinationUrl)
  {
    $wasSet = false;
    $this->destinationUrl = $aDestinationUrl;
    $wasSet = true;
    return $wasSet;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getDestinationUrl()
  {
    return $this->destinationUrl;
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

class sms_service_NewCreativeService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'NewCreativeService' );
    }

  // ABSTRACT METHODS 

 public function getSublink ($getSublinkRequest){
 return $this->execute ( 'getSublink', $getSublinkRequest );
}
 public function getBridge ($getBridgeRequest){
 return $this->execute ( 'getBridge', $getBridgeRequest );
}
 public function getPhone ($getPhoneRequest){
 return $this->execute ( 'getPhone', $getPhoneRequest );
}
 public function addBridge ($addBridgeRequest){
 return $this->execute ( 'addBridge', $addBridgeRequest );
}
 public function addSublink ($addSublinkRequest){
 return $this->execute ( 'addSublink', $addSublinkRequest );
}
 public function updateSublink ($updateSublinkRequest){
 return $this->execute ( 'updateSublink', $updateSublinkRequest );
}
 public function deleteSublink ($deleteSublinkRequest){
 return $this->execute ( 'deleteSublink', $deleteSublinkRequest );
}
 public function addPhone ($addPhoneRequest){
 return $this->execute ( 'addPhone', $addPhoneRequest );
}
 public function updatePhone ($updatePhoneRequest){
 return $this->execute ( 'updatePhone', $updatePhoneRequest );
}
 public function updateBridge ($updateBridgeRequest){
 return $this->execute ( 'updateBridge', $updateBridgeRequest );
}
  
}


?>