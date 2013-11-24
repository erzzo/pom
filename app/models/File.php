<?php

namespace Models;

class File extends Base
{
	//TODO: dokoncit pre theme
	public function getFiles($taskId)
	{
		$files= $this->getAll()->order('name ASC');

		if (!is_null($taskId)) {
			$files->where('task_id', $taskId);
		}
		return $files;
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
}