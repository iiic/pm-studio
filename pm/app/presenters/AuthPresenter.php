<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Security\AuthenticationException,
	Nette\Web\Html,
	Nette\Environment,
	\OpenIDForm;



class AuthPresenter extends BasePresenter// bylo by lepší (z bezpečnostíních důvodů) udělat final class, ale vadí to Navigation panelu, takže kdyžtak až na ostrém serveru
{
	/** @persistent */
	public $backlink = '';
	public $original_email = false;

	const NICKNAME = 'namePerson/friendly';// mojeid.cz umožňuje přezdívku změnit
	const EMAIL = 'contact/email';// mojeid.cz NEumožňuje email změnit


	public function startup()
	{
		parent::startup();
		$this->session->start(); // required by $form->addProtection()
	}



	/********************* component factories *********************/



	public function renderDefault()
	{
		$this->template->shortSalt1 = Environment::getConfig('security')->shortSalt1;
		$this->template->shortSalt2 = Environment::getConfig('security')->shortSalt2;
		if ($this->user->isLoggedIn()) {
			$this->flashMessage('Už jste přihlášen');
			$backlink = $this->application->storeRequest();
			$this->redirect('Default:', array('backlink' => $backlink));
		}
	}



	public function renderOpenid()
	{
		if ($this->user->isLoggedIn()) {
			$this->flashMessage('Už jste přihlášen');
			$backlink = $this->application->storeRequest();
			$this->redirect('Default:', array('backlink' => $backlink));
		}
	}



	public function renderRegister() {
		$oidsession = Environment::getSession('openid');
		if(!isset($oidsession->identity)) {
			throw Exception('Ztratila se OpenID session... proces nemůže pokračovat!');
		}
		$this->template->nickname = (isset($oidsession->attributes[self::NICKNAME])) ? $oidsession->attributes[self::NICKNAME] : '';
	}



	/**
	 * Sign in form component factory.
	 * @return Nette\Application\AppForm
	 */
	protected function createComponentAuthForm()
	{
		$form = new AppForm;
		//$form->getElementPrototype()->setNovalidate('novalidate');// zastaví defaultní validaci HTML5
		$form->addGroup('Formulář pro přihlášení uživatele')
			->setOption('description', Html::el('p')
				->setText('Pokud nebudete mít zapnutý javascript vaše heslo se odešle v čitelné podobě, hrozí tedy možnost jeho odposlechu.')
				->setClass('hide-with-js important')
			);
		$form->getElementPrototype()->class('shift-left');

		$form->addText('username', 'Uživatelské jméno')
			->addRule(Form::FILLED, 'Prosím zadejte uživatelské jméno.');

		$form->addPassword('password', 'Příslušné heslo')
			->addRule(Form::FILLED, 'Prosím zadejte heslo.');

		$form->addHidden('password2');// pokaždé umístí do <div> až pod fieldset což je divné, možná by na to pomohly wrappers

		// @todo : vyřešit nějak ochranu proti spamu, aby se boti nezkoušeli zbytečně přihlásit a nezatěžovali tak server

		$form->addProtection('Odešlete prosím formulář znovu. Možný CSRF útok');

		$form->addSubmit('send', 'přihlásit');

		$form->onSubmit[] = callback($this, 'authFormSubmitted');
		return $form;
	}



	public function authFormSubmitted($form)
	{
		try {
			if($form['password2']->value) {
				$this->user->login($form['username']->value, $form['password2']->value, $this->template->settings->admins, $form['_token_']->value);// směřuje na UsersModel::authenticate
			} else {
				$this->user->login($form['username']->value, $form['password']->value, $this->template->settings->admins);
			}

			$this->application->restoreRequest($this->backlink);
			$this->redirect('Default:');

		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}



	/**
	 * OpenID Form control factory.
	 * @return mixed
	 */
	protected function createComponentOpenIdAuthForm()
	{
		$oid = new \OpenIDForm\OpenIDForm();
		$oid->setOptional(self::NICKNAME);
		$oid->setOptional(self::EMAIL);
		$oid->onSignin[] = callback($this, 'openIDSigned');
		return $oid;
	}



	/**
	 * Successful login callback.
	 * @param string
	 * @param array
	 */
	public function openIDSigned($identity, $attributes) {
		try {
			$this->user->login(array($identity, $this->template->settings->admins));
			$this->redirect('Default:');
		} catch(Nette\Security\AuthenticationException $e) {
			if( (!empty($identity)) && ($attributes[self::NICKNAME] != '') ) {// @todo : dodělat další pravidla pro tento server, jako třeba nepovolené znaky, nebo délka menší než 2 písmena
				$users = new UsersModel;
				$users->registerOpenId(array('identity' => $identity, 'username' => $attributes[self::NICKNAME], 'email' => $attributes[self::EMAIL], 'validate_email' => 1));
				try {
					$this->user->login(array($identity, $this->template->settings->admins));
					$this->flashMessage('Jste přihlášen pomocí OpenID jako uživatel '.$attributes[self::NICKNAME].'.');
					$this->redirect('Default:');
				} catch(Nette\Security\AuthenticationException $e) {}// nevyhazovat vyjímky... zatím není nic ztraceno
			}
			$oidsession = Environment::getSession('openid');
			$oidsession->identity = $identity;
			$oidsession->attributes = $attributes;
			$this->redirect('Auth:register');
		}
	}



	/**
	 * Create the register form
	 */
	public function createComponentRegisterForm() {
		$oidsession = Environment::getSession('openid');
		$username = false;
		$email = false;
		if(isset($oidsession->attributes[self::NICKNAME])) {
			$username = $oidsession->attributes[self::NICKNAME];
		}
		if(isset($oidsession->attributes[self::EMAIL])) {
			$email = $oidsession->attributes[self::EMAIL];
		}
		if(!isset($oidsession->identity)) {
			throw Exception('Ztratila se OpenID session... proces nemůže pokračovat!');
		}
		$form = new AppForm;
		$form->getElementPrototype()->class('shift-left');
		if($username) {
			$infoText = 'Vaše přezdívka z OpenID už na tomto serveru existuje, zvolte si prosím nějakou jinou.';
		} else {
			$infoText = 'Při autentizaci pomocí OpenID jste nezobrazil vaši přezdívku, zvolte si tedy nějakou ručně.';
		}
		$form->addGroup('Formulář pro přihlášení uživatele')
			->setOption('description', Html::el('p')
				->setText($infoText)
			);
		$form->addText('username', 'Vaše přezdívka')
			->addRule(Form::FILLED, 'Prosíme vyplňte vaši přezdívku!')
			->setDefaultValue($username);
		if($email) {
			$this->original_email = $email;
		} else {
			$form->addText('email', 'Email')
				->addCondition(Form::FILLED, 'Prosím nezapomeňte vyplnit e-mail.')// conditional rule: if is email filled, ...
				->addRule(Form::EMAIL, 'Prosím vložte platnou e-mailovou adresu.');// ... then check email
		}
		//todo : email pokud není zadán, pokud je tak ho udělat taky hidden
		$form->addHidden('openid')
			->setValue($oidsession->identity);
		$form->addSubmit('register', 'Dokončit');
		$form->onSubmit[] = array($this, 'registerFormSubmitted');
		return $form;
	}



	/**
	 * The register form onSubmit function
	 * @param AppForm
	 */
	public function registerFormSubmitted(AppForm $form) {
		$values = $form->values;
		if($this->original_email) {
			$values['email'] = $this->original_email;
			$values['validate_email'] = 1;
		} else {
			$values['validate_email'] = 0;
		}
		$users = new UsersModel;
		$users->registerOpenId(array('identity' => $values['openid'], 'username' => $values['username'], 'email' => $values['email'], 'validate_email' => $values['validate_email']));
		$this->user->login(array($values['openid'], $this->template->settings->admins));
		$this->redirect('Default:');
	}



	public function actionLogout()
	{
		$this->user->logout(true);// bez true zůstávají stopy (id, role, ...) nevím na co by se to mohlo hodit
		//$this->getUser()->logout();
		$this->flashMessage('Byl jste právě odhlášen.');
		$this->redirect('Default:');
	}

}
