<?php

$page_title = 'Books to Rent';
include('./includes/header_user.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Page header
echo '<h2 id="mainhead">Books to Rent</h2>';

// Start form
echo '<form action="user_book_view.php" method="post">';
echo '<p>Email Address: <input type="text" name="email" size="20" maxlength="40" required /> </p>';
echo '<p>Password: <input type="password" name="password" size="10" maxlength="20" required /></p>';

// Display available books
echo "<h3>New Books</h3>\n";
$query = "SELECT book_id, book_name FROM books WHERE book_status = 'Available'";
$result = @mysqli_query($dbc, $query);
$num = @mysqli_num_rows($result);

if ($num > 0) {
    echo "<p>There are currently $num new books available for rent.</p>\n";
    echo '<table align="center" cellspacing="0" cellpadding="5">
          <tr><td align="left"><b>Book Name</b></td></tr>';
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr><td align="left">' . $row['book_name'] . '</td></tr>';
    }
    echo '</table>';
    @mysqli_free_result($result);
} else {
    echo '<p class="error">There are currently no new books available.</p>';
}

// Create dropdown for renting books
echo "<h3>To Rent</h3>\n";
$query1 = "SELECT book_id, book_name FROM books WHERE book_status = 'Available'";
$result1 = @mysqli_query($dbc, $query1);
$num1 = @mysqli_num_rows($result1);

if ($num1 > 0) {
    echo "<p>Select a book to rent:</p>";
    echo '<select name="book_id" required>';
    while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
        echo '<option value="' . $row1["book_id"] . '">' . $row1["book_name"] . '</option>';
    }
    echo '</select>';
    echo '<p><input type="submit" name="submitBook" value="Rent This Book!" /></p>';
} else {
    echo '<p class="error">There are currently no books available for rent.</p>';
}

echo '</form>';

// Handle form submission
if (isset($_POST['submitBook'])) {
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
    $book_id = (int)$_POST['book_id'];

    // Verify user credentials
    $query2 = "SELECT user_id, name FROM users WHERE email = '$email' AND password = SHA('$password')";
    $result2 = @mysqli_query($dbc, $query2);
    $num2 = @mysqli_num_rows($result2);

    if ($num2 == 1) {
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $user_id = $row2['user_id'];
        $user_name = $row2['name'];

        echo "<p>Hello $user_name!</p>";
        echo "<p>You have chosen to rent the selected book.</p>";

        // Update book status and assign to user
        $query3 = "UPDATE books 
                   SET book_status = 'Rented'
                   WHERE book_id = $book_id";
        $result3 = @mysqli_query($dbc, $query3);

        if ($result3 && mysqli_affected_rows($dbc) > 0) {
            echo '<h1>Thank you!</h1>
                  <p>Your rental request has been submitted successfully.</p>';
        } else {
            echo '<h1>System Error</h1>
                  <p class="error">Your rental request could not be processed due to a system error. We apologize for the inconvenience.</p>';
        }
    } else {
        echo '<h1>Error!</h1>
              <p class="error">Invalid email address or password. Please try again.</p>';
    }
}

include('./includes/footer.html');
?>
