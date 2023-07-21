<?php
session_start();

/* If the user is already signed in with a valid session, don't make them login again. */
if (isset($_SESSION['agree']) && isset($_SESSION['customer']['ID'])) header("Location: signup.php");
elseif (isset($_SESSION['customer']['ID'])) header("Location: welcome.php");

$pageTitle = "me card | Sign in";
include 'header.php';
?>

<img class="centered" id="joinMeImg" src="images/JOIN_ME_White_Square.png" alt="Join me" style="margin-top:8px;width:326px;height:326px;"/>

<div class="container" style="padding-bottom:10px;">
  <div class="centered" id="mainbox_container" style="float:right;right:50%;position:relative">
    <div id="meInfo" class="mainbox" style="display:block;"><!--<h1 style="margin-bottom:5px;text-align:center;">What does ME do?</h1>-->
    <h2 id="meInfoP">Use your library card to access over 10 million items from over 300 libraries &mdash; for free!</h2>
    <p>You must be 18 or older and have a card from a participating Alberta public library to sign up.</p>
    <p><span class="small">eBooks and other electronic content are not included due to licensing restrictions.</span>
    </p></div>
  </div><!--centered mainbox_container-->
  <div style="clear:both;"></div>

  <div class="centered" id="mainbox_container2" style="float:right;right:50%;position:relative;margin-bottom:10px;">
    <div class="mainbox" id="formDiv" style="padding:10px;">
      <h2 style="margin-left:20px;margin-bottom:10px;">Login</h2>
        <?php
        if (isset($_GET['timeout'])) {echo '<div id="errorTimeout" class="error" style="display:inline;">Your session has timed out - please login.</div>';}
        ?>
      <form name="loginForm" id="loginForm" action="get_info.php" method="post">
        <div class="formItem">
          <label class="login" for="cardNoField">Library card number</label>
          <input type="text" class="rounded" id="cardNoField" name="cardNoField" />
          <div id="errorCardNo" class="error">Invalid card number</div>
        </div>

        <div class="formItem" style="margin-bottom:12px;">
          <label class="login" for="pin">PIN</label>
          <input type="password" class="rounded" id="pin" name="pin"/>
        </div>

        <div style="text-align:center;margin-bottom:10px;">
          <!--- I'll have to put some kind of fancy spinner and a delay here --->
          <input type="submit" class="button enter" value="ENTER">
          <img src="images/ajax-loader.gif" id="loadSpinner" />
        </div>
      </form>

      <!--- we submit this form with jQuery after we have the library figured out --->
      <form name="jsonForm" id="jsonForm" action="welcome.php" method="post">
        <input type="hidden" id="jsonField" name="jsonField" value="" />
      </form>
    </div><!--formDiv-->
  </div><!--centered mainbox_container2-->
</div><!--container-->

<div id="backCurtain"></div>
<h3 style="text-align:center;clear:both;margin-top:20px;">A service of Alberta's Public Library Network</h3>
<div id="spacer">&nbsp;</div>
<?php
include 'footer.php';
?>