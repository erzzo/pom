<?php

namespace Models;

class Theme extends Base
{
	public function getThemeUsers($themeId)
	{
		$theme_users = $this->db->table('theme_user')->select('theme_id, user.login')->where('theme_id', $themeId)->fetchPairs('theme_id','login');
		return $theme_users;
	}

	public function getMyThemes($subjectId)
	{
		return $this->db->table('theme_user')->select('theme.id,theme.name,theme.description,theme.submitted,theme.project_id')
			->where('user_id', $this->user->getId())
			->where('theme.project.subject_id', $subjectId)
			->order('theme.name ASC');
	}

	public function getThemes($projectId)
	{
		$themes = $this->getAll()->order('name ASC');

		if (!is_null($projectId)) {
			$themes->where('project_id', $projectId);
		}
		return $themes;
	}

	public function addRemoveUsersToTheme($themeId, $studentIds)
	{
		$this->db->table('theme_user')->where('theme_id', $themeId)->delete();
		foreach ($studentIds as $id)
		{
			$this->db->table('theme_user')->insert(
				array(
					"user_id" => $id,
					"theme_id" => $themeId
				)
			);
		}

		return true;
	}

	public function getThemeStudents($projectId)
	{
		return $this->db->table('theme_user')->where("theme.project_id", $projectId)->order('theme_id');
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