<?php
$page_title = 'Pending Approvals';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Fetch pending approvals
$query = "SELECT r.rental_id, u.name AS user_name, b.book_name, r.total_rental
          FROM rental r
          INNER JOIN users u ON r.user_id = u.user_id
          INNER JOIN books b ON r.book_code = b.book_code
          WHERE r.rental_status = 'Pending'";
$result = @mysqli_query($dbc, $query);

echo '<h1>Pending Approvals</h1>';

if (@mysqli_num_rows($result) > 0) {
    echo '<form action="librarian_pending_approvals.php" method="post">';
    echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
          <tr>
              <th>Rental ID</th>
              <th>User</th>
              <th>Book</th>
              <th>Total Rental</th>
              <th>Action</th>
          </tr>';
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
                  <td>' . $row['rental_id'] . '</td>
                  <td>' . $row['user_name'] . '</td>
                  <td>' . $row['book_name'] . '</td>
                  <td>$' . number_format($row['total_rental'], 2) . '</td>
                  <td>
                      <button type="submit" name="approve" value="' . $row['rental_id'] . '">Approve</button>
                      <button type="submit" name="reject" value="' . $row['rental_id'] . '">Reject</button>
                  </td>
              </tr>';
    }
    echo '</table>';
    echo '</form>';
} else {
    echo '<p>No pending approvals.</p>';
}

if (isset($_POST['approve'])) {
    $rental_id = (int)$_POST['approve'];
    $query_approve = "UPDATE rental SET rental_status = 'Approved' WHERE rental_id = $rental_id";
    @mysqli_query($dbc, $query_approve);
    echo '<p>Rental approved.</p>';
} elseif (isset($_POST['reject'])) {
    $rental_id = (int)$_POST['reject'];
    $query_reject = "UPDATE rental SET rental_status = 'Rejected' WHERE rental_id = $rental_id";
    @mysqli_query($dbc, $query_reject);
    echo '<p>Rental rejected.</p>';
}

mysqli_close($dbc);
include('./includes/footer.html');
?>
