<?php
namespace App;

use Nette;

class HomePresenter extends Nette\Application\UI\Presenter {

    /** @var \Nette\DI\Container @inject */
    public $oContainer;


    function actionDefault() {
        $this->template->hello = 'world';
        $oCountries = $this->oContainer->getByType(\My\Countries::class);
        $this->template->countries = $oCountries->getAll();
    }

    function handleClick() {
        $this->template->hello = 'universe';
        $this->redrawControl('hello');
    }

    function actionGroups() {
        $oGroups = $this->oContainer->getByType(\My\Groups::class);
        $this->template->groups = $oGroups->getAll();
    }

    function actionTeams() {
        $oTeams = $this->oContainer->getByType(\My\Teams::class);
        $this->template->teams = $oTeams->getAll();
    }

    function actionMatches() {
        $oGroups = $this->oContainer->getByType(\My\Groups::class);

        $this->template->matches = [];
        foreach ($oGroups->getAll() as $oGroup) {
            foreach ($oGroup->matches as $oMatch) {
                $this->template->matches[] = $oMatch;
            }
        }
        usort($this->template->matches, function($a, $b) {
            return $a->time < $b->time ? -1 : (
                $a->time > $b->time ? 1 : 0);
        });
    }

    function actionCompare() {
        $oTeams = $this->oContainer->getByType(\My\Teams::class);
        $this->template->teams = $oTeams->getAll();
    }

    function actionCalendar() {
        $oMatches = $this->oContainer->getByType(\My\Matches::class);
        $aMatch= array_values($oMatches->getAll());
        $from = $aMatch[0]->time;
        $to = $aMatch[count($aMatch)-1]->time;
        #bdump([$aMatches, $from, $to]);

        $this->template->days = [];
        while ($from < $to) {
            $oDay = new \stdClass;
            $oDay->date = $from;
            $oDay->weekend = $from->format('n') == 1;
            $oDay->matches = $oMatches->getForDay($from);
            $this->template->days[] = $oDay;
            $from->modify('+1 day');
        }
    }

    function renderDefault() {
        #$this->template->countries = $this->oCountries;
    }
}
