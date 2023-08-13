<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Emma de Roo - MIT License
//	++		File: derivation.class.php
// 	Represents a derivation

class pDerivation{

	protected $_data, $_inflection, $_input = array(), $_skipLemmas;

	public function __construct($id){
		
		// Loading the derivation itself
		$this->_data = (new pDataModel('derivation'))->setWhereID($id)->getAndFetch();
		
		// Let's create an inflection out of the rule
		$this->_inflection = new pInflection($this->_data['rule']);

	}

	protected function getInputRules(){

		// The lexical categories
		foreach((new pDataModel('derivation_lexcat'))->setCondition(" WHERE derivation_id = ".$this->_data['id'])->getAndFetchAll() as $lexcat)
			$this->_input['lexcat_Str'][] = $lexcat['lexcat_id'];

		// The grammatical categories
		foreach((new pDataModel('derivation_gramcat'))->setCondition(" WHERE derivation_id = ".$this->_data['id'])->getAndFetchAll() as $gramcat)
			$this->_input['gramcat_Str'][] = $gramcat['gramcat_id'];

		// The grammatical tags
		foreach((new pDataModel('derivation_tags'))->setCondition(" WHERE derivation_id = ".$this->_data['id'])->getAndFetchAll() as $tag)
			$this->_input['tags_Str'][] = $tag['tag_id'];

		// The lemmas that should be skipped
		foreach((new pDataModel('words'))->setCondition(" WHERE derivation = ".$this->_data['id'])->getAndFetchAll() as $word){
			$this->_input['skip_Str'][] = $word['derived_from'];
			$this->_input['skip_Str'][] = $word['id'];
		}

	}


	public function compile(){
		// Loading the input rules...
		$this->getInputRules();
		// Let's load the lemmas, based on those rules...
		$this->loadLemmas();
		// Chain this thing
		return $this;
	}

	protected function loadLemmas(){
		
		// Building a where clause, using the rules.
		$where = array((!empty($this->_input['skip_Str']) ? " id NOT IN (" . implode(', ', $this->_input['skip_Str']) . ")" : false), (!empty($this->_input['lexcat_Str']) ? "type_id IN (" . implode(', ', $this->_input['lexcat_Str']) . ")" : false), (!empty($this->_input['gramcat_Str']) ? "classification_id IN (" . implode(', ', $this->_input['gramcat_Str']) . ")" : false), (!empty($this->_input['tags_Str']) ? "subclassification_id IN (" . implode(', ', $this->_input['tags_Str']) . ")" : false), " (derivation <> ".$this->_data['id']." OR derivation IS NULL)");

		// Load the lemmas that are compatiable with the rules, and are not already done
		$this->_lemmas = (new pDataModel('words'))->setCondition(" WHERE ".join(" AND ", array_filter($where)))->getAndFetchAll();
	}

	public function derive(){
		$twolcIPA = (new pTwolc((new pTwolcRules('phonology_ipa_generation'))->toArray()))->compile();
		$twolcContext = (new pTwolc((new pTwolcRules('phonology_contexts'))->toArray()))->compile();
		foreach($this->_lemmas as $lemma)
			(new pDataModel('words'))->insertIgnore($twolcContext->feed($this->_inflection->inflect(($lemma['lexical_form'] != '' ? $lemma['lexical_form'] : $lemma['native'])))->toSurface(), $this->_inflection->inflect(($lemma['lexical_form'] != '' ? $lemma['lexical_form'] : $lemma['native'])), $twolcIPA->feed($this->_inflection->inflect(($lemma['lexical_form'] != '' ? $lemma['lexical_form'] : $lemma['native'])))->toSurface(), $lemma['hidden'], ($this->_data['output_lexcat'] != NULL ? $this->_data['output_lexcat'] : $lemma['type_id']), ($this->_data['output_gramcat'] != NULL ? $this->_data['output_gramcat'] : $lemma['classification_id']), ($this->_data['output_tag'] != NULL ? $this->_data['output_tag'] : $lemma['subclassification_id']), date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "-1", "", $this->_data['id'], $lemma['id']);
	}

}