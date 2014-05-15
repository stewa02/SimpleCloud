<?php
$script_types = array('sh','cf','css',
'c','js','diff',
'path','java',
'pl','php','ps',
'py','rb','sql',
'vba','vbs','vb',
'xml','html','hrm',
'xhtml');
$Language = strtolower($Language);
switch ($Language){
	case 'sh':
	$balias = 'shell';
	$Script_brush = "shBrushBash.js";
	break;
	case 'cf':
	$balias = 'cf';
	$Script_brush = "shBrushColdFusion.js";
	break;
	case 'css':
	$balias = 'css';
	$Script_brush = "shBrushCss.js";
	break;
	case 'c':
	$balias = 'c';
	$Script_brush = "shBrushCpp.js";
	break;
	case 'js':
	$balias = "js";
	$Script_brush = "shBrushJScript.js";
	break;
	case 'diff':
	case 'patch':
	$balias = 'diff';
	$Script_brush = "shBrushDiff.js";
	break;
	case 'java':
	$balias = 'java';
	$Script_brush = "shBrushJava.js";
	break;
	case 'pl':
	$balias = 'perl';
	$Script_brush = "shBrushPerl.js";
	break;
	case 'php':
	$balias = 'php';
	$Script_brush = "shBrushPhp.js";
        break;
        case 'ps':
	$balias = 'powersehll';
        $Script_brush = "shBrushPowerShell.js";
        break;
        case 'py':
	$balias = 'python';
        $Script_brush = "shBrushPython.js";
        break;
        case 'rb':
	$balias = 'ruby';
        $Script_brush = "shBrushRuby.js";
        break;
        case 'sql':
	$balias = 'sql';
        $Script_brush = "shBrushSql.js";
        break;
	case 'txt':
	case 'plain':
	$balias = 'plain';
	$Script_brush = "shBrushPlain.js";
	break;
        case 'vb':
	case 'vba':
	case 'vbs':
	$balias = 'vbnet';
        $Script_brush = "shBrushVb.js";
        break;
        case 'xml':
        case 'xhtml':
        case 'htm':
	case 'html':
	$balias = 'xml';
        $Script_brush = "shBrushXml.js";
        break;
}
?>
