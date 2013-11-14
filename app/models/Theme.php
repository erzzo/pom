<?php

namespace Models;

class Theme extends Base
{
	public function getThemes()
	{
		return $this->getAll()->order('created DESC, name ASC');
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