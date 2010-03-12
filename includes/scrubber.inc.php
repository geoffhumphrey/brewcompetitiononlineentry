<?php 
// ---------------------------- Scrubber --------------------------------------------------------------------
$string = array( 
chr(38) => "&#38;", 
chr(60) => "&#60;", 
chr(62) => "&#62;", 
chr(34) => "&#34;", 
chr(35) => "&#35;", 
chr(39) => "&#39;",
chr(176) => "&#176;"
);

$html_string = array( 
chr(38) => "&amp;", 
chr(60) => "&lt;", 
chr(62) => "&gt;", 
chr(34) => "&quot;", 
chr(39) => "&rsquo;",
"&#39;" => "&rsquo;",
chr(176) => "&deg;",
"“" => "&ldquo;",   // left side double smart quote
"”" => "&rdquo;",  // right side double smart quote
"‘" => "&lsquo;",  // left side single smart quote
"’" => "&rsquo;",  // right side single smart quote
"…" => "...",  // elipsis
"—" => "&mdash;",  // em dash
"–" => "&ndash;",  // en dash
);

$html_remove = array( 
"&amp;" => "&",
"&lt;" => "<", 
"&gt;" => ">", 
"&quot;" => "\"", 
"&rsquo;" => "'",
"&rdquo;" => "\"",
"&ldquo;" => "\"",
"&lsquo;" => "'",
"&#39;" => "\"",
"&deg;" => "",
"“" => "\"",   // left side double smart quote
"”" => "\"",  // right side double smart quote
"‘" => "'",  // left side single smart quote
"’" => "'",  // right side single smart quote
"…" => "...",  // elipsis
"—" => "--",  // em dash
"–" => "-",  // en dash
);

$space_remove = array( 
"&amp;" => "",
"&lt;" => "", 
"&gt;" => "", 
"&quot;" => "", 
"&rsquo;" => "",
"&#39;" => "",
"&deg;" => "",
" " => "",
"&nbsp;" => ""
);


?>