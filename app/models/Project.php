<?php

namespace Models;

class Project extends Base
{
	public function getProjects()
	{
		return $this->getAll()->order('created DESC, name ASC');
	}

	public function addEdit($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->getTable()->insert($values);
		} else {
			$project = $this->getTable()->get($id);
			return $project->update($values);
		}
	}
}