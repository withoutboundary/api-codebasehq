<?php

namespace CodebaseHq\Models;

use \CodebaseHq\Api as Api;
use \CodebaseHq\Models\Entity as Entity;

class Project extends Entity
{
	protected $entityName = 'project';
	protected $properties = array(
		'name',
		'permalink',
		'groupId',
		'overview',
		'startPage',
		'status',
		'icon',
	);
	
	public function __construct($properties)
	{
	}
	
	static public function getAll(Api $api, $conditions)
	{
	}
	
	static public function get(Api $api, $conditions)
	{
	}
	
	static public function create(Api $api, Project $project)
	{
	}
	
	static public function delete(Api $api, Project $project)
	{
	}
}