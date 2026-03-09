<?php 

$page_title = 'My Account';
include ('./includes/header_user.html');

require_once ('mysqli.php'); // Connect to the db.
global $dbc;

// Page header.
echo '<h2 id="mainhead">My Account</h2>';
echo '<h3 id="mainhead">View Account Information</h3>';
echo'<form action="user_view.php" method="post">';
	echo'<p>Email Address: <input type="text" name="email" size="20" maxlength="40" required/> </p>';
	echo'<p>Password: <input type="password" name="password1" size="10" maxlength="20" required/></p>';
	echo'<p><input type="submit" name="submit" value="Submit" /></p>';
echo'</form>';

if (isset($_POST['submit'])) {

	$e = $_POST['email'];
	$p = $_POST['password1'];
	
	$query1 = "select user_id, name, email, password from users
		     where email = '$e' and password = SHA('$p');";		
	$result1 = @mysqli_query ($dbc,$query1);// Run the query.
	$num1 = @mysqli_num_rows($result1);// OR die ('SQL Statement: ' . mysqli_error($dbc) );
	
	if ($num1 == 1) { 
	
		$row1 = mysqli_fetch_array($result1, MYSQLI_NUM); // Fetch the user data as a numeric array

        // Display user's details
		echo '<p>User ID: ' . $row1[0] . '</p>'; // $row1[0] is the 'user_id' column
        echo '<p>Name: ' . $row1[1] . '</p>'; // $row1[1] is the 'name' column
        echo '<p>Email Address: ' . $row1[2] . '</p>'; // $row1[2] is the 'email' column
		echo '<p>Password: ' . $row1[3] . '</p>'; // $row1[3] is the 'password' column

        mysqli_close($dbc); // Close the database connection.
					
						
	}else { // Invalid email address/password combination.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The email address and password do not match those on file.</p>';
	
	}
}

include ('./includes/footer.html'); // Include the HTML footer.
?>