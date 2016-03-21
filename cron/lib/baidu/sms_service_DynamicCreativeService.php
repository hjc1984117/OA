<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class AddDynCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddDynCreativeRequest Attributes

  /**
   * 单元ID
   */
  public $dynCreativeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeTypes($adynCreativeTypes) {
       $this->dynCreativeTypes = $adynCreativeTypes;
   }

  public function addDynCreativeType($aDynCreativeType)
  {
    $wasAdded = false;
    $this->dynCreativeTypes[] = $aDynCreativeType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeType($aDynCreativeType)
  {
    $wasRemoved = false;
    unset($this->dynCreativeTypes[$this->indexOfDynCreativeType($aDynCreativeType)]);
    $this->dynCreativeTypes = array_values($this->dynCreativeTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getDynCreativeTypes()
  {
    $newDynCreativeTypes = $this->dynCreativeTypes;
    return $newDynCreativeTypes;
  }

  public function numberOfDynCreativeTypes()
  {
    $number = count($this->dynCreativeTypes);
    return $number;
  }

  public function indexOfDynCreativeType($aDynCreativeType)
  {
    $rawAnswer = array_search($aDynCreativeType,$this->dynCreativeTypes);
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

class GetExclusionTypeByCampaignIdResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetExclusionTypeByCampaignIdResponse Attributes
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

class DelDynCreativeExclusionResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DelDynCreativeExclusionResponse Attributes
  public $result;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setResult($aResult)
  {
    $wasSet = false;
    $this->result = $aResult;
    $wasSet = true;
    return $wasSet;
  }

  public function getResult()
  {
    return $this->result;
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

class GetDynCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetDynCreativeResponse Attributes
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

class DynCreativeType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DynCreativeType Attributes
  public $dynCreativeId;
  public $campaignId;
  public $adgroupId;
  public $bindingType;
  public $title;
  public $url;
  public $murl;
  public $pause;
  public $status;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setDynCreativeId($aDynCreativeId)
  {
    $wasSet = false;
    $this->dynCreativeId = $aDynCreativeId;
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

  public function setAdgroupId($aAdgroupId)
  {
    $wasSet = false;
    $this->adgroupId = $aAdgroupId;
    $wasSet = true;
    return $wasSet;
  }

  public function setBindingType($aBindingType)
  {
    $wasSet = false;
    $this->bindingType = $aBindingType;
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

  public function setUrl($aUrl)
  {
    $wasSet = false;
    $this->url = $aUrl;
    $wasSet = true;
    return $wasSet;
  }

  public function setMurl($aMurl)
  {
    $wasSet = false;
    $this->murl = $aMurl;
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

  public function getDynCreativeId()
  {
    return $this->dynCreativeId;
  }

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getBindingType()
  {
    return $this->bindingType;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getUrl()
  {
    return $this->url;
  }

  public function getMurl()
  {
    return $this->murl;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
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

class ExclusionType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ExclusionType Attributes
  public $exclusionId;
  public $exclusionContent;
  public $exclusionType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setExclusionId($aExclusionId)
  {
    $wasSet = false;
    $this->exclusionId = $aExclusionId;
    $wasSet = true;
    return $wasSet;
  }

  public function setExclusionContent($aExclusionContent)
  {
    $wasSet = false;
    $this->exclusionContent = $aExclusionContent;
    $wasSet = true;
    return $wasSet;
  }

  public function setExclusionType($aExclusionType)
  {
    $wasSet = false;
    $this->exclusionType = $aExclusionType;
    $wasSet = true;
    return $wasSet;
  }

  public function getExclusionId()
  {
    return $this->exclusionId;
  }

  public function getExclusionContent()
  {
    return $this->exclusionContent;
  }

  public function getExclusionType()
  {
    return $this->exclusionType;
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

class DelExclusionTypeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DelExclusionTypeResponse Attributes
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

class DeleteDynCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteDynCreativeRequest Attributes
  public $dynCreativeIds;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeIds($adynCreativeIds) {
       $this->dynCreativeIds = $adynCreativeIds;
   }

  public function addDynCreativeId($aDynCreativeId)
  {
    $wasAdded = false;
    $this->dynCreativeIds[] = $aDynCreativeId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeId($aDynCreativeId)
  {
    $wasRemoved = false;
    unset($this->dynCreativeIds[$this->indexOfDynCreativeId($aDynCreativeId)]);
    $this->dynCreativeIds = array_values($this->dynCreativeIds);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getDynCreativeIds()
  {
    $newDynCreativeIds = $this->dynCreativeIds;
    return $newDynCreativeIds;
  }

  public function numberOfDynCreativeIds()
  {
    $number = count($this->dynCreativeIds);
    return $number;
  }

  public function indexOfDynCreativeId($aDynCreativeId)
  {
    $rawAnswer = array_search($aDynCreativeId,$this->dynCreativeIds);
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

class GetExclusionTypeByCampaignIdRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetExclusionTypeByCampaignIdRequest Attributes
  public $campaignIds;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setCampaignIds($acampaignIds) {
       $this->campaignIds = $acampaignIds;
   }

  public function addCampaignId($aCampaignId)
  {
    $wasAdded = false;
    $this->campaignIds[] = $aCampaignId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeCampaignId($aCampaignId)
  {
    $wasRemoved = false;
    unset($this->campaignIds[$this->indexOfCampaignId($aCampaignId)]);
    $this->campaignIds = array_values($this->campaignIds);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getCampaignIds()
  {
    $newCampaignIds = $this->campaignIds;
    return $newCampaignIds;
  }

  public function numberOfCampaignIds()
  {
    $number = count($this->campaignIds);
    return $number;
  }

  public function indexOfCampaignId($aCampaignId)
  {
    $rawAnswer = array_search($aCampaignId,$this->campaignIds);
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

class UpdateDynCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateDynCreativeRequest Attributes
  public $dynCreativeTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeTypes($adynCreativeTypes) {
       $this->dynCreativeTypes = $adynCreativeTypes;
   }

  public function addDynCreativeType($aDynCreativeType)
  {
    $wasAdded = false;
    $this->dynCreativeTypes[] = $aDynCreativeType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeType($aDynCreativeType)
  {
    $wasRemoved = false;
    unset($this->dynCreativeTypes[$this->indexOfDynCreativeType($aDynCreativeType)]);
    $this->dynCreativeTypes = array_values($this->dynCreativeTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getDynCreativeTypes()
  {
    $newDynCreativeTypes = $this->dynCreativeTypes;
    return $newDynCreativeTypes;
  }

  public function numberOfDynCreativeTypes()
  {
    $number = count($this->dynCreativeTypes);
    return $number;
  }

  public function indexOfDynCreativeType($aDynCreativeType)
  {
    $rawAnswer = array_search($aDynCreativeType,$this->dynCreativeTypes);
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

class AddDynCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddDynCreativeResponse Attributes
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

class AddExclusionTypeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddExclusionTypeResponse Attributes
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

class UpdateDynCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateDynCreativeResponse Attributes
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

class GetDynCreativeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetDynCreativeRequest Attributes
  public $dynCreativeFields;
  public $ids;
  public $idType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeFields($adynCreativeFields) {
       $this->dynCreativeFields = $adynCreativeFields;
   }

  public function addDynCreativeField($aDynCreativeField)
  {
    $wasAdded = false;
    $this->dynCreativeFields[] = $aDynCreativeField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeField($aDynCreativeField)
  {
    $wasRemoved = false;
    unset($this->dynCreativeFields[$this->indexOfDynCreativeField($aDynCreativeField)]);
    $this->dynCreativeFields = array_values($this->dynCreativeFields);
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


  public function getDynCreativeFields()
  {
    $newDynCreativeFields = $this->dynCreativeFields;
    return $newDynCreativeFields;
  }

  public function numberOfDynCreativeFields()
  {
    $number = count($this->dynCreativeFields);
    return $number;
  }

  public function indexOfDynCreativeField($aDynCreativeField)
  {
    $rawAnswer = array_search($aDynCreativeField,$this->dynCreativeFields);
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

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}


/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class DynCreativeExclusionType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DynCreativeExclusionType Attributes
  public $campaignId;
  public $exclusionTypes;

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
   public function setExclusionTypes($aexclusionTypes) {
       $this->exclusionTypes = $aexclusionTypes;
   }

  public function addExclusionType($aExclusionType)
  {
    $wasAdded = false;
    $this->exclusionTypes[] = $aExclusionType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeExclusionType($aExclusionType)
  {
    $wasRemoved = false;
    unset($this->exclusionTypes[$this->indexOfExclusionType($aExclusionType)]);
    $this->exclusionTypes = array_values($this->exclusionTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getCampaignId()
  {
    return $this->campaignId;
  }


  public function getExclusionTypes()
  {
    $newExclusionTypes = $this->exclusionTypes;
    return $newExclusionTypes;
  }

  public function numberOfExclusionTypes()
  {
    $number = count($this->exclusionTypes);
    return $number;
  }

  public function indexOfExclusionType($aExclusionType)
  {
    $rawAnswer = array_search($aExclusionType,$this->exclusionTypes);
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

class DelExclusionTypeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DelExclusionTypeRequest Attributes
  public $dynCreativeExclusionTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeExclusionTypes($adynCreativeExclusionTypes) {
       $this->dynCreativeExclusionTypes = $adynCreativeExclusionTypes;
   }

  public function addDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $wasAdded = false;
    $this->dynCreativeExclusionTypes[] = $aDynCreativeExclusionType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $wasRemoved = false;
    unset($this->dynCreativeExclusionTypes[$this->indexOfDynCreativeExclusionType($aDynCreativeExclusionType)]);
    $this->dynCreativeExclusionTypes = array_values($this->dynCreativeExclusionTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getDynCreativeExclusionTypes()
  {
    $newDynCreativeExclusionTypes = $this->dynCreativeExclusionTypes;
    return $newDynCreativeExclusionTypes;
  }

  public function numberOfDynCreativeExclusionTypes()
  {
    $number = count($this->dynCreativeExclusionTypes);
    return $number;
  }

  public function indexOfDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $rawAnswer = array_search($aDynCreativeExclusionType,$this->dynCreativeExclusionTypes);
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

class AddExclusionTypeRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddExclusionTypeRequest Attributes
  public $dynCreativeExclusionTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setDynCreativeExclusionTypes($adynCreativeExclusionTypes) {
       $this->dynCreativeExclusionTypes = $adynCreativeExclusionTypes;
   }

  public function addDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $wasAdded = false;
    $this->dynCreativeExclusionTypes[] = $aDynCreativeExclusionType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $wasRemoved = false;
    unset($this->dynCreativeExclusionTypes[$this->indexOfDynCreativeExclusionType($aDynCreativeExclusionType)]);
    $this->dynCreativeExclusionTypes = array_values($this->dynCreativeExclusionTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getDynCreativeExclusionTypes()
  {
    $newDynCreativeExclusionTypes = $this->dynCreativeExclusionTypes;
    return $newDynCreativeExclusionTypes;
  }

  public function numberOfDynCreativeExclusionTypes()
  {
    $number = count($this->dynCreativeExclusionTypes);
    return $number;
  }

  public function indexOfDynCreativeExclusionType($aDynCreativeExclusionType)
  {
    $rawAnswer = array_search($aDynCreativeExclusionType,$this->dynCreativeExclusionTypes);
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

class DeleteDynCreativeResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteDynCreativeResponse Attributes
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

class sms_service_DynamicCreativeService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'DynamicCreativeService' );
    }

  // ABSTRACT METHODS 

 public function getDynCreative ($getDynCreativeRequest){
 return $this->execute ( 'getDynCreative', $getDynCreativeRequest );
}
 public function getExclusionTypeByCampaignId ($getExclusionTypeByCampaignIdRequest){
 return $this->execute ( 'getExclusionTypeByCampaignId', $getExclusionTypeByCampaignIdRequest );
}
 public function addDynCreative ($addDynCreativeRequest){
 return $this->execute ( 'addDynCreative', $addDynCreativeRequest );
}
 public function deleteDynCreative ($deleteDynCreativeRequest){
 return $this->execute ( 'deleteDynCreative', $deleteDynCreativeRequest );
}
 public function updateDynCreative ($updateDynCreativeRequest){
 return $this->execute ( 'updateDynCreative', $updateDynCreativeRequest );
}
 public function addExclusionType ($addExclusionTypeRequest){
 return $this->execute ( 'addExclusionType', $addExclusionTypeRequest );
}
 public function delExclusionType ($delExclusionTypeRequest){
 return $this->execute ( 'delExclusionType', $delExclusionTypeRequest );
}
  
}


?>