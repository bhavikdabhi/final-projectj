<?php
// Start session
session_start();
include("../db_config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_data'])) {
    // Get user data from session
    $user_data = $_SESSION['user_data'];
    $first_name = isset($user_data['first_name']) ? $user_data['first_name'] : "";
    $last_name = isset($user_data['last_name']) ? $user_data['last_name'] : "";
    $eno_no = isset($user_data['eno_no']) ? $user_data['eno_no'] : "";
    $email = isset($user_data['email']) ? $user_data['email'] : "";

    // Fetch user details
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE eno_no = '$eno_no'");
    if ($select) {
        $fetch = mysqli_fetch_assoc($select);
        if (!$fetch) {
            echo "No user found.";
            exit;
        }
    } else {
        echo "Error fetching user details: " . mysqli_error($conn);
        exit;
    }
} else {
    // Redirect to login if session not set
    header("Location: ../homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="profile">
        <?php
        if (empty($fetch['avatar'])) {
         echo '<img src="images/default-avatar.png">';
      } else {
         echo '<img src="data:image/jpeg;base64,' . base64_encode($fetch['avatar']) . '">';
      }
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">'.$msg.'</div>';
         }
      }
        ?>
        <h3><?php echo htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name); ?></h3>
        <a href="update_profile.php" class="btn">Update Profile</a>
        <a href="home.php?logout=<?php echo htmlspecialchars($eno_no); ?>" class="delete-btn">Logout</a>
        <p>New <a href="login.php">Login</a> or <a href="register.php">Register</a></p>
    </div>
</div>
</body>
</html>

<?php
// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../homepage.php");
    exit;
}

$conn->close();
?>
