<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class GetCountByIdRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetCountByIdRequest Attributes
  public $idType;
  public $countType;
  public $ids;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setIdType($aIdType)
  {
    $wasSet = false;
    $this->idType = $aIdType;
    $wasSet = true;
    return $wasSet;
  }

  public function setCountType($aCountType)
  {
    $wasSet = false;
    $this->countType = $aCountType;
    $wasSet = true;
    return $wasSet;
  }

  public function setIds($aIds)
  {
    $wasSet = false;
    $this->ids = $aIds;
    $wasSet = true;
    return $wasSet;
  }

  public function getIdType()
  {
    return $this->idType;
  }

  public function getCountType()
  {
    return $this->countType;
  }

  public function getIds()
  {
    return $this->ids;
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

class GetMaterialInfoResultType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetMaterialInfoResultType Attributes
  public $moreMaterial;
  public $materialSearchInfos;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setMoreMaterial($aMoreMaterial)
  {
    $wasSet = false;
    $this->moreMaterial = $aMoreMaterial;
    $wasSet = true;
    return $wasSet;
  }

  public function setMaterialSearchInfos($aMaterialSearchInfos)
  {
    $wasSet = false;
    $this->materialSearchInfos = $aMaterialSearchInfos;
    $wasSet = true;
    return $wasSet;
  }

  public function getMoreMaterial()
  {
    return $this->moreMaterial;
  }

  public function getMaterialSearchInfos()
  {
    return $this->materialSearchInfos;
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

class GetMaterialInfoBySearchResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetMaterialInfoBySearchResponse Attributes
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

class GetCountByIdResultType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetCountByIdResultType Attributes
  public $countInfos;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setCountInfos($aCountInfos)
  {
    $wasSet = false;
    $this->countInfos = $aCountInfos;
    $wasSet = true;
    return $wasSet;
  }

  public function getCountInfos()
  {
    return $this->countInfos;
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

class CountInfo
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //CountInfo Attributes
  public $id;
  public $count;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setId($aId)
  {
    $wasSet = false;
    $this->id = $aId;
    $wasSet = true;
    return $wasSet;
  }

  public function setCount($aCount)
  {
    $wasSet = false;
    $this->count = $aCount;
    $wasSet = true;
    return $wasSet;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getCount()
  {
    return $this->count;
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

class GetMaterialInfoBySearchRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetMaterialInfoBySearchRequest Attributes
  public $searchWord;
  public $startNum;
  public $endNum;
  public $campaignId;
  public $adgroupId;
  public $searchType;
  public $searchLevel;
  public $materialFields;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setSearchWord($aSearchWord)
  {
    $wasSet = false;
    $this->searchWord = $aSearchWord;
    $wasSet = true;
    return $wasSet;
  }

  public function setStartNum($aStartNum)
  {
    $wasSet = false;
    $this->startNum = $aStartNum;
    $wasSet = true;
    return $wasSet;
  }

  public function setEndNum($aEndNum)
  {
    $wasSet = false;
    $this->endNum = $aEndNum;
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

  public function setSearchType($aSearchType)
  {
    $wasSet = false;
    $this->searchType = $aSearchType;
    $wasSet = true;
    return $wasSet;
  }

  public function setSearchLevel($aSearchLevel)
  {
    $wasSet = false;
    $this->searchLevel = $aSearchLevel;
    $wasSet = true;
    return $wasSet;
  }
   public function setMaterialFields($amaterialFields) {
       $this->materialFields = $amaterialFields;
   }

  public function addMaterialField($aMaterialField)
  {
    $wasAdded = false;
    $this->materialFields[] = $aMaterialField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeMaterialField($aMaterialField)
  {
    $wasRemoved = false;
    unset($this->materialFields[$this->indexOfMaterialField($aMaterialField)]);
    $this->materialFields = array_values($this->materialFields);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getSearchWord()
  {
    return $this->searchWord;
  }

  public function getStartNum()
  {
    return $this->startNum;
  }

  public function getEndNum()
  {
    return $this->endNum;
  }

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getSearchType()
  {
    return $this->searchType;
  }

  public function getSearchLevel()
  {
    return $this->searchLevel;
  }


  public function getMaterialFields()
  {
    $newMaterialFields = $this->materialFields;
    return $newMaterialFields;
  }

  public function numberOfMaterialFields()
  {
    $number = count($this->materialFields);
    return $number;
  }

  public function indexOfMaterialField($aMaterialField)
  {
    $rawAnswer = array_search($aMaterialField,$this->materialFields);
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

class GetCountByIdResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetCountByIdResponse Attributes
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

class MaterialSearchInfo
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //MaterialSearchInfo Attributes
  public $materialInfos;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setMaterialInfos($amaterialInfos) {
       $this->materialInfos = $amaterialInfos;
   }

  public function addMaterialInfo($aMaterialInfo)
  {
    $wasAdded = false;
    $this->materialInfos[] = $aMaterialInfo;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeMaterialInfo($aMaterialInfo)
  {
    $wasRemoved = false;
    unset($this->materialInfos[$this->indexOfMaterialInfo($aMaterialInfo)]);
    $this->materialInfos = array_values($this->materialInfos);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getMaterialInfos()
  {
    $newMaterialInfos = $this->materialInfos;
    return $newMaterialInfos;
  }

  public function numberOfMaterialInfos()
  {
    $number = count($this->materialInfos);
    return $number;
  }

  public function indexOfMaterialInfo($aMaterialInfo)
  {
    $rawAnswer = array_search($aMaterialInfo,$this->materialInfos);
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

class sms_service_SearchService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'SearchService' );
    }

  // ABSTRACT METHODS 

 public function getCountById ($getCountByIdRequest){
 return $this->execute ( 'getCountById', $getCountByIdRequest );
}
 public function getMaterialInfoBySearch ($getMaterialInfoBySearchRequest){
 return $this->execute ( 'getMaterialInfoBySearch', $getMaterialInfoBySearchRequest );
}
  
}


?>