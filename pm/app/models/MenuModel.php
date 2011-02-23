<?php

class MenuModel
{

	public static function fetchAll()
	{
		return dibi::fetchAll('
			SELECT *
			FROM [menu]'
		);
	}

	public static function fetchPairs()
	{
		return dibi::fetchPairs('
			SELECT id, title
			FROM [menu]
			WHERE p_type > 1
			AND title != ""'
		);
	}


	public function update(array $data)
	{
		foreach($data as $row) {
			$id = $row['id'];
			unset($row['id']);
			dibi::query('UPDATE [menu] SET %a WHERE id = %i', $row, $id);
		}
	}

}
