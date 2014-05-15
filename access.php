<?php
session_start();
include "mysql_conf.php";
include("blowfish/blowfish.class.php");
$connection = mysql_connect($MySQL_Host, $MySQL_Benutzer, $MySQL_Password)
        OR die ("Keine Verbindung zu der Datenbank moeglich.");
$db = mysql_select_db($MySQL_DB, $connection)
        OR die ("Auswahl der Datenbank nicht moeglich.");
if (isset($_COOKIE['erspu2435'])){
        $session_key = $_COOKIE['erspu2435'];
	$SESSION_UNIQ_KEY = $_SESSION['session_key'];
        $decrypte_blow = new Blowfish($SESSION_UNIQ_KEY);
        $enc_session_key = $decrypte_blow->Decrypt($session_key);
}else{
        echo "Sie m&uuml;ssen COOKIES aktivieren.";
}
$res = mysql_query('select * from user where email="'.$enc_session_key.'"');
while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
        $session_id = $entry['session'];
	$Homedir = $entry['homedir'];
	$quota = $entry['quota'];
	$quota_usd = $entry['usage_quota'];
	$id = $entry['id'];
	$Fullname = $entry['vorname']." ".$entry['nachname'];
}
if (isset($_SESSION['session_id'])){
        if ($session_id !== $_SESSION['session_id']){
                Header("Location: forbidden.php");
                exit;
        }
}else{
        Header("Location: login.php");
        exit;
}
?>
