<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class AddCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddCreativeRequest Attributes
  public $creativeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setCreativeTypes($acreativeTypes) {
       $this->creativeTypes = $acreativeTypes;
   }

  public function addCreativeType($aCreativeType)
  {
    $wasAdded = false;
    $this->creativeTypes[] = $aCreativeType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeCreativeType($aCreativeType)
  {
    $wasRemoved = false;
    unset($this->creativeTypes[$this->indexOfCreativeType($aCreativeType)]);
    $this->creativeTypes = array_values($this->creativeTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getCreativeTypes()
  {
    $newCreativeTypes = $this->creativeTypes;
    return $newCreativeTypes;
  }

  public function numberOfCreativeTypes()
  {
    $number = count($this->creativeTypes);
    return $number;
  }

  public function indexOfCreativeType($aCreativeType)
  {
    $rawAnswer = array_search($aCreativeType,$this->creativeTypes);
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

class DeleteCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteCreativeResponse Attributes
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

class DeleteCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteCreativeRequest Attributes
  public $creativeIds;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setCreativeIds($acreativeIds) {
       $this->creativeIds = $acreativeIds;
   }

  public function addCreativeId($aCreativeId)
  {
    $wasAdded = false;
    $this->creativeIds[] = $aCreativeId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeCreativeId($aCreativeId)
  {
    $wasRemoved = false;
    unset($this->creativeIds[$this->indexOfCreativeId($aCreativeId)]);
    $this->creativeIds = array_values($this->creativeIds);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getCreativeIds()
  {
    $newCreativeIds = $this->creativeIds;
    return $newCreativeIds;
  }

  public function numberOfCreativeIds()
  {
    $number = count($this->creativeIds);
    return $number;
  }

  public function indexOfCreativeId($aCreativeId)
  {
    $rawAnswer = array_search($aCreativeId,$this->creativeIds);
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

class UpdateCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateCreativeRequest Attributes
  public $creativeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setCreativeTypes($acreativeTypes) {
       $this->creativeTypes = $acreativeTypes;
   }

  public function addCreativeType($aCreativeType)
  {
    $wasAdded = false;
    $this->creativeTypes[] = $aCreativeType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeCreativeType($aCreativeType)
  {
    $wasRemoved = false;
    unset($this->creativeTypes[$this->indexOfCreativeType($aCreativeType)]);
    $this->creativeTypes = array_values($this->creativeTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getCreativeTypes()
  {
    $newCreativeTypes = $this->creativeTypes;
    return $newCreativeTypes;
  }

  public function numberOfCreativeTypes()
  {
    $number = count($this->creativeTypes);
    return $number;
  }

  public function indexOfCreativeType($aCreativeType)
  {
    $rawAnswer = array_search($aCreativeType,$this->creativeTypes);
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

class OfflineReason
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //OfflineReason Attributes
  public $mainReason;
  public $detailReason;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setMainReason($aMainReason)
  {
    $wasSet = false;
    $this->mainReason = $aMainReason;
    $wasSet = true;
    return $wasSet;
  }

  public function setDetailReason($aDetailReason)
  {
    $wasSet = false;
    $this->detailReason = $aDetailReason;
    $wasSet = true;
    return $wasSet;
  }

  public function getMainReason()
  {
    return $this->mainReason;
  }

  public function getDetailReason()
  {
    return $this->detailReason;
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

class AddCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddCreativeResponse Attributes
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

class CreativeType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //CreativeType Attributes
  public $campaignId;
  public $creativeId;
  public $adgroupId;
  public $title;
  public $description1;
  public $description2;
  public $destinationUrl;
  public $displayUrl;
  public $pause;
  public $status;
  public $mobileDestinationUrl;
  public $mobileDisplayUrl;
  public $pcDestinationUrl;
  public $pcDisplayUrl;
  public $offlineReasons;
  public $devicePreference;
  public $temp;
  public $operator;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setCampaignId($aCampaignId)
  {
    $wasSet = false;
    $this->campaignId = $aCampaignId;
    $wasSet = true;
    return $wasSet;
  }

  public function setCreativeId($aCreativeId)
  {
    $wasSet = false;
    $this->creativeId = $aCreativeId;
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

  public function setTitle($aTitle)
  {
    $wasSet = false;
    $this->title = $aTitle;
    $wasSet = true;
    return $wasSet;
  }

  public function setDescription1($aDescription1)
  {
    $wasSet = false;
    $this->description1 = $aDescription1;
    $wasSet = true;
    return $wasSet;
  }

  public function setDescription2($aDescription2)
  {
    $wasSet = false;
    $this->description2 = $aDescription2;
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

  public function setDisplayUrl($aDisplayUrl)
  {
    $wasSet = false;
    $this->displayUrl = $aDisplayUrl;
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

  public function setMobileDestinationUrl($aMobileDestinationUrl)
  {
    $wasSet = false;
    $this->mobileDestinationUrl = $aMobileDestinationUrl;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobileDisplayUrl($aMobileDisplayUrl)
  {
    $wasSet = false;
    $this->mobileDisplayUrl = $aMobileDisplayUrl;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcDestinationUrl($aPcDestinationUrl)
  {
    $wasSet = false;
    $this->pcDestinationUrl = $aPcDestinationUrl;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcDisplayUrl($aPcDisplayUrl)
  {
    $wasSet = false;
    $this->pcDisplayUrl = $aPcDisplayUrl;
    $wasSet = true;
    return $wasSet;
  }
   public function setOfflineReasons($aofflineReasons) {
       $this->offlineReasons = $aofflineReasons;
   }

  public function addOfflineReason($aOfflineReason)
  {
    $wasAdded = false;
    $this->offlineReasons[] = $aOfflineReason;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeOfflineReason($aOfflineReason)
  {
    $wasRemoved = false;
    unset($this->offlineReasons[$this->indexOfOfflineReason($aOfflineReason)]);
    $this->offlineReasons = array_values($this->offlineReasons);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setDevicePreference($aDevicePreference)
  {
    $wasSet = false;
    $this->devicePreference = $aDevicePreference;
    $wasSet = true;
    return $wasSet;
  }

  public function setTemp($aTemp)
  {
    $wasSet = false;
    $this->temp = $aTemp;
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

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function getCreativeId()
  {
    return $this->creativeId;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getDescription1()
  {
    return $this->description1;
  }

  public function getDescription2()
  {
    return $this->description2;
  }

  public function getDestinationUrl()
  {
    return $this->destinationUrl;
  }

  public function getDisplayUrl()
  {
    return $this->displayUrl;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function getMobileDestinationUrl()
  {
    return $this->mobileDestinationUrl;
  }

  public function getMobileDisplayUrl()
  {
    return $this->mobileDisplayUrl;
  }

  public function getPcDestinationUrl()
  {
    return $this->pcDestinationUrl;
  }

  public function getPcDisplayUrl()
  {
    return $this->pcDisplayUrl;
  }


  public function getOfflineReasons()
  {
    $newOfflineReasons = $this->offlineReasons;
    return $newOfflineReasons;
  }

  public function numberOfOfflineReasons()
  {
    $number = count($this->offlineReasons);
    return $number;
  }

  public function indexOfOfflineReason($aOfflineReason)
  {
    $rawAnswer = array_search($aOfflineReason,$this->offlineReasons);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getDevicePreference()
  {
    return $this->devicePreference;
  }

  public function getTemp()
  {
    return $this->temp;
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

class GetCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetCreativeRequest Attributes
  public $creativeFields;
  public $ids;
  public $idType;
  public $getTemp;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setCreativeFields($acreativeFields) {
       $this->creativeFields = $acreativeFields;
   }

  public function addCreativeField($aCreativeField)
  {
    $wasAdded = false;
    $this->creativeFields[] = $aCreativeField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeCreativeField($aCreativeField)
  {
    $wasRemoved = false;
    unset($this->creativeFields[$this->indexOfCreativeField($aCreativeField)]);
    $this->creativeFields = array_values($this->creativeFields);
    $wasRemoved = true;
    return $wasRemoved;
  }
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

  public function setGetTemp($aGetTemp)
  {
    $wasSet = false;
    $this->getTemp = $aGetTemp;
    $wasSet = true;
    return $wasSet;
  }


  public function getCreativeFields()
  {
    $newCreativeFields = $this->creativeFields;
    return $newCreativeFields;
  }

  public function numberOfCreativeFields()
  {
    $number = count($this->creativeFields);
    return $number;
  }

  public function indexOfCreativeField($aCreativeField)
  {
    $rawAnswer = array_search($aCreativeField,$this->creativeFields);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
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

  public function getGetTemp()
  {
    return $this->getTemp;
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

class GetCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetCreativeResponse Attributes
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

class UpdateCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateCreativeResponse Attributes
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

class sms_service_CreativeService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'CreativeService' );
    }

  // ABSTRACT METHODS 

 public function updateCreative ($updateCreativeRequest){
 return $this->execute ( 'updateCreative', $updateCreativeRequest );
}
 public function deleteCreative ($deleteCreativeRequest){
 return $this->execute ( 'deleteCreative', $deleteCreativeRequest );
}
 public function addCreative ($addCreativeRequest){
 return $this->execute ( 'addCreative', $addCreativeRequest );
}
 public function getCreative ($getCreativeRequest){
 return $this->execute ( 'getCreative', $getCreativeRequest );
}
  
}


?>