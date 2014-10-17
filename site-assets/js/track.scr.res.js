/*
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 ** Javascript ** 
 *
 * This is used to create the cookie to store 
 * screen resolution for php visitor tracking script. 
 *
*/
function writePhpTACookie() {
	date=new Date;
	date.setMonth(date.getMonth()+1);
	var name = "phpTA_resolution";
	var value = screen.width +"x"+ screen.height;
	var domain = "ezsite.com";
	var path= "/";
	document.cookie=name+"="+escape(value)+"; expires="+date.toGMTString()+"; path="+path+"; domain="+domain;
}
window.onload=writePhpTACookie;
