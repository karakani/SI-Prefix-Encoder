<?php

namespace com\karakani\SIPrefixEncoder;

class Encoder {
	public function encode($integer) {
		$div = $this->_log10($integer);
		$suffix = $this->_prefix($div);

		return sprintf('%d%s', $integer / pow(10, $div), $suffix);
	}

	public function decode($string) {
		if (!preg_match('/^([0-9]+)(da|[hkMGTPEZY])?$/', $string, $match)) return FALSE;

		if (count($match) == 2) {
			return $match[1] - 0;
		}

		$number = $match[1];
		$prefix = $match[2];

		foreach ($this->prefixes as $k => $p) {
			if ($p === $prefix) {
				return $number * pow(10, $k);
			}
		}
		return FALSE;
	}

	protected function _log10($integer) {
		$div = 0;
		while ($integer % 10 == 0) {
			$div++;
			$integer /= 10;
		}

		if ($div > 3)  $div -= $div % 3;
		if ($div > 24) $div = 24;

		return $div;
	}

	protected $prefixes = [0 => '',
		   1 => 'da',
		   2 => 'h',
		   3 => 'k',
		   6 => 'M',
		   9 => 'G',
		   12 => 'T',
		   15 => 'P',
		   18 => 'E',
		   21 => 'Z',
		   24 => 'Y'];

	protected function _prefix($pow) {
		$prefix = '';
		foreach ($this->prefixes as $k => $p) {
			if ($k > $pow) break;
			$prefix = $p;
		}
		return $prefix;
	}
}

