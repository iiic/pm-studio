<?php

class MenuControl extends BaseControl
{

	private $menu;

	public function __construct() {
                $sectionNames = array('vlastní', 'prázdné', 'novinky', 'kontakt', 'registrace', 'autentizace');
                $sectionPresenters = array('article','', 'news', 'contact', 'register', 'auth');// @todo : article presenter
		$menu = new MenuModel;
                $data = $menu->fetchAll();
		foreach($data as $i => $row) {
                        if($data[$i]['p_type'] != 1) {
                                $data[$i]['link'] = $sectionPresenters[$data[$i]['p_type']];
                                $data[$i]['hash'] = 'p'.$i;
                                if(empty($data[$i]['title'])) { $data[$i]['title'] = $sectionNames[$data[$i]['p_type']]; }
                                unset($data[$i]['id']);// není potřeba
                                unset($data[$i]['p_type']);
                                unset($data[$i]['content']);
                        }
		}
                $this->menu = $data;
	}

	public function render()
	{
		$this->template->items = $this->menu;
		$this->template->setFile(__DIR__.'/menu.latte');
		$this->template->render();
	}

}
