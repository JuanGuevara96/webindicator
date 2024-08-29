<?php 

function numabs($n){
    return ABS($n);
}
function miles($n){
    return $n = $n / 1000;
}
function half($n,$h){
    return $n = $n / $h;
}
function sum($row, $var2, $var1){
    if ($row == '1') {
        $var2 += $var1;
    }
    else if ($row == '0') {
        $var2 -= abs($var1);
    }
    return $var2;
}
function microtime_float() {
    list($useg, $seg) = explode(" ", microtime());
    return ((float)$useg + (float)$seg);
}

function privileges($str){
    $arr = str_split($_SESSION['sections'], 3);
    foreach ($arr as $value) {
        if (strtoupper($value) == $str) {
                return true;
        }	
    }
    return false;
}

function presupday($pmref){ //calcula el presupuesto al dia actual
	global $date;
	if (date('Ym') == $date) {
		$day = date('d');
		$month =  new \DateTime('now');
		$month_days = $month->format('t');	
		return $pmref / $month_days * $day;
	}
	else {
		return $pmref;
	}
}


function MultiArrtoList($arr, $column){
	//convierte array multidimensional en lista IN, [(1,2,3)]
		 $list="";
		 // $arraymap = array_map('current', $arr);
		 foreach ($arr as $value) {
		 	$list .= ", ".$value[$column];
		 }
		 return $list = substr($list, 1);
	}

function arrtoList($arr){
//convierte array multidimensional en lista IN, [(1,2,3)]
        $list="";
        // $arraymap = array_map('current', $arr);
        foreach ($arr as $value) {
        $list .= ", ".$value;
        }
        return $list = substr($list, 1);
}	 


function my_error_handler($error_no, $error_msg){
    //message error format
    echo "Opps, something went wrong:"
    +"\nError number: [$error_no]"
    +"\nError Description: [$error_msg]";
}	

?>