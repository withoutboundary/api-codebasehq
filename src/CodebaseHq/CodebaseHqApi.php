<?php

namespace CodebaseHq;

class CodebaseHqApi
{
	protected $defaults = array(
		'username' => '',
		'token' => '',
		'https' => true,
		'baseUrl' => 'api3.codebasehq.com',
	);
	protected $options = array();
	
	public function __construct($options = array())
	{
		$this->setOptions($options);
	}
	
	public function setOptions($options = array())
	{
		$options = $this->mergeOptions($options);
		if ($this->checkOptions($options))
		{
			$this->options = $options;
		}
	}
	
	/**
	* Merges the provided options into the defaults array
	* 
	* @param mixed $options
	*/
	public function mergeOptions($options = array())
	{
		return array_merge($this->defaults, $options);
	}
	
	/**
	* Checks if a provided array contains all the options this class requires
	* 
	* @param mixed $options
	* @throws \RuntimeException when there's a missing option
	*/
	static public function checkOptions($options = array())
	{
		switch (true)
		{
			//case array_intersect_key( 
			case empty($options['username']):
				throw new \RuntimeException('Username is a required option');
			case empty($options['token']):
				throw new \RuntimeException('Token is a required option');
			case !is_bool($options['https']):
				throw new \RuntimeException('Https must be a boolean');
			case empty($options['baseUrl']) || (!empty($options['baseUrl']) && !static::validBaseUrl($options['baseUrl'])):
				throw new \RuntimeException('Base URL must be a valid parseable URL for host');
		}
		
		return true;
	}
	
	/**
	* Checks if a provided "URL" is valid, in that it:
	* * Can be parsed with parse_url
	* * Has "host" with an empty "path"
	* * Otherwise an empty "scheme", non-empty "path", and path does not include a "/"
	* 
	* @param mixed $url
	*/
	static public function validBaseUrl($url)
	{
		$url = parse_url($url);
		
		if ($url !== false && is_array($url))
		{
			if (!empty($url['host']) && empty($url['path']))
			{
				return true;
			}
			elseif (empty($url['scheme']) && !empty($url['path']) && stripos($url['path'], '/') === false)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function request()
	{
		
	}
}
