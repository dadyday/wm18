<?php
namespace App;

use Nette;

class HomePresenter extends Nette\Application\UI\Presenter {

    function actionDefault() {
        $this->template->hello = 'world';
    }

    function handleClick() {
        $this->template->hello = 'universe';
        $this->redrawControl('hello');
    }

    function renderDefault() {
        
    }
}
