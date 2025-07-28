<?php
// Database connection info
$host = "localhost";
$dbname = "testdb";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$submitted = false;

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $address = clean_input($_POST['address']);
    $phone = clean_input($_POST['phone']);

    if ($id > 0 && $name !== "" && $email !== "") {
        $stmt = $conn->prepare("INSERT INTO users (id, name, email, address, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id, $name, $email, $address, $phone);
        if ($stmt->execute()) {
            $message = "<div class='success'>User added successfully!</div>";
            $submitted = true;
        } else {
            $message = "<div class='error'>Error inserting user. Maybe the ID already exists.</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='error'>ID, Name, and Email are required fields.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add User</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Add User</h1>
    <?php echo $message; ?>
    <form method="post" action="" id="addUserForm">
      <label for="id">ID *</label>
      <input
        type="number"
        name="id"
        id="id"
        required
        min="1"
        value="<?php echo $submitted ? $id : ''; ?>"
        <?php if ($submitted) echo 'readonly'; ?>
      />

      <label for="name">Name *</label>
      <input
        type="text"
        name="name"
        id="name"
        required
        value="<?php echo $submitted ? htmlspecialchars($name) : ''; ?>"
        <?php if ($submitted) echo 'readonly'; ?>
      />

      <label for="email">Email *</label>
      <input
        type="email"
        name="email"
        id="email"
        required
        value="<?php echo $submitted ? htmlspecialchars($email) : ''; ?>"
        <?php if ($submitted) echo 'readonly'; ?>
      />

      <label for="address">Address</label>
      <input
        type="text"
        name="address"
        id="address"
        value="<?php echo $submitted ? htmlspecialchars($address) : ''; ?>"
        <?php if ($submitted) echo 'readonly'; ?>
      />

      <label for="phone">Phone Number</label>
      <input
        type="text"
        name="phone"
        id="phone"
        value="<?php echo $submitted ? htmlspecialchars($phone) : ''; ?>"
        <?php if ($submitted) echo 'readonly'; ?>
      />

      <?php if (!$submitted): ?>
        <input type="submit" value="Submit" id="submitBtn" />
      <?php endif; ?>
    </form>
    <div id="responseMessage"></div>
    <div class="back-link"><a href="index.php">&larr; Back to Menu</a></div>
  </div>

  <script>
    const form = document.getElementById('addUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const responseDiv = document.getElementById('responseMessage');

    form.addEventListener('submit', function() {
      if (submitBtn) {
        submitBtn.style.display = 'none'; // hide submit button immediately
      }
      responseDiv.textContent = 'Submitting data, please wait...'; // instant feedback
    });
  </script>
</body>
</html>
