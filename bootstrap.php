<?php
require('functions.php');

/* ***********************************************
 * Language
 */
$lang = 'en_US';
if (isset($_GET['lang']) && file_exists(__DIR__.'/locale/'.$_GET['lang'].'.UTF-8/') && is_dir(__DIR__.'/locale/'.$_GET['lang'].'.UTF-8/')) {
  $lang = $_GET['lang'];
}

putenv('LC_ALL='.$lang.'.UTF-8');
setlocale(LC_ALL, $lang.'.UTF-8');

bindtextdomain('members', '../locale');

textdomain('members');

/* ***********************************************
 * Configuration
 */
$config = parse_ini_file('config.ini', TRUE);

define('MAILCHIMP_API_KEY', $config['mailchimp']['key']);