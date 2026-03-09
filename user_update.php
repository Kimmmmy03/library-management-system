<?php
// Set the page title and include the HTML header.
$page_title = 'Update User Details';
include ('./includes/header_user.html');

require_once('mysqli.php'); // Include the database connection file
global $dbc;

// Handle form submission
if (isset($_POST['submitted'])) {
    $errors = array();

    // Validate user ID
    if (empty($_POST['user_id'])) {
        $errors[] = 'You forgot to select a user.';
    } else {
        $user_id = (int)$_POST['user_id'];
    }

    // Validate user name
    if (empty($_POST['name'])) {
        $errors[] = 'You forgot to enter the user name.';
    } else {
        $n = $_POST['name'];
    }

    // Validate email
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = $_POST['email'];
	}
	
	// Check for a password and match against the confirmed password.
	if (!empty($_POST['password1'])) {
		if ($_POST['password1'] != $_POST['password2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = $_POST['password1'];
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	
	if (empty($errors)) { // If no errors, proceed with the update
        $query = "UPDATE users SET name='$n', email='$e', password=SHA('$p') WHERE user_id = $user_id";
        $result = @mysqli_query($dbc, $query);

        if ($result && mysqli_affected_rows($dbc) > 0) {
            echo '<h1>Success!</h1>
                  <p>User details have been updated successfully.</p>';
        } else {
            echo '<h1>System Error</h1>
                  <p class="error">User details could not be updated. Please try again.</p>';
        }
    } else { // Report errors
        echo '<h1>Error!</h1>
              <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) {
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
    }
	
	

    mysqli_close($dbc); // Close the database connection
}
?>

<h2>My Account</h2>
<h3>Update Account Information</h3>
<form action="user_update.php" method="post">
    <p>Select user:
        <select name="user_id" required>
            <option value="">-- Select a User --</option>
            <?php
            // Fetch the list of users
            $query = "SELECT user_id, name FROM users";
            $result = @mysqli_query($dbc, $query);

            while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<option value="' . $row['user_id'] . '">' . $row['name'] . '</option>';
            }

            mysqli_free_result($result);
            ?>
        </select>
    </p>
    <p>Name: <input type="text" name="name" size="15" maxlength="15" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
	<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Update" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include('./includes/footer.html');
?>
