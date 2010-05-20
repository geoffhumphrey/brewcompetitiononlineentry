<?php



// Based upon a script from www.plus2net.com 



include "../Connections/config.php";

include "../includes/db_connect.inc.php"; 



$username = $_POST['loginUsername'];



mysql_select_db($database, $brewing);

$query_forgot = "SELECT * FROM users WHERE user_name = '$username'";

$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());

$row_forgot = mysql_fetch_assoc($forgot);

$totalRows_forgot = mysql_num_rows($forgot);



if ($totalRows_forgot == 0) { header("Location: ../index.php?section=login&amp;action=forgot&amp;msg=1"); }

if ($_POST['userQuestionAnswer'] == $row_forgot['userQuestionAnswer']) { //if answer is correct



/*

echo $username."<br>";

echo $query_forgot."<br>";

echo $row_forgot['user_name']."<br>";

echo $totalRows_forgot."<br>";



$em = $row->username;// email is stored to a variable



// Send the email with key

*/



// function to generate random number

function random_generator($digits){

srand ((double) microtime() * 10000000);

//Array of alphabets

$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");



$random_generator = "";// Initialize the string to store random numbers

for ($i=1;$i<$digits+1;$i++) { // Loop the number of times of required digits

	if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet

	// Add one random alphabet 

	$rand_index = array_rand($input);

	$random_generator .=$input[$rand_index]; // One char is added

	}

	else

	{

	// Add one numeric digit between 1 and 10

	$random_generator .=rand(1,10); // one number is added

	} // end of if else

} // end of for loop 



return $random_generator;



} // end of function





$key = random_generator(10);

//echo $key;



$password = md5($key);

$updateSQL = sprintf("UPDATE users SET password='%s' WHERE user_name='%s'", $password, $username);

					   

  mysql_select_db($database, $brewing);

  $Result = mysql_query($updateSQL, $brewing) or die(mysql_error());

  

  $updateGoTo = "../index.php?section=login&amp;go=".$key."&amp;msg=2";

  header(sprintf("Location: %s", $updateGoTo)); 



} else {

header("Location: ../index.php?section=login&amp;action=forgot&amp;go=verify&amp;msg=2&amp;username=".$username); 

}



/*

$headers4	 = $row_contest_info['contestContactEmail'];

$headers	.= "Reply-to: $headers4\n";

$headers 	.= "From: $headers4\n"; 

$headers 	.= "Errors-to: $headers4\n"; 

$headers 	= "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail

 

if (mail("$em","Password Reset","This is in response to your request for a password reset at from the ".$row_contest_info['contestName']." entry site. \n \nYour user name: $row->userid \n Your new password: ".$key."\n\n

\n\n Thank You \n \n The ".$row_contest_info['contestName']." Staff","$headers")) 

{

header("Location: ../index.php?section=login&amp;action=forgot&amp;msg=2");

}

else

{ 

header("Location: ../index.php?section=login&amp;action=forgot&amp;msg=3");

}

*/



//}

?>