<?php

namespace Models;

class Task extends Base
{
	public function getTasks($themeId)
	{
		$tasks = $this->getAll()->order('name ASC');

		if (!is_null($themeId)) {
			$tasks->where('theme_id', $themeId);
		}
		return $tasks;
	}

	public function addEdit($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->getTable()->insert($values);
		} else {
			$task = $this->getTable()->get($id);
			return $task->update($values);
		}
	}

	public function markDone($id)
	{
		$this->findBy(array('id' => $id))->update(array('grade' => 1));

	}
}