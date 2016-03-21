<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class GetProfessionalReportIdRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetProfessionalReportIdRequest Attributes
  public $reportRequestType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setReportRequestType($aReportRequestType)
  {
    $wasSet = false;
    $this->reportRequestType = $aReportRequestType;
    $wasSet = true;
    return $wasSet;
  }

  public function getReportRequestType()
  {
    return $this->reportRequestType;
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

class GetRealTimeDataRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimeDataRequest Attributes
  public $realTimeRequestType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setRealTimeRequestType($aRealTimeRequestType)
  {
    $wasSet = false;
    $this->realTimeRequestType = $aRealTimeRequestType;
    $wasSet = true;
    return $wasSet;
  }

  public function getRealTimeRequestType()
  {
    return $this->realTimeRequestType;
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

class GetRealTimeDataResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimeDataResponse Attributes
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

class GetProfessionalReportIdResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetProfessionalReportIdResponse Attributes
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

class RealTimeQueryResultType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //RealTimeQueryResultType Attributes
  public $query;
  public $queryInfo;
  public $keywordId;
  public $creativeId;
  public $pairInfo;
  public $date;
  public $KPIs;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setQuery($aQuery)
  {
    $wasSet = false;
    $this->query = $aQuery;
    $wasSet = true;
    return $wasSet;
  }
   public function setQueryInfo($aqueryInfo) {
       $this->queryInfo = $aqueryInfo;
   }

  public function addQueryInfo($aQueryInfo)
  {
    $wasAdded = false;
    $this->queryInfo[] = $aQueryInfo;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeQueryInfo($aQueryInfo)
  {
    $wasRemoved = false;
    unset($this->queryInfo[$this->indexOfQueryInfo($aQueryInfo)]);
    $this->queryInfo = array_values($this->queryInfo);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setKeywordId($aKeywordId)
  {
    $wasSet = false;
    $this->keywordId = $aKeywordId;
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
   public function setPairInfo($apairInfo) {
       $this->pairInfo = $apairInfo;
   }

  public function addPairInfo($aPairInfo)
  {
    $wasAdded = false;
    $this->pairInfo[] = $aPairInfo;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePairInfo($aPairInfo)
  {
    $wasRemoved = false;
    unset($this->pairInfo[$this->indexOfPairInfo($aPairInfo)]);
    $this->pairInfo = array_values($this->pairInfo);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setDate($aDate)
  {
    $wasSet = false;
    $this->date = $aDate;
    $wasSet = true;
    return $wasSet;
  }
   public function setKPIs($aKPIs) {
       $this->KPIs = $aKPIs;
   }

  public function addKPI($aKPI)
  {
    $wasAdded = false;
    $this->KPIs[] = $aKPI;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKPI($aKPI)
  {
    $wasRemoved = false;
    unset($this->KPIs[$this->indexOfKPI($aKPI)]);
    $this->KPIs = array_values($this->KPIs);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getQuery()
  {
    return $this->query;
  }


  public function getQueryInfo()
  {
    $newQueryInfo = $this->queryInfo;
    return $newQueryInfo;
  }

  public function numberOfQueryInfo()
  {
    $number = count($this->queryInfo);
    return $number;
  }

  public function indexOfQueryInfo($aQueryInfo)
  {
    $rawAnswer = array_search($aQueryInfo,$this->queryInfo);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getKeywordId()
  {
    return $this->keywordId;
  }

  public function getCreativeId()
  {
    return $this->creativeId;
  }


  public function getPairInfo()
  {
    $newPairInfo = $this->pairInfo;
    return $newPairInfo;
  }

  public function numberOfPairInfo()
  {
    $number = count($this->pairInfo);
    return $number;
  }

  public function indexOfPairInfo($aPairInfo)
  {
    $rawAnswer = array_search($aPairInfo,$this->pairInfo);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getDate()
  {
    return $this->date;
  }


  public function getKPIs()
  {
    $newKPIs = $this->KPIs;
    return $newKPIs;
  }

  public function numberOfKPIs()
  {
    $number = count($this->KPIs);
    return $number;
  }

  public function indexOfKPI($aKPI)
  {
    $rawAnswer = array_search($aKPI,$this->KPIs);
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

class AttributeType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AttributeType Attributes
  public $key;
  public $value;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setKey($aKey)
  {
    $wasSet = false;
    $this->key = $aKey;
    $wasSet = true;
    return $wasSet;
  }

  public function setValue($aValue)
  {
    $wasSet = false;
    $this->value = $aValue;
    $wasSet = true;
    return $wasSet;
  }

  public function getKey()
  {
    return $this->key;
  }

  public function getValue()
  {
    return $this->value;
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

class GetProfessionalReportIdData
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetProfessionalReportIdData Attributes
  public $reportId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setReportId($aReportId)
  {
    $wasSet = false;
    $this->reportId = $aReportId;
    $wasSet = true;
    return $wasSet;
  }

  public function getReportId()
  {
    return $this->reportId;
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

class GetReportFileUrlRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportFileUrlRequest Attributes
  public $reportId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setReportId($aReportId)
  {
    $wasSet = false;
    $this->reportId = $aReportId;
    $wasSet = true;
    return $wasSet;
  }

  public function getReportId()
  {
    return $this->reportId;
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

class GetReportStateRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportStateRequest Attributes
  public $reportId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setReportId($aReportId)
  {
    $wasSet = false;
    $this->reportId = $aReportId;
    $wasSet = true;
    return $wasSet;
  }

  public function getReportId()
  {
    return $this->reportId;
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

class RealTimeResultType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //RealTimeResultType Attributes
  public $ID;
  public $relatedId;
  public $date;
  public $KPIs;
  public $name;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setID($aID)
  {
    $wasSet = false;
    $this->ID = $aID;
    $wasSet = true;
    return $wasSet;
  }

  public function setRelatedId($aRelatedId)
  {
    $wasSet = false;
    $this->relatedId = $aRelatedId;
    $wasSet = true;
    return $wasSet;
  }

  public function setDate($aDate)
  {
    $wasSet = false;
    $this->date = $aDate;
    $wasSet = true;
    return $wasSet;
  }
   public function setKPIs($aKPIs) {
       $this->KPIs = $aKPIs;
   }

  public function addKPI($aKPI)
  {
    $wasAdded = false;
    $this->KPIs[] = $aKPI;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKPI($aKPI)
  {
    $wasRemoved = false;
    unset($this->KPIs[$this->indexOfKPI($aKPI)]);
    $this->KPIs = array_values($this->KPIs);
    $wasRemoved = true;
    return $wasRemoved;
  }
   public function setName($aname) {
       $this->name = $aname;
   }

  public function addName($aName)
  {
    $wasAdded = false;
    $this->name[] = $aName;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeName($aName)
  {
    $wasRemoved = false;
    unset($this->name[$this->indexOfName($aName)]);
    $this->name = array_values($this->name);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getID()
  {
    return $this->ID;
  }

  public function getRelatedId()
  {
    return $this->relatedId;
  }

  public function getDate()
  {
    return $this->date;
  }


  public function getKPIs()
  {
    $newKPIs = $this->KPIs;
    return $newKPIs;
  }

  public function numberOfKPIs()
  {
    $number = count($this->KPIs);
    return $number;
  }

  public function indexOfKPI($aKPI)
  {
    $rawAnswer = array_search($aKPI,$this->KPIs);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }


  public function getName()
  {
    $newName = $this->name;
    return $newName;
  }

  public function numberOfName()
  {
    $number = count($this->name);
    return $number;
  }

  public function indexOfName($aName)
  {
    $rawAnswer = array_search($aName,$this->name);
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

class GetReportFileUrlData
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportFileUrlData Attributes
  public $reportFilePath;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setReportFilePath($aReportFilePath)
  {
    $wasSet = false;
    $this->reportFilePath = $aReportFilePath;
    $wasSet = true;
    return $wasSet;
  }

  public function getReportFilePath()
  {
    return $this->reportFilePath;
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

class GetRealTimeQueryDataResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimeQueryDataResponse Attributes
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

class GetRealTimePairDataRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimePairDataRequest Attributes
  public $realTimePairRequestType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setRealTimePairRequestType($aRealTimePairRequestType)
  {
    $wasSet = false;
    $this->realTimePairRequestType = $aRealTimePairRequestType;
    $wasSet = true;
    return $wasSet;
  }

  public function getRealTimePairRequestType()
  {
    return $this->realTimePairRequestType;
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

class GetRealTimePairDataResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimePairDataResponse Attributes
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

class GetReportStateData
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportStateData Attributes
  public $isGenerated;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setIsGenerated($aIsGenerated)
  {
    $wasSet = false;
    $this->isGenerated = $aIsGenerated;
    $wasSet = true;
    return $wasSet;
  }

  public function getIsGenerated()
  {
    return $this->isGenerated;
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

class RealTimePairResultType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //RealTimePairResultType Attributes
  public $keywordId;
  public $creativeId;
  public $pairInfo;
  public $date;
  public $KPIs;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setKeywordId($aKeywordId)
  {
    $wasSet = false;
    $this->keywordId = $aKeywordId;
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
   public function setPairInfo($apairInfo) {
       $this->pairInfo = $apairInfo;
   }

  public function addPairInfo($aPairInfo)
  {
    $wasAdded = false;
    $this->pairInfo[] = $aPairInfo;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePairInfo($aPairInfo)
  {
    $wasRemoved = false;
    unset($this->pairInfo[$this->indexOfPairInfo($aPairInfo)]);
    $this->pairInfo = array_values($this->pairInfo);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setDate($aDate)
  {
    $wasSet = false;
    $this->date = $aDate;
    $wasSet = true;
    return $wasSet;
  }
   public function setKPIs($aKPIs) {
       $this->KPIs = $aKPIs;
   }

  public function addKPI($aKPI)
  {
    $wasAdded = false;
    $this->KPIs[] = $aKPI;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKPI($aKPI)
  {
    $wasRemoved = false;
    unset($this->KPIs[$this->indexOfKPI($aKPI)]);
    $this->KPIs = array_values($this->KPIs);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getKeywordId()
  {
    return $this->keywordId;
  }

  public function getCreativeId()
  {
    return $this->creativeId;
  }


  public function getPairInfo()
  {
    $newPairInfo = $this->pairInfo;
    return $newPairInfo;
  }

  public function numberOfPairInfo()
  {
    $number = count($this->pairInfo);
    return $number;
  }

  public function indexOfPairInfo($aPairInfo)
  {
    $rawAnswer = array_search($aPairInfo,$this->pairInfo);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getDate()
  {
    return $this->date;
  }


  public function getKPIs()
  {
    $newKPIs = $this->KPIs;
    return $newKPIs;
  }

  public function numberOfKPIs()
  {
    $number = count($this->KPIs);
    return $number;
  }

  public function indexOfKPI($aKPI)
  {
    $rawAnswer = array_search($aKPI,$this->KPIs);
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

class ReportRequestType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ReportRequestType Attributes
  public $performanceData;
  public $startDate;
  public $endDate;
  public $levelOfDetails;
  public $idOnly;
  public $attributes;
  public $format;
  public $statIds;
  public $statRange;
  public $reportType;
  public $unitOfTime;
  public $device;
  public $order;
  public $number;
  public $isrelativetime;
  public $platform;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setPerformanceData($aperformanceData) {
       $this->performanceData = $aperformanceData;
   }

  public function addPerformanceData($aPerformanceData)
  {
    $wasAdded = false;
    $this->performanceData[] = $aPerformanceData;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePerformanceData($aPerformanceData)
  {
    $wasRemoved = false;
    unset($this->performanceData[$this->indexOfPerformanceData($aPerformanceData)]);
    $this->performanceData = array_values($this->performanceData);
    $wasRemoved = true;
    return $wasRemoved;
  }

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

  public function setLevelOfDetails($aLevelOfDetails)
  {
    $wasSet = false;
    $this->levelOfDetails = $aLevelOfDetails;
    $wasSet = true;
    return $wasSet;
  }

  public function setIdOnly($aIdOnly)
  {
    $wasSet = false;
    $this->idOnly = $aIdOnly;
    $wasSet = true;
    return $wasSet;
  }
   public function setAttributes($aattributes) {
       $this->attributes = $aattributes;
   }

  public function addAttribute($aAttribute)
  {
    $wasAdded = false;
    $this->attributes[] = $aAttribute;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeAttribute($aAttribute)
  {
    $wasRemoved = false;
    unset($this->attributes[$this->indexOfAttribute($aAttribute)]);
    $this->attributes = array_values($this->attributes);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setFormat($aFormat)
  {
    $wasSet = false;
    $this->format = $aFormat;
    $wasSet = true;
    return $wasSet;
  }
   public function setStatIds($astatIds) {
       $this->statIds = $astatIds;
   }

  public function addStatId($aStatId)
  {
    $wasAdded = false;
    $this->statIds[] = $aStatId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeStatId($aStatId)
  {
    $wasRemoved = false;
    unset($this->statIds[$this->indexOfStatId($aStatId)]);
    $this->statIds = array_values($this->statIds);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setStatRange($aStatRange)
  {
    $wasSet = false;
    $this->statRange = $aStatRange;
    $wasSet = true;
    return $wasSet;
  }

  public function setReportType($aReportType)
  {
    $wasSet = false;
    $this->reportType = $aReportType;
    $wasSet = true;
    return $wasSet;
  }

  public function setUnitOfTime($aUnitOfTime)
  {
    $wasSet = false;
    $this->unitOfTime = $aUnitOfTime;
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

  public function setOrder($aOrder)
  {
    $wasSet = false;
    $this->order = $aOrder;
    $wasSet = true;
    return $wasSet;
  }

  public function setNumber($aNumber)
  {
    $wasSet = false;
    $this->number = $aNumber;
    $wasSet = true;
    return $wasSet;
  }

  public function setIsrelativetime($aIsrelativetime)
  {
    $wasSet = false;
    $this->isrelativetime = $aIsrelativetime;
    $wasSet = true;
    return $wasSet;
  }

  public function setPlatform($aPlatform)
  {
    $wasSet = false;
    $this->platform = $aPlatform;
    $wasSet = true;
    return $wasSet;
  }


  public function getPerformanceData()
  {
    $newPerformanceData = $this->performanceData;
    return $newPerformanceData;
  }

  public function numberOfPerformanceData()
  {
    $number = count($this->performanceData);
    return $number;
  }

  public function indexOfPerformanceData($aPerformanceData)
  {
    $rawAnswer = array_search($aPerformanceData,$this->performanceData);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getStartDate()
  {
    return $this->startDate;
  }

  public function getEndDate()
  {
    return $this->endDate;
  }

  public function getLevelOfDetails()
  {
    return $this->levelOfDetails;
  }

  public function getIdOnly()
  {
    return $this->idOnly;
  }


  public function getAttributes()
  {
    $newAttributes = $this->attributes;
    return $newAttributes;
  }

  public function numberOfAttributes()
  {
    $number = count($this->attributes);
    return $number;
  }

  public function indexOfAttribute($aAttribute)
  {
    $rawAnswer = array_search($aAttribute,$this->attributes);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getFormat()
  {
    return $this->format;
  }


  public function getStatIds()
  {
    $newStatIds = $this->statIds;
    return $newStatIds;
  }

  public function numberOfStatIds()
  {
    $number = count($this->statIds);
    return $number;
  }

  public function indexOfStatId($aStatId)
  {
    $rawAnswer = array_search($aStatId,$this->statIds);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getStatRange()
  {
    return $this->statRange;
  }

  public function getReportType()
  {
    return $this->reportType;
  }

  public function getUnitOfTime()
  {
    return $this->unitOfTime;
  }

  public function getDevice()
  {
    return $this->device;
  }

  public function getOrder()
  {
    return $this->order;
  }

  public function getNumber()
  {
    return $this->number;
  }

  public function getIsrelativetime()
  {
    return $this->isrelativetime;
  }

  public function getPlatform()
  {
    return $this->platform;
  }

  public function isIdOnly()
  {
    return $this->idOnly;
  }

  public function isOrder()
  {
    return $this->order;
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

class GetReportStateResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportStateResponse Attributes
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

class GetRealTimeQueryDataRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetRealTimeQueryDataRequest Attributes
  public $realTimeQueryRequestType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setRealTimeQueryRequestType($aRealTimeQueryRequestType)
  {
    $wasSet = false;
    $this->realTimeQueryRequestType = $aRealTimeQueryRequestType;
    $wasSet = true;
    return $wasSet;
  }

  public function getRealTimeQueryRequestType()
  {
    return $this->realTimeQueryRequestType;
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

class GetReportFileUrlResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetReportFileUrlResponse Attributes
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

class sms_service_ReportService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'ReportService' );
    }

  // ABSTRACT METHODS 

 public function getRealTimeQueryData ($getRealTimeQueryDataRequest){
 return $this->execute ( 'getRealTimeQueryData', $getRealTimeQueryDataRequest );
}
 public function getRealTimePairData ($getRealTimePairDataRequest){
 return $this->execute ( 'getRealTimePairData', $getRealTimePairDataRequest );
}
 public function getReportState ($getReportStateRequest){
 return $this->execute ( 'getReportState', $getReportStateRequest );
}
 public function getReportFileUrl ($getReportFileUrlRequest){
 return $this->execute ( 'getReportFileUrl', $getReportFileUrlRequest );
}
 public function getRealTimeData ($getRealTimeDataRequest){
 return $this->execute ( 'getRealTimeData', $getRealTimeDataRequest );
}
 public function getProfessionalReportId ($getProfessionalReportIdRequest){
 return $this->execute ( 'getProfessionalReportId', $getProfessionalReportIdRequest );
}
  
}


?>