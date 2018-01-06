<?php
/*
Copyright (c) 2009 Grzegorz Żydek

This file is part of PGRFileManager v2.1.0

Permission is hereby granted, free of charge, to any person obtaining a copy
of PGRFileManager and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

PGRFileManager IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

//Include your own script with authentication if you wish
//i.e. include($_SERVER['DOCUMENT_ROOT'].'/_files/application/PGRFileManagerConfig.php');

// Start SESSION if not started 
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); 

// Set SESSION ADMIN Login Flag to false if not set
if (!isset($_SESSION['EZUSERID'])) $_SESSION['EZUSERID'] = false;

// Check logged in user ID
if (!$_SESSION['EZUSERID']) 
	die('<div><h1>Permission denied.<br>You must be logged in to access this</h1>
		 <p><img src="../../../img/noaccess.png"></p></div>');
		 
// Check if user has permissions for this.
if (!isset($_SESSION['MANAGEFILES'])) $_SESSION['MANAGEFILES'] = false;
if (!$_SESSION['MANAGEFILES']) 
	die('<div><h1>Permission denied.<br>You must have page edit privileges to access this</h1>
		 <p><img src="../../../img/noaccess.png"></p></div>');


//real absolute path to root directory (directory you want to use with PGRFileManager) on your server  
//i.e  PGRFileManagerConfig::$rootPath = '/home/user/htdocs/userfiles'
//you can check your absoulte path using
PGRFileManagerConfig::$rootPath = $_SERVER['DOCUMENT_ROOT'].'/site-assets';
//url path to root directory
//this path is using to display images and will be returned to ckeditor with relative path to selected file
//i.e http://my-super-web-page/gallery
//i.e /gallery
PGRFileManagerConfig::$urlPath = '/site-assets';

//    !!!How to determine rootPath and urlPath!!!
//    1. Copy mypath.php file to directory which you want to use with PGRFileManager
//    2. Run mypath.php script, i.e http://my-super-web-page/gallery/mypath.php
//    3. Insert correct values to myconfig.php
//    4. Delete mypath.php from your root directory


//Max file upload size in bytes
PGRFileManagerConfig::$fileMaxSize = 2048 * 2048 * 20;
//Allowed file extensions
//PGRFileManagerConfig::$allowedExtensions = '' means all files
PGRFileManagerConfig::$allowedExtensions = '';
//Allowed image extensions
PGRFileManagerConfig::$imagesExtensions = 'jpg|gif|jpeg|png|bmp';
//Max image file height in px
PGRFileManagerConfig::$imageMaxHeight = 1024;
//Max image file width in px
PGRFileManagerConfig::$imageMaxWidth = 2048;
//Thanks to Cycle.cz
//Allow or disallow edit, delete, move, upload, rename files and folders
PGRFileManagerConfig::$allowEdit = true;		// true - false
//Autorization
PGRFileManagerConfig::$authorize = false;        // true - false
PGRFileManagerConfig::$authorizeUser = 'user';
PGRFileManagerConfig::$authorizePass = 'password';
//Path to CKEditor script
//i.e. http://mypage/ckeditor/ckeditor.js
//PGRFileManagerConfig::$ckEditorScriptPath = '/ckeditor/ckeditor.js';
//File extensions editable by CKEditor
//PGRFileManagerConfig::$ckEditorExtensions = 'html|html|txt';