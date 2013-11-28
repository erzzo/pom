<?php

namespace Models;

class Theme extends Base
{
	public function getComments($themeId = NULL, $taskId = NULL)
	{
		$comments = $this->db->table('comment')->select('*')
		if ($themeId) {
			$comments->where('theme_id',$themeId)->where('task_id',$taskId)->order('id DESC');
		}else {
			$comments->where('task_id',$taskId)->order('id DESC');
		}return $comments;
	}

	public function getThemeUsers($themeId)
	{
		return $this->db->table('theme_user')->select('theme_id, user.login')->where('theme_id', $themeId)->fetchPairs('theme_id','login');
	}

	public function getMyThemes($subjectId)
	{
		return $this->db->table('theme_user')->select('theme.id,theme.name,theme.description,theme.submitted,theme.project_id')
			->where('user_id', $this->user->getId())
			->where('theme.project.subject_id', $subjectId)
			->order('theme.name ASC');
	}

	public function getThemePercentage($subjectId)
	{
		$themes = $this->getMyThemes($subjectId);

		$percentage = array();
		foreach ($themes as $key => $theme) {
			$doneTaskCount = $this->db->table('task')->where('theme_id', $theme->id)->where('grade', TRUE)->count();
			$taskCount = $this->db->table('task')->where('theme_id', $theme->id)->count();
			$percentage[$key] = ($doneTaskCount/$taskCount) * 100;
		}

		return $percentage;
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

	public function addEditComment($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->db->table('comment')->insert($values);
		} else {
			$comment = $this->db->table('comment')->get($id);
			return $comment->update($values);
		}
	}

}