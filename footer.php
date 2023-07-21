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


<script>
/* attach a submit handler to the login form */
document.addEventListener('submit', function(e) {
    /* check if the event is fired by the loginForm */
    if (e.target.matches('#loginForm')) {
        /* stop form from submitting normally */
        e.preventDefault()
        /* hide our error message if it's displayed */
        document.getElementById('errorCardNo').style.display = 'none'

        /* Validate to ensure that the numbers are the correct length/format and that there's data here. */
        let cardNumber = document.getElementById('cardNoField').value
        cardNumber = cardNumber.trim()
        if ((cardNumber.length === 14 || cardNumber.length === 13) && !isNaN(cardNumber)) {
            /* Show the waiting spinner */
            // document.getElementById('enterText').style.display = 'none'
            document.getElementById('loadSpinner').style.display = 'block'

            /* Let's post the data with Ajax and figure out what library we are coming from. */
            const url = document.getElementById('loginForm').action
            const formdata = new FormData(document.getElementById('loginForm'))

            /* Send the data using post */
            fetch(url, {
                method: 'POST',
                body: formdata
            })
            .then(response => response.json())
            .then(data => {
                /* Detect error condition. */
                if (data.error) {
                    const errorCardNo = document.getElementById('errorCardNo')
                    errorCardNo.innerHTML = data.errorMsg
                    errorCardNo.style.display = 'block'
                    document.getElementById('loadSpinner').style.display = 'none'
                } else {
                    window.location.href = 'welcome.php'
                }
            })
        } else {
            document.getElementById('errorCardNo').style.display = 'block'
            document.getElementById('loadSpinner').style.display = 'none'
        }
        // document.getElementById('loginForm').submit();
    }
})
</script>


</body>

</html>
