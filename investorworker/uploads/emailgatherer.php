<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Web Database Script</title>
 </head>
<body>			
<a href="../easternmasoftware/index.php">Back to lemonmama web apps Demos Page</a><br />
<br />
Enter Email Address<br />
<div style="padding: 20px;">


<?php
/* 
你好. Hello. 

2014-03-30
MIT license.
MIT 授權規定.

******
The MIT License (MIT)

Copyright (c) 2014 Morgan Jassen

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
******

START OF THE README SECTION. 
This is the email gathering web database script that consists of a single input textbox on a web page that validates email addresses before proceeding to store them into a MySQL database.
Instructions:
1.Use the "Create Database... ...Create Table..." script below to create your MySQL database.
2.update the user, password, server and db section, below to reflect your MySQL connection credentials. 
3.copy this insert.php file to your web server using FTP or other means.
4.you're done. now people browse to the insert.php page and can type in their email which if valid becomes inserted into the database.
END OF THE README SECTION
*/
?>

<?php
/* 你好. Hello. here's the database creation script, for reference purposes.(it'll have to be run manually to actually install the db)

CREATE DATABASE emailform;
USE emailform;

CREATE TABLE `table1` (
  `varchar1` varchar(250),
  `date1` datetime
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/
?>


<?php
// Begin logic to insert into the database
//initialize variables
$message_text = '';
$post_connect_user = 'root';
$post_connect_password = '123qaz804';
$post_connect_server = 'localhost';
$post_connect_db = 'emailform';
$post_text_varchar1 = '';
$email_is_valid = False;




//test if this form was posted from itself
if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {



//open connection to MySQL database
$mysqli = 0;
$mysqli = mysqli_connect($post_connect_server, $post_connect_user, $post_connect_password, $post_connect_db);


if (mysqli_connect_errno()) {
   $message_text .= "I'm sorry, DB Connection failed. ";
}


//if the sql connection is open then run the sql query
if ($mysqli){


//test if any fields were posted back
if ($_POST[text_varchar1] ) {



//read in the post values and prep them for insert


$post_text_varchar1 = mzzrdbmsinput(mzzprepareinput($_POST[text_varchar1]),$mysqli);


//this part validates the email addresses
if(!(validate_email($post_text_varchar1))){
	   $message_text .= "Please Enter a valid Email address. ";
}else{
	   $email_is_valid = True;


	   
//preserve utf-8 characters
$mysqli->query("SET NAMES 'utf8'");



$sql_insert = $mysqli->prepare("INSERT INTO table1 (varchar1, date1) VALUES('$post_text_varchar1', NOW())");

//test if the insert statement returned an error for example if the user didn't have INSERT privilege etc. etc.
if (!FALSE == $sql_insert){


$the_result = $sql_insert->execute();

	
	
//put a message about the result on the message stack
$the_result == 1 ? ($message_text .= "Thanks, we got your email address.") : ($message_text .= "I'm Sorry, there was an error inserting.") ;

}//end test to make sure the insert statement didn't return an error
else {
$message_text .= ' There was an error with the inserting of the data.';
}



}


//close MySQL connection
mysqli_close($mysqli);


}else{ //End test if any fields were posted back
$message_text .= 'Please enter an email address. ';
}

} //end test if the sql connection was open

} //end test if this form was posted from itself



//start of database string cleaning functions
// cleans a string to make it safe to input into mysql database.
  function mzzprepareinput($string) {
    if (is_string($string)) {
      return trim(mzzcleanstring(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = mzzprepareinput($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
    
  function mzzcleanstring($string) {
    $patterns = array ('/ +/','/[<>]/');
    $replace = array (' ', '_');
    return preg_replace($patterns, $replace, trim($string));
  }
   
  function mzzrdbmsinput($string, $linky) {

    if (function_exists('mysql_real_escape_string')) {
      return mysqli_real_escape_string($linky, $string);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }
//end of database string cleaning functions


// this function takes a string and returns true if it remotely resembles an email address
// @string $email
function validate_email($email){
return ereg("^[a-zA-Z0-9\._-]{1,30}@[a-zA-Z0-9\._-]{1,50}", $email) ;
}



	
		
		


// end logic to insert into the database
?>




<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">

<?php 
if($message_text != ''){
echo "Message: ". $message_text ;
?>
<br />
<?php
}
 ?>

<input type="text" name="text_varchar1" value="<?php if($email_is_valid == False){echo $post_text_varchar1;} ?>" size="20" maxlength="25"/>


<input type="hidden" name="action" value="process"/>

<input type="submit" name="submit" value="Go"/>

</form>


</body>

</div>
</html>
