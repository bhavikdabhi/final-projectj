<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "files";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Function to display files as buttons
function displayFiles($conn) {
  $sql = "SELECT id, filename FROM file_uploads";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $filename = $row['filename'];
      echo "<button onclick='openFile($id)'>$filename</button> </br> "; // Call JavaScript function
      
    }
  } else {
    echo "No files found.";
  }

  mysqli_free_result($result);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>File Uploader and Viewer</title>
    <script>
    function openFile(fileId) {
        window.location.href = "open_file.php?id=" + fileId;
    }
    </script>
    <script>
    function upFile() {
        window.location.href = "upload.php";
    }
    </script>
</head>

<body>
    <h1>File Uploader</h1>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="files[]" multiple>
        <input type="submit" value="Upload">
    </form>

    <h2>Uploaded Files</h2>
    </br>
    <?php displayFiles($conn); ?></br>

    <?php
  // ... rest of your code using $conn for database operations ...

  mysqli_close($conn);
  ?>

   
</body>

</html>

