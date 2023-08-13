<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: MDParser.class.php

class pMDParser extends ParsedownExtra
{

    function __construct()
    {
        $this->InlineTypes['['][] = 'LinkLemma';
        $this->InlineTypes['['][] = 'LinkLemmaDirect';
        $this->inlineMarkerList .= '[';
    }

    protected function inlineLinkLemma($excerpt)
    {
        if (preg_match('/^\[:(.*?):\]/', $excerpt['text'], $matches))
        {

            return array(

                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.

                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'span',
                    'text' => $this->internLinkLemma($matches[1]),
                    'attributes' => array(),
                ),

            );
        }
    }

    protected function inlineLinkLemmaDirect($excerpt)
    {
        if (preg_match('/^\[(.*?)\]/', $excerpt['text'], $matches))
        {

            return array(

                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.

                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'span',
                    'text' => $this->internLinkLemma($matches[1], true),
                    'attributes' => array(),
                ),

            );
        }
    }

    protected function internLinkLemma($input, $direct = false){
    	if($direct)
    	{
    		@$lemma = (new pDataModel("words"))->setCondition(" WHERE id = ".p::Quote(is_numeric($input) ? $input : p::HashId($input, true)[0]))->getObjects()->fetchAll()[0];
    		if($lemma == null)
    			return "<em class='red-ne ssignore'>NA-".$input."</em>";
    		return "<a href='".p::Url('?entry/'.p::HashId($lemma['id']))."' class='native lemmaLink'>".$lemma['native']."</a> ".(new pIcon('chevron-right', 13, 'opacity-6'));
    	}
    	$collapsed = explode('|', $input);
    	if(empty($collapsed[1])){
    		@$lemma = (new pDataModel("words"))->setCondition(" WHERE native = ".p::Quote($collapsed[0]))->getObjects()->fetchAll()[0];
    		if($lemma == null)
    			return "<em><a class='tooltip red-ne ssignore' ".(((new pUser)->noGuest() AND (new pUser)->checkPermission((int)CONFIG_PERMISSION_CREATE_LEMMAS)) ? "href='".p::Url('?editor/new/pre-filled/'.urlencode($collapsed[0]))."'" : '').">".$input."</a></em>";
    		return "<a href='".p::Url('?entry/'.p::HashId($lemma['id']))."' class='native lemmaLink'>".$lemma['native']."</a>".(new pIcon('chevron-right', 13, 'opacity-6'));
    	}else{
    		if(p::StartsWith($collapsed[1], ':')){
    			$id = substr($collapsed[1], 1);
    			@$lemma = (new pDataModel("words"))->setCondition(" WHERE id = ".p::Quote(is_numeric($id) ? $input : p::HashId($id, true)[0]))->getObjects()->fetchAll()[0];
    			if($lemma == null)
    				return "<em><a class='tooltip red-ne ssignore' ".(((new pUser)->noGuest() AND (new pUser)->checkPermission((int)CONFIG_PERMISSION_CREATE_LEMMAS)) ? "href='".p::Url('?editor/new/pre-filled'.urlencode($collapsed[0]))."'" : '').">".$collapsed[0]."</a></em>";
    			return "<a href='".p::Url('?entry/'.p::HashId($lemma['id']))."' class='native lemmaLink'>".$collapsed[0]."</a>".(new pIcon('chevron-right', 13, 'opacity-6'));
    		}
    		else{
    			@$lemma = (new pDataModel("words"))->setCondition(" WHERE native = ".p::Quote($collapsed[1]))->getObjects()->fetchAll()[0];
    			if($lemma == null)
    				return "<em><a class='tooltip red-ne ssignore' ".(((new pUser)->noGuest() AND (new pUser)->checkPermission((int)CONFIG_PERMISSION_CREATE_LEMMAS)) ? "href='".p::Url('?editor/new/pre-filled/'.urlencode($collapsed[1]))."'" : '').">".$collapsed[0]."</a></em>";
    			return "<a href='".p::Url('?entry/'.p::HashId($lemma['id']))."' class='native lemmaLink'>".$collapsed[0]."</a>".(new pIcon('chevron-right', 13, 'opacity-6'));
    		}
    	}
    }

}