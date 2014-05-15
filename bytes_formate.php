<?php
function formate_bytes($B, $out=true){
        if ($B <= 1024){
                $Nenner = "B";
        }else{
                $B = $B/1024;
                if ($B <= 1024){
                        $Nenner = "kB";
                }else{
                        $B = $B/1024;
                        if ($B <= 1024){
                                $Nenner = "MB";
                        }else{
                                $B = $B/1024;
                                $Nenner = "GB";
                        }
                }
        }
        $B = round($B, 2);
	if ($out == true){
       		$output = "$B $Nenner";
        	return $output;
	}else{
		return $Nenner;
	}
}
?>
