<?php

namespace CodebaseHq\Models;

abstract class Entity
{
	static protected $entityName = 'entity';
	static protected $propertyList = array();
	
	protected $properties = array();
	
	public function __construct($properties = array())
	{
		$this->setProperties($properties);
	}
	
	/**
	* Sets the properties on this object based on its property list only
	* 
	* @param array $properties
	*/
	public function setProperties($properties = array())
	{
		foreach (static::$propertyList as $key)
		{
			if (isset($properties[$key]))
			{
				$this->properties[$key] = $properties[$key];
			}
		}
		
		return true;
	}
	
	/**
	* Overloaded __get for entity properties
	* 
	* @param mixed $name
	*/
	public function __get($name = null)
	{
		if (array_key_exists($name, $this->properties))
		{
			return $this->properties[$name];
		}
		
		$trace = debug_backtrace(1);
		trigger_error('Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
	}
	
	/**
	* Serialises the entity into XML for Codebase
	*/
	public function serialize()
	{
		$xml = array();
		
		$entityName = static::$entityName;
		
		$xml[] = "<{$entityName}>";
		foreach ($this->properties as $key => $value)
		{
			$key = static::translatePropertyKey($key);
			$xml[] = "<{$key}><![CDATA[{$value}]]></{$key}>";
		}
		$xml[] = "</{$entityName}>";
		
		return implode('', $xml);
	}
	
	/**
	* Unserialises, from XML, into this Entity
	* 
	* @param string xml
	*/
	static public function unserialize($xml = null)
	{
		$object = simplexml_load_string($xml);
		
		$calledClass = get_called_class();
		$class = current(array_reverse(explode('\\', strtolower($calledClass))));
		
		if (strtolower(static::$entityName) === strtolower($object->getName()))
		{
			$properties = array();
			foreach (static::$propertyList as $key)
			{
				if (isset($object->{$key}))
				{
					$properties[$key] = (string) $object->{$key};
				}
			}
			
			return new $calledClass($properties);
		}
		
		return null;
	}
	
	/**
	* Translates a property key into its CodebaseHQ XML equivalent
	* 
	* eg. fooBar becomes foo-bar
	* 
	* @param mixed $key
	*/
	static public function translatePropertyKey($key)
	{
		return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '-\\1', $key));
	}
}
