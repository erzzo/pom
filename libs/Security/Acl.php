<?php
namespace Fleetlog;

use Nette\Security\Permission;

class Acl
{
	/**
	 * @return \Nette\Security\Permission
	 */
	public function createPermission()
	{
		$permission = new Permission();
		$permission->addRole('student');
		$permission->addRole('teacher');

		$permission->addResource('AdminModule:');
		// V basepresenteri
		/*Pravidla opravneni*/

		//$permission->deny('driver', ['Main:Vehicles:Overview,Main:Vehicles']);
		$permission->allow('teacher','AdminModule:');


		return $permission;
	}



}