<?php
$page_title = 'View Books';
include('./includes/header_librarian.html');

require_once('mysqli.php');
global $dbc;

// Initialize search and filter variables
$search_term = '';
$min_price = 0;
$max_price = 1000;
$book_status = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['search_term'])) {
        $search_term = mysqli_real_escape_string($dbc, trim($_POST['search_term']));
    }

    if (!empty($_POST['min_price']) && is_numeric($_POST['min_price'])) {
        $min_price = (float)$_POST['min_price'];
    }

    if (!empty($_POST['max_price']) && is_numeric($_POST['max_price'])) {
        $max_price = (float)$_POST['max_price'];
    }

    if (!empty($_POST['book_status'])) {
        $book_status = $_POST['book_status'];
    }
}

// Build the query
$query = "SELECT * FROM books WHERE rental_price BETWEEN $min_price AND $max_price";

if (!empty($search_term)) {
    $query .= " AND book_name LIKE '%$search_term%'";
}

if (!empty($book_status)) {
    $query .= " AND book_status = '$book_status'";
}

$result = @mysqli_query($dbc, $query);
$num = @mysqli_num_rows($result);
?>

<h1>Library Books</h1>
<p>Search and filter books for easy navigation:</p>

<!-- Search and Filter Form -->
<form action="librarian_view.php" method="post">
    <p>Search by Name: <input type="text" name="search_term" value="<?php echo $search_term; ?>" /></p>
    <p>Price Range:  </p>
    <p>
        Min: <input type="number" name="min_price" value="<?php echo $min_price; ?>" step="0.01" />
        </p>
    <p>
        Max: <input type="number" name="max_price" value="<?php echo $max_price; ?>" step="0.01" />
    </p>
    <p>Filter by Status:
    <select name="book_status">
        <option value="">-- All Statuses --</option>
        <option value="Available" <?php if ($book_status === 'Available') echo 'selected'; ?>>Available</option>
        <option value="Rented" <?php if ($book_status === 'Rented') echo 'selected'; ?>>Rented</option>
        <option value="to be approved" <?php if ($book_status === 'to be approved') echo 'selected'; ?>>to be approved</option>
    </select>
</p>

    </p>
    <p><input type="submit" value="Search and Filter" /></p>
</form>

<!-- Display Results -->
<?php
if ($num > 0) {
    echo "<p>Found $num book(s) matching your criteria:</p>";
    echo '<table align="center" cellspacing="0" cellpadding="5">
          <tr><th>Book Code</th><th>Book Name</th><th>Description</th><th>Rental Price</th><th>Status</th></tr>';

    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr><td>' . $row['book_code'] . '</td>
                  <td>' . $row['book_name'] . '</td>
                  <td>' . $row['description'] . '</td>
                  <td>' . $row['rental_price'] . '</td>
                  <td>' . $row['book_status'] . '</td></tr>';
    }

    echo '</table>';
    @mysqli_free_result($result);
} else {
    echo '<p>No books found matching your criteria.</p>';
}

mysqli_close($dbc);
include('./includes/footer.html');
?>
