<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Display Data</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <?php
  include_once 'db.php';
$sql = "SELECT * FROM form_data";
$result = $conn->query($sql);
?>

  <h1><a href="index.html">Add Data</a></h1>
  <link rel="stylesheet" href="styles.css">
  <table class="table-wrap">
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Account Type</th>
        <th>Age</th>
        <th>Referrer</th>
        <th>Bio</th>
        <th>Profile Picture</th>
        <th>Country</th>
        <th>State</th>
        <th>City</th>
        <th>Category</th>
        <th>Subcategory</th>
        <th>Actions</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row["first_name"]; ?></td>
            <td><?php echo $row["last_name"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td><?php echo $row["password"]; ?></td>
            <td><?php echo $row["account_type"]; ?></td>
            <td><?php echo $row["age"]; ?></td>
            <td><?php echo $row["referrer"]; ?></td>
            <td><?php echo $row["bio"]; ?></td>

            <td>
                <?php
                if ($row['profile_picture']) {
                    $targetDir = "uploads/";
                    $imagePath = $targetDir . $row['profile_picture'];
                    echo '<div class="image-box"><img src="' . $imagePath . '" alt="Profile Picture" width="50"></div>';
                }
                ?>
            </td>
            <td><?php echo $row["sel_country"]; ?></td>
            <td><?php echo $row["sel_state"]; ?></td>
            <td><?php echo $row["sel_city"]; ?></td>
            <td><?php echo $row["sel_category"]; ?></td>
            <td><?php echo $row["sel_subcategory"]; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $row["id"]; ?>"
                   onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
  </table>
  <?php
  ?>
</body>
</html>