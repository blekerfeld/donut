<?php

	function pCurrencyDelete($id){

		global $donut;
		$q = "DELETE FROM currencies WHERE id = $id";
		return $donut['db']->query($q);
	}

	function pCurrencyUpdate($id, $name, $shortname, $symbol){

		global $donut;
		$q = "UPDATE currencies SET name = '$name', shortname  = '$short_name',  symbol = '$symbol' WHERE id = '$id';";
		return $donut['db']->query($q);
	}

	function pCurrencyAdd($name, $short_name, $symbol){

		global $donut;
		$q = "INSERT INTO currencies (`name`, `shortname`, `symbol`) VALUES ('$name', '$short_name', '$symbol');";
		return $donut['db']->query($q);
	}


	function pGetCurrencies(){

		global $donut;
		$q = "SELECT * FROM currencies WHERE id <> 0";
		return $donut['db']->query($q);
	}

	function pGetCurrencyZero(){
		global $donut;
		$q = "SELECT * FROM currencies WHERE id = 0";
		$rs = $donut['db']->query($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}

	function pGetCurrency($id){
		global $donut;
		$q = "SELECT * FROM currencies WHERE id = $id";
		$rs = $donut['db']->query($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}



?>