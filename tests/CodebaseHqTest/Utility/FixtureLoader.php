<?php

namespace CodebaseHqTest\Utility;

class FixtureLoader
{
	/**
	* Gets and returns the contents of a file in Fixtures/
	* 
	* eg. 'Models/project/projects.xml'
	* 
	* @param string $fixture
	*/
	static public function load($fixture)
	{
		$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . $fixture;
		
		if (file_exists($filename) && is_readable($filename))
		{
			return file_get_contents($filename);
		}
		
		throw new \RuntimeException("Unable to find or read fixture '{$fixture}' from '{$filename}'");
	}
}
