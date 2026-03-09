<?php
$page_title = 'Calculate Totals';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

// Calculate total payment for approved rentals
$query_total_payment = "SELECT SUM(total_rental) AS total_payment 
                        FROM rental 
                        WHERE rental_status = 'Approved'";
$result_payment = @mysqli_query($dbc, $query_total_payment);
$row_payment = @mysqli_fetch_array($result_payment, MYSQLI_ASSOC);
$total_payment = $row_payment['total_payment'] ?? 0.00;

echo '<h1>Rental Totals</h1>';
echo '<p><strong>Total Payment for Approved Rentals:</strong> $' . number_format($total_payment, 2) . '</p>';

mysqli_free_result($result_payment);
mysqli_close($dbc);

include('./includes/footer.html');
?>
