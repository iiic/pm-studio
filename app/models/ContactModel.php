<?php

class ContactModel
{
	public static function fetch()
	{
		return dibi::fetch('
			SELECT *
			FROM [contact]'
		);
	}

	public static function getContent()
	{
		return dibi::fetchSingle('
			SELECT content
			FROM [contact]'
		);
	}

	public function update(array $data)
	{
		$data['date'] = time();
		return dibi::query('UPDATE [contact] SET %a', $data);
	}

}
