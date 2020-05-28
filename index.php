<?php
session_start();

	$db = 'trivia';
	include_once 'includes/connect.php'; //connect.php CONTAINS CONN CODE BUT CREDS IN SEPARATE FILE
	include_once 'includes/myfuncs.php';
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
				<td id="dataId1" data-row="1" data-column="0" ondblclick="createInput(this);"
					>1</td>
				<td data-row="1" data-column="1" ondblclick="createInput(this);"
				>Jeff</td>
				<td id="dataId3"  data-row="1" data-column="2" ondblclick="createInput(this);"
				>Carter</td>
			</tr>
			<tr>
				<td id="dataId1" data-row="2" data-column="0" ondblclick="createInput(this);"
				>2</td>
				<td data-row="2" data-column="1" ondblclick="createInput(this);"
				>Todd</td>
				<td id="dataId3" data-row="2" data-column="2" ondblclick="createInput(this);"
				>Payne</td>
			</tr>
		</table>
		<script>
			var lookupRow;
			var lookupCol;
			function createInput(elem) {
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
							createInput(tbl.rows[lookupRow*1+1].cells[lookupCol*1]);
						} else {
							createInput(parentTd);
						}
						break;
					case 39:
						saveVal();
						if (lookupCol*1 + 1 <= (maxCols - 1)) {
							createInput(tbl.rows[lookupRow*1].cells[lookupCol*1+1]);
						} else {
							createInput(parentTd);
						}
						break;
					case 9:
						if (event.shiftKey) {
							saveVal();
							if (lookupCol*1 - 1 >= 0) {
								createInput(tbl.rows[lookupRow*1].cells[lookupCol*1-1]);
							} else {
								createInput(parentTd);
							}
						} else {
							saveVal();
							if (lookupCol*1 + 1 <= (maxCols - 1)) {
								createInput(tbl.rows[lookupRow*1].cells[lookupCol*1+1]);
							} else {
								createInput(parentTd);
							}
						}
						break;
					case 37:
						saveVal();
						if (lookupCol*1 - 1 >= 0) {
							createInput(tbl.rows[lookupRow*1].cells[lookupCol*1-1]);
						} else {
							createInput(parentTd);
						}
						break;
					case 38:
						saveVal();
						if (lookupRow*1 - 1 >= 0) {
							createInput(tbl.rows[lookupRow*1-1].cells[lookupCol*1]);
						} else {
							createInput(parentTd);
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
		</script>
	</body>
</html>