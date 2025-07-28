<?php
// Database connection info
$host = "localhost";
$dbname = "testdb";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_id = intval($_POST['search_id']);

    if ($search_id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $search_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            $message = "<div class='error'>No user found with ID $search_id.</div>";
        }
    } else {
        $message = "<div class='error'>Please enter a valid ID.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Search User</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Search User by ID</h1>
    <?php echo $message; ?>
    <form method="post" action="">
      <label for="search_id">User ID</label>
      <input type="number" name="search_id" id="search_id" min="1" required />
      <input type="submit" value="Search" class="search-submit" />
    </form>

    <?php if ($user): ?>
      <div class="result">
        <h3>User Details</h3>
        <p><strong>ID:</strong> <?php echo $user['id']; ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
      </div>
    <?php endif; ?>

    <div class="back-link"><a href="index.php">&larr; Back to Menu</a></div>
  </div>
</body>
</html>
