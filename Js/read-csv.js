function handleFiles(files) {
	// Check for the various File API support.
	if (window.FileReader) {
		// FileReader are supported.
		getAsText(files[0]);
	} else {
		alert('FileReader are not supported in this browser.');
	}
}

function getAsText(fileToRead) {
	var reader = new FileReader();
	// Handle errors load
	reader.onload = loadHandler;
	reader.onerror = errorHandler;
	// Read file into memory as UTF-8      
	reader.readAsText(fileToRead, 'big5');
}

function loadHandler(event) {
	var csv = event.target.result;
	processData(csv);             
}

function processData(csv) {
    var allTextLines = csv.split(/\r\n|\n/);
    var lines = [];
    while (allTextLines.length) {
        lines.push(allTextLines.shift().split(','));
    }
	console.log(lines);
	drawOutput(lines);
}

function errorHandler(evt) {
	if(evt.target.error.name == "NotReadableError") {
		alert("Canno't read file !");
	}
}

function drawOutput(lines){
	//Clear previous data
	document.getElementById("timeTable").innerHTML = "";
	
	var jsonString = JSON.stringify(lines);
	$.ajax({
		  url: "Template_script.php",
		  data: {data : jsonString},
		  type:"POST",
		  success: function(msg){
			  $(".timeTable").append(msg);
			  ExcuteFalse();
		  },

		   error:function(xhr, ajaxOptions, thrownError){ 
			  alert(xhr.status); 
			  alert(thrownError); 
		   }
	  });
	
//	alert(lines[1][1]);
//	alert(lines[1][2]);
//	alert(lines[1][3]);
//	i=12, j=4
	var Html = '';
//	var table = document.createElement("table");
	document.getElementById("rows").value = lines.length ;
//		alert(lines.length);	12
//		alert(lines[i].length); none
	for (var i = 0; i < lines.length-1; i++) {	
//		if(i==0){
//			Html += '<tr>';
//		}else if(i%2==0){
//			Html += '<tr class="even">';
//		}else{
//			Html += '<tr class="odd">';
//		}
//		var row = table.insertRow(-1);
//		alert(lines[i].length);	4
		
		document.getElementById("cols").value = lines[i].length ;
//		for (var j = 0; j < lines[i].length; j++) {
//			if(i==0){	
//				Html += '<th><span class="hor-box-text-normal"><input id="TeachingPlan'+j+'" name="TeachingPlan'+j+'" value="'+lines[i][j]+'" style="background-color:transparent;border:0px;color: #514f4c;font-weight: 	normal;text-align: center;"></span></th>';			
//			}else{
//				Html += '<td><span class="hor-box-text-normal">'+lines[i][j]+'</span></td>';				
//			}
//			var firstNameCell = row.insertCell(-1);
//			firstNameCell.appendChild(document.createTextNode(lines[i][j]));
//		}
//		Html += '</tr>';
	}
								
//	$(".timeTable").append(Html);
	
//	document.getElementById("output").appendChild(table);

}

function ExcuteFalse(){
	
	if($("#ExcuteFalse").val()!= null){					//判斷是否有紅字用
		document.getElementById('inNote').style.display = 'block';
		document.getElementById('Go').disabled=true;　// 變更欄位為禁用
	}else{
		document.getElementById('inNote').style.display = 'none';
		document.getElementById('Go').disabled=false;　// 變更欄位為禁用
	}
}