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
      && basename($_SERVER['PHP_SELF']) != 'stats.php'
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
  && basename($_SERVER['PHP_SELF']) != 'stats.php'
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
  && basename($_SERVER['PHP_SELF']) != 'stats.php'
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

<!-- SEO Stuff can go here. Meta tags, etc. -->

<script>
//This function resizes an element to fit within a certain available size.
function autoResize(id){
    let newHeight
    let newWidth
    const maxHeight = 326
    const minHeight = 190
    const sideMargin = 48
    const availableHeight = window.innerHeight - 600
    const availableWidth = window.innerWidth < 950 ? 950 - sideMargin - sideMargin : window.innerWidth - sideMargin - sideMargin

    let aspectRatio = 326/326
    if(document.getElementById(id)){
        if (availableHeight < (availableWidth / aspectRatio)) {
            newHeight = availableHeight
            newWidth = availableHeight * aspectRatio
        } else {
            newHeight = availableWidth / aspectRatio
            newWidth = newHeight * aspectRatio
        }
    }
    const el = document.getElementById(id)
    if (newHeight < minHeight) {
        el.style.height = minHeight + 'px'
        el.style.width = minHeight * aspectRatio + 'px'
    } else if (newHeight < maxHeight) {
        el.style.height = newHeight + 'px'
        el.style.width = newWidth + 'px'
    }
}

// Adjust the height of the '#mainContent' div based on the window height.
function adjustHeight(){
    const mainContent = document.querySelector('#mainContent')
    if (mainContent && window.innerHeight > mainContent.offsetHeight) {
        mainContent.style.height = (window.innerHeight - 20) + 'px'
    }
}

// Run functions on document ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('#joinMeImg')) autoResize('joinMeImg')
    adjustHeight()
})

// Run functions on window resize
window.addEventListener('resize', function() {
    if (document.querySelector('#joinMeImg')) autoResize('joinMeImg')
    adjustHeight()
})

// Displaying or hiding a button based on whether a checkbox is checked.
function enableButton(id) {
    const checkbox = document.querySelector('#agree')
    const deadButton = document.querySelector('#deadButton')
    const button = document.querySelector('#' + id)
    if (checkbox.checked) {
        deadButton.style.display = 'none'
        button.style.display = 'inline'
    } else {
        deadButton.style.display = 'inline'
        button.style.display = 'none'
    }
}

// Displaying a loading spinner.
function showSpinner() {
    document.querySelector('#enterText').style.display = 'none'
    document.querySelector('#loadSpinner').style.display = 'block'
    this.parentElement.submit('get_info.php')
}
</script>

</head>

<body>