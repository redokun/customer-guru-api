<?php

namespace CustomerGuruTest;

use CustomerGuru\CustomerGuru;

/**
 * Class CustomerGuruTest
 * Date: 12/09/2016
 *
 * @author Paolo Agostinetto <paolo@redokun.com>
 */
class CustomerGuruTest extends \PHPUnit_Framework_TestCase {

	public function testOk(){

		$cred = include __DIR__."/../credentials.php";

		$guru = new CustomerGuru(
			$cred["apiKey"],
			$cred["apiSecret"],
			true
		);

		$email = sprintf('email+rand%d@example.com', mt_rand(100, 999));

		$this->assertFalse(
			$guru->sendSurvey($email)
		);
	}

	public function testWithSpecificDate(){

		$cred = include __DIR__."/../credentials.php";

		$guru = new CustomerGuru(
			$cred["apiKey"],
			$cred["apiSecret"],
			true
		);

		$email = sprintf('email+rand%d@example.com', mt_rand(100, 999));

		$this->assertFalse(
			$guru->sendSurvey($email, new \DateTime('now'))
		);
	}

	/**
	 * @expectedException \CustomerGuru\Exception\ServiceException
	 */
	public function testInvalidCredentials(){

		$guru = new CustomerGuru(
			"XXX",
			"XXX",
			true
		);

		$email = sprintf('email+rand%d@example.com', mt_rand(100, 999));

		$guru->sendSurvey($email, new \DateTime('now'));
	}

	public function testInvalidEmail(){

		$cred = include __DIR__."/../credentials.php";

		$guru = new CustomerGuru(
			$cred["apiKey"],
			$cred["apiSecret"],
			true
		);

		$this->assertFalse(
			$guru->sendSurvey("not-an-email", new \DateTime('now'))
		);
	}
}