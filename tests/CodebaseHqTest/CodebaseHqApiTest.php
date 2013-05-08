<?php

namespace CodebaseHqTest;

use CodebaseHq\CodebaseHqApi;

class CodebaseHqApiTest extends \PHPUnit_Framework_TestCase
{
	protected $connectionOptions = array(
		'username' => 'wb-api-codebasehq/api-api-38',
		'token' => 'f43b452dd947c63066113ff2ac33cc371cc83d5d',
		'https' => true,
		'baseUrl' => 'api3.codebasehq.com',
	);
	protected $api;
	
	public function setUp()
	{
		$this->api = new CodebaseHqApi($this->connectionOptions);
	}
	
	public function testCheckOptionsChecksForOptionsStructure()
	{
		$this->setExpectedException('RuntimeException');
		CodebaseHqApi::checkOptions(array(
			'invalidKey' => 'value',
		));
	}
	
	public function testCheckOptionsUsernameShouldBeNotEmpty()
	{
		$this->setExpectedException('RuntimeException');
		CodebaseHqApi::checkOptions(array(
			'username' => '',
			'token' => 'not empty',
			'https' => true,
			'baseUrl' => 'example.com',
		));
	}
	
	public function testCheckOptionsTokenShouldBeNotEmpty()
	{
		$this->setExpectedException('RuntimeException');
		CodebaseHqApi::checkOptions(array(
			'username' => 'not empty',
			'token' => '',
			'https' => true,
			'baseUrl' => 'example.com',
		));
	}
	
	public function testCheckOptionsHttpsShouldBeNotEmpty()
	{
		$this->setExpectedException('RuntimeException');
		CodebaseHqApi::checkOptions(array(
			'username' => 'not empty',
			'token' => 'not empty',
			'https' => 'not boolean',
			'baseUrl' => 'example.com',
		));
	}
	
	public function testCheckOptionsBaseUrlShouldBeNotEmpty()
	{
		$this->setExpectedException('RuntimeException');
		CodebaseHqApi::checkOptions(array(
			'username' => 'not empty',
			'token' => 'not empty',
			'https' => true,
			'baseUrl' => '',
		));
	}
	
	public function testCheckOptionsBaseUrlShouldBeParseableUrl()
	{
		try
		{
			CodebaseHqApi::checkOptions(array(
				'username' => 'not empty',
				'token' => 'not empty',
				'https' => true,
				'baseUrl' => 'example.com',
			));
		}
		catch (\RuntimeException $e)
		{
			$this->fail('Not expecting a RuntimeException for a baseUrl of "example.com"');
		}
		
		try
		{
			CodebaseHqApi::checkOptions(array(
				'username' => 'not empty',
				'token' => 'not empty',
				'https' => true,
				'baseUrl' => 'http://example.com',
			));
		}
		catch (\RuntimeException $e)
		{
			$this->fail('Not expecting a RuntimeException for a baseUrl of "http://example.com"');
		}
		
		try
		{
			CodebaseHqApi::checkOptions(array(
				'username' => 'not empty',
				'token' => 'not empty',
				'https' => true,
				'baseUrl' => 'http://example.com:80',
			));
		}
		catch (\RuntimeException $e)
		{
			$this->fail('Not expecting a RuntimeException for a baseUrl of "http://example.com:80"');
		}
		
		try
		{
			CodebaseHqApi::checkOptions(array(
				'username' => 'not empty',
				'token' => 'not empty',
				'https' => true,
				'baseUrl' => 'http://user:pass@example.com:80',
			));
		}
		catch (\RuntimeException $e)
		{
			$this->fail('Not expecting a RuntimeException for a baseUrl of "http://user:pass@example.com:80"');
		}
		
		$exception = false;
		try
		{
			CodebaseHqApi::checkOptions(array(
				'username' => 'not empty',
				'token' => 'not empty',
				'https' => true,
				'baseUrl' => 'example.com/foo',
			));
		}
		catch (\RuntimeException $e)
		{
			$exception = true;
		}
		$this->assertTrue($exception, 'A RuntimeException should be thrown for "example.com/foo", there should be no path');
	}
	
	/*public function testRequest()
	{
	}*/
	
	/**
	* This should not throw any exceptions
	*/
	public function testCheckOptions()
	{
		$this->assertTrue(CodebaseHqApi::checkOptions(array(
			'username' => 'not empty',
			'token' => 'not empty',
			'https' => true,
			'baseUrl' => 'example.com',
		)));
	}
}