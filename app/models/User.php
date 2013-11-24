<?php

namespace Models;

class User extends Base
{
	public function getFreeStudentsForTheme($projectId, $query)
	{
		$project = $this->db->table('project')->get($projectId);

		$allUsersInSubject = $this->db->table('user_subject')->where('subject_id', $project->subject_id)->fetchPairs('id', 'user_id');
		$usersAssignedToProjectTheme = $this->db->table('theme_user')->where('theme.project_id', $projectId)->fetchPairs('id', 'user_id');

		$usersAllowedToAssign = array_diff($allUsersInSubject, $usersAssignedToProjectTheme);

		return $this->getAll()->select('id, CONCAT(firstname, \' \', lastname) AS name')
			->where('role_id', 2)
			->where('id', $usersAllowedToAssign)
			->where('CONCAT(firstname, \' \', lastname) LIKE ? OR CONCAT(lastname, \' \', firstname) LIKE ?', '%' . $query . '%', '%' . $query . '%')
			->fetchPairs('id', 'name');
	}
}