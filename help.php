<?php
session_start();
$pageTitle="ME Libraries | Sign up";
include 'header.php';

/*
	On this page we will insert the member into the user table if they haven't been added yet.
	After this, we will insert the membership for the library that they have just joined into the membership table
	
	There will be a component here that requires talking to the server to do.
	Send request to create/update user, 
	$message=array(
		"code" => "CREATE_CUSTOMER",
		"authorityToken" => $authorityToken,
		"customer" => "null"
	);	
*/


?>
<div class="mainContent" id="mainContent">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Help & FAQ</h1>

<div class="subContent">
<h2>ME Libraries Frequently Asked Questions</h2>
<!--<p>The Metro Edmonton Federation of Libraries members are Edmonton Public Library, Ft. Saskatchewan Public Library, St. Albert Public Library, and Strathcona County Library. The Metro Federation members share an interest in collaborating to increase access to library collections and programs for our customers across the Metro Edmonton region. The ME Libraries project was created to reduce barriers, granting access to the library collections and diverse programs of the Metro Edmonton Federation libraries.</p>-->

<p>Alberta wide borrowing is here! ME Libraries lets you join other public libraries across Alberta and borrow their materials using ONE library card. Make the most of your library card and access more items for free!</p>
<p>ME Libraries is a service of Alberta Public Library Network.</p>

<h3>Overview</h3>
<ul>
	<li><span class="question">What is the ME Libraries service?</span>
		<span class="answer">ME Libraries is a web-based service that allows customers with a library card from a participating library to create an account with and access the physical collections at all other participating libraries. Interested customers complete a self-service web form to create an account with libraries other than their home library. This allows them to use their home library card as their library card at any participating library they have registered with. All items must be picked up at the owning library but can conveniently be returned to any ME library.</span>
	</li>


	<li><span class="question">Do I have to register with all of the ME libraries?</span>
		<span class="answer">No. You choose which libraries you want an account with as part of the web-based registration process.</span>
	</li>

	<li><span class="question">Are there any restrictions on which services I can access?</span>
		<span class="answer">You can access all of the collections available to a regular library cardholder at any library you create an account with <u>excluding</u> access to online resources and interlibrary loan services. Licensing restrictions for online resources do not currently permit member libraries to extend access to customers that do not live in their municipal area. You may use your home library for interlibrary loan services.</span>
	</li>

	<li><span class="question">Is there a cost to use the ME Libraries service and create accounts at member libraries?</span>
		<span class="answer">There is no cost to customers who have an existing home library card with one of the participating libraries.</span>
	</li>

	<li><span class="question">Do I have to pay late fees for overdue or lost materials at libraries I register with?</span>
		<span class="answer">Yes. ME Libraries is a service that creates a membership for you. You agree to abide by the borrowing rules of each library you create an account with. Any fees are to be paid directly to the billing library.</span>
	</li>
</ul>

<h3>Eligibility</h3>
<ul>
	<li><span class="question">Who is eligible to register to use the ME Libraries service?</span>
		<span class="answer">Adults, age 18 and over, with a home library card in good standing from one of the participating libraries may register for access to additional libraries via the ME Libraries service. You must have an e-mail address registered in your home library account to successfully use the ME Libraries service. All notices about your account(s) will be sent to your registered e-mail address.</span>
	</li>

	<li><span class="question">What about children's cards?</span>
	<span class="answer">Permission from a parent or guardian is required to register a child's card. Please register your child's card for access to participating libraries in person.</span>
	</li>

	<li><span class="question">I have paid for a reciprocal library card with one of the participating libraries. Can I get a refund?</span>
		<span class="answer">Unfortunately, no. Libraries participating in the ME Libraries service are not able to refund fees paid for previous reciprocal library cards. </span>
	</li>

	<li><span class="question">I already have a reciprocal library card with one of the participating libraries. If I sign up for an account at that library using the ME Libraries service, what happens to that reciprocal card account?</span>
		<span class="answer">In this scenario, you will have two accounts at that library and will be responsible for both. You may choose to close your reciprocal account by contacting the library.</span>
	</li>

	<li><span class="question">I have been using a TAL card (The Alberta Library). Why would I use the ME Libraries service?</span>
		<span class="answer">Your TAL card is valid at all participating library systems in Alberta, including the libraries that make up the ME Libraries service. However, the TAL card requires a separate card and in-person registration with each library you wish to use.</span>
	</li>

	<li><span class="question">I have outstanding charges on my existing home library account. Can I still register for the ME Libraries?</span>
		<span class="answer">Yes, if your account is deemed in good standing. This means that the charges on your account are below the limit where your home library restricts borrowing privileges.</span>
	</li>

</ul>

<h3>Registration</h3>
<ul>
	<li><span class="question">How do I register?</span>
		<span class="answer">It's simple. Go to <a href="http://melibraries.ca">http://melibraries.ca</a> and follow the registration prompts. Once registered, your home library card will immediately serve as your card for all libraries you have registered with.</span>
	</li>

	<li><span class="question">Anything else I need to know about registration?</span>
		<span class="answer">Some registrations may result in a modified PIN. You will be notified at registration if your PIN was changed. Also, the first time you use your barcode and PIN to access your online account at a new library, you may be prompted to create a username.</span>
	</li>

	<li><span class="question">What if I lose my home library card and/or get a replacement home library card for any reason?</span>
		<span class="answer">Please report a lost card to your home library as soon as possible. Once you have a replacement home library card, login to  Me Libraries with your new card number and PIN. Choose the “update” option for each library you have joined.</span>
	</li>

	<li><span class="question">What will my membership term be with at the libraries I register with?</span>
		<span class="answer">Your account membership length at the libraries you register with will be set as your home library expiry date or one year, whichever is shorter.</span>
	</li>

	<li><span class="question">Do I have to register for this service each year?</span>
		<span class="answer">Yes. You will need to renew your membership at all of the ME libraries you chose to register with each year. Once you've renewed your membership at your home library, visit <a href="http://melibraries.ca">melibraries.ca</a> to renew your other accounts.</span>
	</li>

	<li><span class="question">Why do I need to approve giving my personal account information as part of the registration process?</span>
		<span class="answer">We require this information to automatically set up your account(s) with the other libraries. You will be presented with the information that is being shared with each library and your approval is required before it is shared. Your personal information will not be used for any other purposes.</span>
	</li>

	<li><span class="question">I tried to register via the ME Libraries website but was unsuccessful. What do I do?</span>
		<span class="answer">Please contact your home library for assistance. They will troubleshoot your account and determine the cause.</span>
	</li>
</ul>

<h3>Contact</h3>
<ul>
	<li><span class="question">Who do I contact for more information about this service?</span>
		<span class="answer">Contact your home library for more information.
	</li>
</ul>


<a href="http://melibraries.ca/">Return to MELibraries.ca</a>	

	
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>