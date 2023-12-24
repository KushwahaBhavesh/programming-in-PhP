<?php
// database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "imgprocess";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("connection failed" . $conn->connect_error);
} else {
  echo "Connection successfully";
}

/**
 * Here we can verify the reqeust.
 * if it successful then remaining code is execute otherwise it genererate error 
 * if it generate error then else part is execute.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // validating the username and date of birth;
  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $name = test_input($_POST["name"]);
  $dob = test_input($_POST["dob"]);


  // Here we verify the image is receiving or not.
  if (isset($_FILES["img"])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["img"]["name"]);

    // performing server side validating the code (file size and dimension of the file and their extensions)
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));

    // if any error in file extension
    if (!in_array($fileExtension, $allowedExtensions)) {
      $pictureErr = '<script>alert("Only JPG, JPEG, and PNG files are allowed.")</script>';
      echo $pictureErr;
    }

    // identify image dimensions
    list($width, $height) = getimagesize($_FILES["img"]["tmp_name"]);
    if (($width < 1200 && $height < 800) || ($width < 650 && $height < 900)) {
      $pictureErr = '<script>alert("Minimum dimensions: 1200x800 (landscape) or 650x900 (portrait).")</script>';
      echo '' . $pictureErr . '';
    }

    // validating size of the image file 
    $maxSize = 10 * 1024 * 1024; // 10 MB
    if ($_FILES["img"]["size"] > $maxSize) {

      $pictureErr = '<script>alert("Maximum file size is 10 MB")</script>';
      echo $pictureErr;
    }

    // if there is no error then remining code is execute
    if (empty($pictureErr)) {

      // creating parent folder
      $ParentFolder = strtolower(substr($name, 0, 4) . date('md', strtotime($dob)));
      $ParentFolderPath = "uploads/$ParentFolder/";

      if (!file_exists($ParentFolderPath)) {
        mkdir($ParentFolderPath, 0777, true);
      }

      // image upload and folder creation
      $originalFolder = $ParentFolderPath . "Original/";
      $thumbnailFolder = $ParentFolderPath . "Thumbnail/";
      $mainFolder = $ParentFolderPath . "Main/";

      // creating child folder
      mkdir($originalFolder, 0777, true);
      mkdir($mainFolder, 0777, true);
      mkdir($thumbnailFolder, 0777, true);

      // renaming image name for different folder
      $timeStamp = date('Y-m-d-H-i-s');
      $originalFileName = "$timeStamp-org.jpg";
      $thumbnailFileName = "$timeStamp-thum.jpg";
      $mainFileName = "$timeStamp-main.jpg";

      // uploading the image in folder
      move_uploaded_file($_FILES["img"]["tmp_name"], $originalFolder . $originalFileName);

      // resizeImage
      list($width, $height) = getimagesize($originalFolder . $originalFileName);
      $mainWidth = ($width > $height) ? 1200 : 650;
      $mainHeight = ($width > $height) ? floor(650 * $height / $width) : null;

      // reducing dimension of the image 50%.
      $mainImage = imagecreatetruecolor($mainWidth, $mainHeight);
      $source = imagecreatefromjpeg($originalFolder . $originalFileName);
      imagecopyresampled($mainImage, $source, 0, 0, 0, 0, $mainWidth, $mainHeight, $width, $height);
      imagejpeg($mainImage, $mainFolder . $mainFileName, 50); 

      // crop & Save Thumbnail 
      $thumbnailImage = imagecreatetruecolor(500, 500);
      $cropX = ($width - 500) / 2;
      $cropY = ($height - 500) / 2;
      imagecopyresampled($thumbnailImage, $source, 0, 0, $cropX, $cropY, 500, 500, 500, 500);
      imagejpeg($thumbnailImage, $thumbnailFolder . $thumbnailFileName, 50);


      // creating reference variable for database query
      $originalUrl = $ParentFolder . "/Original/" . $originalFileName;
      $mainUrl = $ParentFolder . "/Main/" . $mainFileName;
      $thumbnailUrl = $ParentFolder . "/Thumbnail/" . $thumbnailFileName;

      // sel query
      $sql = "INSERT INTO USER (username,date_of_birth,original_url,main_url,thumbnail_url) VALUES ('$name','$dob','$originalUrl','$mainUrl','$thumbnailUrl')";

      // verifying  data is inserted or not
      if ($conn->query($sql) == TRUE) {
        echo '<script>alert("Data Inserted Successfully")</script>';
      } else {
        echo '<script>alert("Data Inserted Failed")</script>';
      }
    }
    // closing connection
    $conn->close();
  } else {
    echo '<script>alert("code error")</script>';
  }
}
