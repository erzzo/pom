<?php

namespace Models;

abstract class Base extends \Nette\Object
{
	/** @var Nette\Database\Connection */
	protected $db;
	protected $user;

	public function __construct(\Nette\Database\Connection $db, \Nette\Security\User $user)
	{
		$this->db = $db;
		$this->user = $user;
	}

	protected function getTable()
	{
		preg_match('#(\w+)$#', get_class($this), $m);
		return $this->db->table(lcfirst($m[1]));
	}

	public function get($id)
	{
		return $this->getTable()->get($id);
	}

	public function getAll()
	{
		return $this->getTable();
	}

	public function findBy(array $by)
	{
		return $this->getTable()->where($by);
	}

	public function update($id, $values)
	{
		return $this->getTable()->get($id)->update($values);
	}

	public function insert($values)
	{
		return $this->getTable()->insert($values);
	}

}