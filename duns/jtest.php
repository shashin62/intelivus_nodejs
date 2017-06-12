<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<a href="#" id="get-data">Get JSON data</a>
    <div id="show-data"></div>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="ajax.js"></script>
    <script>
		$(document).ready(function () {
		  $('#get-data').click(function () {
			var showData = $('#show-data');
		
			showData.text('Loading the JSON file.');
		
			jQuery.ajax({
				  url: 'http://54.255.136.38:8080/dunsreader/reader/read',
				  type: "POST",
				  data: {"id":null,"companyName1":"LVMH INC","address1":"19 E 57TH ST FL 16","city1":"NEW YORK","state1":"NY","zipcode1":null,"phone1":null,"companyName2":"LVMH INC","address2":null,"city2":null,"state2":null,"zipcode2":null,"phone2":null,"contactName":null,"duns":"121565709"},
				  dataType: "json",
				  beforeSend: function(x) {
					if (x && x.overrideMimeType) {
					  x.overrideMimeType("application/j-son;charset=UTF-8");
					}
				  },
				  success: function(result) {
				 	showData.text(result);
				 }
			});
			
			showData.text("Loading Done...");      
		
		  });
		});

</script>
</body>
</html>
