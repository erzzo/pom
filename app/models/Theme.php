<?php

namespace Models;

class Theme extends Base
{
	public function getThemes($projectId)
	{
		$projects = $this->getAll()->order('name ASC');

		if (!is_null($projectId)) {
			$projects->where('project_id', $projectId);
		}
		return $projects;
	}

	public function addEdit($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->getTable()->insert($values);
		} else {
			$theme = $this->getTable()->get($id);
			return $theme->update($values);
		}
	}
}