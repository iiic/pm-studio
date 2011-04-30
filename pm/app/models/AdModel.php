<?php

class AdModel
{

	public static function fetch()
	{
		return dibi::fetch('
			SELECT *
			FROM [ad]'
		);
	}

	public static function getContent()
	{
		return dibi::fetchSingle('
			SELECT content
			FROM [ad]'
		);
	}

	public function update(array $data)
	{
		$data['date'] = time();
		return dibi::query('UPDATE [ad] SET %a', $data);
	}

}
