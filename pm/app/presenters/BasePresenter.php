<?php

use Nette\Environment,
	Nette\Application\Presenter,
	WebLoader\CssLoader,
	Webloader\JavaScriptLoader,
	WebLoader\VariablesFilter,
	Nette\Templates\TemplateFilters,
	DependentSelectBox\DependentSelectBox,
	Extras\Debug\RequestsPanel;


abstract class BasePresenter extends Presenter
{

	public $menu;

	public function startup()
	{
		$this->template->settings = SettingsModel::fetch();
		$this->menu = MenuModel::fetchAll();
		if($this->template->settings->robots) {
			$robots = $this->template->settings->robots;// $robots se z nějakého důvodu nepředá do šablony, přestože {$settings->robots} tam je... asi je to lokální proměnná, takže ji nějak zglobálnit
		}
		$httpResponse = Environment::getHttpResponse();
		if(!$httpResponse->isSent()) {// isSent, ta vrací TRUE pokud byly hlavičky odeslány a nelze tedy už odeslat další.
			$httpResponse->addHeader('Content-Script-Type', 'text/javascript');// odešle HTTP hlavičku
			$httpResponse->addHeader('Content-Style-Type', 'text/css');// odešle HTTP hlavičku
			//$httpResponse->addHeader('Vary', 'Accept,User-Agent,Accept-Language,Accept-Encoding');// odešle HTTP hlavičku
			if($this->template->settings->keywords) {
				$httpResponse->addHeader('keywords', $this->template->settings->keywords);// odešle HTTP hlavičku
			}
			//$httpResponse->addHeader('P3P', 'CP="NON DSP COR ADM TAI DELa SAMi IND ONL UNI COM NAV INT DEM CNT STA PRE", policyref="/w3c/p3p.xml"');// odešle HTTP hlavičku
			$httpResponse->addHeader('Content-Language', 'cs');// odešle HTTP hlavičku
			$httpResponse->addHeader('Content-Type', 'text/html; charset=utf-8'); //odešle HTTP hlavičku
			$httpResponse->addHeader('X-Frame-Options', 'deny');// zákaz zobrazování mojí stránky v frame a iframe
			//$httpResponse->addHeader('imagetoolbar', 'no');// zakáže v Internet Exploreru lištičku s ikonkami pro uložení, tisk, ... zobrazovanou u obrázků větších něž určité minimum
		}
		parent::startup();
		RequestsPanel::register();
		\Panel\UserPanel::register();// ->addCredentials('demo', 'demo'); //nebude možné předávat dokud nějak nevyřeším předávání template->settings->admins do modelu
		if( ($this->user->isLoggedIn()) && ($this->user->isInRole('administrator')) ) {
			Nette\Debug::enable(Nette\Debug::DEVELOPMENT);
		}
		$data = $this->menu;
		$sectionNames = array('prázdné', 'vlastní obsah', 'novinky', 'kontakt', 'reklama', 'registrace');
		$sectionPresenters = array('','content', 'news', 'contact', 'ad', 'register');// @todo : article presenter
		foreach($data as $i => $row) {
			if($data[$i]['p_type'] != 0) {
				if($data[$i]['title'] == '') { $data[$i]['title'] = $sectionNames[$data[$i]['p_type']]; }
				$data[$i]['link'] = $sectionPresenters[$data[$i]['p_type']];
				if($this->template->settings->default_presenter == $data[$i]['id']) { $this->template->settings->default_presenter = $data[$i]['hash']; }
				unset($data[$i]['id']);// není potřeba
			}
		}
		$this->template->siteContent = $data;
		$session = Environment::getSession('session');
		if(isset($session->skin_id)) {
			$this->template->settings->style = $session->skin_id;
		}
	}



	public function handleSwitchSkin($skinId = 1) {
		$session = Environment::getSession('session');
		$session->setExpiration('+ 14 days');
		if(intval($skinId) < 9) {
			$session->skin_id = $skinId;
		}
		$this->redirect('this');
	}



	protected function checkAuth()
	{
		// user authentication
		if(!$this->user->isLoggedIn()) {
			if($this->user->logoutReason === Nette\Web\User::INACTIVITY) {
				$this->flashMessage('Byl jste odhlášen z důvodu neaktivity... přihlašte se prosím znovu.');
			}
			$backlink = $this->application->storeRequest();
			$this->redirect('auth:', array('backlink' => $backlink));
		} elseif(!$this->user->isInRole('administrator')) {
			$this->flashMessage('Nemáte dostatečná oprávnění pro přístup do této sekce');
			$this->redirect('default:');//@todo : místo přesměrování na default vykázat chybu 403
		}
	}



	protected function createComponentCssSmall()
	{
		$css = new CssLoader;

		$css->media = "screen";
		$css->sourcePath = APP_DIR . "/../less";// cesta na disku ke zdroji
		$css->tempUri = Environment::getVariable("baseUri") . "webtemp";// cesta na webu k cílovému adresáři
		$css->tempPath = WWW_DIR . "/webtemp";// cesta na disku k cílovému adresáři

		$css->fileFilters[] = new Webloader\LessFilter;

		$css->filters[] = function ($code) {
			return cssmin::minify($code, "remove-last-semicolon");
		};

		return $css;
	}

	protected function createComponentCssBig()
	{
		$css = new CssLoader;

		$css->media = "screen and (min-width: 600px)";
		$css->sourcePath = APP_DIR . "/../less";// cesta na disku ke zdroji
		$css->tempUri = Environment::getVariable("baseUri") . "webtemp";// cesta na webu k cílovému adresáři
		$css->tempPath = WWW_DIR . "/webtemp";// cesta na disku k cílovému adresáři

		$css->fileFilters[] = new Webloader\LessFilter;

		$css->filters[] = function ($code) {
			return cssmin::minify($code, "remove-last-semicolon");
		};

		return $css;
	}



	protected function createComponentJs()
	{
		$js = new JavaScriptLoader;

		$js->tempUri = Environment::getVariable("baseUri") . "webtemp";// cesta na webu ke zdroji (kvůli absolutizaci cest v css souboru)
		$js->tempPath = WWW_DIR . "/webtemp";// cesta na disku k cílovému adresáři
		$js->sourcePath = APP_DIR . "/../js";// cesta na disku ke zdroji

		$js->filters[] = new VariablesFilter(array(
			// texyla
			"baseUri" => Environment::getVariable("baseUri"),
			"texylaPreviewPath" => $this->link(":Texyla:preview"),
			"texylaFilesPath" => $this->link(":Texyla:listFiles"),
			"texylaFilesUploadPath" => $this->link(":Texyla:upload"),
		));

		return $js;
	}

	protected function createComponentPackedJs()
	{
		$js = $this->createComponentJs();// předpokládá existenci továrničky výše
		$js->filters[] = array($this, "packJs");// přidá filtr, což je jakýkoliv callback
		return $js;
	}

	public function packJs($code)
	{
		$packer = new JavaScriptPacker($code, "None");
		return $packer->pack();
	}



	public function templatePrepareFilters($template)
	{
		parent::templatePrepareFilters($template);

		// inicializace Texy
		TemplateFilters::$texy = new Texy();
		TemplateFilters::$texy->encoding = 'utf-8';
		TemplateFilters::$texy->allowedTags = Texy::NONE;
		TemplateFilters::$texy->allowedStyles = Texy::NONE;
		TemplateFilters::$texy->allowedClasses = Texy::NONE;
		TemplateFilters::$texy->setOutputMode(Texy::HTML5);

		// registrace filtru texyElements
		$template->registerFilter('Nette\Templates\TemplateFilters::texyElements');
	}

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
		//$template->setTranslator(\Nette\Environment::getService('Nette\ITranslator'));// překladač

		return $template;
	}

	public function createComponentMenu()
	{
		$data = new MenuControl($this->menu);
		return $data;
	}
/*
	public function beforeRender()
	{
		//Debug::disableProfiler();
		Debug::fireLog('takhle funguje logování do fireloggeru', Debug::INFO);
	}
*/
}
