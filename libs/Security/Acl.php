<?php
namespace Security;

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
		$permission->addRole('ucitel');
		// V basepresenteri
		/*Pravidla opravneni*/
		$permission->addResource('Main:Subject');
		$permission->addResource('Main:Task');
		$permission->addResource('Main:Theme');
		$permission->addResource('Main:Mentor:Subject');
		$permission->addResource('Main:Mentor:Task');
		$permission->addResource('Main:Mentor:Project');
		$permission->addResource('Main:Mentor:Theme');
		$permission->addResource('admin');
		$permission->allow('student',['Main:Subject','Main:Task','Main:Theme']);

		$permission->allow('ucitel');
		return $permission;
	}
}