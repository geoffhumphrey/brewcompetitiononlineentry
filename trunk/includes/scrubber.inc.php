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
chr(34) => "&quot;",  
chr(37) => "&#37;",
chr(38) => "&amp;",
chr(39) => "&rsquo;", 
chr(60) => "&lt;", 
chr(62) => "&gt;", 
chr(161) => "&iexcl;",
chr(162) => "&cent;",
chr(163) => "&pound;",
chr(164) => "&curren;",
chr(165) => "&yen;",
chr(168) => "&uml;",
chr(169) => "&copy;",
chr(171) => "&laquo;",
chr(174) => "&reg;",
chr(176) => "&deg;",
chr(188) => "&frac14;",
chr(189) => "&frac12;",
chr(190) => "&frac34;",
chr(191) => "&iquest;",

"&#39;" => "&rsquo;",
"&apos;" => "&rsquo;",

"“" => "&ldquo;",   // left side double smart quote
"”" => "&rdquo;",  	// right side double smart quote
"‘" => "&lsquo;",  	// left side single smart quote
"’" => "&rsquo;",  	// right side single smart quote
"…" => "...",  		// elipsis
"—" => "&mdash;",  	// em dash
"–" => "&ndash;",  	// en dash
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
"&copy;" => "",
"&reg;" => "",
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