<?php
require_once 'CommonService.php';

/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE @UMPLE_VERSION@ modeling language!*/

class OfflineTimeType
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //OfflineTimeType Attributes
  public $time;
  public $flag;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setTime($aTime)
  {
    $wasSet = false;
    $this->time = $aTime;
    $wasSet = true;
    return $wasSet;
  }

  public function setFlag($aFlag)
  {
    $wasSet = false;
    $this->flag = $aFlag;
    $wasSet = true;
    return $wasSet;
  }

  public function getTime()
  {
    return $this->time;
  }

  public function getFlag()
  {
    return $this->flag;
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

class AccountInfo
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //AccountInfo Attributes
  public $userId;
  public $balance;
  public $pcBalance;
  public $mobileBalance;
  public $frameStat;
  public $cost;
  public $payment;
  public $budget;
  public $regionTarget;
  public $excludeIp;
  public $openDomains;
  public $budgetType;
  public $regDomain;
  public $weeklyBudget;
  public $userStat;
  public $budgetOfflineTime;
  public $isDynamicCreative;
  public $dynamicCreativeParam;
  public $isDynamicTagSublink;
  public $isDynamicTitle;
  public $isDynamicHotRedirect;

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

  public function setBalance($aBalance)
  {
    $wasSet = false;
    $this->balance = $aBalance;
    $wasSet = true;
    return $wasSet;
  }

  public function setPcBalance($aPcBalance)
  {
    $wasSet = false;
    $this->pcBalance = $aPcBalance;
    $wasSet = true;
    return $wasSet;
  }

  public function setMobileBalance($aMobileBalance)
  {
    $wasSet = false;
    $this->mobileBalance = $aMobileBalance;
    $wasSet = true;
    return $wasSet;
  }

  public function setFrameStat($aFrameStat)
  {
    $wasSet = false;
    $this->frameStat = $aFrameStat;
    $wasSet = true;
    return $wasSet;
  }

  public function setCost($aCost)
  {
    $wasSet = false;
    $this->cost = $aCost;
    $wasSet = true;
    return $wasSet;
  }

  public function setPayment($aPayment)
  {
    $wasSet = false;
    $this->payment = $aPayment;
    $wasSet = true;
    return $wasSet;
  }

  public function setBudget($aBudget)
  {
    $wasSet = false;
    $this->budget = $aBudget;
    $wasSet = true;
    return $wasSet;
  }
   public function setRegionTarget($aregionTarget) {
       $this->regionTarget = $aregionTarget;
   }

  public function addRegionTarget($aRegionTarget)
  {
    $wasAdded = false;
    $this->regionTarget[] = $aRegionTarget;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeRegionTarget($aRegionTarget)
  {
    $wasRemoved = false;
    unset($this->regionTarget[$this->indexOfRegionTarget($aRegionTarget)]);
    $this->regionTarget = array_values($this->regionTarget);
    $wasRemoved = true;
    return $wasRemoved;
  }
   public function setExcludeIp($aexcludeIp) {
       $this->excludeIp = $aexcludeIp;
   }

  public function addExcludeIp($aExcludeIp)
  {
    $wasAdded = false;
    $this->excludeIp[] = $aExcludeIp;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeExcludeIp($aExcludeIp)
  {
    $wasRemoved = false;
    unset($this->excludeIp[$this->indexOfExcludeIp($aExcludeIp)]);
    $this->excludeIp = array_values($this->excludeIp);
    $wasRemoved = true;
    return $wasRemoved;
  }
   public function setOpenDomains($aopenDomains) {
       $this->openDomains = $aopenDomains;
   }

  public function addOpenDomain($aOpenDomain)
  {
    $wasAdded = false;
    $this->openDomains[] = $aOpenDomain;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeOpenDomain($aOpenDomain)
  {
    $wasRemoved = false;
    unset($this->openDomains[$this->indexOfOpenDomain($aOpenDomain)]);
    $this->openDomains = array_values($this->openDomains);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setBudgetType($aBudgetType)
  {
    $wasSet = false;
    $this->budgetType = $aBudgetType;
    $wasSet = true;
    return $wasSet;
  }

  public function setRegDomain($aRegDomain)
  {
    $wasSet = false;
    $this->regDomain = $aRegDomain;
    $wasSet = true;
    return $wasSet;
  }
   public function setWeeklyBudget($aweeklyBudget) {
       $this->weeklyBudget = $aweeklyBudget;
   }

  public function addWeeklyBudget($aWeeklyBudget)
  {
    $wasAdded = false;
    $this->weeklyBudget[] = $aWeeklyBudget;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeWeeklyBudget($aWeeklyBudget)
  {
    $wasRemoved = false;
    unset($this->weeklyBudget[$this->indexOfWeeklyBudget($aWeeklyBudget)]);
    $this->weeklyBudget = array_values($this->weeklyBudget);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setUserStat($aUserStat)
  {
    $wasSet = false;
    $this->userStat = $aUserStat;
    $wasSet = true;
    return $wasSet;
  }
   public function setBudgetOfflineTime($abudgetOfflineTime) {
       $this->budgetOfflineTime = $abudgetOfflineTime;
   }

  public function addBudgetOfflineTime($aBudgetOfflineTime)
  {
    $wasAdded = false;
    $this->budgetOfflineTime[] = $aBudgetOfflineTime;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeBudgetOfflineTime($aBudgetOfflineTime)
  {
    $wasRemoved = false;
    unset($this->budgetOfflineTime[$this->indexOfBudgetOfflineTime($aBudgetOfflineTime)]);
    $this->budgetOfflineTime = array_values($this->budgetOfflineTime);
    $wasRemoved = true;
    return $wasRemoved;
  }

  public function setIsDynamicCreative($aIsDynamicCreative)
  {
    $wasSet = false;
    $this->isDynamicCreative = $aIsDynamicCreative;
    $wasSet = true;
    return $wasSet;
  }

  public function setDynamicCreativeParam($aDynamicCreativeParam)
  {
    $wasSet = false;
    $this->dynamicCreativeParam = $aDynamicCreativeParam;
    $wasSet = true;
    return $wasSet;
  }

  public function setIsDynamicTagSublink($aIsDynamicTagSublink)
  {
    $wasSet = false;
    $this->isDynamicTagSublink = $aIsDynamicTagSublink;
    $wasSet = true;
    return $wasSet;
  }

  public function setIsDynamicTitle($aIsDynamicTitle)
  {
    $wasSet = false;
    $this->isDynamicTitle = $aIsDynamicTitle;
    $wasSet = true;
    return $wasSet;
  }

  public function setIsDynamicHotRedirect($aIsDynamicHotRedirect)
  {
    $wasSet = false;
    $this->isDynamicHotRedirect = $aIsDynamicHotRedirect;
    $wasSet = true;
    return $wasSet;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getBalance()
  {
    return $this->balance;
  }

  public function getPcBalance()
  {
    return $this->pcBalance;
  }

  public function getMobileBalance()
  {
    return $this->mobileBalance;
  }

  public function getFrameStat()
  {
    return $this->frameStat;
  }

  public function getCost()
  {
    return $this->cost;
  }

  public function getPayment()
  {
    return $this->payment;
  }

  public function getBudget()
  {
    return $this->budget;
  }


  public function getRegionTarget()
  {
    $newRegionTarget = $this->regionTarget;
    return $newRegionTarget;
  }

  public function numberOfRegionTarget()
  {
    $number = count($this->regionTarget);
    return $number;
  }

  public function indexOfRegionTarget($aRegionTarget)
  {
    $rawAnswer = array_search($aRegionTarget,$this->regionTarget);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }


  public function getExcludeIp()
  {
    $newExcludeIp = $this->excludeIp;
    return $newExcludeIp;
  }

  public function numberOfExcludeIp()
  {
    $number = count($this->excludeIp);
    return $number;
  }

  public function indexOfExcludeIp($aExcludeIp)
  {
    $rawAnswer = array_search($aExcludeIp,$this->excludeIp);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }


  public function getOpenDomains()
  {
    $newOpenDomains = $this->openDomains;
    return $newOpenDomains;
  }

  public function numberOfOpenDomains()
  {
    $number = count($this->openDomains);
    return $number;
  }

  public function indexOfOpenDomain($aOpenDomain)
  {
    $rawAnswer = array_search($aOpenDomain,$this->openDomains);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getBudgetType()
  {
    return $this->budgetType;
  }

  public function getRegDomain()
  {
    return $this->regDomain;
  }


  public function getWeeklyBudget()
  {
    $newWeeklyBudget = $this->weeklyBudget;
    return $newWeeklyBudget;
  }

  public function numberOfWeeklyBudget()
  {
    $number = count($this->weeklyBudget);
    return $number;
  }

  public function indexOfWeeklyBudget($aWeeklyBudget)
  {
    $rawAnswer = array_search($aWeeklyBudget,$this->weeklyBudget);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getUserStat()
  {
    return $this->userStat;
  }


  public function getBudgetOfflineTime()
  {
    $newBudgetOfflineTime = $this->budgetOfflineTime;
    return $newBudgetOfflineTime;
  }

  public function numberOfBudgetOfflineTime()
  {
    $number = count($this->budgetOfflineTime);
    return $number;
  }

  public function indexOfBudgetOfflineTime($aBudgetOfflineTime)
  {
    $rawAnswer = array_search($aBudgetOfflineTime,$this->budgetOfflineTime);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getIsDynamicCreative()
  {
    return $this->isDynamicCreative;
  }

  public function getDynamicCreativeParam()
  {
    return $this->dynamicCreativeParam;
  }

  public function getIsDynamicTagSublink()
  {
    return $this->isDynamicTagSublink;
  }

  public function getIsDynamicTitle()
  {
    return $this->isDynamicTitle;
  }

  public function getIsDynamicHotRedirect()
  {
    return $this->isDynamicHotRedirect;
  }

  public function isIsDynamicCreative()
  {
    return $this->isDynamicCreative;
  }

  public function isIsDynamicTagSublink()
  {
    return $this->isDynamicTagSublink;
  }

  public function isIsDynamicTitle()
  {
    return $this->isDynamicTitle;
  }

  public function isIsDynamicHotRedirect()
  {
    return $this->isDynamicHotRedirect;
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

class UpdateAccountInfoResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateAccountInfoResponse Attributes
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

class UpdateAccountInfoRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //UpdateAccountInfoRequest Attributes
  public $accountInfo;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function setAccountInfo($aAccountInfo)
  {
    $wasSet = false;
    $this->accountInfo = $aAccountInfo;
    $wasSet = true;
    return $wasSet;
  }

  public function getAccountInfo()
  {
    return $this->accountInfo;
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

class GetAccountInfoRequest
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetAccountInfoRequest Attributes
  public $accountFields;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------
   public function setAccountFields($aaccountFields) {
       $this->accountFields = $aaccountFields;
   }

  public function addAccountField($aAccountField)
  {
    $wasAdded = false;
    $this->accountFields[] = $aAccountField;
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeAccountField($aAccountField)
  {
    $wasRemoved = false;
    unset($this->accountFields[$this->indexOfAccountField($aAccountField)]);
    $this->accountFields = array_values($this->accountFields);
    $wasRemoved = true;
    return $wasRemoved;
  }


  public function getAccountFields()
  {
    $newAccountFields = $this->accountFields;
    return $newAccountFields;
  }

  public function numberOfAccountFields()
  {
    $number = count($this->accountFields);
    return $number;
  }

  public function indexOfAccountField($aAccountField)
  {
    $rawAnswer = array_search($aAccountField,$this->accountFields);
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

class GetAccountInfoResponse
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //GetAccountInfoResponse Attributes
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

class sms_service_AccountService extends CommonService 
{    public function __construct() {
        parent::__construct ( 'sms', 'service', 'AccountService' );
    }

  // ABSTRACT METHODS 

 public function getAccountInfo ($getAccountInfoRequest){
 return $this->execute ( 'getAccountInfo', $getAccountInfoRequest );
}
 public function updateAccountInfo ($updateAccountInfoRequest){
 return $this->execute ( 'updateAccountInfo', $updateAccountInfoRequest );
}
  
}


?>