<?php
namespace Models;

class Subject extends Base
{
	public function isInSubject($subjectId, $userId)
	{
		return $this->db->table('user_subject')->where('subject_id', $subjectId)->where('user_id',$userId)->fetch();
	}
	public function getSubjects($gradeId = NULL)
	{
		$subjects = $this->getAll();
		if ($gradeId) {
			return $subjects->where('grade_id',$gradeId)->order('school_year.year DESC, name ASC');
		} else {
			return $subjects->order('school_year.year DESC, name ASC');
		}
	}

	public function getUserSubjects()
	{
		return $this->db->table('user_subject')->where('user_id', $this->user->getId())->order('subject.name ASC');
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
			$this->addUserToSubject($subject->id);
			return $subject;
		} else {
			$subject = $this->getTable()->get($id);
			return $subject->update($values);
		}
	}

	public function addUserToSubject($subjectId)
	{
		return $this->db->table('user_subject')->insert(array('user_id' => $this->user->getId(), 'subject_id' => $subjectId));
	}
}