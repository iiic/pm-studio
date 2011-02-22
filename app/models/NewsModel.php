<?php

class NewsModel
{

	public static function fetchAll()
	{
		return dibi::fetchAll('
			SELECT *
			FROM [news]
			ORDER BY date DESC'
		);
	}

	public static function fetch($id)
	{
		return dibi::fetch('
			SELECT *
			FROM [news]
			WHERE id = %i', $id
		);
	}

	public static function insert(array $data)
	{
		$data['date'] = time();
		dibi::query('INSERT INTO [news] %v', $data);
		return dibi::InsertId();
	}

	public static function update($id, array $data)
	{
		$data['date'] = time();
		return dibi::query('UPDATE [news] SET %a WHERE id = %i', $data, $id);
	}

	public static function delete($id)
	{
		return dibi::query('DELETE FROM [news] WHERE id = %i', $id);
	}

}
