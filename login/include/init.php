<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * Include: starts the session and checks if user is logged in and connects db
 * To be included on all logged in pages of admin
 */
session_start();
if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
if ($_SESSION['LOGGEDIN'] == false) { header("Location: index.php?flg=expired"); exit; }
include('../config.php');
?>