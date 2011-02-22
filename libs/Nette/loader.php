<?php

/**
 * Nette Framework (version 2.0-dev 9f535f9 released on 2011-01-10)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */



/**
 * Check and reset PHP configuration.
 */
error_reporting(E_ALL | E_STRICT);
@set_magic_quotes_runtime(FALSE); // @ - deprecated since PHP 5.3.0
iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');
@header('X-Powered-By: Nette Framework'); // @ - headers may be sent



/**
 * Load and configure Nette Framework
 */
define('NETTE', TRUE);
define('NETTE_DIR', __DIR__);
define('NETTE_VERSION_ID', 20000); // v2.0.0
define('NETTE_PACKAGE', '5.3');



require_once __DIR__ . '/Utils/shortcuts.php';
require_once __DIR__ . '/Utils/exceptions.php';
require_once __DIR__ . '/Utils/Object.php';
require_once __DIR__ . '/Loaders/LimitedScope.php';
require_once __DIR__ . '/Loaders/AutoLoader.php';
require_once __DIR__ . '/Loaders/NetteLoader.php';


class_alias('Nette\Object', 'NObject');
class_alias('Nette\Loaders\LimitedScope', 'NLimitedScope');
class_alias('Nette\Loaders\AutoLoader', 'NAutoLoader');
class_alias('Nette\Loaders\NetteLoader', 'NNetteLoader');


Nette\Loaders\NetteLoader::getInstance()->register();
