<?php

namespace CustomerGuru;

use CustomerGuru\Exception\ServiceException;
use Zend\Http\Client;

/**
 * CustomerGuru client
 * Date: 12/09/2016
 *
 * @author Paolo Agostinetto <paolo@redokun.com>
 */
class CustomerGuru {

	const VERSION = '1.0';

	/**
	 * @var string
	 */
	protected $apiKey;

	/**
	 * @var string
	 */
	protected $apiSecret;

	/**
	 * @var bool
	 */
	protected $testMode;

	/**
	 * CustomerGuru constructor.
	 *
	 * @param string $apiKey
	 * @param string $apiSecret
	 */
	public function __construct($apiKey, $apiSecret, $testMode = false){
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->testMode = $testMode;
	}

	/**
	 * Create a new contact
	 *
	 * @param string|array $email
	 * @param \DateTime|null $when
	 * @return bool
	 * @throws ServiceException
	 */
	public function createContact($email, \DateTime $when = null){

		// Email
		if(is_array($email)){
			$emailStr = implode(",", $email);
		}
		else{
			$emailStr = $email;
		}

		// Date
		if($when){
			$dateStr = $when->format("c");
		}
		else{
			$dateStr = 'now';
		}

		$postData = [
			'api_token' => $this->apiKey,
			'api_secret' => $this->apiSecret,
			'emails' => $emailStr,
			'scheduled_for' => $dateStr,
		];

		if($this->testMode){
			$postData["test"] = "true";
		}

		// Call the API
		$client = new Client('https://customer.guru/api/v1/survey', [
			'timeout' => 60,
			'adapter' => 'Zend\Http\Client\Adapter\Curl',
			'useragent' => sprintf('Redokun CustomerGuru PHPClient/%s', self::VERSION),
		]);
		$client->setMethod("POST");
		$client->setParameterPost($postData);

		$response = $client->send();

		if($response->getStatusCode() != 200){
			throw new ServiceException(sprintf("An error occured, service replied with non-ok response (HTTP %d)",
				$response->getStatusCode()
			));
		}

		$body = $response->getBody();

		$data = json_decode($body, true);

		if($data["status"] != "OK" || (!isset($data["test"]) && $data["failed_to_send"])){
			throw new ServiceException("Failed to create customer");
		}

		return $data["successfully_sent"] == "1";
	}

	/**
	 * @return string
	 */
	public function getApiKey(){
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 */
	public function setApiKey($apiKey){
		$this->apiKey = $apiKey;
	}

	/**
	 * @return string
	 */
	public function getApiSecret(){
		return $this->apiSecret;
	}

	/**
	 * @param string $apiSecret
	 */
	public function setApiSecret($apiSecret){
		$this->apiSecret = $apiSecret;
	}

}