<?php
$page_title = 'Update Payment Status';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Fetch pending payments
$query = "SELECT r.rental_id, u.name AS user_name, b.book_name, r.total_rental, r.rental_status, b.book_code
          FROM rentals r
          INNER JOIN users u ON r.user_id = u.user_id
          INNER JOIN books b ON r.book_code = b.book_code
          WHERE r.rental_status = 'Pending'";
$result = @mysqli_query($dbc, $query);

echo '<h1>Update Payment Status</h1>';

if (@mysqli_num_rows($result) > 0) {
    echo '<form action="librarian_update_payment.php" method="post">';
    echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
          <tr>
              <th>Rental ID</th>
              <th>User</th>
              <th>Book</th>
              <th>Total Payment (RM)</th>
              <th>Status</th>
              <th>Action</th>
          </tr>';
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
                  <td>' . $row['rental_id'] . '</td>
                  <td>' . $row['user_name'] . '</td>
                  <td>' . $row['book_name'] . '</td>
                  <td>RM ' . number_format($row['total_rental'], 2) . '</td>
                  <td>' . $row['rental_status'] . '</td>
                  <td>
                      <button type="submit" name="approve" value="' . $row['rental_id'] . '">Approve</button>
                      <button type="submit" name="reject" value="' . $row['rental_id'] . '">Reject</button>
                  </td>
              </tr>';
    }
    echo '</table>';
    echo '</form>';
} else {
    echo '<p>No pending payments found.</p>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $rental_id = (int)$_POST['approve'];

        // Mark payment as approved
        $query_update = "UPDATE rentals SET rental_status = 'Approved' WHERE rental_id = $rental_id";
        $result_update = @mysqli_query($dbc, $query_update);

        if ($result_update && mysqli_affected_rows($dbc) > 0) {
            // Retrieve the book code and update book status
            $query_get_book = "SELECT book_code FROM rentals WHERE rental_id = $rental_id";
            $result_book = @mysqli_query($dbc, $query_get_book);
            if ($row = @mysqli_fetch_array($result_book, MYSQLI_ASSOC)) {
                $book_code = $row['book_code'];
                $query_update_book = "UPDATE books SET book_status = 'Rented' WHERE book_code = '$book_code'";
                @mysqli_query($dbc, $query_update_book);
            }
            echo '<p>Payment marked as approved and book status updated successfully.</p>';
        } else {
            echo '<p class="error">Failed to update payment status.</p>';
        }
    } elseif (isset($_POST['reject'])) {
        $rental_id = (int)$_POST['reject'];

        // Mark payment as rejected
        $query_update = "UPDATE rentals SET rental_status = 'Rejected' WHERE rental_id = $rental_id";
        $result_update = @mysqli_query($dbc, $query_update);

        if ($result_update && mysqli_affected_rows($dbc) > 0) {
            // Retrieve the book code and update book status
            $query_get_book = "SELECT book_code FROM rentals WHERE rental_id = $rental_id";
            $result_book = @mysqli_query($dbc, $query_get_book);
            if ($row = @mysqli_fetch_array($result_book, MYSQLI_ASSOC)) {
                $book_code = $row['book_code'];
                $query_update_book = "UPDATE books SET book_status = 'Available' WHERE book_code = '$book_code'";
                @mysqli_query($dbc, $query_update_book);
            }
            echo '<p>Payment marked as rejected and book status updated successfully.</p>';
        } else {
            echo '<p class="error">Failed to update payment status.</p>';
        }
    }


}

mysqli_free_result($result);
mysqli_close($dbc);

include('./includes/footer.html');
?>
