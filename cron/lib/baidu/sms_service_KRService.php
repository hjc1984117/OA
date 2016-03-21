<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class EstimatedBidType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //EstimatedBidType Attributes
  public $show;
  public $click;
  public $cpc;
  public $charge;
  public $rank;
  public $ctr;
  public $pv;
  public $recBid;
  public $showRate;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setShow($aShow)
  {
    $wasSet = false;
    $this->show = $aShow;
    $wasSet = true;
    return $wasSet;
  }

  public function setClick($aClick)
  {
    $wasSet = false;
    $this->click = $aClick;
    $wasSet = true;
    return $wasSet;
  }

  public function setCpc($aCpc)
  {
    $wasSet = false;
    $this->cpc = $aCpc;
    $wasSet = true;
    return $wasSet;
  }

  public function setCharge($aCharge)
  {
    $wasSet = false;
    $this->charge = $aCharge;
    $wasSet = true;
    return $wasSet;
  }

  public function setRank($aRank)
  {
    $wasSet = false;
    $this->rank = $aRank;
    $wasSet = true;
    return $wasSet;
  }

  public function setCtr($aCtr)
  {
    $wasSet = false;
    $this->ctr = $aCtr;
    $wasSet = true;
    return $wasSet;
  }

  public function setPv($aPv)
  {
    $wasSet = false;
    $this->pv = $aPv;
    $wasSet = true;
    return $wasSet;
  }

  public function setRecBid($aRecBid)
  {
    $wasSet = false;
    $this->recBid = $aRecBid;
    $wasSet = true;
    return $wasSet;
  }

  public function setShowRate($aShowRate)
  {
    $wasSet = false;
    $this->showRate = $aShowRate;
    $wasSet = true;
    return $wasSet;
  }

  public function getShow()
  {
    return $this->show;
  }

  public function getClick()
  {
    return $this->click;
  }

  public function getCpc()
  {
    return $this->cpc;
  }

  public function getCharge()
  {
    return $this->charge;
  }

  public function getRank()
  {
    return $this->rank;
  }

  public function getCtr()
  {
    return $this->ctr;
  }

  public function getPv()
  {
    return $this->pv;
  }

  public function getRecBid()
  {
    return $this->recBid;
  }

  public function getShowRate()
  {
    return $this->showRate;
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

class GetKRFileIdByWordsType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFileIdByWordsType Attributes
  public $fileId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setFileId($aFileId)
  {
    $wasSet = false;
    $this->fileId = $aFileId;
    $wasSet = true;
    return $wasSet;
  }

  public function getFileId()
  {
    return $this->fileId;
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

class GetEstimatedDataByBidRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataByBidRequest Attributes
  public $words;
  public $searchRegions;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setWords($awords) {
       $this->words = $awords;
   }

  public function addWord($aWord)
  {
    $wasAdded = false;
    $this->words[] = $aWord;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeWord($aWord)
  {
    $wasRemoved = false;
    unset($this->words[$this->indexOfWord($aWord)]);
    $this->words = array_values($this->words);
    $wasRemoved = true;
    return $wasRemoved;
  }
   public function setSearchRegions($asearchRegions) {
       $this->searchRegions = $asearchRegions;
   }

  public function addSearchRegion($aSearchRegion)
  {
    $wasAdded = false;
    $this->searchRegions[] = $aSearchRegion;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSearchRegion($aSearchRegion)
  {
    $wasRemoved = false;
    unset($this->searchRegions[$this->indexOfSearchRegion($aSearchRegion)]);
    $this->searchRegions = array_values($this->searchRegions);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getWords()
  {
    $newWords = $this->words;
    return $newWords;
  }

  public function numberOfWords()
  {
    $number = count($this->words);
    return $number;
  }

  public function indexOfWord($aWord)
  {
    $rawAnswer = array_search($aWord,$this->words);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }


  public function getSearchRegions()
  {
    $newSearchRegions = $this->searchRegions;
    return $newSearchRegions;
  }

  public function numberOfSearchRegions()
  {
    $number = count($this->searchRegions);
    return $number;
  }

  public function indexOfSearchRegion($aSearchRegion)
  {
    $rawAnswer = array_search($aSearchRegion,$this->searchRegions);
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

class GetEstimatedDataRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataRequest Attributes
  public $words;
  public $searchRegions;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setWords($awords) {
       $this->words = $awords;
   }

  public function addWord($aWord)
  {
    $wasAdded = false;
    $this->words[] = $aWord;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeWord($aWord)
  {
    $wasRemoved = false;
    unset($this->words[$this->indexOfWord($aWord)]);
    $this->words = array_values($this->words);
    $wasRemoved = true;
    return $wasRemoved;
  }
   public function setSearchRegions($asearchRegions) {
       $this->searchRegions = $asearchRegions;
   }

  public function addSearchRegion($aSearchRegion)
  {
    $wasAdded = false;
    $this->searchRegions[] = $aSearchRegion;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSearchRegion($aSearchRegion)
  {
    $wasRemoved = false;
    unset($this->searchRegions[$this->indexOfSearchRegion($aSearchRegion)]);
    $this->searchRegions = array_values($this->searchRegions);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getWords()
  {
    $newWords = $this->words;
    return $newWords;
  }

  public function numberOfWords()
  {
    $number = count($this->words);
    return $number;
  }

  public function indexOfWord($aWord)
  {
    $rawAnswer = array_search($aWord,$this->words);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }


  public function getSearchRegions()
  {
    $newSearchRegions = $this->searchRegions;
    return $newSearchRegions;
  }

  public function numberOfSearchRegions()
  {
    $number = count($this->searchRegions);
    return $number;
  }

  public function indexOfSearchRegion($aSearchRegion)
  {
    $rawAnswer = array_search($aSearchRegion,$this->searchRegions);
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

class GetBidByWordsRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetBidByWordsRequest Attributes
  public $words;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setWords($awords) {
       $this->words = $awords;
   }

  public function addWord($aWord)
  {
    $wasAdded = false;
    $this->words[] = $aWord;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeWord($aWord)
  {
    $wasRemoved = false;
    unset($this->words[$this->indexOfWord($aWord)]);
    $this->words = array_values($this->words);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getWords()
  {
    $newWords = $this->words;
    return $newWords;
  }

  public function numberOfWords()
  {
    $number = count($this->words);
    return $number;
  }

  public function indexOfWord($aWord)
  {
    $rawAnswer = array_search($aWord,$this->words);
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

class GetKRCustomRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRCustomRequest Attributes
  public $id;
  public $idType;
  public $seedFilter;

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

  public function setIdType($aIdType)
  {
    $wasSet = false;
    $this->idType = $aIdType;
    $wasSet = true;
    return $wasSet;
  }

  public function setSeedFilter($aSeedFilter)
  {
    $wasSet = false;
    $this->seedFilter = $aSeedFilter;
    $wasSet = true;
    return $wasSet;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getIdType()
  {
    return $this->idType;
  }

  public function getSeedFilter()
  {
    return $this->seedFilter;
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

class GetKRFilePathResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFilePathResponse Attributes
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

class RecBidType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //RecBidType Attributes
  public $bid;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setBid($aBid)
  {
    $wasSet = false;
    $this->bid = $aBid;
    $wasSet = true;
    return $wasSet;
  }

  public function getBid()
  {
    return $this->bid;
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

class GetEstimatedDataByBidResult
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataByBidResult Attributes
  public $word;
  public $bid;
  public $matchType;
  public $all;
  public $pc;
  public $mobile;
  public $competition;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setWord($aWord)
  {
    $wasSet = false;
    $this->word = $aWord;
    $wasSet = true;
    return $wasSet;
  }

  public function setBid($aBid)
  {
    $wasSet = false;
    $this->bid = $aBid;
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

  public function setAll($aAll)
  {
    $wasSet = false;
    $this->all = $aAll;
    $wasSet = true;
    return $wasSet;
  }

  public function setPc($aPc)
  {
    $wasSet = false;
    $this->pc = $aPc;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobile($aMobile)
  {
    $wasSet = false;
    $this->mobile = $aMobile;
    $wasSet = true;
    return $wasSet;
  }

  public function setKwc($aKwc)
  {
    $wasSet = false;
    $this->kwc = $aKwc;
    $wasSet = true;
    return $wasSet;
  }

  public function getWord()
  {
    return $this->word;
  }

  public function getBid()
  {
    return $this->bid;
  }

  public function getMatchType()
  {
    return $this->matchType;
  }

  public function getAll()
  {
    return $this->all;
  }

  public function getPc()
  {
    return $this->pc;
  }

  public function getMobile()
  {
    return $this->mobile;
  }

  public function getKwc()
  {
    return $this->kwc;
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

class GetKRFileIdByWordsRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFileIdByWordsRequest Attributes
  public $seedFilter;
  public $seedWords;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setSeedFilter($aSeedFilter)
  {
    $wasSet = false;
    $this->seedFilter = $aSeedFilter;
    $wasSet = true;
    return $wasSet;
  }
   public function setSeedWords($aseedWords) {
       $this->seedWords = $aseedWords;
   }

  public function addSeedWord($aSeedWord)
  {
    $wasAdded = false;
    $this->seedWords[] = $aSeedWord;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSeedWord($aSeedWord)
  {
    $wasRemoved = false;
    unset($this->seedWords[$this->indexOfSeedWord($aSeedWord)]);
    $this->seedWords = array_values($this->seedWords);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getSeedFilter()
  {
    return $this->seedFilter;
  }


  public function getSeedWords()
  {
    $newSeedWords = $this->seedWords;
    return $newSeedWords;
  }

  public function numberOfSeedWords()
  {
    $number = count($this->seedWords);
    return $number;
  }

  public function indexOfSeedWord($aSeedWord)
  {
    $rawAnswer = array_search($aSeedWord,$this->seedWords);
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

class GetFileStatusRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetFileStatusRequest Attributes
  public $fileId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setFileId($aFileId)
  {
    $wasSet = false;
    $this->fileId = $aFileId;
    $wasSet = true;
    return $wasSet;
  }

  public function getFileId()
  {
    return $this->fileId;
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

class GetKRByQueryResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRByQueryResponse Attributes
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

class GetEstimatedDataResult
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataResult Attributes
  public $all;
  public $pc;
  public $mobile;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setAll($aAll)
  {
    $wasSet = false;
    $this->all = $aAll;
    $wasSet = true;
    return $wasSet;
  }

  public function setPc($aPc)
  {
    $wasSet = false;
    $this->pc = $aPc;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobile($aMobile)
  {
    $wasSet = false;
    $this->mobile = $aMobile;
    $wasSet = true;
    return $wasSet;
  }

  public function getAll()
  {
    return $this->all;
  }

  public function getPc()
  {
    return $this->pc;
  }

  public function getMobile()
  {
    return $this->mobile;
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

class GetKRFilePath
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFilePath Attributes
  public $filePath;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setFilePath($aFilePath)
  {
    $wasSet = false;
    $this->filePath = $aFilePath;
    $wasSet = true;
    return $wasSet;
  }

  public function getFilePath()
  {
    return $this->filePath;
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

class GetKRByQueryRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRByQueryRequest Attributes
  public $queryType;
  public $seedFilter;
  public $query;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setQueryType($aQueryType)
  {
    $wasSet = false;
    $this->queryType = $aQueryType;
    $wasSet = true;
    return $wasSet;
  }

  public function setSeedFilter($aSeedFilter)
  {
    $wasSet = false;
    $this->seedFilter = $aSeedFilter;
    $wasSet = true;
    return $wasSet;
  }

  public function setQuery($aQuery)
  {
    $wasSet = false;
    $this->query = $aQuery;
    $wasSet = true;
    return $wasSet;
  }

  public function getQueryType()
  {
    return $this->queryType;
  }

  public function getSeedFilter()
  {
    return $this->seedFilter;
  }

  public function getQuery()
  {
    return $this->query;
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

class GetEstimatedDataResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataResponse Attributes
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

class GetFileStatusResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetFileStatusResponse Attributes
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

class EstimatedDataType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //EstimatedDataType Attributes
  public $bid;
  public $show;
  public $click;
  public $charge;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setBid($aBid)
  {
    $wasSet = false;
    $this->bid = $aBid;
    $wasSet = true;
    return $wasSet;
  }

  public function setShow($aShow)
  {
    $wasSet = false;
    $this->show = $aShow;
    $wasSet = true;
    return $wasSet;
  }

  public function setClick($aClick)
  {
    $wasSet = false;
    $this->click = $aClick;
    $wasSet = true;
    return $wasSet;
  }

  public function setCharge($aCharge)
  {
    $wasSet = false;
    $this->charge = $aCharge;
    $wasSet = true;
    return $wasSet;
  }

  public function getBid()
  {
    return $this->bid;
  }

  public function getShow()
  {
    return $this->show;
  }

  public function getClick()
  {
    return $this->click;
  }

  public function getCharge()
  {
    return $this->charge;
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

class GetEstimatedDataByBidResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetEstimatedDataByBidResponse Attributes
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

class SeedFilter
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //SeedFilter Attributes
  public $device;
  public $competeLow;
  public $competeHigh;
  public $maxNum;
  public $negativeWords;
  public $positiveWord;
  public $pvLow;
  public $pvHigh;
  public $regionExtend;
  public $removeDuplicate;
  public $searchRegions;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setDevice($aDevice)
  {
    $wasSet = false;
    $this->device = $aDevice;
    $wasSet = true;
    return $wasSet;
  }

  public function setCompeteLow($aCompeteLow)
  {
    $wasSet = false;
    $this->competeLow = $aCompeteLow;
    $wasSet = true;
    return $wasSet;
  }

  public function setCompeteHigh($aCompeteHigh)
  {
    $wasSet = false;
    $this->competeHigh = $aCompeteHigh;
    $wasSet = true;
    return $wasSet;
  }

  public function setMaxNum($aMaxNum)
  {
    $wasSet = false;
    $this->maxNum = $aMaxNum;
    $wasSet = true;
    return $wasSet;
  }
   public function setNegativeWords($anegativeWords) {
       $this->negativeWords = $anegativeWords;
   }

  public function addNegativeWord($aNegativeWord)
  {
    $wasAdded = false;
    $this->negativeWords[] = $aNegativeWord;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeNegativeWord($aNegativeWord)
  {
    $wasRemoved = false;
    unset($this->negativeWords[$this->indexOfNegativeWord($aNegativeWord)]);
    $this->negativeWords = array_values($this->negativeWords);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setPositiveWord($aPositiveWord)
  {
    $wasSet = false;
    $this->positiveWord = $aPositiveWord;
    $wasSet = true;
    return $wasSet;
  }

  public function setPvLow($aPvLow)
  {
    $wasSet = false;
    $this->pvLow = $aPvLow;
    $wasSet = true;
    return $wasSet;
  }

  public function setPvHigh($aPvHigh)
  {
    $wasSet = false;
    $this->pvHigh = $aPvHigh;
    $wasSet = true;
    return $wasSet;
  }

  public function setRegionExtend($aRegionExtend)
  {
    $wasSet = false;
    $this->regionExtend = $aRegionExtend;
    $wasSet = true;
    return $wasSet;
  }

  public function setRemoveDuplicate($aRemoveDuplicate)
  {
    $wasSet = false;
    $this->removeDuplicate = $aRemoveDuplicate;
    $wasSet = true;
    return $wasSet;
  }
   public function setSearchRegions($asearchRegions) {
       $this->searchRegions = $asearchRegions;
   }

  public function addSearchRegion($aSearchRegion)
  {
    $wasAdded = false;
    $this->searchRegions[] = $aSearchRegion;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeSearchRegion($aSearchRegion)
  {
    $wasRemoved = false;
    unset($this->searchRegions[$this->indexOfSearchRegion($aSearchRegion)]);
    $this->searchRegions = array_values($this->searchRegions);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function getDevice()
  {
    return $this->device;
  }

  public function getCompeteLow()
  {
    return $this->competeLow;
  }

  public function getCompeteHigh()
  {
    return $this->competeHigh;
  }

  public function getMaxNum()
  {
    return $this->maxNum;
  }


  public function getNegativeWords()
  {
    $newNegativeWords = $this->negativeWords;
    return $newNegativeWords;
  }

  public function numberOfNegativeWords()
  {
    $number = count($this->negativeWords);
    return $number;
  }

  public function indexOfNegativeWord($aNegativeWord)
  {
    $rawAnswer = array_search($aNegativeWord,$this->negativeWords);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getPositiveWord()
  {
    return $this->positiveWord;
  }

  public function getPvLow()
  {
    return $this->pvLow;
  }

  public function getPvHigh()
  {
    return $this->pvHigh;
  }

  public function getRegionExtend()
  {
    return $this->regionExtend;
  }

  public function getRemoveDuplicate()
  {
    return $this->removeDuplicate;
  }


  public function getSearchRegions()
  {
    $newSearchRegions = $this->searchRegions;
    return $newSearchRegions;
  }

  public function numberOfSearchRegions()
  {
    $number = count($this->searchRegions);
    return $number;
  }

  public function indexOfSearchRegion($aSearchRegion)
  {
    $rawAnswer = array_search($aSearchRegion,$this->searchRegions);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function isRegionExtend()
  {
    return $this->regionExtend;
  }

  public function isRemoveDuplicate()
  {
    return $this->removeDuplicate;
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

class GetKRCustomResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRCustomResponse Attributes
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

class KREstimatedType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //KREstimatedType Attributes
  public $word;
  public $bid;
  public $matchType;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setWord($aWord)
  {
    $wasSet = false;
    $this->word = $aWord;
    $wasSet = true;
    return $wasSet;
  }

  public function setBid($aBid)
  {
    $wasSet = false;
    $this->bid = $aBid;
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

  public function getWord()
  {
    return $this->word;
  }

  public function getBid()
  {
    return $this->bid;
  }

  public function getMatchType()
  {
    return $this->matchType;
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

class GetKRFileRequestParams
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFileRequestParams Attributes
  public $fileId;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setFileId($aFileId)
  {
    $wasSet = false;
    $this->fileId = $aFileId;
    $wasSet = true;
    return $wasSet;
  }

  public function getFileId()
  {
    return $this->fileId;
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

class GetKRFileStatus
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFileStatus Attributes
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

class KRResult
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //KRResult Attributes
  public $word;
  public $competition;
  public $wordPackage;
  public $businessPoints;
  public $recBid;
  public $PV;
  public $pcPV;
  public $mobilePV;
  public $showReasons;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setWord($aWord)
  {
    $wasSet = false;
    $this->word = $aWord;
    $wasSet = true;
    return $wasSet;
  }

  public function setCompetition($aCompetition)
  {
    $wasSet = false;
    $this->competition = $aCompetition;
    $wasSet = true;
    return $wasSet;
  }

  public function setWordPackage($aWordPackage)
  {
    $wasSet = false;
    $this->wordPackage = $aWordPackage;
    $wasSet = true;
    return $wasSet;
  }

  public function setBusinessPoints($aBusinessPoints)
  {
    $wasSet = false;
    $this->businessPoints = $aBusinessPoints;
    $wasSet = true;
    return $wasSet;
  }

  public function setRecBid($aRecBid)
  {
    $wasSet = false;
    $this->recBid = $aRecBid;
    $wasSet = true;
    return $wasSet;
  }

  public function setPV($aPV)
  {
    $wasSet = false;
    $this->PV = $aPV;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcPV($aPcPV)
  {
    $wasSet = false;
    $this->pcPV = $aPcPV;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobilePV($aMobilePV)
  {
    $wasSet = false;
    $this->mobilePV = $aMobilePV;
    $wasSet = true;
    return $wasSet;
  }

  public function setShowReasons($aShowReasons)
  {
    $wasSet = false;
    $this->showReasons = $aShowReasons;
    $wasSet = true;
    return $wasSet;
  }

  public function getWord()
  {
    return $this->word;
  }

  public function getCompetition()
  {
    return $this->competition;
  }

  public function getWordPackage()
  {
    return $this->wordPackage;
  }

  public function getBusinessPoints()
  {
    return $this->businessPoints;
  }

  public function getRecBid()
  {
    return $this->recBid;
  }

  public function getPV()
  {
    return $this->PV;
  }

  public function getPcPV()
  {
    return $this->pcPV;
  }

  public function getMobilePV()
  {
    return $this->mobilePV;
  }

  public function getShowReasons()
  {
    return $this->showReasons;
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

class GetBidByWordsResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetBidByWordsResponse Attributes
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

class GetKRFileIdByWordsResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetKRFileIdByWordsResponse Attributes
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

class sms_service_KRService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'KRService' );
    }

  // ABSTRACT METHODS 

 public function getEstimatedDataByBid ($getEstimatedDataByBidRequest){
 return $this->execute ( 'getEstimatedDataByBid', $getEstimatedDataByBidRequest );
}
 public function getEstimatedData ($getEstimatedDataRequest){
 return $this->execute ( 'getEstimatedData', $getEstimatedDataRequest );
}
 public function getKRFileIdByWords ($getKRFileIdByWordsRequest){
 return $this->execute ( 'getKRFileIdByWords', $getKRFileIdByWordsRequest );
}
 public function getFilePath ($getKRFileRequestParams){
 return $this->execute ( 'getFilePath', $getKRFileRequestParams );
}
 public function getFileStatus ($getFileStatusRequest){
 return $this->execute ( 'getFileStatus', $getFileStatusRequest );
}
 public function getKRByQuery ($getKRByQueryRequest){
 return $this->execute ( 'getKRByQuery', $getKRByQueryRequest );
}
 public function getKRCustom ($getKRCustomRequest){
 return $this->execute ( 'getKRCustom', $getKRCustomRequest );
}
 public function getBidByWords ($getBidByWordsRequest){
 return $this->execute ( 'getBidByWords', $getBidByWordsRequest );
}
  
}


?>