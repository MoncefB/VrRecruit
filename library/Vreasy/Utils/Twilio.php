<?php

namespace Vreasy\Utils;

class Twilio {


// These are the words we'll be looking for in the Twilio messages
protected static $yes_words = array('yes', 'ok', 'okay', 'si', 'sí');
protected static $no_words = array('no', "non");



// If one of them is detected, we return one of the following answers 
const ANSWER_YES = 'Yes';
const ANSWER_NO = 'No';



// To do so, we will split the 'Body' (cf Twilio Doc) into a string table called keywords
// The following function aims at doing that, then analysing it

public static function analyzeAnswer($body) {

	// We first remove all the accents
	$message = htmlentities($body, ENT_NOQUOTES, 'utf-8');
    $message = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $message);
    $message = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $message);

    // We remove the capital letters
	$message = strtolower($message);

	// Then we remove the punctuation, and we replace it by underscores
	$message = preg_replace('/[^a-z0-9]+/i', '_', $message);

	// Then we split the message into a table, using the '_' delimiter
	$key_words = explode('_', $message);

	// Now, we can test if one of the keywords is a yes_word, or a no_word
	if(array_intersect(self::$yes_words, $key_words)) {
		return self::ANSWER_YES;
	}

	elseif(array_intersect(self::$no_words, $key_words)) {
		return self::ANSWER_NO;
	}

	else
		return 'Unknown';
	// How to interpret 'Unknown' answers ? Pending ?...

}

// NB : we still have to implement a function taking into account mistyping...
// By evaluating the distance between the strings ?

}

