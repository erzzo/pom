<?php
namespace Models;

class Subject extends Base
{
	public function getSubjects()
	{
		return $this->getAll()->order('school_year.year DESC, name ASC');
	}

	public function getSchoolYears()
	{
		return $this->db->table('school_year');
	}

	public function addEdit($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->getTable()->insert($values);
		} else {
			$subject = $this->getTable()->get($id);
			return $subject->update($values);
		}
	}
}