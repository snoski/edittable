<?php
session_start();

	$db = 'trivia';
	include_once 'includes/connect.php'; //connect.php CONTAINS CONN CODE BUT CREDS IN SEPARATE FILE
	include_once 'includes/myfuncs.php';

	//runSql($sql, $params = NULL)
	//$sql as string, $params as array
	// $quizmaster = runSql('SELECT * FROM users WHERE userID = ?',[1],'assoc');
	// fred('query result',$quizmaster);
	// if($quizmaster) {
	// 	echo 'query result shows as true<br>';
	// } else {
	// 	echo 'query result shows as false<br>';
	// }
	// $quizmaster = $quizmaster[0]['defLocState'];
	// if($quizmaster) {
	// 	echo 'defLocState shows as true<br>';
	// } else {
	// 	echo 'defLocState result shows as false<br>';
	// }
	// fred('result',$quizmaster);

	/*$sql = 'select * from test';
	//params:
	//		$sql as string, 
	//		optional $params as array (default null), 
	//		optional $arrayType as string (default 'num' or 'assoc')
	$prevLocArray = runSql($sql,NULL,'assoc');
	fred('result',$prevLocArray);
	$locationStr = "";
	if($prevLocArray) {
		$locationStr = "<label for=\"locSelect\">Location</label>";
		$locationStr .= "<select id=\"locSelect\" onchange=\"setCompany(this);\">";
		$locationStr .= "<option></option>";
		$locObjScriptStr = "<script>var locObj = {};\r\n";
		foreach ($prevLocArray as $val) {
			$city = $val['city'] ? $val['city'] : '';
			$state = $val['state'] ? $val['state'] : '';
			$locationName = $val['locationName'];
			$locObjScriptStr .= "locObj.$locationName = '$state';\r\n";
			$locationStr .= "<option>$locationName, $city, $state</option>";
			// $locationStr .= "<option>$val['locationName']</option>";
		}
		$locObjScriptStr .= '</script>';
		$locationStr .= '<option>--Location Not Listed--</option>';
		$locationStr .= '</select>';
	}*/

?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			table {
				border: solid black 1px;
				border-collapse: collapse;
				font-size: 24pt;
			}
			th {
				border: solid black 1px;
			}
			td {
				border: solid black 1px;
			}
		</style>
	</head>
	<body>
		<h2>Date Validation Using jQuery Datepicker</h2>
		<table id="tableId" data="users" style="border: solid black 1px">
			<colgroup>
				<col data="userId">
				<col data="userFName">
				<col data="defLocState">
			</colgroup>
			<tr>
				<th data-row="0" data-column="0">Id</th>
				<th data-row="0" data-column="1">First Name</th>
				<th data-row="0" data-column="2">Last Name</th>
			</tr>
			<tr>
				<td id="dataId1" data-row="1" data-column="0" ondblclick="testFunc(this);"
					>1</td>
				<td data-row="1" data-column="1" ondblclick="testFunc(this);"
				>Jeff</td>
				<td id="dataId3"  data-row="1" data-column="2" ondblclick="testFunc(this);"
				>Carter</td>
			</tr>
			<tr>
				<td id="dataId1" data-row="2" data-column="0" ondblclick="testFunc(this);"
				>2</td>
				<td data-row="2" data-column="1" ondblclick="testFunc(this);"
				>Todd</td>
				<td id="dataId3" data-row="2" data-column="2" ondblclick="testFunc(this);"
				>Payne</td>
			</tr>
		</table>
		<script>
			var lookupRow;
			var lookupCol;
			function testFunc(elem) {
				lookupRow = elem.dataset.row;
				lookupCol = elem.dataset.column;
				var tbl = document.getElementById("tableId");
				console.log('row',lookupRow);
				console.log('column',lookupCol);
				console.log('userId',tbl.rows[lookupRow*1].cells[0].innerHTML);
				console.log('field',tbl.rows[0].cells[lookupCol*1].innerHTML);
				var oldText = elem.innerHTML;
				elem.innerHTML = '';
				var newElem = document.createElement("input");
				newElem.type = 'text';
				newElem.value = oldText;
				newElem.setAttribute('onkeydown','enterVal(event,this);')
				elem.appendChild(newElem);
				window.setTimeout(function ()
				{
					newElem.focus();
				},25);
				window.setTimeout(function ()
				{
					newElem.select();
				},25);
			}

			function enterVal(event,elem) {
				console.log(event.keyCode);
				var tbl = document.getElementById("tableId");
				var maxRows = tbl.rows.length;
				var maxCols = tbl.rows[0].cells.length;
				var parentTd = elem.parentElement;
				console.log('maxRows',maxRows);
				console.log('maxCols',maxCols);
				switch (event.keyCode) {
					case 13:
					case 40:
						saveVal();
						if (lookupRow*1 + 1 <= (maxRows - 1)) {
							testFunc(tbl.rows[lookupRow*1+1].cells[lookupCol*1]);
						} else {
							testFunc(parentTd);
						}
						break;
					case 39:
						saveVal();
						if (lookupCol*1 + 1 <= (maxCols - 1)) {
							testFunc(tbl.rows[lookupRow*1].cells[lookupCol*1+1]);
						} else {
							testFunc(parentTd);
						}
						break;
					case 9:
						if (event.shiftKey) {
							saveVal();
							if (lookupCol*1 - 1 >= 0) {
								testFunc(tbl.rows[lookupRow*1].cells[lookupCol*1-1]);
							} else {
								testFunc(parentTd);
							}
						} else {
							saveVal();
							if (lookupCol*1 + 1 <= (maxCols - 1)) {
								testFunc(tbl.rows[lookupRow*1].cells[lookupCol*1+1]);
							} else {
								testFunc(parentTd);
							}
						}
						break;
					case 37:
						saveVal();
						if (lookupCol*1 - 1 >= 0) {
							testFunc(tbl.rows[lookupRow*1].cells[lookupCol*1-1]);
						} else {
							testFunc(parentTd);
						}
						break;
					case 38:
						saveVal();
						if (lookupRow*1 - 1 >= 0) {
							testFunc(tbl.rows[lookupRow*1-1].cells[lookupCol*1]);
						} else {
							testFunc(parentTd);
						}
						break;
					case 27:
						saveVal();
						break;					
				}

				function saveVal() {
					var newText = elem.value;
					parentTd.innerHTML = newText;
				}
				// enter - 	13-----
				// up -		38-----
				// down -	40-----
				// left -	37-----
				// right -	39-----
				// tab -	9------
				// esc -	27
			}


			function validate() {
				var inp = document.getElementById("datepicker");
				try {
					console.log('inp.value is ' + inp.value + ' in the try block');
					throw 'intentional error';
					console.log('2nd log attept in try');
				} catch (e) {
					console.log('inp.value is ' + inp.value + ' in the catch block');
					throw 'intentional error';
					console.log('2nd log attept in catch');
				}
				console.log('inp.value is ' + inp.value + ' AFTER the catch block');
			}
		</script>
	</body>
</html>