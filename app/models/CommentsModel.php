<?php

class CommentsModel
{
	public static function fetchAll($postId)
	{
		return dibi::fetchAll('
			SELECT *
			FROM [comments]
			WHERE [post_id] = %i', $postId
		);
	}

	public static function insert($data)
	{
		dibi::query('
			INSERT INTO [comments]', $data
		);

		return dibi::getInsertId();
	}

	public static function delete($id)
	{
		return dibi::query('
			DELETE FROM [comments] WHERE id = %i', $id
		);
	}
}
