<?php
$page_title = 'Delete Book';
include('./includes/header_librarian.html');

require_once('mysqli.php'); // Connect to the database
global $dbc;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['book_code'])) {
        $book_code = mysqli_real_escape_string($dbc, trim($_POST['book_code']));

        // Delete the book
        $query = "DELETE FROM books WHERE book_code = '$book_code'";
        $result = @mysqli_query($dbc, $query);

        if ($result && mysqli_affected_rows($dbc) > 0) {
            echo '<p>The book has been deleted successfully.</p>';
        } else {
            echo '<p class="error">Could not delete the book. Please try again.</p>';
        }
    } else {
        echo '<p class="error">You must select a book to delete.</p>';
    }
}

// Fetch all books
$query_books = "SELECT book_code, book_name FROM books";
$result_books = @mysqli_query($dbc, $query_books);

echo '<h1>Delete a Book</h1>';
if (@mysqli_num_rows($result_books) > 0) {
    echo '<form action="librarian_delete.php" method="post">';
    echo '<p>Select Book: 
            <select name="book_code" required>';
    while ($row_books = @mysqli_fetch_array($result_books, MYSQLI_ASSOC)) {
        echo '<option value="' . $row_books['book_code'] . '">' . $row_books['book_name'] . '</option>';
    }
    echo '</select></p>';
    echo '<p><input type="submit" value="Delete Book" /></p>';
    echo '</form>';
} else {
    echo '<p>No books available to delete.</p>';
}

mysqli_free_result($result_books);
mysqli_close($dbc);

include('./includes/footer.html');
?>
