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
        $from = (clone $aMatch[0]->time)->modify('00:00:00');
        $to = $aMatch[count($aMatch)-1]->time;
        #bdump([$aMatches, $from, $to]);

        $this->template->days = [];
        while ($from <= $to) {
            $oDay = new \stdClass;
            $oDay->date = clone $from;
            $oDay->dow = $oDay->date->format('N');
            $oDay->events = [];
            $aMatch = $oMatches->getForDay($from);
            usort($aMatch, function($a, $b) {
                return $a->time < $b->time ? -1 : (
                    $a->time > $b->time ? 1 : 0);
            });

            foreach ($aMatch as $n => $oMatch) {
                $oEvent = new \stdClass;
                $oEvent->match = $oMatch;
                $oEvent->space = 0;
                $oDay->events[] = $oEvent;
            }
            $h = 12;
            while ($aMatch && count($oDay->events) < 4) {
                if ($aMatch[0]->time->format('H') <= $h) break;
                array_unshift($oDay->events, new \stdClass);
                $oDay->events[0]->match = null;
                $h += 3;
            }
            while (count($oDay->events) < 4) {
                $oEvent = new \stdClass;
                $oEvent->match = null;
                $oDay->events[] = $oEvent;
            }

            $this->template->days[] = $oDay;
            $from->modify('+1 day');
        }
    }

    function renderDefault() {
        #$this->template->countries = $this->oCountries;
    }
}
