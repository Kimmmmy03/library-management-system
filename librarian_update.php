<?php
$page_title = 'Update Book';
include('./includes/header_librarian.html');

require_once('mysqli.php');
global $dbc;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();

    // Validate book code
    if (empty($_POST['book_code'])) {
        $errors[] = 'You forgot to select a book.';
    } else {
        $book_code = (int)$_POST['book_code'];
    }

    // Validate book name
    if (empty($_POST['book_name'])) {
        $errors[] = 'You forgot to enter the book name.';
    } else {
        $book_name = mysqli_real_escape_string($dbc, trim($_POST['book_name']));
    }

    // Validate description
    if (empty($_POST['description'])) {
        $errors[] = 'You forgot to enter the description.';
    } else {
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
    }

    // Validate rental price
    if (empty($_POST['rental_price']) || !is_numeric($_POST['rental_price'])) {
        $errors[] = 'You forgot to enter a valid rental price.';
    } else {
        $rental_price = (float)$_POST['rental_price'];
    }

    // Validate book status
    if (empty($_POST['book_status'])) {
        $errors[] = 'You forgot to select the book status.';
    } elseif (!in_array($_POST['book_status'], ['Available', 'Rented'])) {
        $errors[] = 'Invalid book status selected.';
    } else {
        $book_status = mysqli_real_escape_string($dbc, trim($_POST['book_status']));
    }

    // If no errors, update the book
    if (empty($errors)) {
        $query = "UPDATE books 
                  SET book_name='$book_name', description='$description', rental_price=$rental_price, book_status='$book_status' 
                  WHERE book_code=$book_code";
        $result = @mysqli_query($dbc, $query);

        if ($result && mysqli_affected_rows($dbc) > 0) {
            echo '<p>Book updated successfully.</p>';
        } else {
            echo '<p class="error">Failed to update the book. Please try again later.</p>';
        }
    } else {
        echo '<p class="error">The following error(s) occurred:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo '</p>';
    }

    mysqli_close($dbc);
}
?>

<h2>Update Book Details</h2>
<form action="librarian_update.php" method="post">
    <p>Select a Book:
        <select name="book_code" required>
            <option value="">-- Select a Book --</option>
            <?php
            $query = "SELECT book_code, book_name FROM books";
            $result = @mysqli_query($dbc, $query);
            while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<option value="' . $row['book_code'] . '">' . $row['book_name'] . '</option>';
            }
            ?>
        </select>
    </p>
    <p>Book Name: <input type="text" name="book_name" required /></p>
    <p>Description: <textarea name="description" required></textarea></p>
    <p>Rental Price (RM): <input type="number" name="rental_price" step="0.01" required /></p>
    <p>Book Status:
        <select name="book_status" required>
            <option value="Available">Available</option>
            <option value="Rented">Rented</option>
        </select>
    </p>
    <p><input type="submit" value="Update Book" /></p>
</form>

<?php
include('./includes/footer.html');
?>
