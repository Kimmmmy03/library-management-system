<?php

$page_title = 'LIBRARIAN';
include ('./includes/header_librarian.html');
?>

<form action="librarian.php" method="post">
	<p>
		<h2>1. Librarian Features: </h2>
		<h3>1a. Books Management:</h3>
		<ol>
			<li>Insert New Books</li>
			<li>View and Select Books</li>
			<li> Update Book Details</li> 
			<li> Delete Books</li> 
		</ol>
		<h3>1b. Payment Management:</h3>
		<ol>
			<li>View Payment Records</li>
			<li>Update Payment Status</li>
		</ol>
	</p>
</form>
<?php
include ('./includes/footer.html');
?>