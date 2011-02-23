<?php

class IntroductionModel
{
	public static function fetch()
	{
		return dibi::fetch('
			SELECT *
			FROM [introduction]'
		);
	}

	public static function getContent()
	{
		return dibi::fetchSingle('
			SELECT content
			FROM [introduction]'
		);
	}

	public function update(array $data)
	{
		$data['date'] = time();
		return dibi::query('UPDATE [introduction] SET %a', $data);
	}

}
