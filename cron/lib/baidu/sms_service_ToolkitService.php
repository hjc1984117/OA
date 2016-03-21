<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class OptlogType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //OptlogType Attributes
  public $userId;
  public $planId;
  public $unitId;
  public $optTime;
  public $optContent;
  public $optType;
  public $optLevel;
  public $oldValue;
  public $newValue;
  public $optObj;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setUserId($aUserId)
  {
    $wasSet = false;
    $this->userId = $aUserId;
    $wasSet = true;
    return $wasSet;
  }

  public function setPlanId($aPlanId)
  {
    $wasSet = false;
    $this->planId = $aPlanId;
    $wasSet = true;
    return $wasSet;
  }

  public function setUnitId($aUnitId)
  {
    $wasSet = false;
    $this->unitId = $aUnitId;
    $wasSet = true;
    return $wasSet;
  }

  public function setOptTime($aOptTime)
  {
    $wasSet = false;
    $this->optTime = $aOptTime;
    $wasSet = true;
    return $wasSet;
  }

  public function setOptContent($aOptContent)
  {
    $wasSet = false;
    $this->optContent = $aOptContent;
    $wasSet = true;
    return $wasSet;
  }

  public function setOptType($aOptType)
  {
    $wasSet = false;
    $this->optType = $aOptType;
    $wasSet = true;
    return $wasSet;
  }

  public function setOptLevel($aOptLevel)
  {
    $wasSet = false;
    $this->optLevel = $aOptLevel;
    $wasSet = true;
    return $wasSet;
  }

  public function setOldValue($aOldValue)
  {
    $wasSet = false;
    $this->oldValue = $aOldValue;
    $wasSet = true;
    return $wasSet;
  }

  public function setNewValue($aNewValue)
  {
    $wasSet = false;
    $this->newValue = $aNewValue;
    $wasSet = true;
    return $wasSet;
  }

  public function setOptObj($aOptObj)
  {
    $wasSet = false;
    $this->optObj = $aOptObj;
    $wasSet = true;
    return $wasSet;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getPlanId()
  {
    return $this->planId;
  }

  public function getUnitId()
  {
    return $this->unitId;
  }

  public function getOptTime()
  {
    return $this->optTime;
  }

  public function getOptContent()
  {
    return $this->optContent;
  }

  public function getOptType()
  {
    return $this->optType;
  }

  public function getOptLevel()
  {
    return $this->optLevel;
  }

  public function getOldValue()
  {
    return $this->oldValue;
  }

  public function getNewValue()
  {
    return $this->newValue;
  }

  public function getOptObj()
  {
    return $this->optObj;
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

class GetOperationRecordResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetOperationRecordResponse Attributes
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

class GetOperationRecordRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetOperationRecordRequest Attributes
  public $startDate;
  public $endDate;
  public $optTypes;
  public $optLevel;
  public $optContents;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setStartDate($aStartDate)
  {
    $wasSet = false;
    $this->startDate = $aStartDate;
    $wasSet = true;
    return $wasSet;
  }

  public function setEndDate($aEndDate)
  {
    $wasSet = false;
    $this->endDate = $aEndDate;
    $wasSet = true;
    return $wasSet;
  }
   public function setOptTypes($aoptTypes) {
       $this->optTypes = $aoptTypes;
   }

  public function addOptType($aOptType)
  {
    $wasAdded = false;
    $this->optTypes[] = $aOptType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeOptType($aOptType)
  {
    $wasRemoved = false;
    unset($this->optTypes[$this->indexOfOptType($aOptType)]);
    $this->optTypes = array_values($this->optTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setOptLevel($aOptLevel)
  {
    $wasSet = false;
    $this->optLevel = $aOptLevel;
    $wasSet = true;
    return $wasSet;
  }
   public function setOptContents($aoptContents) {
       $this->optContents = $aoptContents;
   }

  public function addOptContent($aOptContent)
  {
    $wasAdded = false;
    $this->optContents[] = $aOptContent;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeOptContent($aOptContent)
  {
    $wasRemoved = false;
    unset($this->optContents[$this->indexOfOptContent($aOptContent)]);
    $this->optContents = array_values($this->optContents);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getStartDate()
  {
    return $this->startDate;
  }

  public function getEndDate()
  {
    return $this->endDate;
  }


  public function getOptTypes()
  {
    $newOptTypes = $this->optTypes;
    return $newOptTypes;
  }

  public function numberOfOptTypes()
  {
    $number = count($this->optTypes);
    return $number;
  }

  public function indexOfOptType($aOptType)
  {
    $rawAnswer = array_search($aOptType,$this->optTypes);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getOptLevel()
  {
    return $this->optLevel;
  }


  public function getOptContents()
  {
    $newOptContents = $this->optContents;
    return $newOptContents;
  }

  public function numberOfOptContents()
  {
    $number = count($this->optContents);
    return $number;
  }

  public function indexOfOptContent($aOptContent)
  {
    $rawAnswer = array_search($aOptContent,$this->optContents);
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

class sms_service_ToolkitService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'ToolkitService' );
    }

  // ABSTRACT METHODS 

 public function getOperationRecord ($getOperationRecordRequest){
 return $this->execute ( 'getOperationRecord', $getOperationRecordRequest );
}
  
}


?>