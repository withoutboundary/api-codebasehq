<?php

namespace CodebaseHq\Models;

use \CodebaseHq\CodebaseHqApi;
use \CodebaseHq\Models\Entity;

class Project extends Entity
{
	static protected $entityName = 'project';
	static protected $propertyList = array(
		'projectId',
		'name',
		'permalink',
		'groupId',
		'overview',
		'startPage',
		'status',
		'icon',
	);
	
	static public function getList(CodebaseHqApi $api)
	{
		$response = $api->request('projects', CodebaseHqApi::METHOD_GET);
		
		$list = static::unserializeList($response, 'projects');
		
		return $list;
	}
	
	static public function create(CodebaseHqApi $api, Project $project)
	{
	}
	
	static public function read(CodebaseHqApi $api, $permalink)
	{
	}
	
	static public function delete(CodebaseHqApi $api, Project $project)
	{
	}
}