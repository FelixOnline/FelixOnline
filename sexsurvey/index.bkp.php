<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title>Felix Sex Survey 2011</title>
  
  <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="../favicon.ico">

  <link rel="stylesheet" href="style.css">
  
</head>
<body>

<center><h1>Felix Sex Survey</h1></center>
<div id="info">
	<p>This survey is intended to be a light-hearted look at the sexual lives of Imperial students. We sincerely apologise if anyone is offended by any of the questions. Please answer honestly.</p>
	<p><i>Your answers and comments are strictly <b>confidential</b> (your password is required only to stop you voting twice, and not even the sysadmin can see who voted or how) and will not be released, leaked or left on a USB stick on a train. All data will be deleted within two weeks of the end of the survey.</i></p>
	<h3><i>You do not have to answer any questions that you do not feel comfortable with.</i></h3>
</div>
<?php

session_name("felix_sex_survey");
session_start();

$user = "media_felix_sex";
$db = "media_felix";
$pass = "vPVEqKzxeMZb8y6x";
mysql_connect("localhost",$user,$pass);
mysql_select_db($db);

if (!isset($_SESSION['felix_sex_survey']) || !$_SESSION['felix_sex_survey']['uname']) {
	if ($_POST['login']) {
		if (pam_auth($_POST['uname'],$_POST['pass'])) {
			$_SESSION['felix_sex_survey'] = strtolower($_POST['uname']);
		}
		else
			echo "<p>Authentication failed. Please go back and try again.</p>";
	}
	else {
?>
		<form method="post" id="loginForm">
		<p>Please enter your username/password to continue:</p>
		<table>
			<tr><td><label for="uname">IC Username:</label></td><td><input type="text" name="uname" /></td></tr>
			<tr><td><label for="pass">IC Password:</label></td><td><input type="password" name="pass" /></td></tr>
			<tr><td></td><td><input type="submit" value="Login" name="login" id="submitButton"/></td></tr>
		</table>
		</form>	
<?php
	}
}

if (isset($_SESSION['felix_sex_survey'])) {
	$id = sha1(md5($_SESSION['felix_sex_survey']));
	if ($_POST['submit']) {
		// foreach ($_POST as $k => $v)
			// if ($k != "submit") {
				// echo $k;
				// $sql = "ALTER TABLE `sexsurvey` ADD `$k` varchar(16)";
				// if (mysql_query($sql))
					// echo " added.<p />";
				// else
					// echo " NOT added: ".mysql_error()."<p />";
			// }
		foreach ($_POST as $k => $v) {
			if ($k != "submit") {
				$ks[] = "`".mysql_real_escape_string($k)."`";
				$vs[] = "'".mysql_real_escape_string($v)."'";
			}
		}
		$sql = "INSERT INTO `sexsurvey` (id,".(implode(",",$ks)).") VALUES ('$id',".(implode(",",$vs)).")";
		mysql_query($sql);
	}
	$sql = "SELECT COUNT(*) FROM sexsurvey WHERE id='$id'";
	$rsc = mysql_query($sql);
	list($match) = mysql_fetch_array($rsc);
	if ($match > 0) {
		echo "<div id='thankyou'><img src='thumbsup.jpg' width='200px'/><p>Thank you for submitting your answers to this survey. Your data will be deleted as soon after the survey as results have been aggregated.</p></div>";
	}
	else {
?>
		<form method="post">
		
		<h2>Basic information</h2>
		
		<p>Q1: Are you male or female?</p>
		<input type="radio" name="sex" value="male" /> Male<br />
		<input type="radio" name="sex" value="female" /> Female</br />
		
		<p>Q2: What age are you?</p>
		<input type="radio" name="age" value="17" /> 17<br />
		<input type="radio" name="age" value="18" /> 18<br />
		<input type="radio" name="age" value="19" /> 19<br />
		<input type="radio" name="age" value="20" /> 20<br />
		<input type="radio" name="age" value="21" /> 21<br />
		<input type="radio" name="age" value="22" /> 22<br />
		<input type="radio" name="age" value="23" /> 23<br />
		<input type="radio" name="age" value="24" /> 24<br />
		<input type="radio" name="age" value="25" /> 25+<br />
		
		<p>Q3: Which department are you in?</p>
		<input type="radio" name="dept" value="aero" /> Aero<br />
		<input type="radio" name="dept" value="bio" /> Bioengineering<br />
		<input type="radio" name="dept" value="bs" /> Business School<br />
		<input type="radio" name="dept" value="chem" /> Chemical Engineering<br />
		<input type="radio" name="dept" value="chemistry" /> Chemistry<br />
		<input type="radio" name="dept" value="civil" /> Civil Engineering<br />
		<input type="radio" name="dept" value="civil" /> Computing<br />
		<input type="radio" name="dept" value="ese" /> Earth Science and Engineering<br />
		<input type="radio" name="dept" value="eee" /> Electrical and Electronic Engineering<br />
		<input type="radio" name="dept" value="mat" /> Materials<br />
		<input type="radio" name="dept" value="med" /> Medicine<br />
		<input type="radio" name="dept" value="mech" /> Mechanical Engineering<br />
		<input type="radio" name="dept" value="life" /> Life Sciences<br />
		<input type="radio" name="dept" value="maths" /> Mathematics<br />
		<input type="radio" name="dept" value="phys" /> Physics<br />
		<input type="radio" name="dept" value="other" /> Other (please specify:)<br />
		<input type="text" name="dept_other" /><br />
		
		<p>Q4: In which year of study are you?</p>
		<input type="radio" name="year" value="1" /> 1st<br />
		<input type="radio" name="year" value="2" /> 2nd<br />
		<input type="radio" name="year" value="3" /> 3rd<br />
		<input type="radio" name="year" value="4" /> 4th<br />
		<input type="radio" name="year" value="5" /> 5th<br />
		<input type="radio" name="year" value="6" /> 6th<br />
		<input type="radio" name="year" value="m" /> Masters<br />
		<input type="radio" name="year" value="p" /> PhD<br />
		
		<p>Q5: Are you in a relationship?</p>
		<input type="radio" name="relationship" value="yes" /> Yes <br />
		<input type="radio" name="relationship" value="no" /> No <br />
		
		<h2>Sex</h2>
		
		<p>Q6: At what age did you lose your virginity?</p>
		<input type="radio" name="virginity" value="13" /> 13<br />
		<input type="radio" name="virginity" value="14" /> 14<br />
		<input type="radio" name="virginity" value="15" /> 15<br />
		<input type="radio" name="virginity" value="16" /> 16<br />
		<input type="radio" name="virginity" value="17" /> 17<br />
		<input type="radio" name="virginity" value="18" /> 18<br />
		<input type="radio" name="virginity" value="19" /> 19<br />
		<input type="radio" name="virginity" value="20" /> 20<br />
		<input type="radio" name="virginity" value="21" /> 21<br />
		<input type="radio" name="virginity" value="22" /> 22<br />
		<input type="radio" name="virginity" value="23" /> 23<br />
		<input type="radio" name="virginity" value="24" /> 24<br />
		<input type="radio" name="virginity" value="25" /> 25+<br />
		<input type="radio" name="virginity" value="n" /> I am a virgin<br />

		<p>Q7: How many sexual partners have you had?</p>
		<input type="radio" name="partners" value="0" /> 0<br />
		<input type="radio" name="partners" value="1" /> 1-5<br />
		<input type="radio" name="partners" value="6" /> 6-10<br />
		<input type="radio" name="partners" value="11" /> 11-15<br />
		<input type="radio" name="partners" value="16" /> 16-20<br />
		<input type="radio" name="partners" value="21" /> 21-30<br />
		<input type="radio" name="partners" value="31" /> 31+<br />
		
		<p>Q8: How often do you have sex?</p>
		<input type="radio" name="often" value="a" /> Daily<br />
		<input type="radio" name="often" value="b" /> A few times a week<br />
		<input type="radio" name="often" value="c" /> Once a week<br />
		<input type="radio" name="often" value="d" /> Once a month<br />
		<input type="radio" name="often" value="e" /> A few times a year<br />
		<input type="radio" name="often" value="f" /> Less or never<br />
		
		<p>Q9: How often do you masturbate?</p>
		<input type="radio" name="masturbate" value="a" /> Daily<br />
		<input type="radio" name="masturbate" value="b" /> A few times a week<br />
		<input type="radio" name="masturbate" value="c" /> Once a week<br />
		<input type="radio" name="masturbate" value="d" /> Once a month<br />
		<input type="radio" name="masturbate" value="e" /> A few times a year<br />
		<input type="radio" name="masturbate" value="f" /> Less or never<br />
		
		<p>Q9: How often do you watch porn?</p>
		<input type="radio" name="porn" value="a" /> Daily<br />
		<input type="radio" name="porn" value="b" /> A few times a week<br />
		<input type="radio" name="porn" value="c" /> Once a week<br />
		<input type="radio" name="porn" value="d" /> Once a month<br />
		<input type="radio" name="porn" value="e" /> A few times a year<br />
		<input type="radio" name="porn" value="f" /> Less or never<br />
		
		<h2>Sexual Orientation</h2>
		
		<p>Q10: What sexual orientation are you?</p>
		<input type="radio" name="orientation" value="s" /> Straight<br />
		<input type="radio" name="orientation" value="g" /> Gay<br />
		<input type="radio" name="orientation" value="b" /> Bisexual<br />
		
		<p>Q11: Have you ever kissed a member of the same sex?</p>
		<input type="radio" name="kisssamesex" value="yes" /> Yes<br />
		<input type="radio" name="kisssamesex" value="no" /> No<br />
		
		<p>Q12: Have you had sexual relations with a member of the same sex?</p>
		<input type="radio" name="sexsamesex" value="yes" /> Yes<br />
		<input type="radio" name="sexsamesex" value="no" /> No<br />
		
		<h2>Sexual Health</h2>
		
		<p>Q13: Have you ever had sex without protection?</p>
		<input type="radio" name="noprotection" value="yes" /> Yes<br />
		<input type="radio" name="noprotection" value="no" /> No<br />
		
		<p>Q14: What type of contraception do you usually use? </p>
		<input type="radio" name="contraception" value="condoms" /> Condoms<br />
		<input type="radio" name="contraception" value="pill" /> Pill<br />
		<input type="radio" name="contraception" value="coil" /> Coil<br />
		<input type="radio" name="contraception" value="femidom" /> Femidom<br />
		<input type="radio" name="contraception" value="withdraw" /> Withdrawal method<br />
		<input type="radio" name="contraception" value="implant" /> Implant<br />
		<input type="radio" name="contraception" value="other" /> Other<br />
		
		<p>Q15: Have you ever had an STI?</p>
		<input type="radio" name="sti" value="yes" /> Yes<br />
		<input type="radio" name="sti" value="no" /> No<br />
		
		<h2>At Imperial</h2>
		
		<p>Q16: Have you ever had sex or sexual relations on campus?</p>
		<input type="radio" name="campus" value="yes" /> Yes<br />
		<input type="radio" name="campus" value="no" /> No<br />
		
		<p>Q17: If yes, where?</p>
		
		<input type="radio" name="where" value="aero" /> Aero<br />
		<input type="radio" name="where" value="bess" /> Bessemer<br />
		<input type="radio" name="where" value="black" /> Blackett<br />
		<input type="radio" name="where" value="bs" /> Business School<br />
		<input type="radio" name="where" value="chem" /> Chemistry<br />
		<input type="radio" name="where" value="eastside" /> Eastside Bar<br />
		<input type="radio" name="where" value="eee" /> EEE<br />
		<input type="radio" name="where" value="ethos" /> Ethos<br />
		<input type="radio" name="where" value="huxley" /> Huxley<br />
		<input type="radio" name="where" value="library" /> Library<br />
		<input type="radio" name="where" value="mech" /> MechEng<br />
		<input type="radio" name="where" value="rsm" /> RSM<br />
		<input type="radio" name="where" value="saf" /> SAF<br />
		<input type="radio" name="where" value="sherfield" /> Sherfield<br />
		<input type="radio" name="where" value="skempton" /> Skempton<br />
		<input type="radio" name="where" value="union" /> Union<br />
		<input type="radio" name="where" value="wolf" /> Wolfson/Flowers<br />
		<input type="radio" name="where" value="other" /> Other (please specify:)<br />
		<input type="text" name="where_other" /><br />
		
		<p>Q18: Have you ever had sexual relations with a lecturer?</p>
		<input type="radio" name="lecturer" value="yes" /> Yes<br />
		<input type="radio" name="lecturer" value="no" /> No<br />
		
		<p>Q19: Do you feel like studying at Imperial has restricted your sex life?</p>
		<input type="radio" name="restrict" value="yes" /> Yes<br />
		<input type="radio" name="restrict" value="no" /> No<br />
		
		<p>Q19a: If so, how?</p>
		<textarea rows="4" cols="30" name="restrictcomments" id="textarea"></textarea>
		
		<h2>In the bedroom</h2>
		
		<p>Q20: Do you own any sex toys?</p>
		<input type="radio" name="toys" value="yes" /> Yes<br />
		<input type="radio" name="toys" value="no" /> No<br />
		
		<p>Q21: If yes, how many?</p>
		<input type="radio" name="numtoys" value="1" /> 1<br />
		<input type="radio" name="numtoys" value="2" /> 2<br />
		<input type="radio" name="numtoys" value="3" /> 3<br />
		<input type="radio" name="numtoys" value="4" /> 4<br />
		
		<p>Q22: Have you ever had a threesome?</p>
		<input type="radio" name="threesome" value="yes" /> Yes<br />
		<input type="radio" name="threesome" value="no" /> No<br />
		
		<p>Q23: Have you ever used handcuffs or other restraints?</p>
		<input type="radio" name="restraints" value="yes" /> Yes<br />
		<input type="radio" name="restraints" value="no" /> No<br />
		
		<p>Q24: Have you ever had anal sex?</p>
		<input type="radio" name="anal" value="yes" /> Yes<br />
		<input type="radio" name="anal" value="no" /> No<br />
		
		<p>Q25: Any further comments or stories?</p>
		<textarea rows="4" cols="30" name="comments" id="textarea"></textarea>
		
		<p />
		<input type="submit" name="submit" value="Submit" id="submitButton"/>
		</form>
<?php
	}
}

?>

</body>
</html>