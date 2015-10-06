<?php
/**
* Rserve client for PHP
* Supports Rserve protocol 0103 only (used by Rserve 0.5 and higher)
* $Revision$
* @author Cl�ment TURBELIN
* Developped using code from Simple Rserve client for PHP by Simon Urbanek Licensed under GPL v2 or at your option v3
* This code is inspired from Java client for Rserve (Rserve package v0.6.2) developped by Simon Urbanek(c)
*/

/**
* R Double vector
*/
class Rserve_REXP_Vector extends Rserve_REXP {
	
	protected $values;
	
	public function __construct() {
		$this->values = array();
	}
	
	/**
	 * return int
	 */
	public function length() {
		return( count($this->values) );
	}
	
	public function isVector() {
		return true;
	}
	
	public function setValues($values) {
		$this->values = $values;
	}
	
	public function getValues() {
		return $this->values;
	}
	
	/**
	 * Get value 
	 * @param unknown_type $index
	 */
	public function at($index) {
		return $this->values[$index];
	}
	
	public function getType() {
		return Rserve_Parser::XT_VECTOR;
	}
	
	public function toHTML() {
		$s = '<div class="rexp vector xt_'.$this->getType().'">';
		$n = $this->length();
		$s .= '<span class="typename">'.Rserve_Parser::xtName($this->getType()).'</span> <span class="length">'.$n.'</span>';
		$s .= '<div class="values">';
		if($n) {
			$m = ($n > 20) ? 20 : $n;
			for($i = 0; $i < $m; ++$i) {
				$v = $this->values[$i];
				if(is_object($v) AND ($v instanceof Rserve_REXP)) {
					$v = $v->toHTML();
				} else {
					if($this->isString()) {
						$v = '"'.(string)$v.'"';
					} else {
						$v = (string)$v;
					}
				}
				$s .= '<div class="value">'.$v.'</div>';
			}
		}
		$s .= '</div>';
		$s .= $this->attrToHTML();
		$s .= '</div>';
		return $s;
	}
}