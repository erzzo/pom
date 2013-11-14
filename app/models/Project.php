<?php

namespace Models;

class Project extends Base
{

	public function getProjects($subjectId = NULL)
	{
		$subjects = $this->getAll()->order('name ASC');

		if (!is_null($subjectId)) {
			$subjects->where('subject_id', $subjectId);
		}

		return $subjects;
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