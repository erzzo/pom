<?php

namespace Models;

class Theme extends Base
{
	public function isInTheme($themeId, $userId)
	{
		return $this->db->table('theme_user')->where('theme_id', $themeId)->where('user_id',$userId)->fetch();
	}

	public function getComments($themeId, $taskId = NULL,$offset = 0, $limit = 5)
	{
		$comments = $this->db->table('comment')->select('*');
		if ($taskId) {
			$comments->where('task_id', $taskId)->order("id DESC")->limit($limit,$offset);
		} else {
			$comments->where('theme_id', $themeId)->where('task_id', NULL)->order("id DESC")->limit($limit,$offset);
		}
		return $comments;
	}

	public function getAllComments($themeId, $taskId = NULL)
	{
		$comments = $this->db->table('comment')->select('*');
		if ($taskId) {
			$comments->where('task_id', $taskId)->order("id DESC");
		} else {
			$comments->where('theme_id', $themeId)->where('task_id', NULL)->order("id DESC");
		}
		return $comments;
	}

	public function getThemeUsers($themeId)
	{
		return $this->db->table('theme_user')->select('user_id AS user_id, CONCAT(user.firstname, \' \', user.lastname) AS name')->where('theme_id', $themeId)->fetchPairs('user_id','name');
	}

	public function getEvaluation($evaluationId)
	{
		return $this->db->table('evaluation')->where('id', $evaluationId)->fetch();
	}

	public function getMyThemes($subjectId)
	{
		return $this->db->table('theme_user')
			->where('user_id', $this->user->getId())
			->where('theme.project.subject_id', $subjectId)
			->order('theme.name ASC');
	}

	// pre vsetky temy
	public function getThemesPercentage($subjectId)
	{
		$themes = $this->getMyThemes($subjectId);

		$percentage = array();
		foreach ($themes as $key => $theme) {
			$doneTaskCount = $this->db->table('task')->where('theme_id', $theme->theme_id)->where('grade', TRUE)->count();
			$taskCount = $this->db->table('task')->where('theme_id', $theme->theme_id)->count();
			$percentage[$theme->theme_id] = $taskCount != 0 ? ($doneTaskCount/$taskCount) * 100 : 0;
		}

		return $percentage;
	}

	//pre danu temu
	public function getThemePercentage($themeId)
	{
		$doneTaskCount = $this->db->table('task')->where('theme_id', $themeId)->where('grade', TRUE)->count();
		$taskCount = $this->db->table('task')->where('theme_id', $themeId)->count();

		return $percentage = $taskCount != 0 ? ($doneTaskCount/$taskCount) * 100 : 0;
	}

	//pre vsetky temy v danom projekte
	public function getThemePercentageForMentor($projectId)
	{
		$themes = $this->getThemes($projectId);
		$percentage = array();
		foreach ($themes as $key => $theme) {
			$doneTaskCount = $this->db->table('task')->where('theme_id', $theme->id)->where('grade', TRUE)->count();
			$taskCount = $this->db->table('task')->where('theme_id', $theme->id)->count();
			$percentage[$key] = array(
				"label" => $theme->name,
				"percent" => $taskCount != 0 ? ($doneTaskCount/$taskCount) * 100 : 0
			);
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

	public function addEditEvaluation($values, $id = NULL)
	{
		if(is_null($id)) {
			return $this->db->table('evaluation')->insert($values);
		} else {
			$evaluation = $this->db->table('evaluation')->get($id);
			return $evaluation->update($values);
		}
	}

}