<?php 
function PaginateArray($p) { // Input page number
	if($p == 1){
		$s = 0;
		$f = 9;
	} else {
		$s = ($p - 1) * 10; // Eg, if page 2, start at 10; if page 3, start at 20
		$f = $s + 9; // Eg, if page 2, finish at 29; if page 3, finish at 29
	}
	return array($s, $f);
}
?>