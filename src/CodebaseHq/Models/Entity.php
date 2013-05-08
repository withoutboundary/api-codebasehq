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
			if (array_key_exists($key, $properties))
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
	* Unserialises, from XML, a single object into this Entity
	* 
	* @param mixed xml string or \SimpleXMLElement
	*/
	static public function unserialize($xml)
	{
		if (is_string($xml))
		{
			$object = simplexml_load_string($xml);
		}
		elseif ('SimpleXMLElement' === get_class($xml))
		{
			$object = $xml;
		}
		else
		{
			throw new \RuntimeException('Invalid parameter $xml, should be string or SimpleXMLElement');
		}
		
		$calledClass = get_called_class();
		$class = current(array_reverse(explode('\\', strtolower($calledClass))));
		
		if (strtolower(static::$entityName) === strtolower($object->getName()))
		{
			$properties = array();
			foreach (static::$propertyList as $key)
			{
				$translatedKey = static::translatePropertyKey($key);
				if (isset($object->{$translatedKey}))
				{
					if (isset($object->{$translatedKey}['nil']) && 'true' === strtolower($object->{$translatedKey}['nil']))
					{
						$properties[$key] = null;
					}
					elseif ((isset($object->{$translatedKey}['type']) && 'integer' === strtolower($object->{$translatedKey}['type'])))
					{
						$properties[$key] = (int) $object->{$translatedKey};
					}
					else
					{
						$properties[$key] = (string) $object->{$translatedKey};
					}
				}
			}
			
			return new $calledClass($properties);
		}
		
		return null;
	}
	
	static public function unserializeList($xml, $root)
	{
		$xml = simplexml_load_string($xml);
		$list = array();
		
		foreach ($xml as $entity)
		{
			$list[] = static::unserialize($entity);
		}
		return $list;
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
