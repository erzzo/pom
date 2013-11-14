<?php
namespace Models;

class Subject extends Base
{
	public function getSubjects()
	{
		return $this->getAll()->order('school_year.year DESC, name ASC');
	}

	public function getUserSubjects()
	{
		return $this->db->table('user_subject')->order('subject.name ASC');
	}

	public function getSchoolYears()
	{
		return $this->db->table('school_year')->order('year DESC');
	}

	public function getTerms()
	{
		return $this->db->table('term');
	}

	public function getGrades()
	{
		return $this->db->table('grade');
	}

	public function addEdit($values, $id = NULL)
	{
		if(is_null($id)) {
			$subject = $this->getTable()->insert($values);
			$this->db->table('user_subject')->insert(['user_id' => $this->user->getId(), 'subject_id' => $subject->id]);
			return $subject;
		} else {
			$subject = $this->getTable()->get($id);
			return $subject->update($values);
		}
	}
}