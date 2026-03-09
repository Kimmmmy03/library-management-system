<?php

$page_title = 'USER';
include ('./includes/header_user.html');
?>

<form action="user.php" method="post">
	<p>
		<h2>2. User (Public) Features: </h2>
		<h3>2a. Registration and Account Management:</h3>
		<ol>
			<li>Insert Registration Details</li>
			<li>View and Update Account Information</li>
		</ol>
		<h3>2b. Book Selection and Rental Management:</h3>
		<ol>
			<li>View Available Books (Select)</li>
			<li>Insert Selected Books for Rental</li>
			<li>View and Update Rental</li>
			<li>Submit Rental Request (Insert)</li>
		</ol>
	</p>
</form>
<?php
include ('./includes/footer.html');
?>