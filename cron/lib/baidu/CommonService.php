<?php

//define ( 'URL', 'http://tjyx-testing-ecombh54.vm.baidu.com:8443' );


define ( 'URL', 'https://api.baidu.com' );
//USERNAME
define ( 'USERNAME', '' );

//PASSWORD
define ( 'PASSWORD', '' );

//TOKEN
define ( 'TOKEN', '' );

//TARGET
define ( 'TARGET', '' );

//ACCESSTOKEN
define( 'ACCESSTOKEN' , '');

class CommonService {
	public $productline;
	public $version;
	public $serviceName;
	
	public $serviceurl = URL;
	public $authHeader;
	
	public $isJson = true;
	public $soapClient;
	public $soap_headers = array ();
	public $json_result;
	public $json_string;
	/**
	 * @return unknown
	 */
	public function getIsJson() {
		return $this->isJson;
	}
	
	/**
	 * @param unknown_type $isJson
	 */
	public function setIsJson($isJson) {
		$this->isJson = $isJson;
	}
	
	/**
	 * @return unknown
	 */
	public function getAuthHeader() {
		return $this->authHeader;
	}
	
	/**
	 * @return unknown
	 */
	public function getServiceurl() {
		return $this->serviceurl;
	}
	
	/**
	 * @param unknown_type $authHeader
	 */
	public function setAuthHeader($authHeader) {
		$this->authHeader = $authHeader;
	}
	
	/**
	 * @param unknown_type $serviceurl
	 */
	public function setServiceurl($serviceurl) {
		$this->serviceurl = $serviceurl;
	}

	//public $url;
	public function __construct($productline, $version, $serviceName) {
		$this->productline = $productline;
		$this->version = $version;
		$this->serviceName = $serviceName;
		
		$this->authHeader = new AuthHeader ( );
		$this->authHeader->setUsername ( USERNAME );
		$this->authHeader->setPassword ( PASSWORD );
		$this->authHeader->setToken ( TOKEN );
		$this->authHeader->setTarget ( TARGET );
		$this->authHeader->setAccessToken( ACCESSTOKEN );
	
	}
	
	public function executeJson($method, $request) {
		$ch = curl_init ();
		
		$url = $this->serviceurl . '/json/' . $this->productline . '/' . $this->version . '/' . $this->serviceName . '/' . $method;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POST, true );

		$jsonEv = new JsonEnvelop ( );
		
		$jsonEv->setBody ( $request );
		$jsonEv->setHeader ( $this->authHeader );
		$data = json_encode ( $jsonEv );
		//echo "the data is: " . $data . "\n";
		Log::write("access","request:".$data);
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data ); //$data是每个接口的json字符串
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); //不加会报证书问题
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); //不加会报证书问题
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json; charset=utf-8' ) );
		
		$this->json_string = curl_exec ( $ch );
		curl_close ($ch );
		$this->json_result = json_decode ( $this->json_string );
		return @$this->json_result->body;
	}
	public function executeSoap($method, $request) {
		$this->soapClient = new SoapClient ( $this->serviceurl . '/sem/' . $this->productline . '/' . $this->version . '/' . $this->serviceName . '?wsdl', array ('trace' => TRUE, 'connection_timeout' => 30 ) );
		
		// Prepare SoapHeader parameters 
		$sh_param = array ('username' => $this->authHeader->getUsername(), 'password' => $this->authHeader->getPassword(), 'token' => $this->authHeader->getToken(), 'target' => $this->authHeader->getTarget(),'accesstoken' => $this->authHeader->getAccesstoken() );
		$headers = new SoapHeader ( 'http://api.baidu.com/sem/common/v2', 'AuthHeader', $sh_param );
		
		// Prepare Soap Client 
		$this->soapClient->__setSoapHeaders ( array ($headers ) );
		$arguments = array (get_class ( $request ) => $request );
		
		return $this->soapClient->__soapCall ( $method, $arguments, null, null, $this->soap_headers );
	}
	public function execute($method, $request) {
		if ($this->isJson) {
			return $this->executeJson ( $method, $request );
		} else {
			return $this->executeSoap ( $method, $request );
		}
	}
	public function getSoapHeader() {
		return $this->soap_headers;
	}
	public function getJsonHeader() {
		return $this->json_result->header;
	}
	public function getJsonEnv() {
		return $this->json_result;
	}
	public function getJsonStr() {
		return $this->json_string;
	}
}
class JsonEnvelop {
	public $header;
	public $body;
	
	/**
	 * @return unknown
	 */
	public function getBody() {
		return $this->body;
	}
	
	/**
	 * @return unknown
	 */
	public function getHeader() {
		return $this->header;
	}
	
	/**
	 * @param unknown_type $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}
	
	/**
	 * @param unknown_type $header
	 */
	public function setHeader($header) {
		$this->header = $header;
	}

}
class AuthHeader {
	public $username;
	public $password;
	public $target;
	public $token;
	public $action = "API-SDK";
	public $accessToken;
	public $account_type;
	
	/**
	 * @return unknown
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * @param unknown_type $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}
	/**
	 * @return unknown
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}
	
	/**
	 * @return unknown
	 */
	public function getAccount_type() {
		return $this->account_type;
	}
	
	/**
	 * @return unknown
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @return unknown
	 */
	public function getTarget() {
		return $this->target;
	}
	
	/**
	 * @return unknown
	 */
	public function getToken() {
		return $this->token;
	}
	
	/**
	 * @return unknown
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param unknown_type $accessToken
	 */
	public function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;
	}
	
	/**
	 * @param unknown_type $account_type
	 */
	public function setAccount_type($account_type) {
		$this->account_type = $account_type;
	}
	
	/**
	 * @param unknown_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * @param unknown_type $target
	 */
	public function setTarget($target) {
		$this->target = $target;
	}
	
	/**
	 * @param unknown_type $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}
	
	/**
	 * @param unknown_type $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

}
?>