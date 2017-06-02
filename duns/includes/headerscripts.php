
<link rel="stylesheet" href="css/accord.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.collapse.js"></script>

<script src="js/chart.min.js"></script>
<script src="js/pretty-doughtnut.js"></script>

<script src="js/jquery.validate.min.js"></script>

<script type="text/javascript" language="javascript">
function log_out()
{
  bo = document.getElementsByTagName('body');
  bo[0].style.filter = 'Alpha(opacity="30")';
  bo[0].style.MozOpacity = '0.3';
  bo[0].style.opacity = '0.3';
  if (confirm('Are you sure wanted to logout?'))
  {
    location.replace('logout.php');
  }
  else
  {
    bo[0].style.filter = 'Alpha(opacity="100")';
    bo[0].style.MozOpacity = '1';
    bo[0].style.opacity = '1';
    
  }
}
</script>

<script type="text/javascript" language="javascript">

		function getProject(sel)
		{
			document.frm12.submit();
		}
		
	</script>
   
    <script>
	function updateClock ( )
 	{
 	var currentTime = new Date ( );
  	var currentHours = currentTime.getHours();
  	var currentMinutes = currentTime.getMinutes ( );
  	var currentSeconds = currentTime.getSeconds ( );

  	// Pad the minutes and seconds with leading zeros, if required
  	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  	// Choose either "AM" or "PM" as appropriate
  	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  	// Convert the hours component to 12-hour format if needed
  	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  	// Convert an hours component of "0" to "12"
  	currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  	// Compose the string for display
  	var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
  	
  	
   	$("#clock").html(currentTimeString);
   	  	
 }

$(document).ready(function()
{
   setInterval('updateClock()', 1000);
});

</script>

<script type='text/javascript'>
	var isCtrl = false;
	document.onkeyup=function(e)
	{
		if(e.which == 17)
			isCtrl=false;
	}
	document.onkeydown=function(e) {
		if (e.which == 17)
			isCtrl = true;
		if((e.which == 85)  && (isCtrl == true)){
		 return false;
		 }
		 if((e.which == 88) && (isCtrl == true)){
		 return false;
		 }
		 if((e.which == 86) && (isCtrl == true) ){
		 return false;
		 }
		 if((e.which == 67) && (isCtrl == true)){
		 return false;
		 }
	}
	var isNS = (navigator.appName == "Netscape") ? 1 : 0;
	if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
	function mischandler(){
		return false;
	}
	function mousehandler(e){
		var myevent = (isNS) ? e : event;
		var eventbutton = (isNS) ? myevent.which : myevent.button;
		if((eventbutton==2)||(eventbutton==3)) return false;
	}
	document.oncontextmenu = mischandler;
	document.onmousedown = mousehandler;
	document.onmouseup = mousehandler;
</script>
