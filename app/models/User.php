<?php

namespace Models;

class User extends Base
{
	public function getFreeStudentsForTheme($projectId, $query)
	{
		$project = $this->db->table('project')->get($projectId);

		$allUsersInSubject = $this->db->table('user_subject')->where('subject_id', $project->subject_id)->fetchPairs('id', 'user_id');

		return $this->getAll()->select('id, CONCAT(firstname, \' \', lastname) AS name')
			->where('role_id', 2)
			->where('id', $allUsersInSubject)
			->where('CONCAT(firstname, \' \', lastname) LIKE ? OR CONCAT(lastname, \' \', firstname) LIKE ?', '%' . $query . '%', '%' . $query . '%')
			->fetchPairs('id', 'name');
	}
}