<?php
include_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $firstName = $_POST["first-name"];
  $lastName = $_POST["last-name"];
  $email = $_POST["email"];
  $password = $_POST["new-password"];
  $accountType = isset($_POST["account-type"]) ? $_POST["account-type"] : "";
  $termsAccepted = isset($_POST["terms-and-conditions"]) ? "Accepted" : "Not Accepted";
  $age = $_POST["age"] ?? "";
  $referrer = isset($_POST["referrer"])? $_POST["referrer"] : "";
  $bio = $_POST["bio"] ?? "";
  
  $targetDir = "uploads/";
  $fileName = basename($_FILES["file"]["name"]);
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
  
  if (!empty($_FILES["file"]["tmp_name"])) {
    move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);
  }
  $sel_country = $_POST["country"] ?? "";
  $sel_state = $_POST["state"] ?? "";
  $sel_city = $_POST["city"] ?? "";
  $sel_category = $_POST["category"] ?? "";
  $sel_subcategory = $_POST["subcategory"] ?? "";

  // echo "<pre>";
  // print_r( $_POST );
  // exit();
  
    $stmt = $conn->prepare("INSERT INTO form_data ( `first_name`, `last_name`, `email`, `password`, `account_type`, `terms_accepted`, `age`, `referrer`, `bio`, `profile_picture`, `sel_country`, `sel_state`, `sel_city`, `sel_category`, `sel_subcategory`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssissssssss", $firstName, $lastName, $email, $password, $accountType, $termsAccepted, $age, $referrer, $bio, $fileName, $sel_country, $sel_state, $sel_city, $sel_category, $sel_subcategory);
  
    if ($stmt->execute()) {
      echo "Form data saved successfully.";
      header("location: ./display_data.php");
    } else {
      echo "Error: " . $stmt->error;
    }
$conn->close();
  
} else {
  echo "Invalid request.";
}
