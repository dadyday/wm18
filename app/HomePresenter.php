<?php
namespace App;

use Nette;

class HomePresenter extends Nette\Application\UI\Presenter {

    /** @var \My\Countries @inject */
    public $oCountries;
    /** @var \My\Groups @inject */
    public $oGroups;


    function actionDefault() {
        $this->template->hello = 'world';
    }

    function handleClick() {
        $this->template->hello = 'universe';
        $this->redrawControl('hello');
    }

    function actionGroups() {
        $this->template->oGroups = $this->oGroups;
    }

    function actionMatches() {
        $oMatches = [];
        foreach ($this->oGroups as $oGroup) {
            foreach ($oGroup->oMatches as $oMatch) {
                $oMatches[] = $oMatch;
            }
        }
        usort($oMatches, function($a, $b) {
            return $a->time < $b->time ? -1 : (
                $a->time > $b->time ? 1 : 0);
        });
        $this->template->oMatches = $oMatches;
    }

    function renderDefault() {
        $this->template->countries = $this->oCountries;
        $this->template->oGroups = $this->oGroups;
    }
}
