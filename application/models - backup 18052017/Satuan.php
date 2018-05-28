<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Satuan extends CI_Model{

	public function printSatuan($getKey)
	{
		if ( $getKey == 'ltr' ) {
			return 'Satuan : Liter (L)';
		} elseif ( $getKey == 'kilo' ) {
			return 'Satuan : Kiloliter (Kl)';
		}
	}

	public function printValue($val, $getKey) {
		if ( $getKey == 'ltr' ) {
			return number_format($val,0);
		} elseif ( $getKey == 'kilo' ) {
			return number_format($val/1000,2);
		}	
	}

}