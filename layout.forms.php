<?php

function sendemail($to, $sub, $msg) {
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= 'From: "Silverdale High School" <info@silverdalehighschool.com>' . "\r\n" .
    			'Reply-To: "Silverdale High School" <info@silverdalehighschool.com>' . "\r\n";
	return @mail($to, $sub, $msg, $headers);
}

// form must be post here ... if not then err
if ($_SERVER['REQUEST_METHOD']!='POST') die('x');

// form must be posted from within the site
if (isset($_SERVER["HTTP_REFERER"])) $form = $_SERVER["HTTP_REFERER"]; else die('xx');

if ($form=='http://www.silverdalehighschool.com/admission.html') {
	// Admissions form is posted here
	
	// ensure all the form variale are posted ... if not then die
	if (!isset($_POST['cboForm'])) die('xxx');
	if (!isset($_POST['txtName'])) die('xxx');
	if (!isset($_POST['txtAddress'])) die('xxx');
	if (!isset($_POST['txtMobile'])) die('xxx');
	if (!isset($_POST['txtEmail'])) die('xxx');
	if (!isset($_POST['txtComments'])) die('xxx');

	// fetch all the posted fields.
	$cboForm=$_POST['cboForm'];
	$name=trim($_POST['txtName']);
	$address=trim($_POST['txtAddress']);
	$mobile=trim($_POST['txtMobile']);
	$email=trim($_POST['txtEmail']);
	$comments=trim($_POST['txtComments']);
	
	// check which form is requested for download
	if($cboForm=="S.S.C") $downloadfile="Admission%20Form-SSC.pdf";
	else if($cboForm=="N.I.O.S") $downloadfile="Admission%20Form-NIOS.pdf";
	else if($cboForm=="H.S.C Science") $downloadfile="Admission%20Form-JC-Science.pdf";
	else if($cboForm=="H.S.C Commerce") $downloadfile="Admission%20Form-JC-Commerce.pdf";	
	else die('xx');
	
	// validate the data
	// name must be more that 2 in len
	if (strlen($name)<2) die('xxn');
	// mobile must be more that 6 in len
	if (!preg_match('/^[0-9]*$/', $mobile)) die('xxm');
	if (strlen($mobile)<6) die('xxmn');
	// email must be valid
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) die('xxe');
	// address cannot be blank
	if (strlen($address)<7) die('xxa');
	
	// send an email to the user
	$emailContentUser="<p>Dear <b>$name:</b></p>
  <p>Thank you for requesting the $cboForm Admission Package.</p>
  <p><a title='$cboForm Admission Package' 
  	href='http://www.silverdalehighschool.com/site-assets/file/$downloadfile'>
  	Please click here to download the $cboForm Admission Package in PDF format.</a></p>
  <p><a title='Silverdale Prospectus'
  	href='http://www.silverdalehighschool.com/site-assets/file/Silverdale-Prospectus.pdf'>
  	To download the Silverdale Prospectus please click here.</a></p>
  <p>Thanking you.</p>
  <p><b>Silverdale High School Admission Team.</b><br />
    Ring Road, Panchgani,<br />
    District Satara 412 805<br />
    Maharashtra, India.</p>
  <p>Tel: 091-2168-240638 / 241850<br />
    Telefax: 091-2168-240268<br />
    Email: info@silverdalehighschool.com</p>";
	
	// send an email to the admin
	$emailContentAdmin="<p>Dear <b>Silverdale High School</b>:</p>
  <p>The website has recieved a $cboForm Admission Form Request..</p>
  <p><b>Admission to: </b> $cboForm</p>
  <p><b>Name: </b> $name</p>
  <p><b>Address: </b> $address</p>
  <p><b>Mobile: </b> $mobile</p>
  <p><b>Email: </b> $email</p>
  <p><b>Comments: </b> $comments</p>
  <p>Thanking you.</p>
  <p><b>HMI Tech Support Team.</b></p>";
  
	//echo($emailContent);
	// redirect to thank you page	
	
	if (sendemail($email, "$cboForm Admission Package", $emailContentUser)) {
		// done
		sendemail("info@silverdalehighschool.com", "New $cboForm Admission Package Request", $emailContentAdmin);
		header ("Location: /request-sent.html");
		
	} else {
		// failed
		header ("Location: /request-failed.html");
	}
	exit;
} else {
	// form is posted from unknown url.
	die('xx');
}?>
