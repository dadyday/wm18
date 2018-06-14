<?php
namespace My;

class Compare {

	public
		$oTeam1,
		$oTeam2,

		$wins,
		$ties,
		$lost,

		$goal,
		$anti,
		$result;

	static function create($oTeam1, $oTeam2) {
		return new static($oTeam1, $oTeam2);
	}

	function __construct($oTeam1, $oTeam2) {
		$this->oTeam1 = is_string($oTeam1) ? Team::get($oTeam1) : $oTeam1;
		$this->oTeam2 = is_string($oTeam2) ? Team::get($oTeam2) : $oTeam2;

		#$aComp = $this->oTeam1->compare($this->oTeam2);
		$this->_calc();
	}

	function _calc() {

		$this->wins = ($this->oTeam1->wins + $this->oTeam2->lost) / 2;
		$this->ties = ($this->oTeam1->ties + $this->oTeam2->ties) / 2;
		$this->lost = ($this->oTeam1->lost + $this->oTeam2->wins) / 2;

		$this->goal = ($this->oTeam1->goal + $this->oTeam2->anti) / 2;
		$this->anti = ($this->oTeam1->anti + $this->oTeam2->goal) / 2;

		$this->result = [
			round(($this->wins + $this->ties) * $this->goal, 0),
			round(($this->lost + $this->ties) * $this->anti, 0),
			#round($this->goal, 0),
			#round($this->anti, 0),
		];
		if ($this->result[0] == $this->result[1]) {
			if ($this->goal > $this->anti) $this->result[0]++;
			else if ($this->goal < $this->anti) $this->result[1]++;
			else if ($this->oTeam1->rank < $this->oTeam2->rank) $this->result[0]++;
			else $this->result[1]++;
		}
	}

	function getResult() {
		return $this->result;
	}

	function resultHtml() {
		$r = $this->getResult();
		return $r[0].' : '.$r[1];
	}

	function html() {
		return '
			<div style="float:left; width:4rem; text-align: center; padding: 0.1rem;">
				'.$this->resultHtml().'
				'.winHtml($this->win, $this->tie, $this->loss).'
				'.goalHtml($this->goal, $this->anti).'
			</div>
		';
	}

	function htmlFull() {
		return '
			<div style="clear:both;">
				'.$this->oTeam1->html('left').'
				'.$this->oTeam1->html().'
				'.$this->oTeam2->html('right').'
			</div>
		';
	}
}
