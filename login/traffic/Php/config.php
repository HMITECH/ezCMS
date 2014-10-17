<?php
global $tmpdirectory, $ip2c, $dirFlags, $extFlag;

// Cookie text (text displayed when loggued in as administrator)
$cookieTxt = "Admin";

// Lines below should only be changed if you know what you're doing!

// Option to set the default language for the user interface
// Leave empty for auto-selection (en: English, fr: French, es: Spanish, ru: Russian, de: German, nl: dutch...)
$defaultlang = "";
// Location of tmp directory (relative to the main phpTrafficA folder)
$tmpdirectory = "./tmp/";

// Option to set a custom page in case of mysql errors
// To be used to redirect visitor when your server is down
$mysql_error_forward = "0"; // Set to 0 (no redirection) or 1 (redirection)
$mysql_error_location = "http://www.google.com/";  // Where to go


// The lines below should not be changed //

// Locating of IP2C
$ip2c = "./Ip2c";
// Directory with flag images
$dirFlags = "./Flags/";
// Extension for flag images
$extFlag = "png";
// Agents which are bots
$botsagents = array("Googlebot", "Google Adwords", "Crawler");
// Available stylesheets
$stylesheetList = array("red", "green", "purple");
?>
