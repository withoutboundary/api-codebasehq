<?php

namespace CodebaseHqTest\Models;

use CodebaseHq\CodebaseHqApi;
use CodebaseHq\Models\Project;
use CodebaseHqTest\Utility\FixtureLoader;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
	protected $api;
	
	public function setUp()
	{
		$this->api = $this->getMock('CodebaseHq\CodebaseHqApi');
	}
	
	public function testGetList()
	{
		$this->api->expects($this->any())
							->method('request')
							->with('projects', CodebaseHqApi::METHOD_GET)
							->will($this->returnValue(FixtureLoader::load('Models/Project/projects.xml')));
		
		$projects = Project::getList($this->api);
		
		$this->assertInternalType('array', $projects);
		$this->assertCount(1, $projects);
		
		$project = current($projects);
		
		$this->assertInstanceOf('CodebaseHq\Models\Project', $project);
		$this->assertSame(50569, $project->projectId);
		$this->assertNull($project->groupId);
		$this->assertSame(1, $project->icon);
		$this->assertSame('Project 1', $project->name);
		$this->assertNull($project->overview);
		$this->assertSame('active', $project->status);
		$this->assertSame('project-1', $project->permalink);
	}
}
