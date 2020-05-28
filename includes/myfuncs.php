<?php

$db = 'trivia';
include_once 'connect.php'; //connect.php CONTAINS CONN CODE BUT CREDS IN SEPARATE FILE

function hsc($string) { //FOR ESCAPING OUTPUT
	return htmlspecialchars($string,ENT_QUOTES,'UTF-8',false);
}

function destroy_session_and_data() {	//LOGGING OUT OF SESSION
	header('Location:logout.php');
	exit();
}

//STRING MANIPULATION FUNCTIONS
function proper($str) {
	return ucwords(strtolower($str));
}


//DATE MANIPULATION FUNCTIONS
//right now this one just works with two formats: mm/dd/yyyy or m/d/yyyy
//further development will be necessary if I want to convert a 2-digit year
//or if the date has a different delimiter than the forward slash
//params:
//		$date (if user mm/dd/yyyy or m/d/yyyy) (if mysql yyyy-mm-dd)
//		$dir - either 'to' or 'from' (converting to mysql or from mysql)
function dateToFromMysql($date,$dir) {
	if ($dir === 'to') {
		$mth = substr($date,0,strpos($date,"/"));
		$mthLen = strlen($mth);
		if ($mthLen===1) {
			$mth = '0'.$mth;
		}
		$day = substr($date,$mthLen+1,strrpos($date,"/")-($mthLen+1));
		if (strlen($day)===1) {
			$day = '0'.$day;
		}
		$year = substr($date,-4);
		return "$year-$mth-$day";
	} else {
		return substr($date,5,2).'/'.substr($date,8,2).'/'.substr($date,0,4);
	}
}

//THIS FUNCTION UNPACKS ARRAYS (FOR EXAMPLE $_POST OR $_SESSION or any other array) TURNING EVERY KEY IN THE POST VARIABLE INTO A NORMAL PHP VARIABLE.
//IT ALSO HAS A DEBUGGING FEATURE WHERE IF $debug = true, THEN IT WILL ECHO ALL OF THESE VARIABLES AND THEIR VALUES
function unpackArr($array,$debug = null) {
	foreach ($array as $key => $val) {
		global ${$key};
		${$key} = $val;
		if ($debug) {
			echo "$key=${$key},<br>";
		}
	}
}

//SQL FUNCTIONS
//params:
//		$sql as string, 
//		optional $params as array (default null), 
//		optional $arrayType as string (default 'num' or 'assoc')
//example:
// 		$quizmaster = runSql('SELECT * FROM users WHERE userID = ?',[1],'assoc');
// 		$quizmaster = $quizmaster[0]['quizmaster'];
// 		if ($quizmaster) {
//			echo 'works as a boolean, and it is true';
// 		} else {
//			echo 'works as a boolean, and it is false';
// 		}
function runSql($sql, $params = null, $arrayType = 'num')
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    if (strtoupper(substr($sql,0,6)) === 'SELECT') {
	    if ($arrayType === 'num') {
	    	$stmt = $stmt->fetchAll(PDO::FETCH_NUM);
	    } elseif ($arrayType === 'assoc'){
	    	$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    } else {
	    	echo 'incorrect arrayType parameter';
	    }
	}
    return $stmt; 
}

function insertOrUpdate($tbl,$params) {
    //VARIABLE NAMES MUST BE EXACTLY THE SAME AS MYSQL COLUMN NAMES
    //FIRST PARAMETER WILL BE USED AS THE INSERT ID
    //RETURNS LAST INSERT ID OF THE TABLE (IF DUPLICATE, IT IS THE ID OF THE UPDATED ROW)
    //SKIPS FIELDS LEFT BLANK
    
    global $conn;
    $myVarArr = array();
    foreach ($params as $param) {
        global ${$param};
		$myVarArr[] = array(${$param},$param);
	}
	$fieldStrArr = array();
	$qStrArr = array();
	$valStrArr = array();
	$updStrArr = array();
	$updStrArr[] = $myVarArr[0][1].' = last_insert_id('.$myVarArr[0][1].')';
	foreach ($myVarArr as $var) {
		if (strlen($var[0]) !== 0) {
			$fieldStrArr[] = $var[1];
			$qStrArr[] = '?';
			$valStrArr[] = $var[0];
			$updStrArr[] = $var[1].' = VALUES('.$var[1].')';
		}
	}
	$fieldStr = implode(', ',$fieldStrArr);
	$qStr = implode(', ',$qStrArr);
	$valStr = implode(', ',$valStrArr);
	$updStr = implode(', ',$updStrArr);
	$sql = "INSERT INTO $tbl ($fieldStr) VALUES ($qStr) ON DUPLICATE KEY UPDATE $updStr";
	$conn->prepare($sql)->execute($valStrArr);
	$sql = "SELECT last_insert_id()";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$res = $stmt->fetchAll();
	return $res[0][0];
}

//DEBUGGING FUNCTIONS
function fred() { 
	//retrieves variables in argument list and iteratively dumps all vars then dies. useful for debugging multiple variables.
	//odd args are names as strings. even args are variables to be debugged.
	$x = 1;
	foreach (func_get_args() as $arg) {
		if ($x%2 === 0) {
				var_dump( $arg );
				echo '</br></br>';
		} else {
				echo '<strong>';
				echo $arg.':</strong></br>';
		}
		$x++;
	}
}

function freddie() { 
	//same as freddie, but dies
	//retrieves variables in argument list and iteratively dumps all vars then dies. useful for debugging multiple variables.
	//odd args are names as strings. even args are variables to be debugged.
	$x = 1;
	foreach (func_get_args() as $arg) {
		if ($x%2 === 0) {
				var_dump( $arg );
				echo '</br></br>';
		} else {
				echo '<strong>';
				echo $arg.':</strong></br>';
		}
		$x++;
	}
	die();
}
?>