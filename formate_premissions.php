<?php
function symbolic_perm($okt){
	$sym_perm_data = array('---','--x','-w-','-wx','r--','r-x', 'rw-','rwx');
	$num1 = $okt[1];
	$sym_perm['owner'] = $sym_perm_data["$num1"];
	$num2 = $okt[2];
	$sym_perm['group'] = $sym_perm_data["$num2"];
	$num3 = $okt[3];
	$sym_perm['other'] = $sym_perm_data["$num3"];
	return $sym_perm['owner'].$sym_perm['group'].$sym_perm['other'];
}
?>
