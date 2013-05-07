<?php

namespace CodebaseHqTest\Models;

use CodebaseHq\Models\Entity as Entity;
use CodebaseHqTest\Mocks\Models\MockEntity as MockEntity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
	public function testTranslatePropertyKey()
	{
		$keys = array(
			'foo' => 'foo',
			'bAr' => 'b-ar',
			'fooBar' => 'foo-bar',
			'BAR' => 'b-a-r',
		);
		
		foreach ($keys as $before => $after)
		{
			$this->assertEquals($after, Entity::translatePropertyKey($before));
		}
	}
	
	public function testSerialize()
	{
		$stub = $this->getMockForAbstractClass('CodebaseHq\Models\Entity');
		
		$this->assertEquals('<entity></entity>', $stub->serialize());
		
		$refStub = new \ReflectionObject($stub);
		$refEntity = $refStub->getProperty('entityName');
		$refEntity->setAccessible(true);
		$refEntity->setValue($stub, 'foobar');
		
		$this->assertEquals('<foobar></foobar>', $stub->serialize());
		
		$refProperties = $refStub->getProperty('properties');
		$refProperties->setAccessible(true);
		$refProperties->setValue($stub, array(
			'foo' => 'bar',
			'thisThat' => 'cake',
			'one' => 1,
		));
		
		$this->assertEquals('<foobar><foo><![CDATA[bar]]></foo><this-that><![CDATA[cake]]></this-that><one><![CDATA[1]]></one></foobar>', $stub->serialize());
	}
	
	public function testUnserialize()
	{
		$expectingNull = <<<EOF
<wrong>
	<foo>bar</foo>
</wrong>
EOF;
		
		$expectingEntity = <<<EOF
<mock-entity>
	<foo>bar</foo>
</mock-entity>
EOF;
		
		$expectingEntityWithOnlyFoo = <<<EOF
<mock-entity>
	<foo>bar</foo>
	<cake>lie</cake>
</mock-entity>
EOF;
		
		$this->assertEquals(null, MockEntity::unserialize($expectingNull));
		
		$unserialised = MockEntity::unserialize($expectingEntity);
		
		$this->assertInstanceOf('CodebaseHqTest\Mocks\Models\MockEntity', $unserialised);
		$this->assertEquals('bar', $unserialised->foo);
		
		$unserialised = MockEntity::unserialize($expectingEntityWithOnlyFoo);
		
		$this->assertInstanceOf('CodebaseHqTest\Mocks\Models\MockEntity', $unserialised);
		$this->assertEquals('bar', $unserialised->foo);
		$this->assertObjectNotHasAttribute('cake', $unserialised);
	}
	
	public function testSetProperties()
	{
		$less = array(
			'foo' => 'foo value',
		);
		$same = array(
			'foo' => 'foo value 2',
			'bar' => 'bar value',
			'cake' => 'lie',
		);
		$more = array(
			'foo' => 'foo value 3',
			'bar' => 'bar value 2',
			'cake' => 'lie 2',
			'extra' => 'what',
		);
		
		$sameEntity = new MockEntity($same);
		$this->assertEquals($same['foo'], $sameEntity->foo);
		$this->assertEquals($same['bar'], $sameEntity->bar);
		$this->assertEquals($same['cake'], $sameEntity->cake);
		
		$lessEntity = new MockEntity($less);
		$this->assertEquals($less['foo'], $lessEntity->foo);
		
		$moreEntity = new MockEntity($more);
		$this->assertEquals($more['foo'], $moreEntity->foo);
		$this->assertEquals($more['bar'], $moreEntity->bar);
		$this->assertEquals($more['cake'], $moreEntity->cake);
		
		$this->setExpectedException('PHPUnit_Framework_Error');
		$this->assertNull($lessEntity->bar);
		$this->assertNull($lessEntity->cake);
		$this->assertNull($moreEntity->extra);
	}
}
