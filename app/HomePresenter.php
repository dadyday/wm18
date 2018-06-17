<?php
namespace App;

use Nette;

class HomePresenter extends Nette\Application\UI\Presenter {

    /** @var \Nette\DI\Container @inject */
    public $oContainer;


    function actionDefault() {
        $this->template->hello = 'world';
    }

    function handleClick() {
        $this->template->hello = 'universe';
        $this->redrawControl('hello');
    }

    function actionGroups() {
        $this->template->oGroups = $this->oContainer->getByType(\My\Groups::class);
    }

    function actionMatches() {
        $oGroups = $this->oContainer->getByType(\My\Groups::class);

        $oMatches = [];
        foreach ($oGroups as $oGroup) {
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

    function actionTeams() {
        $this->template->oTeams = $this->oContainer->getByType(\My\Teams::class);
    }

    function renderDefault() {
        #$this->template->countries = $this->oCountries;
    }
}
