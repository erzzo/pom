<?php

namespace Models;

class Theme extends Base
{
	public function getThemeUsers($themeId)
	{
		$theme_users = $this->db->table('theme_user')->select('theme_id, user_subject.user.login')->where('theme_id', $themeId)->fetchPairs('theme_id','login');
		return $theme_users;
	}

	public function getThemes($projectId)
	{
		$themes = $this->getAll()->order('name ASC');

		if (!is_null($projectId)) {
			$themes->where('project_id', $projectId);
		}
		return $themes;
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