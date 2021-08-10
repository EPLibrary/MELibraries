<!--- This pushes the page down so the footer doesn't overlap anything if you need to scroll to the bottom of content --->
<div class="footer">
<div class="footerText">Copyright <?php echo date("Y"); ?> | <a href="privacy.php" target="_blank">Terms &amp; Privacy Policy</a> | <a href="help.php" target="_blank">Help/FAQ</a> | <a href="participating.php" target="_blank">Participating Libraries</a>
<?php
	if (isset($_SESSION['customer'])) {
		echo '| <a href="logout.php" style="font-weight:bold;">Log Out</a>';
	}?>
</div>
<a href="http://www.melibraries.ca/index.php" style="border:none;"><img src="images/ME_logo.png" style="border:none;" alt="ME Libraries" id="footerLogo" /></a>
</div>
</div><!--logoAndForm-->


<script language="Javascript">
	/* attach a submit handler to the login form */
	$(document).on('submit', '#loginForm', function(event) {
		/* stop form from submitting normally */
		event.preventDefault();
		/* hide our error message if it's displayed */		
		$('#errorCardNo').hide();
		
		/* Validate to ensure that the numbers are the correct length/format and that there's data here.*/
		var cardNumber = document.getElementById('cardNoField').value;
		cardNumber = $.trim(cardNumber);
		if ((cardNumber.length == 14 || cardNumber.length == 13) && !isNaN(cardNumber)) {			
			/* Show the waiting spinner */
			//$('#enterText').hide();
			$('#loadSpinner').show();

			//Let's post the data with Ajax and figure out what library we are coming from.		
			url = $('#loginForm').attr( 'action' ),
			formdata = $('#loginForm').serialize(),
			/* Send the data using post */
			$.post( url, formdata)
			.done(function(data) {
				/* Detect error condition. */
				dataObj = JSON.parse(data);
				
				if (dataObj.error) {
					$('#errorCardNo').empty();
					$('#errorCardNo').append(dataObj.errorMsg);		
					$('#errorCardNo').fadeIn(300);
					$('#loadSpinner').hide();		
				
				} else {
					document.location.href='welcome.php';
				}
			});
		} else {
			$('#errorCardNo').fadeIn(300);
			$('#loadSpinner').hide();
		}
		//$('#loginForm').submit();
	});
</script>


</body>

</html>