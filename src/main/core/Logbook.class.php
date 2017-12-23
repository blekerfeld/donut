<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: Logbook.class.php

class pLogbook{

	public function __construct(){
		
	}

	public static function write($identifier, $record){
		return (new pDataModel('log'))->prepareForInsert(array($identifier, $record, pUser::read('id'), date('Y-m-d H:i:s')))->insert();
	}

	public static function getIdentifierString($identifier, $record, $user){
		return self::parse($identifier, $record, $user);
	}

	protected static function parse($identifier, $record, $user){
		return self::mold()[$identifier]($record, $user);
	}

	protected static function mold(){
		return [
			'new_lemma' => function($record, $user){
				return "added a new word: <span class='native'>'" . pDataModel::getRecord('words', $record)['native'] . "'</span> ";
			},

		];
	}

}