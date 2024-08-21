<?php 
// Start session
session_start();
include("./db_config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signIn'])) {
    // Handle user login
    $eno_n = trim($_POST['eno_no']);  // trim() to remove any extra spaces
    $password = trim($_POST['password']); 

    // Debug: Output form values (comment these out in production)
    // echo "Debug: Enrollment Number: $eno_n<br>";
    // echo "Debug: Password: $password<br>";

    // Use prepared statement to retrieve user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE eno_no = ?");
    if (!$stmt) {
        die("Error in prepare: " . $conn->error);
    }
    $stmt->bind_param("s", $eno_n);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debug: Output retrieved row (comment this out in production)
        // echo "Debug: Retrieved Row: <pre>" . print_r($row, true) . "</pre><br>";

        // Compare passwords
        if ($password === trim($row['password'])) {
            // Password is correct, start session and redirect
            $_SESSION['eno_no'] = $eno_n;
            $status = "Active now";
            $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE eno_no = {$row['eno_no']}");
            if($sql2){
                $_SESSION['eno_no'] = $row['eno_no'];
                }else{
                echo "Something went wrong. Please try again!";
            }
            header("Location: ../logreg/p/update_profile.php");
            exit();
        } else {
            echo "Incorrect Password";
        }
    } else {
        echo "User not found";
    }

    $stmt->close();
}

$conn->close();
?>

<?php
// Start session
/*session_start();
include("./db_config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signIn'])) {
    // Handle user login
    $eno_n = trim($_POST['eno_no']);  // trim() to remove any extra spaces
    $password =trim($_POST['password']);

    // Debug: Output form values
    echo "Debug: Enrollment Number: $eno_n<br>";
    echo "Debug: Password: $password<br>";

    // Use prepared statement to retrieve user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE eno_no = ?");
    if (!$stmt) {
        die("Error in prepare: " . $conn->error);
    }
    $stmt->bind_param("s", $eno_n);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debug: Output retrieved row
        echo "Debug: Retrieved Row: <pre>" . print_r($row, true) . "</pre><br>";

        // Compare passwords
        if ($password == $row['password']) {
            // Password is correct, start session and redirect
            $_SESSION['eno_no'] = $eno_n;
            header("Location: ../logreg/p/home.php");
            exit();
        } else {
            echo "Incorrect Password";
        }
    } else {
        echo "User not found";
    }

    $stmt->close();
}

$conn->close();*/
?>


