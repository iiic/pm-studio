<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class ContentPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->checkAuth();
		$form = $this['contentForm'];
		if(!$form->isSubmitted()) {
			$content = new ContentModel;
			$row = $content->fetch();
			if(!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$form->setDefaults($row);
		}
	}



	protected function createComponentContentForm()
	{
		$form = new AppForm;
		$form->addGroup('Formulář pro úpravu nastavení webu');
		$form->addTextArea('content', 'Text')
			->addRule(Form::FILLED, 'Prosím zadejte titulek těchto stránek.');
		$form->addSubmit('save', 'uložit');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'contentFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}



	public function contentFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			//úpravy values !
			$content = new ContentModel;
			$content->update($values);
			$this->flashMessage('Obsah byl úspěšně uložen.');
		}
		$this->redirect('content:');
	}

}
