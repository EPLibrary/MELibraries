<?php
session_start();

$pageTitle="ME Libraries | Sign in";
include 'header.php';

$customerHashData =
  //trim($customer["PIN"]) .
  trim((string) $_SESSION['customer']["FIRSTNAME"]) .
  trim((string) $_SESSION['customer']["LASTNAME"]) .
  trim((string) $_SESSION['customer']["DOB"]) .
  trim((string) $_SESSION['customer']["STREET"]) .
  trim((string) $_SESSION['customer']["CITY"]) .
  trim((string) $_SESSION['customer']["PROVINCE"]) .
  trim((string) $_SESSION['customer']["POSTALCODE"]) .
  trim((string) $_SESSION['customer']["EMAIL"]) .
  trim((string) $_SESSION['customer']["PHONE"]) .
  trim((string) $_SESSION['customer']["PRIVILEGE_EXPIRES"]);
$_SESSION['customerHash']=md5($customerHashData);
?>

<div class="mainContent" id="mainContent">
  <a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
  <h1 class="pageTitle">Welcome</h1>

  <div class="subContent">
    <h2 class="purple" style="clear:both;">About you.</h2>

    <p>By using this service you allow the information about yourself shown below to be shared with other ME libraries.</p>

    <table class="personalInfo">
      <tr>
        <th>First Name:</th>
        <td><?=$_SESSION['customer']["FIRSTNAME"]?></td>
      </tr>
      <tr>
        <th>Last Name:</th>
        <td><?=$_SESSION['customer']["LASTNAME"]?></td>
      </tr>
      <tr>
        <th>Email Address:</th>
        <td><?=$_SESSION['customer']["EMAIL"]?></td>
      </tr>
      <tr>
        <th>Street Address:</th>
        <td><?=$_SESSION['customer']["STREET"]?></td>
      </tr>
      <tr>
        <th>City:</th>
        <td><?=$_SESSION['customer']["CITY"]?></td>
      </tr>
      <tr>
        <th>Province:</th>
        <td><?=$_SESSION['customer']["PROVINCE"]?></td>
      </tr>
      <tr>
        <th>Postal Code:</th>
        <td><?=$_SESSION['customer']["POSTALCODE"]?></td>
      </tr>
      <tr>
        <th>Phone:</th>
        <td><?=$_SESSION['customer']["PHONE"]?></td>
      </tr>
      <tr>
        <th>Sex:</th>
        <td><?=$_SESSION['customer']["SEX"]?></td>
      </tr>
      <tr>
        <th>Date of Birth:</th>
        <td><?=$_SESSION['customer']["DOB"]?></td>
      </tr>
      <tr>
        <th>Membership Expires:</th>
        <td><?=$_SESSION['customer']["PRIVILEGE_EXPIRES"]?></td>
      </tr>
    </table>


    <div id="verifyDiv" style="margin-top:20px;margin-bottom:10px;">
    <form name="verifyForm" id="verifyForm" action="signup.php" method="post">
      <label for="agree">I allow this information to be shared with other ME libraries. <input type="checkbox" name="agree" id="agree" onChange="enableButton('nextButton');" /></label>

      <span class="deadButton" id="deadButton" style="margin-left:50px;margin-right:50px;">Next&nbsp;&#9658;</span>

      <!--- Clicking this button submits all the known data--->
      <button type="button" class="button hidden" id="nextButton" style="padding-left:20px;padding-right:20px;margin-left:50px;margin-right:50px;" onClick="document.getElementById('verifyForm').submit()">Next &#9658;</button>
    </form>
    </div>
    <div style="clear:both;"></div>

  </div><!--subContent-->
  <div id="spacer"></div>
</div><!--mainContent-->
<?php include 'footer.php';