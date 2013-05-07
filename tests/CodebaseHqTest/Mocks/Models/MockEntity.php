<?php

namespace CodebaseHqTest\Mocks\Models;

use CodebaseHq\Models\Entity as Entity;

class MockEntity extends Entity
{
	static protected $entityName = 'mock-entity';
	static protected $propertyList = array('foo', 'bar', 'cake');
}
