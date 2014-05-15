<?php
function get_icon_path($typ,$filepath, $icon=true){
	$typ = strtolower($typ);
	switch ($typ){
                case 'htm':
                case 'html':
                        $icon_path = 'icons/htm.png';
			$fileinfo = "HTML Dokument";
                break;
                case 'docx':
                case 'doc':
                        $icon_path = 'icons/word.png';
			$fileinfo = "Microsoft Word";
                break;
                case 'pdf':
                        $icon_path = 'icons/pdf.png';
			$fileinfo = "Portable Document Format";
                break;
                case 'pptx':
                case 'ppt':
                        $icon_path = 'icons/ppt.png';
			$fileinfo = "Microsoft Powerpoint";
                break;
                case 'xls':
                case 'xlsx':
                        $icon_path = 'icons/xls.png';
			$fileinfo = 'Microsoft Excel';
                break;

                case 'avi':
                case 'mov':
		case 'mp4':
		case 'ogv':
		case 'webm':
                        $icon_path = 'icons/avi.png';
			$fileinfo = strtoupper($typ)." Videoformat";
                break;

                case 'mp3':
                case 'midi':
                case 'wav':
                        $icon_path = 'icons/mp3.png';
			$fileinfo = strtoupper($typ)." Audioformat";
                break;

                case 'sh':
                case 'bash':
                case 'cmd':
		case 'bat':
                        $icon_path = 'icons/sh.png';
			$fileinfo = "Command Line Program";
                break;

                case 'css':
                        $icon_path = 'icons/css.png';
			$fileinfo = "CSS Stylesheet";
                break;
                case 'xcf':
                        $icon_path = 'icons/xcf.png';
                        $fileinfo = "GIMP Projectfile";
                break;
                case 'exe':
                        $icon_path = 'icons/exe.png';
			$fileinfo = "Executable File";
                break;
                case 'php':
                        $icon_path = 'icons/php.png';
			$fileinfo = "Hypertext Preprocessor";
                break;
                case 'swf':
		case 'flv':
                        $icon_path = 'icons/swf.png';
                        $fileinfo = "Flashplayer File";
                break;
                case 'msi':
                        $icon_path = 'icons/msi.png';
                        $fileinfo = "Windows Installer File";
                break;

                case 'psd':
                        $icon_path = 'icons/psd.png';
                        $fileinfo = "Adobe Photoshop";
                break;
                case 'mov':
                        $icon_path = 'icons/mov.png';
                        $fileinfo = "MOV Videoformat";
                break;
                case 'sql':
                        $icon_path = 'icons/sql.png';
                        $fileinfo = "SQL Datenbankfile";
                break;
                case 'db':
		case 'sqlite':
		case 'sqlite3':
                        $icon_path = 'icons/sqlite.png';
                        $fileinfo = "SQLite Datnebankfile";
                break;


                case 'deb':
                        $icon_path = 'icons/deb.png';
			$fileinfo = "Debian Package";
                break;
                case 'rpm':
                        $icon_path = 'icons/rpm.png';
			$fileinfo = "Red Hat Package";
                break;
                case 'rb':
                        $icon_path = 'icons/ruby.png';
			$fileinfo = "Ruby Script";
                break;
                case 'pl':
                        $icon_path = 'icons/perl.png';
			$fileinfo = "Perl Script";
                break;
                case 'py':
                        $icon_path = 'icons/python.png';
			$fileinfo = "Python Script";
                break;
		case 'lock':
			$icon_path = 'icons/lock.png';
			$fileinfo = "Crypted File";
		break;
                case 'tar':
		case '7z':
		case 'zip':
		case 'gzip':
		case 'gz':
		case 'bz2':
			if ($typ == "gz"){
				$typ = "gzip";
			}
			if ($typ == "bz2"){
				$typ = "bzip2";
			}
                        $icon_path = 'icons/zip.png';
			$fileinfo = strtoupper($typ)." Archivefile";
                break;
                case 'iso':
                case 'dmg':
                        $icon_path = 'icons/iso.png';
			$fileinfo = strtoupper($typ)." Image";
                break;

		case 'jpg':
		case 'gif':
		case 'png':
		case 'bmp':
			$icon_path = 'icons/jpg.png';
			$img_size = getimagesize($filepath);
			$size_format = "(".$img_size[0]."X".$img_size[1].")";
			$fileinfo = strtoupper($typ)." Picture Format <i>".$size_format."</i>";
		break;
		case 'txt':
                        $icon_path = 'icons/txt.png';
			$fileinfo = "Text Document";
                break;
		default:
                        $icon_path = 'icons/txt.png';
			$fileinfo = strtoupper($typ)." File";
                break;
	}
	if ($icon == true){
		return $icon_path;
	}else{
		return $fileinfo;
	}
}
?>
