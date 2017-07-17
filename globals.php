<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $THIS_VERSION;
$THIS_VERSION = "1.3.4";

if($TELAALBUMS_SHOW_ERRORS = "1"){
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
ini_set('display_errors', 1);
}
require_once dirname(__FILE__) . '/includes/telaalbums_functions.php';
require (dirname(__FILE__) . '/includes/telaalbums_options.php');
require_once dirname(__FILE__) . "/embedAlbumContents.php";
require_once dirname(__FILE__) . '/dumpAlbumList.php';
require_once dirname(__FILE__) . '/showAlbumContents.php';
require_once dirname(__FILE__) . '/shortcodes.php';
require_once dirname(__FILE__) . '/telaalbums.php';

?>