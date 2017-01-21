<?php

	function pCurrencyDelete($id){

		global $pol;
		$q = "DELETE FROM currencies WHERE id = $id";
		return $pol['db']->query($q);
	}

	function pCurrencyUpdate($id, $name, $shortname, $symbol){

		global $pol;
		$q = "UPDATE currencies SET name = '$name', shortname  = '$short_name',  symbol = '$symbol' WHERE id = '$id';";
		return $pol['db']->query($q);
	}

	function pCurrencyAdd($name, $short_name, $symbol){

		global $pol;
		$q = "INSERT INTO currencies (`name`, `shortname`, `symbol`) VALUES ('$name', '$short_name', '$symbol');";
		return $pol['db']->query($q);
	}


	function pGetCurrencies(){

		global $pol;
		$q = "SELECT * FROM currencies WHERE id <> 0";
		return $pol['db']->query($q);
	}

	function pGetCurrencyZero(){
		global $pol;
		$q = "SELECT * FROM currencies WHERE id = 0";
		$rs = $pol['db']->query($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}

	function pGetCurrency($id){
		global $pol;
		$q = "SELECT * FROM currencies WHERE id = $id";
		$rs = $pol['db']->query($q);
		if($rs->rowCount() == 0)
			return false;
		else
			return $rs->fetchObject();
	}



?>