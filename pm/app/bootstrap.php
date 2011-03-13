<?php

/**
 * My Application bootstrap file.
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */


use Nette\Debug,
	Nette\Environment,
	Nette\Application\Route;



// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';



// Step 2: Configure environment
// 2a) enable Nette\Debug for better exception and error visualisation
Debug::$strictMode = TRUE;
Debug::enable(Nette\Debug::DEVELOPMENT);// Nette\Debug::PRODUCTION / Nette\Debug::DEVELOPMENT

// 2b) load configuration from config.ini file
Environment::loadConfig();



\Panel\NavigationPanel::register();
\Panel\TodoPanel::register();
Nella\VersionPanel::register();
//Extras\Debug\ComponentTreePanel::register();



// Step 3: Configure application
// 3a) get and setup a front controller
$application = Environment::getApplication();
$application->errorPresenter = 'Error';
$application->catchExceptions = FALSE;



// Step 4: Setup application router
$router = $application->getRouter();

Route::setStyleProperty('presenter', Route::FILTER_TABLE, array(
	'novinky' => 'News',// jméno presenteru MUSÍ začínat velkým písmenem
	'kontakt' => 'Contact',
	'obsah' => 'Content',
	'autentizace' => 'Auth',
	'nastaveni' => 'Settings',
	'registrace' => 'Register',
));

Route::setStyleProperty('action', Route::FILTER_TABLE, array(
	'pridat' => 'add',
	'upravit' => 'edit',
	'registrovat' => 'register',
	'smazat' => 'delete',
	'odhlasit' => 'logout',
));

$router[] = new Route('<presenter>/<action>/<id>', array(
	'presenter' => 'Default',
	'action' => 'default',
	'id' => NULL,
));



dibi::connect(Environment::getConfig('db'));



// Step 5: Run the application!
if(Environment::getName() !== "console") {
	$application->run();
}
