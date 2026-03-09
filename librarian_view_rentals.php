<?php
$page_title = 'View Rentals';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Fetch rental details including user and book information
$query = "SELECT r.rental_id, u.name AS user_name, b.book_name, b.rental_price, 
                 r.rental_duration, r.total_rental, r.rental_status
          FROM rental r
          INNER JOIN users u ON r.user_id = u.user_id
          INNER JOIN books b ON r.book_code = b.book_code";
$result = @mysqli_query($dbc, $query);

echo '<h1>Rental Records</h1>';

if (@mysqli_num_rows($result) > 0) {
    echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
          <tr>
              <th>Rental ID</th>
              <th>User</th>
              <th>Book Name</th>
              <th>Rental Price (Per Week, RM)</th>
              <th>Weeks Rented</th>
              <th>Total Rental (RM)</th>
              <th>Status</th>
          </tr>';
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
                  <td>' . $row['rental_id'] . '</td>
                  <td>' . $row['user_name'] . '</td>
                  <td>' . $row['book_name'] . '</td>
                  <td>RM ' . number_format($row['rental_price'], 2) . '</td>
                  <td>' . $row['rental_duration'] . '</td>
                  <td>RM ' . number_format($row['total_rental'], 2) . '</td>
                  <td>' . $row['rental_status'] . '</td>
              </tr>';
    }
    echo '</table>';
} else {
    echo '<p>No rentals found.</p>';
}

mysqli_free_result($result);
mysqli_close($dbc);

include('./includes/footer.html');
?>
