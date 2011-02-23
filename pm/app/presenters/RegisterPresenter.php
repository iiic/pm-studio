<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Security\AuthenticationException;



class RegisterPresenter extends BasePresenter
{
	/** @persistent */
	public $backlink = '';



	public function startup()
	{
		parent::startup();
		$this->session->start();// required by $form->addProtection()
	}



	public function renderDefault()
	{
		if($this->user->isLoggedIn()) {
			$this->flashMessage('Pro registraci se musíte odhlásit.');
			$backlink = $this->application->storeRequest();
			$this->redirect('default:', array('backlink' => $backlink));
		}
	}



	/**
	 * Sign in form component factory.
	 * @return Nette\Application\AppForm
	 */
	protected function createComponentRegisterForm()
	{
		$form = new AppForm;
		$form->addGroup('Formulář pro registraci uživatele');
		//přidat onsubmit
		$form->addText('username', 'Uživatelské jméno')
			->addRule(Form::FILLED, 'Prosím zadejte uživatelské jméno.');
			//->addRule(Form::RANGE, 'Délka uživatelského jména musí být v rozmezí %d až %d znaků.', array(2, 80));

		$form->addText('email', 'Email')
			->addCondition(Form::FILLED, 'Prosím nezapomeňte vyplnit e-mail.')// conditional rule: if is email filled, ...
			->addRule(Form::EMAIL, 'Prosím vložte platnou e-mailovou adresu.');// ... then check email

		$form->addPassword('password', 'Heslo')
			->addRule(Form::FILLED, 'Prosím zadejte heslo.')
			->addRule(Form::MIN_LENGTH, 'Minimální povolená délka hesla, jsou %d znaky', 4)
			->addRule(Form::MAX_LENGTH, 'Maximální délka hesla je 1000 znaků.', 1024);

		$form->addPassword('password2', 'heslo pro kontrolu')
			->addRule(Form::FILLED, 'Prosím zadejte heslo pro kontrolu.')
			->addRule(Form::EQUAL, 'Heslo a heslo pro kontrolu si neodpovídají.', $form['password']);

		$form->addSubmit('send', 'registrovat');

		$form->onSubmit[] = callback($this, 'registerFormSubmitted');
		return $form;
	}



	public function registerFormSubmitted($form)
	{
		$values = $form->getValues();
		unset($values['password2']);
		$values['validate_email'] = $this->salt(5, $values['username']);
		// @todo : zaslat mail na zadanou adresu s ověřovacím kódem $values['validate_email'];
		$users = new UsersModel;
		try {
			$users->registerNew($values);
		} catch(DibiDriverException $e) {
			return $form->addError("Uživatel s tímto nickem již existuje, prosím zvolte si jiný.");
		}
		$this->flashMessage('Vaše registrace byla úspěšně dokončena. Nyní se můžete přihlásit.');
		// @todo : přesměrovat uživatele na stránku s přihlášením, kde bude předvyplněné uživatelské jméno (možná bude potřeba ho předvyplnit javascriptem) a hláška o tom, že na jeho email byla zaslána zpráva s ověřovacím kódem
		$this->redirect('default:');
	}



	private function salt($length = 8, $string = false)
	{
		$i = 0;
		$begin = 0;
		$salt = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789'.'abcdefghijklmnopqrstuvwxyz';
		while($i++ < $length) {
			if($string) {
				$begin = ord(substr(base64_encode($string),$i-1,$i))-45;
				$begin = floor($begin/3);
			}
			$salt .= substr($chars, mt_rand($begin, strlen($chars)-1), 1);
		}
		return $salt;
	}

}
