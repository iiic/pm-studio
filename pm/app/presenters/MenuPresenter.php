<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class MenuPresenter extends BasePresenter
{

	private $menu_items = array('prázdné', 'vlastní obsah', 'novinky', 'kontakt', 'reklama', 'registrace');// v databázi jsou tyto položky jako 'prázdné' p_type 1, 'novinky' p_type 2... a p_type 0 je pro vlastní obsah
	private $menu_hashes = array('', '', 'novinky', 'kontakt', 'reklama', 'registrace');

	public function renderDefault()
	{
		$this->checkAuth();
		$form = $this['menuForm'];
		if(!$form->isSubmitted()) {
			$menu = new MenuModel;
			$row = $menu->fetchAll();
			if(!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$r['p_type_1'] = $row[0]->p_type; $r['title_1'] = $row[0]->title; if( ($row[0]->title != '') || ($row[0]->p_type == 1) ){ $r['custom_title_1'] = 1; }
			$r['p_type_2'] = $row[1]->p_type; $r['title_2'] = $row[1]->title; if( ($row[1]->title != '') || ($row[1]->p_type == 1) ){ $r['custom_title_2'] = 1; }
			$r['p_type_3'] = $row[2]->p_type; $r['title_3'] = $row[2]->title; if( ($row[2]->title != '') || ($row[2]->p_type == 1) ){ $r['custom_title_3'] = 1; }
			$r['p_type_4'] = $row[3]->p_type; $r['title_4'] = $row[3]->title; if( ($row[3]->title != '') || ($row[3]->p_type == 1) ){ $r['custom_title_4'] = 1; }
			$r['p_type_5'] = $row[4]->p_type; $r['title_5'] = $row[4]->title; if( ($row[4]->title != '') || ($row[4]->p_type == 1) ){ $r['custom_title_5'] = 1; }
			$r['p_type_6'] = $row[5]->p_type; $r['title_6'] = $row[5]->title; if( ($row[5]->title != '') || ($row[5]->p_type == 1) ){ $r['custom_title_6'] = 1; }
			$r['p_type_7'] = $row[6]->p_type; $r['title_7'] = $row[6]->title; if( ($row[6]->title != '') || ($row[6]->p_type == 1) ){ $r['custom_title_7'] = 1; }
			$r['p_type_8'] = $row[7]->p_type; $r['title_8'] = $row[7]->title; if( ($row[7]->title != '') || ($row[7]->p_type == 1) ){ $r['custom_title_8'] = 1; }
			$r['p_type_9'] = $row[8]->p_type; $r['title_9'] = $row[8]->title; if( ($row[8]->title != '') || ($row[8]->p_type == 1) ){ $r['custom_title_9'] = 1; }
			$r['p_type_10'] = $row[9]->p_type; $r['title_10'] = $row[9]->title; if( ($row[9]->title != '') || ($row[9]->p_type == 1) ){ $r['custom_title_10'] = 1; }
			$r['p_type_11'] = $row[10]->p_type; $r['title_11'] = $row[10]->title; if( ($row[10]->title != '') || ($row[10]->p_type == 1) ){ $r['custom_title_11'] = 1; }
			$form->setDefaults($r);
		}
	}



	protected function createComponentMenuForm()
	{
		$users = new UsersModel;
		$form = new AppForm;
		$form->getElementPrototype();
		$form->addGroup('Formulář pro sestavení menu');

		$form->addSelect('p_type_1', '1. položka', $this->menu_items);
		$form->addText('title_1', 'vlastní nadpis')
			->setOption('id', 'title_1');
		$form->addCheckbox('custom_title_1', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE)// conditional rule: if is checkbox checked...
				->toggle('title_1');

		$form->addSelect('p_type_2', '2. položka', $this->menu_items);
		$form->addText('title_2', 'vlastní nadpis')
			->setOption('id', 'title_2');
		$form->addCheckbox('custom_title_2', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_2');

		$form->addSelect('p_type_3', '3. položka', $this->menu_items);
		$form->addText('title_3', 'vlastní nadpis')
			->setOption('id', 'title_3');
		$form->addCheckbox('custom_title_3', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_3');

		$form->addSelect('p_type_4', '4. položka', $this->menu_items);
		$form->addText('title_4', 'vlastní nadpis')
			->setOption('id', 'title_4');
		$form->addCheckbox('custom_title_4', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_4');

		$form->addSelect('p_type_5', '5. položka', $this->menu_items);
		$form->addText('title_5', 'vlastní nadpis')
			->setOption('id', 'title_5');
		$form->addCheckbox('custom_title_5', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_5');

		$form->addSelect('p_type_6', '6. položka', $this->menu_items);
		$form->addText('title_6', 'vlastní nadpis')
			->setOption('id', 'title_6');
		$form->addCheckbox('custom_title_6', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_6');

		$form->addSelect('p_type_7', '7. položka', $this->menu_items);
		$form->addText('title_7', 'vlastní nadpis')
			->setOption('id', 'title_7');
		$form->addCheckbox('custom_title_7', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_7');

		$form->addSelect('p_type_8', '8. položka', $this->menu_items);
		$form->addText('title_8', 'vlastní nadpis')
			->setOption('id', 'title_8');
		$form->addCheckbox('custom_title_8', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_8');

		$form->addSelect('p_type_9', '9. položka', $this->menu_items);
		$form->addText('title_9', 'vlastní nadpis')
			->setOption('id', 'title_9');
		$form->addCheckbox('custom_title_9', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_9');

		$form->addSelect('p_type_10', '10. položka', $this->menu_items);
		$form->addText('title_10', 'vlastní nadpis')
			->setOption('id', 'title_10');
		$form->addCheckbox('custom_title_10', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_10');

		$form->addSelect('p_type_11', '11. položka', $this->menu_items);
		$form->addText('title_11', 'vlastní nadpis')
			->setOption('id', 'title_11');
		$form->addCheckbox('custom_title_11', 'použít vlastní nadpis')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('title_11');

		$form->addSubmit('save', 'uložit');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'menuFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');

		return $form;
	}



	public function menuFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			$data = array();
			for($i=1; $i<12; $i++) {
				$id = $i;
				$p_type = $values['p_type_'.$i];
				$hash = $this->menu_hashes[$values['p_type_'.$i]];
				$title = ($values['custom_title_'.$i]==1) ? $values['title_'.$i] : '';
				if($p_type != 1) {
					$data[] = array('id' => $id, 'p_type' => $p_type, 'hash' => $hash, 'title' => $title);
				} else {
					$data[] = array('id' => $id, 'p_type' => $p_type, 'title' => $title);
				}
			}
			$menu = new MenuModel;
			$menu->update($data);
			$this->flashMessage('Úpravy v menu byly uloženy.');
		}
		$this->redirect('menu:');
	}

}
