<?php
//Set max idle_time to 5 minutes (300 seconds)
define("MAX_IDLE_TIME", 300);

//Track number of pages viewed just for fun
if(isset($_SESSION['views']))
$_SESSION['views']=$_SESSION['views']+1;
else
$_SESSION['views']=1;
//echo "Views=". $_SESSION['views'];


//Timeout the session if the user hasn't loaded a page in a few minutes
if (!isset($_SESSION['timeout_idle']) || basename($_SERVER['PHP_SELF']) != 'get_info.php') {
    $_SESSION['timeout_idle'] = time() + MAX_IDLE_TIME;
} elseif ($_SESSION['timeout_idle'] < time()) {    
        //destroy session
		session_destroy();
		if (basename($_SERVER['PHP_SELF']) != 'index.php'
			&& basename($_SERVER['PHP_SELF']) != 'privacy.php'
			&& basename($_SERVER['PHP_SELF']) != 'help.php'
			&& basename($_SERVER['PHP_SELF']) != 'participating.php') {
			header("Location: index.php?timeout");
		}
    } else {$_SESSION['timeout_idle'] = time() + MAX_IDLE_TIME;}

//Requires the user's IP to match the one that the session was started with
if (!isset($_SESSION['originating_ip'])) {
	$_SESSION['originating_ip']=$_SERVER['REMOTE_ADDR'];
}elseif ($_SESSION['originating_ip']!=$_SERVER['REMOTE_ADDR']) {
		session_destroy();//Hasta la vista, baby
};


//Ensure the user has agreed to our terms before allowing them in.
if (!isset($_SESSION['agree'])
	&& basename($_SERVER['PHP_SELF']) != 'logout.php'
	&& basename($_SERVER['PHP_SELF'])!= 'index.php'
	&& basename($_SERVER['PHP_SELF']) != 'privacy.php'
	&& basename($_SERVER['PHP_SELF']) != 'help.php'
	&& basename($_SERVER['PHP_SELF']) != 'participating.php'	
	&& basename($_SERVER['PHP_SELF'])!= 'welcome.php') {
	header("Location: welcome.php"); 
}


//Kick the user back to the login screen if they don't have a customer array (and thus haven't successfully logged in)
//Informational pages are exempted from this.
if (!isset($_SESSION['customer']) 
	&& basename($_SERVER['PHP_SELF']) != 'logout.php'
	&& basename($_SERVER['PHP_SELF']) != 'privacy.php'
	&& basename($_SERVER['PHP_SELF']) != 'help.php'
	&& basename($_SERVER['PHP_SELF']) != 'participating.php'
	&& basename($_SERVER['PHP_SELF']) != 'index.php') {
	header("Location: index.php");
	die();
}

?>
<!DOCTYPE html>
<html>

<head>

<link rel="icon" type="image/png" href="/images/favicon_64x64.png" />
<!--[if IE]>
	<link rel="SHORTCUT ICON" type="image/x-icon" href="/favicon_32x32.ico" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="me.css" />

<title><?=$pageTitle?></title>

<!--
SEO Stuff can go here. Meta tags, etc.
-->



<script type="text/javascript" language="Javascript" src="jquery-1.10.2.min.js"></script>

<script language="Javascript">
	
	//This function resizes an element to fit within a certain available size.
	//I can tweak it with by adjusting the availableHeight/availableWidth variables if necessary
	function autoResize(id){
		var newHeight;
		var newWidth;
		var maxHeight=326;
		var minHeight=190;
		var availableWidth;
		var sideMargin=48;
		var availableHeight=$(window).height()-600;
		if ($(window).width()<950) availableWidth=950-sideMargin-sideMargin;
		else availableWidth=$(window).width()-sideMargin-sideMargin;
		
		var aspectRatio=326/326;	
		if(document.getElementById){

			if (availableHeight<(availableWidth/aspectRatio)) {
				newHeight=availableHeight;
				newWidth=availableHeight*aspectRatio;
			} else {
				newHeight=availableWidth/aspectRatio;
				newWidth=newHeight*aspectRatio;
			}

		}
		if (newHeight < minHeight) {
			document.getElementById(id).style.height= minHeight+"px";
			document.getElementById(id).style.width= minHeight*aspectRatio+"px";
		} else if (newHeight < maxHeight) {
			document.getElementById(id).style.height= newHeight+"px";
			document.getElementById(id).style.width= newWidth+"px";
		}
	}		
	
	$(document).ready(function(){
		if ($('#joinMeImg').length) autoResize('joinMeImg');
		if ($(window).height() > $('#mainContent').height()) $('#mainContent').height($(window).height()-20+'px');
		
	});
	
	$(window).resize(function () {
		if ($('#joinMeImg').length) autoResize('joinMeImg');
		if ($(window).height() > $('#mainContent').height()) $('#mainContent').height($(window).height()-20+'px');
	});



	function enableButton(id) {
		if (document.getElementById('agree').checked==true) {
			document.getElementById('deadButton').style.display='none';
			document.getElementById(id).style.display='inline';
		} else {
			document.getElementById('deadButton').style.display='inline';
			document.getElementById(id).style.display='none';
		}
	}


	
	
	function showSpinner() {
		$('#enterText').hide();
		$('#loadSpinner').show();
		//$('#backCurtain').show();	
		$(this).parent().submit('get_info.php');
	}
	
	
</script>

</head>

<body>