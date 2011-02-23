<?php

use Nette\Application\Control;

abstract class BaseControl extends Control
{

	public function createTemplate()
	{
		// inicializace
		$texy = new Texy();

		$texy->encoding = 'utf-8';
		$texy->allowedTags = Texy::NONE;
		$texy->allowedStyles = Texy::NONE;
		$texy->allowedClasses = Texy::NONE;
		$texy->setOutputMode(Texy::HTML5);

		$texy->allowed['emoticon'] = TRUE;
		$texy->emoticonModule->fileRoot = WWW_DIR . '/images';

		// zavolám původní createTemplate
		$template = parent::createTemplate();
		// zaregistruji texy helper
		$template->registerHelper('texy', callback($texy, 'process'));

		return $template;
	}

}
