<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Database\Drivers;

use Nette;



/**
 * Supplemental SQLite2 database driver.
 *
 * @author     David Grudl
 */
class PdoSqlite2Driver extends PdoSqliteDriver
{

	/**
	 * Encodes string for use in a LIKE statement.
	 */
	public function formatLike($value, $pos)
	{
		throw new NotSupportedException;
	}



	/**
	 * Normalizes result row.
	 */
	public function normalizeRow($row, $statement)
	{
		foreach ($row as $key => $value) {
			if ($key[0] === '[') {
				unset($row[$key]);
				$key = substr($key, 1, -1);
				$row[$key] = $value;
			}
		}
		return $row;
	}

}
