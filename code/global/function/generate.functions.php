<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: apps.functions.php
*/

	mb_internal_encoding('UTF-8');

	function pLatexEscape($text) {
	// Prepare backslash/newline handling
	$text = str_replace("\n", "\\\\", $text); // Rescue newlines
	$text = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $text); // Strip all non-printables
	$text = str_replace("\\\\", "\n", $text); // Re-insert newlines and clear \\
	$text = str_replace("\\", "\\\\", $text); // Use double-backslash to signal a backslash in the input (escaped in the final step).

	// Symbols which are used in LaTeX syntax
	$text = str_replace("{", "\\{", $text);
	$text = str_replace("}", "\\}", $text);
	$text = str_replace("$", "\\$", $text);
	$text = str_replace("&", "\\&", $text);
	$text = str_replace("#", "\\#", $text);
	$text = str_replace("^", "\\textasciicircum{}", $text);
	$text = str_replace("_", "\\_", $text);
	$text = str_replace("~", "\\textasciitilde{}", $text);
	$text = str_replace("%", "\\%", $text);

	// Brackets & pipes
	$text = str_replace("<", "\\textless{}", $text);
	$text = str_replace(">", "\\textgreater{}", $text);
	$text = str_replace("|", "\\textbar{}", $text);

	// Quotes
	$text = str_replace("\"", "\\textquotedbl{}", $text);
	$text = str_replace("'", "\\textquotesingle{}", $text);
	$text = str_replace("`", "\\textasciigrave{}", $text);

	// Clean up backslashes from before
	$text = str_replace("\\\\", "\\textbackslash{}", $text); // Substitute backslashes from first step.
	$text = str_replace("\n", "\\\\", trim($text)); // Replace newlines (trim is in case of leading \\)
	return $text;
	}

	function pWriteUTF8File($filename,$content) {
        if($f=@fopen($filename,"w")){
       		fwrite($f, pack("CCC",0xef,0xbb,0xbf));
       		if(fwrite($f,$content))
       			if(fclose($f))
       				return true;
       			else
       				return false;
       		else
       			return false;
        }
       	else
       		return false;
}


//  @author Rodney Rehm

function mb_range($start, $end) {
    // if start and end are the same, well, there's nothing to do
    if ($start == $end) {
        return array($start);
    }
    
    $_result = array();
    // get unicodes of start and end
    list(, $_start, $_end) = unpack("N*", mb_convert_encoding($start . $end, "UTF-32BE", "UTF-8"));
    // determine movement direction
    $_offset = $_start < $_end ? 1 : -1;
    $_current = $_start;
    while ($_current != $_end) {
        $_result[] = mb_convert_encoding(pack("N*", $_current), "UTF-8", "UTF-32BE");
        $_current += $_offset;
    }
    $_result[] = $end;
    return $_result;
}

 ?>