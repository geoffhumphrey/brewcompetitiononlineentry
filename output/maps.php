<?php 
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
$address = rtrim($id,"&amp;KeepThis=true");
$address1 = str_replace(' ', '+', $address);

if ($section == "map") {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Maps</title>
<link href="../css/default.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color:#fff;">
<div align="center">
<img style="border:1px solid #999;" src="http://maps.google.com/maps/api/staticmap?center=<?php echo $address1; ?>&zoom=13&size=900x500&markers=color:red|<?php echo $address1; ?>&sensor=false" />
</div>
<p align="center"><?php echo $address; ?></p>
</body>
</html>
<?php } 
if ($section == "driving") {
$driving = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address1;
//echo $driving;
header(sprintf("Location: %s", $driving));
}
?>