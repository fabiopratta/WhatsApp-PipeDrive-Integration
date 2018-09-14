<?php
header('Content-type: text/html; charset=UTF-8');

include('../lib/emoji.php');

echo "EMOTICON<br/>";

$data = "\U0001f603";

function codeToSymbol($em) {
	if($em > 0x10000) {
		$first = (($em - 0x10000) >> 10) + 0xD800;
		$second = (($em - 0x10000) % 0x400) + 0xDC00;
		return json_decode('"' . sprintf("\\u%X\\u%X", $first, $second) . '"');
	} else {
		return json_decode('"' . sprintf("\\u%X", $em) . '"');
	}
}
$text = "This is fun \\U1f603!"; // this has just one backslash, it had to be escaped
echo "Database has: $text<br>";
$html = preg_replace("/\\\\u([0-9A-F]{2,5})/i", "&#x$1;", $text);
echo "Browser shows: $html<br>";

function str_encode_utf8binary($str) {
	/** @author Krinkle 2018 */
	$output = '';
	foreach (str_split($str) as $octet) {
		$ordInt = ord($octet);
		// Convert from int (base 10) to hex (base 16), for PHP \x syntax
		$ordHex = base_convert($ordInt, 10, 16);
		$output .= '\x' . $ordHex;
	}
	return $output;
}

function str_convert_html_to_utf8binary($str) {
	return str_encode_utf8binary(html_entity_decode($str));
}
function str_convert_json_to_utf8binary($str) {
	return str_encode_utf8binary(json_decode($str));
}

// Example for raw string: Unicode Character 'INFINITY' (U+221E)
#echo str_encode_utf8binary('∞') . "\n";
// \xe2\x88\x9e

// Example for HTML: Unicode Character 'HAIR SPACE' (U+200A)
#echo str_convert_html_to_utf8binary('&#8202;') . "\n";
// \xe2\x80\x8a

// Example for JSON: Unicode Character 'HAIR SPACE' (U+200A)
//echo str_convert_json_to_utf8binary('"\u0001f603"') . "\n";

?>