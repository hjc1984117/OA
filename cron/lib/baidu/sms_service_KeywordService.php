<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class UpdateWordResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateWordResponse Attributes
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

class AddWordResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddWordResponse Attributes
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

class AddWordRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AddWordRequest Attributes
  public $keywordTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setKeywordTypes($akeywordTypes) {
       $this->keywordTypes = $akeywordTypes;
   }

  public function addKeywordType($aKeywordType)
  {
    $wasAdded = false;
    $this->keywordTypes[] = $aKeywordType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKeywordType($aKeywordType)
  {
    $wasRemoved = false;
    unset($this->keywordTypes[$this->indexOfKeywordType($aKeywordType)]);
    $this->keywordTypes = array_values($this->keywordTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getKeywordTypes()
  {
    $newKeywordTypes = $this->keywordTypes;
    return $newKeywordTypes;
  }

  public function numberOfKeywordTypes()
  {
    $number = count($this->keywordTypes);
    return $number;
  }

  public function indexOfKeywordType($aKeywordType)
  {
    $rawAnswer = array_search($aKeywordType,$this->keywordTypes);
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

class DeleteWordResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteWordResponse Attributes
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

class DeleteWordRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DeleteWordRequest Attributes
  public $keywordIds;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setKeywordIds($akeywordIds) {
       $this->keywordIds = $akeywordIds;
   }

  public function addKeywordId($aKeywordId)
  {
    $wasAdded = false;
    $this->keywordIds[] = $aKeywordId;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKeywordId($aKeywordId)
  {
    $wasRemoved = false;
    unset($this->keywordIds[$this->indexOfKeywordId($aKeywordId)]);
    $this->keywordIds = array_values($this->keywordIds);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getKeywordIds()
  {
    $newKeywordIds = $this->keywordIds;
    return $newKeywordIds;
  }

  public function numberOfKeywordIds()
  {
    $number = count($this->keywordIds);
    return $number;
  }

  public function indexOfKeywordId($aKeywordId)
  {
    $rawAnswer = array_search($aKeywordId,$this->keywordIds);
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

class GetWordRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetWordRequest Attributes
  public $wordFields;
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
   public function setWordFields($awordFields) {
       $this->wordFields = $awordFields;
   }

  public function addWordField($aWordField)
  {
    $wasAdded = false;
    $this->wordFields[] = $aWordField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeWordField($aWordField)
  {
    $wasRemoved = false;
    unset($this->wordFields[$this->indexOfWordField($aWordField)]);
    $this->wordFields = array_values($this->wordFields);
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


  public function getWordFields()
  {
    $newWordFields = $this->wordFields;
    return $newWordFields;
  }

  public function numberOfWordFields()
  {
    $number = count($this->wordFields);
    return $number;
  }

  public function indexOfWordField($aWordField)
  {
    $rawAnswer = array_search($aWordField,$this->wordFields);
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

class GetWordResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetWordResponse Attributes
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

class UpdateWordRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateWordRequest Attributes
  public $keywordTypes;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setKeywordTypes($akeywordTypes) {
       $this->keywordTypes = $akeywordTypes;
   }

  public function addKeywordType($aKeywordType)
  {
    $wasAdded = false;
    $this->keywordTypes[] = $aKeywordType;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeKeywordType($aKeywordType)
  {
    $wasRemoved = false;
    unset($this->keywordTypes[$this->indexOfKeywordType($aKeywordType)]);
    $this->keywordTypes = array_values($this->keywordTypes);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getKeywordTypes()
  {
    $newKeywordTypes = $this->keywordTypes;
    return $newKeywordTypes;
  }

  public function numberOfKeywordTypes()
  {
    $number = count($this->keywordTypes);
    return $number;
  }

  public function indexOfKeywordType($aKeywordType)
  {
    $rawAnswer = array_search($aKeywordType,$this->keywordTypes);
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

class KeywordType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //KeywordType Attributes
  public $campaignId;
  public $keywordId;
  public $adgroupId;
  public $keyword;
  public $price;
  public $destinationUrl;
  public $matchType;
  public $pause;
  public $status;
  public $pcDestinationUrl;
  public $mobileDestinationUrl;
  public $phraseType;
  public $wmatchprefer;
  public $deviceprefer;
  public $owmatch;
  public $pcQuality;
  public $pcReliable;
  public $pcReason;
  public $pcScale;
  public $mobileQuality;
  public $mobileReliable;
  public $mobileReason;
  public $mobileScale;
  public $temp;
  public $operator;
  public $offlineReasons;

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

  public function setKeywordId($aKeywordId)
  {
    $wasSet = false;
    $this->keywordId = $aKeywordId;
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

  public function setKeyword($aKeyword)
  {
    $wasSet = false;
    $this->keyword = $aKeyword;
    $wasSet = true;
    return $wasSet;
  }

  public function setPrice($aPrice)
  {
    $wasSet = false;
    $this->price = $aPrice;
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

  public function setMatchType($aMatchType)
  {
    $wasSet = false;
    $this->matchType = $aMatchType;
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

  public function setPcDestinationUrl($aPcDestinationUrl)
  {
    $wasSet = false;
    $this->pcDestinationUrl = $aPcDestinationUrl;
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

  public function setPhraseType($aPhraseType)
  {
    $wasSet = false;
    $this->phraseType = $aPhraseType;
    $wasSet = true;
    return $wasSet;
  }

  public function setWmatchprefer($aWmatchprefer)
  {
    $wasSet = false;
    $this->wmatchprefer = $aWmatchprefer;
    $wasSet = true;
    return $wasSet;
  }

  public function setDeviceprefer($aDeviceprefer)
  {
    $wasSet = false;
    $this->deviceprefer = $aDeviceprefer;
    $wasSet = true;
    return $wasSet;
  }

  public function setOwmatch($aOwmatch)
  {
    $wasSet = false;
    $this->owmatch = $aOwmatch;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcQuality($aPcQuality)
  {
    $wasSet = false;
    $this->pcQuality = $aPcQuality;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcReliable($aPcReliable)
  {
    $wasSet = false;
    $this->pcReliable = $aPcReliable;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcReason($aPcReason)
  {
    $wasSet = false;
    $this->pcReason = $aPcReason;
    $wasSet = true;
    return $wasSet;
  }
   public function setPcScale($apcScale) {
       $this->pcScale = $apcScale;
   }

  public function addPcScale($aPcScale)
  {
    $wasAdded = false;
    $this->pcScale[] = $aPcScale;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removePcScale($aPcScale)
  {
    $wasRemoved = false;
    unset($this->pcScale[$this->indexOfPcScale($aPcScale)]);
    $this->pcScale = array_values($this->pcScale);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setMobileQuality($aMobileQuality)
  {
    $wasSet = false;
    $this->mobileQuality = $aMobileQuality;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobileReliable($aMobileReliable)
  {
    $wasSet = false;
    $this->mobileReliable = $aMobileReliable;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobileReason($aMobileReason)
  {
    $wasSet = false;
    $this->mobileReason = $aMobileReason;
    $wasSet = true;
    return $wasSet;
  }
   public function setMobileScale($amobileScale) {
       $this->mobileScale = $amobileScale;
   }

  public function addMobileScale($aMobileScale)
  {
    $wasAdded = false;
    $this->mobileScale[] = $aMobileScale;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeMobileScale($aMobileScale)
  {
    $wasRemoved = false;
    unset($this->mobileScale[$this->indexOfMobileScale($aMobileScale)]);
    $this->mobileScale = array_values($this->mobileScale);
    $wasRemoved = true;
    return $wasRemoved;
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

  public function getCampaignId()
  {
    return $this->campaignId;
  }

  public function getKeywordId()
  {
    return $this->keywordId;
  }

  public function getAdgroupId()
  {
    return $this->adgroupId;
  }

  public function getKeyword()
  {
    return $this->keyword;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function getDestinationUrl()
  {
    return $this->destinationUrl;
  }

  public function getMatchType()
  {
    return $this->matchType;
  }

  public function getPause()
  {
    return $this->pause;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function getPcDestinationUrl()
  {
    return $this->pcDestinationUrl;
  }

  public function getMobileDestinationUrl()
  {
    return $this->mobileDestinationUrl;
  }

  public function getPhraseType()
  {
    return $this->phraseType;
  }

  public function getWmatchprefer()
  {
    return $this->wmatchprefer;
  }

  public function getDeviceprefer()
  {
    return $this->deviceprefer;
  }

  public function getOwmatch()
  {
    return $this->owmatch;
  }

  public function getPcQuality()
  {
    return $this->pcQuality;
  }

  public function getPcReliable()
  {
    return $this->pcReliable;
  }

  public function getPcReason()
  {
    return $this->pcReason;
  }


  public function getPcScale()
  {
    $newPcScale = $this->pcScale;
    return $newPcScale;
  }

  public function numberOfPcScale()
  {
    $number = count($this->pcScale);
    return $number;
  }

  public function indexOfPcScale($aPcScale)
  {
    $rawAnswer = array_search($aPcScale,$this->pcScale);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getMobileQuality()
  {
    return $this->mobileQuality;
  }

  public function getMobileReliable()
  {
    return $this->mobileReliable;
  }

  public function getMobileReason()
  {
    return $this->mobileReason;
  }


  public function getMobileScale()
  {
    $newMobileScale = $this->mobileScale;
    return $newMobileScale;
  }

  public function numberOfMobileScale()
  {
    $number = count($this->mobileScale);
    return $number;
  }

  public function indexOfMobileScale($aMobileScale)
  {
    $rawAnswer = array_search($aMobileScale,$this->mobileScale);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getTemp()
  {
    return $this->temp;
  }

  public function getOperator()
  {
    return $this->operator;
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

class sms_service_KeywordService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'KeywordService' );
    }

  // ABSTRACT METHODS 

 public function addWord ($addWordRequest){
 return $this->execute ( 'addWord', $addWordRequest );
}
 public function updateWord ($updateWordRequest){
 return $this->execute ( 'updateWord', $updateWordRequest );
}
 public function deleteWord ($deleteWordRequest){
 return $this->execute ( 'deleteWord', $deleteWordRequest );
}
 public function getWord ($getWordRequest){
 return $this->execute ( 'getWord', $getWordRequest );
}
  
}


?>