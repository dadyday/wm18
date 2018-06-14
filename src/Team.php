<?php

class Team {

	public
		$short,
		$name,
		$rank,

		$matches,
		$wins,
		$ties,
		$lost,
		$score,

		$goal,
		$anti,
		$diff;

	static function getAll() {
		static $aTeam = null;
		if (is_null($aTeam)) {
			$aCfg = include(__DIR__.'/cfg.score.php');
			$aTeam = [];
			$rank = 1;
			foreach ($aCfg as $aData) {
				$oTeam = new static($aData);
				$oTeam->rank = $rank++;
				$aTeam[$oTeam->short] = $oTeam;
			}

		}
		return $aTeam;
	}

	static function get($shortOrName) {
		$aTeam = static::getAll();
		if (isset($aTeam[$shortOrName])) return $aTeam[$shortOrName];

		$pattern = '~'.preg_quote($shortOrName).'~i';
		foreach ($aTeam as $oTeam) {
			if (preg_match($pattern, $oTeam->name)) return $oTeam;
		}
		return null;
	}

	function __construct($aData) {
		list(
			$continent, $quali,
			$this->short, $this->name,
			$this->matches, $this->wins, $this->ties, $this->lost,
			$this->goal, $this->anti
		) = $aData;

		$this->score = $this->wins*3 + $this->ties;
		$this->diff = $this->goal-$this->anti;

		$this->wins /= $this->matches;
		$this->ties /= $this->matches;
		$this->lost /= $this->matches;
		$this->score /= $this->matches;

		$this->goal /= $this->matches;
		$this->anti /= $this->matches;
		$this->diff /= $this->matches;
	}

	function html($align = 'center') {
		return '
			<div style="float:left; text-align: '.$align.'; padding: 0.1rem;">
				<span>
					'.$this->name.'
					<div style="width:6rem; line-height:0.2rem;">
						'.winHtml($this->wins, $this->ties, $this->lost).'
						'.goalHtml($this->goal, $this->anti).'
					</div>
				</span>
			</div>
		';
	}
}
