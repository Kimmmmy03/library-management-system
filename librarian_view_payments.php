<?php
$page_title = 'View Payments';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Fetch total payments by user and payment statuses
$query = "SELECT u.name AS user_name, r.rental_status, SUM(r.total_rental) AS total_payment
          FROM rental r
          INNER JOIN users u ON r.user_id = u.user_id
          GROUP BY u.name, r.rental_status
          ORDER BY u.name, r.rental_status";
$result = @mysqli_query($dbc, $query);

echo '<h1>Total Payments by Users</h1>';

if (@mysqli_num_rows($result) > 0) {
    echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
          <tr>
              <th>User</th>
              <th>Payment Status</th>
              <th>Total Payment (RM)</th>
          </tr>';
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
                  <td>' . $row['user_name'] . '</td>
                  <td>' . $row['rental_status'] . '</td>
                  <td>RM ' . number_format($row['total_payment'], 2) . '</td>
              </tr>';
    }
    echo '</table>';
} else {
    echo '<p>No payment records found.</p>';
}

mysqli_free_result($result);
mysqli_close($dbc);

include('./includes/footer.html');
?>
