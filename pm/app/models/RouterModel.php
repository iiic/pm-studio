<?php

class RouterModel
{

	public static function getTopic($uri)
	{

		$data = dibi::fetchPairs('
			SELECT id, hash
			FROM [menu]
			WHERE p_type = 1
			AND title != ""
		');

// @todo : někdy když jde odkaz z nějaké diskuze nebo fóra jsou za adresou připojeny i znaky, které do ní nepatří jako . (pokud je odkaz vložen na konec věty), ) (pokud je odkaz v závorce), ... . Proto by se hodilo udělat cyklus který bude řezat ze zadané uri znaky až do konce a až potom případně vyhlásí NULL, čimž pošle průběh scriptu na další routu v pořadí (jsou v bootstrap.php). Samožřejěm taky kanonizace s přesměrováním na správný tvar URI, pokud byla nějak poškozena. Dalo by se využít i hledání v databázi, pokud je URI naopak zkrácena a dá se ještě zachránit.
		if (in_array($uri, $data)) {
			return $uri;
		} else {
			return NULL;
		}
	}

}
