<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class MenuPresenter extends BasePresenter
{

	private $menu_items = array('prázdné', 'novinky', 'kontakt', 'registrace', 'autentizace');// v databázi jsou tyto položky jako 'prázdné' p_type 1, 'novinky' p_type 2... a p_type 0 je pro vlastní obsah

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
			$r['p1'] = $row[0]->p_type-1; $r['cvp1'] = $row[0]->title; if($row[0]->p_type == 0){ $r['cp1'] = 1; }
			$r['p2'] = $row[1]->p_type-1; $r['cvp2'] = $row[1]->title; if($row[1]->p_type == 0){ $r['cp2'] = 1; }
			$r['p3'] = $row[2]->p_type-1; $r['cvp3'] = $row[2]->title; if($row[2]->p_type == 0){ $r['cp3'] = 1; }
			$r['p4'] = $row[3]->p_type-1; $r['cvp4'] = $row[3]->title; if($row[3]->p_type == 0){ $r['cp4'] = 1; }
			$r['p5'] = $row[4]->p_type-1; $r['cvp5'] = $row[4]->title; if($row[4]->p_type == 0){ $r['cp5'] = 1; }
			$r['p6'] = $row[5]->p_type-1; $r['cvp6'] = $row[5]->title; if($row[5]->p_type == 0){ $r['cp6'] = 1; }
			$r['p7'] = $row[6]->p_type-1; $r['cvp7'] = $row[6]->title; if($row[6]->p_type == 0){ $r['cp7'] = 1; }
			$r['p8'] = $row[7]->p_type-1; $r['cvp8'] = $row[7]->title; if($row[7]->p_type == 0){ $r['cp8'] = 1; }
			$r['p9'] = $row[8]->p_type-1; $r['cvp9'] = $row[8]->title; if($row[8]->p_type == 0){ $r['cp9'] = 1; }
			$r['p10'] = $row[9]->p_type-1; $r['cvp10'] = $row[9]->title; if($row[9]->p_type == 0){ $r['cp10'] = 1; }
			$r['p11'] = $row[10]->p_type-1; $r['cvp11'] = $row[10]->title; if($row[10]->p_type == 0){ $r['cp11'] = 1; }
			$r['p12'] = $row[11]->p_type-1; $r['cvp12'] = $row[11]->title; if($row[11]->p_type == 0){ $r['cp12'] = 1; }
			$form->setDefaults($r);
		}
	}



	protected function createComponentMenuForm()
	{
		$users = new UsersModel;
		$form = new AppForm;
		$form->getElementPrototype();
		$form->addGroup('Formulář pro sestavení menu');

		$form->addSelect('p1', '1. položka', $this->menu_items);
		$form->addText('cvp1', '1. vlastní sekce')
			->setOption('id', 'cvp1')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp1', 'vlastní')
			->addCondition(Form::EQUAL, TRUE)// conditional rule: if is checkbox checked...
				->toggle('cvp1');

		$form->addSelect('p2', '2. položka', $this->menu_items);
		$form->addText('cvp2', '2. vlastní sekce')
			->setOption('id', 'cvp2')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp2', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp2');

		$form->addSelect('p3', '3. položka', $this->menu_items);
		$form->addText('cvp3', '3. vlastní sekce')
			->setOption('id', 'cvp3')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp3', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp3');

		$form->addSelect('p4', '4. položka', $this->menu_items);
		$form->addText('cvp4', '4. vlastní sekce')
			->setOption('id', 'cvp4')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp4', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp4');

		$form->addSelect('p5', '5. položka', $this->menu_items);
		$form->addText('cvp5', '5. vlastní sekce')
			->setOption('id', 'cvp5')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp5', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp5');

		$form->addSelect('p6', '6. položka', $this->menu_items);
		$form->addText('cvp6', '6. vlastní sekce')
			->setOption('id', 'cvp6')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp6', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp6');

		$form->addSelect('p7', '7. položka', $this->menu_items);
		$form->addText('cvp7', '7. vlastní sekce')
			->setOption('id', 'cvp7')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp7', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp7');

		$form->addSelect('p8', '8. položka', $this->menu_items);
		$form->addText('cvp8', '8. vlastní sekce')
			->setOption('id', 'cvp8')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp8', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp8');

		$form->addSelect('p9', '9. položka', $this->menu_items);
		$form->addText('cvp9', '9. vlastní sekce')
			->setOption('id', 'cvp9')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp9', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp9');

		$form->addSelect('p10', '10. položka', $this->menu_items);
		$form->addText('cvp10', '10. vlastní sekce')
			->setOption('id', 'cvp10')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp10', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp10');

		$form->addSelect('p11', '11. položka', $this->menu_items);
		$form->addText('cvp11', '11. vlastní sekce')
			->setOption('id', 'cvp11')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp11', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp11');

		$form->addSelect('p12', '12. položka', $this->menu_items);
		$form->addText('cvp12', '12. vlastní sekce')
			->setOption('id', 'cvp12')
			->setOption('class', 'move-up');
		$form->addCheckbox('cp12', 'vlastní')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('cvp12');

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
			$data = array(
				array('id' => 1, 'p_type' => ($values['cp1']==1)? 0 : $values['p1']+1, 'title' => $values['cvp1']),
				array('id' => 2, 'p_type' => ($values['cp2']==1)? 0 : $values['p2']+1, 'title' => $values['cvp2']),
				array('id' => 3, 'p_type' => ($values['cp3']==1)? 0 : $values['p3']+1, 'title' => $values['cvp3']),
				array('id' => 4, 'p_type' => ($values['cp4']==1)? 0 : $values['p4']+1, 'title' => $values['cvp4']),
				array('id' => 5, 'p_type' => ($values['cp5']==1)? 0 : $values['p5']+1, 'title' => $values['cvp5']),
				array('id' => 6, 'p_type' => ($values['cp6']==1)? 0 : $values['p6']+1, 'title' => $values['cvp6']),
				array('id' => 7, 'p_type' => ($values['cp7']==1)? 0 : $values['p7']+1, 'title' => $values['cvp7']),
				array('id' => 8, 'p_type' => ($values['cp8']==1)? 0 : $values['p8']+1, 'title' => $values['cvp8']),
				array('id' => 9, 'p_type' => ($values['cp9']==1)? 0 : $values['p9']+1, 'title' => $values['cvp9']),
				array('id' => 10, 'p_type' => ($values['cp10']==1)? 0 : $values['p10']+1, 'title' => $values['cvp10']),
				array('id' => 11, 'p_type' => ($values['cp11']==1)? 0 : $values['p11']+1, 'title' => $values['cvp11']),
				array('id' => 12, 'p_type' => ($values['cp12']==1)? 0 : $values['p12']+1, 'title' => $values['cvp12']),
			);
			$menu = new MenuModel;
			$menu->update($data);
			$this->flashMessage('Úpravy v menu byly uloženy.');
		}
		$this->redirect('menu:');
	}

}
