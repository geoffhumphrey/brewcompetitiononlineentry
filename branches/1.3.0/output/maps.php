<?php 
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
$address = rtrim($id,"&amp;KeepThis=true");
$address = str_replace(' ', '+', $address);

if ($section == "map") {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Maps</title>
</head>
<body>
<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo $address; ?>&zoom=13&size=600x400&markers=color:red|<?php echo $address; ?>&sensor=false" />
</body>
</html>
<?php } 
if ($section == "driving") {
$driving = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
//echo $driving;
header(sprintf("Location: %s", $driving));
}
?>