<?php
$page_title = 'Insert New Book';
include('./includes/header_librarian.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('mysqli.php'); // Database connection
    global $dbc;

    $errors = array();

    // Validate inputs
    if (empty($_POST['book_code'])) {
        $errors[] = 'You forgot to enter the book code.';
    } else {
        $book_code = mysqli_real_escape_string($dbc, trim($_POST['book_code']));
    }

    if (empty($_POST['book_name'])) {
        $errors[] = 'You forgot to enter the book name.';
    } else {
        $book_name = mysqli_real_escape_string($dbc, trim($_POST['book_name']));
    }

    if (empty($_POST['description'])) {
        $errors[] = 'You forgot to enter the description.';
    } else {
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
    }

    if (empty($_POST['rental_price'])) {
        $errors[] = 'You forgot to enter the rental price.';
    } else {
        $rental_price = (float)$_POST['rental_price'];
    }

    if (empty($errors)) {
        $query = "INSERT INTO books (book_code, book_name, description, rental_price, book_status)
                  VALUES ('$book_code', '$book_name', '$description', $rental_price, 'Available')";
        $result = @mysqli_query($dbc, $query);

        if ($result) {
            echo '<h1>Success!</h1><p>The book has been added successfully.</p>';
        } else {
            echo '<h1>Error!</h1><p>The book could not be added due to a system error.</p>';
        }
    } else {
        echo '<h1>Error!</h1><p>The following error(s) occurred:<br />';
        foreach ($errors as $msg) {
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
    }

    mysqli_close($dbc);
}
?>

<h2>Add New Book</h2>
<form action="librarian_insert.php" method="post">
    <p>Book Code: <input type="text" name="book_code" required /></p>
    <p>Book Name: <input type="text" name="book_name" required /></p>
    <p>Description: <textarea name="description" required></textarea></p>
    <p>Rental Price (RM): <input type="number" name="rental_price" step="0.01" required /></p>
    <p><input type="submit" value="Add Book" /></p>
</form>

<?php
include('./includes/footer.html');
?>
