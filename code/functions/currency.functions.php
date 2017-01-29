<?php

	function pCurrencyDelete($id){

		global $donut;
		$q = "DELETE FROM currencies WHERE id = $id";
		return pQuery($q);
	}

	function pCurrencyUpdate($id, $name, $shortname, $symbol){

		global $donut;
		$q = "UPDATE currencies SET name = '$name', shortname  = '$short_name',  symbol = '$symbol' WHERE id = '$id';";
		return pQuery($q);
	}

	function pCurrencyAdd($name, $short_name, $symbol){

		global $donut;
		$q = "INSERT INTO currencies (`name`, `shortname`, `symbol`) VALUES ('$name', '$short_name', '$symbol');";
		return pQuery($q);
	}


	function pGetCurrencies(){

		global $donut;
		$q = "SELECT * FROM currencies WHERE id <> 0";
		return pQuery($q);
	}

	function pGetCurrencyZero(){
		global $donut;
		$q = "SELECT * FROM currencies WHERE id = 0";
		$rs = pQuery($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}

	function pGetCurrency($id){
		global $donut;
		$q = "SELECT * FROM currencies WHERE id = $id";
		$rs = pQuery($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}



?>