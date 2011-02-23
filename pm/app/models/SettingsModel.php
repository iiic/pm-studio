<?php

class SettingsModel
{
	public static function fetch()
	{
		return dibi::fetch('
			SELECT *
			FROM [settings]'
		);
	}

	public function update(array $data)
	{
		$data['date'] = time();
		$data['user_id'] = 1;
		return dibi::query('UPDATE [settings] SET %a', $data);
	}

}
