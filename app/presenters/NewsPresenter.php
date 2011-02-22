<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\Html,
	Nette\Environment;

class NewsPresenter extends BasePresenter
{

	public function actionDelete($id = 0)
	{
		$this->checkAuth();
		$news = new NewsModel;
		$news->delete($id);
		$this->flashMessage('Novinka byla úspěšně odstraněna.');
		$this->redirect('news:');
	}

	public function actionDeleteComment($id = 0, $postId = 0)
	{
		$this->checkAuth();
		$comments = new CommentsModel;
		$comments->delete($id);
		$this->flashMessage('Komentář byl odebrán.');
		$this->redirect('news:detail', array('id'=>$postId));
	}
	
	public function renderDefault()
	{
		$news = new NewsModel;
		$data = $news->fetchAll();
		if($data != array( )) {// pokud existují nějaké hodnoty
			$this->template->items = $data;
		}
	}

	public function renderAdd()
	{
		$this->checkAuth();
		$this['newsForm']['preview']->caption = 'náhled';
		$this['newsForm']['save']->caption = 'uložit';
		$this['newsForm']['null']->caption = 'vyprázdnit pole';
		$this['newsForm']['cancel']->caption = 'zrušit vkládání';
	}

	public function renderEdit($id = 0)
	{
		$this->checkAuth();
		$form = $this['newsForm'];
		if(!$form->isSubmitted()) {
			$news = new NewsModel;
			$row = $news->fetch($id);
			if (!$row) {
				throw new Nette\Application\BadRequestException('Záznam nenalezen');
			}
			$form->setDefaults($row);
		}
	}

	protected function createComponentNewsForm()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro vložení novinky');
		$form->addText('title', 'Nadpis:')
			->addRule(Form::FILLED, 'Prosím vložte nadpis.');
		$form->addTextArea('content', 'Obsah:')
			->addRule(Form::FILLED, 'Musíte zadat nějaký obsah novinky.')
			->setOption('description', Html::el('a')
				->setText('texy! syntax')
				->href('http://www.texy.info/cs/syntax')
				->title('kliknutím otevřete novou stránku s informacemi o syntaxi')
				->onclick('return !window.open(this.href)')
			);
		$form->addSubmit('preview', 'náhled')->setAttribute('class', 'default');
		$form->addSubmit('save', 'uložit');
		$form->addButton('null','původní hodnoty')->setAttribute('type', 'reset');
		$form->addSubmit('cancel', 'přerušit úpravy')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'newsFormSubmitted');
		$form->addProtection('Prsoším odešlete tento formulář znovu (doba sezení vypršela).');
		return $form;
	}

	public function newsFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$id = (int) $this->getParam('id');
			$values = $form->values;
			unset($values['null']);
			$news = new NewsModel;
			if($id > 0) {
				$news->update($id, $values);
				$this->flashMessage('Novinka byla úspěšně upravena.');
				$this->redirect('news:detail', array('id'=>$id));
			} else {
				$id = $news->insert($values);
				$this->flashMessage('Novinka byla přidána.');
				$this->redirect('news:detail', array('id'=>$id));
			}

		} elseif($form['preview']->isSubmittedBy()) {
			$this->template->content = $form->values['content'];
			$this->invalidateControl('content');
			if(!$this->isAjax()) {
				$this->redirect('this');
			}

		} else {
			$this->redirect('news:');
		}
	}

	public function renderDetail($id = 0)
	{
		if(!($data = NewsModel::fetch($id))) {
			$this->redirect('news:');
		}
		$this->template->item = $data;
		$this->template->comments = CommentsModel::fetchAll($id);
	}

	public function createComponentCommentForm($name)
	{
		$user = Environment::getUser();
		if($user->getIdentity()) {
			$username = $user->getIdentity()->username;
		} else {
			$username = '';
		}
		$form = new AppForm($this, $name);
		$form->addGroup('Formulář pro komentování novinky');
		$form->addText('author', 'Jméno')
			->setValue($username)
			->addRule(AppForm::FILLED, 'Zapoměl jste vyplnit jméno!');
		$form->addTextArea('content', 'Komentář')
			->addRule(AppForm::FILLED, 'Samotný komentář je nutné vyplnit');
		$form->addSubmit('send', 'Odeslat');
		$form->onSubmit[] = callback($this, 'commentFormSubmitted');
		return $form;
	}

	public function commentFormSubmitted(AppForm $form)
	{
		$data = $form->getValues();
		$data['date'] = time();
		$data['post_id'] = (int) $this->getParam('id');
		$id = CommentsModel::insert($data);
		$this->flashMessage('Komentář uložen!');
		$this->redirect('this#comment-'.$id);
	}
}
