<!DOCTYPE html>
<html>

<head>
    <title>Edit Form Data</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>

    <h1><a href="display_data.php">Display Data</a></h1>
    <h1>Edit Form Data</h1>
    <?php
    include_once 'db.php';
    $id = $_GET["id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $firstName = $_POST["first-name"];
        $lastName = $_POST["last-name"];
        $email = $_POST["email"];
        $password = $_POST["new-password"];
        $accountType = isset($_POST["account-type"]) ? $_POST["account-type"] : "";
        $termsAccepted = isset($_POST["terms-and-conditions"]) ? "Accepted" : "Not Accepted";
        $age = $_POST["age"] ?? "";
        $referrer = isset($_POST["referrer"]) ? $_POST["referrer"] : "";
        $bio = $_POST["bio"] ?? "";

        if (!empty($_FILES["file"]["tmp_name"])) {
            $targetDir = "/home/xylus/Local Sites/new/app/public/uploads/";
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);

            $sql = "UPDATE form_data SET profile_picture = '$fileName' WHERE id = '$id'";
            $conn->query($sql);
        }
        $sel_country = $_POST["country"] ?? "";
        $sel_state = $_POST["state"] ?? "";
        $sel_city = $_POST["city"] ?? "";
        $sel_category = $_POST["category"] ?? "";
        $sel_subcategory = $_POST["subcategory"] ?? "";

        $sql = "UPDATE form_data SET 
        first_name = '$firstName', 
        last_name = '$lastName', 
        email = '$email', 
        password = '$password', 
        account_type = '$accountType', 
        terms_accepted = '$termsAccepted', 
        age = '$age', 
        referrer = '$referrer', 
        bio = '$bio',
        sel_country = '$sel_country',
        sel_state = '$sel_state',
        sel_city = '$sel_city',
        sel_category = '$sel_category',
        sel_subcategory = '$sel_subcategory'
        WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    $sql = "SELECT * FROM form_data WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }

    $country = $row['sel_country'];
    $state = $row['sel_state'];
    $city = $row['sel_city'];
    $category = $row['sel_category'];
    $subcategory = $row['sel_subcategory'];

    ?>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" name="first-name" value="<?php echo $row['first_name']; ?>"><br>

        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" name="last-name" value="<?php echo $row['last_name']; ?>"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>"><br>

        <label for="new-password">Create a New Password:</label>
        <input id="new-password" name="new-password" type="password" pattern="^[a-zA-Z0-9]{8,}$" required value="<?php echo $row['password']; ?>" />

        <label for="personal-account">
            <input id="personal-account" type="radio" name="account-type" class="inline" value="Personal Account" <?php echo $row['account_type'] === 'Personal Account' ? 'checked' : ''; ?> />
            Personal Account
        </label>
        <label for="business-account">
            <input id="business-account" type="radio" name="account-type" class="inline" value="Business Account" <?php echo $row['account_type'] === 'Business Account' ? 'checked' : ''; ?> />
            Business Account
        </label>

        <label for="terms-and-conditions">
            <input id="terms-and-conditions" type="checkbox" required name="terms-and-conditions" class="inline" <?php echo $row['terms_accepted'] === 'Accepted' ? 'checked' : ''; ?> /> I accept
            the <a href="https://www.freecodecamp.org/news/terms-of-service/">terms and conditions</a>
        </label>

        <label for="age">Input your age (years): <input id="age" type="number" name="age" min="13" max="120" value="<?php echo $row['age']; ?>" /></label>

        <label for="referrer">How did you hear about us?
            <select id="referrer" name="referrer">
                <option value="">(select one)</option>
                <option value="freeCodeCamp News" <?php echo $row['referrer'] === 'freeCodeCamp News' ? 'selected' : ''; ?>>freeCodeCamp News</option>
                <option value="freeCodeCamp YouTube Channel" <?php echo $row['referrer'] === 'freeCodeCamp YouTube Channel' ? 'selected' : ''; ?>>freeCodeCamp YouTube Channel</option>
                <option value="freeCodeCamp Forum" <?php echo $row['referrer'] === 'freeCodeCamp Forum' ? 'selected' : ''; ?>>freeCodeCamp Forum</option>
                <option value="other" <?php echo $row['referrer'] === 'other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </label>

        <label for="bio">Provide a bio:
            <textarea id="bio" name="bio" rows="3" cols="30" placeholder="I like coding on the beach..."><?php echo $row['bio']; ?></textarea>
        </label>

        <label for="profile-picture">Upload a profile picture: <input id="profile-picture" type="file" name="file" /></label>

        <?php
        $sql = "SELECT profile_picture FROM form_data WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fileName = $row['profile_picture'];

            if (!empty($fileName)) {
                $targetDir = "http://registrationpagewithaddress.local/uploads/";
                $targetFilePath = $targetDir . $fileName;
                echo '<img src="' . $targetFilePath . '" alt="Profile Picture" width="200"><br>';
            }
        }

        ?>
        </fieldset>
        <fieldset>
            <label for="country">Country:</label>
            <select name="country" id="country">
                <option value="<?php echo $country ?>" selected><?php echo $country ?></option>

                <?php
                $query = "SELECT * FROM countries";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['name'] == $country) {
                        continue; 
                    }
                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>


            <br><br>

            <label for="state">State:</label>
            <select id="state" name="state">
                <option>select any one</option>
                <option value="<?php echo $state ?>" selected><?php echo $state ?></option>
            </select>

            <br><br>

            <label for="city">City:</label>
            <select id="city" name="city">
                <option>select any one</option>
                <option value="<?php echo $city ?>" selected><?php echo $city ?></option>
            </select>

            <br><br>

        </fieldset>
        <fieldset>
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="<?php echo $category ?>" selected><?php echo $category ?></option>

                <?php
                $query = "SELECT * FROM category";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['categoryname'] == $category) {
                        continue; 
                    }
                    echo "<option value='" . $row['categoryname'] . "'>" . $row['categoryname'] . "</option>";
                }
                ?>
            </select>

            <br><br>

            <label for="subcategory">SubCategory:</label>
            <select name="subcategory" id="subcategory">
                <option value="<?php echo $subcategory ?>" selected><?php echo $subcategory ?></option>
            </select>

            <br><br>

        </fieldset>
        <input type="submit" name="submit" value="Submit">
    </form>
    <!-- <?php
            // Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $countryId = $_POST["country"] ?? '';
                $stateId = isset($_POST["state"]) ? $_POST["state"] : '';
                $cityId = isset($_POST["city"]) ? $_POST["city"] : '';

                // Retrieve selected data from the database

                // Retrieve country name
                $query = "SELECT name FROM countries WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $countryId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $countryName = mysqli_fetch_assoc($result)['name'];
                } else {
                    $countryName = "Unknown Country";
                }

                // Retrieve state name
                $query = "SELECT name FROM states WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $stateId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $stateName = mysqli_fetch_assoc($result)['name'];
                } else {
                    $stateName = "Unknown State";
                }

                // Retrieve city name
                $query = "SELECT name FROM cities WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $cityId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $cityName = mysqli_fetch_assoc($result)['name'];
                } else {
                    $cityName = "Unknown City";
                }


                // Display selected data
                echo "<h2><p>Selected Data:</p></h2>";
                echo "<p>Country: " . $countryName . "</p>";
                echo "<p>State: " . $stateName . "</p>";
                echo "<p>City: " . $cityName . "</p>";
            }
            ?> -->

    <script>
        const countryDropdown = document.getElementById('country');
        const stateDropdown = document.getElementById('state');
        const cityDropdown = document.getElementById('city');
        const categoryDropdown = document.getElementById('category');
        const subcategoryDropdown = document.getElementById('subcategory');


        const statesData = {
            <?php
            $query = "SELECT countries.name AS country_name, states.name AS state_name
            FROM states
            JOIN countries ON states.country_id = countries.id;";

            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $states = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $countryName = $row['country_name'];
                $stateName = $row['state_name'];

                $states[$countryName][$stateName] = array(
                    'id' => $countryName, // Set the name as the ID
                    'name' => $stateName
                );
            }

            foreach ($states as $countryName => $countryData) {


                echo "'" . $countryName . "': " . json_encode($countryData) . ",";
            }
            ?>
        };


        countryDropdown.addEventListener('click', function() {
            const countryId = this.value;
            stateDropdown.innerHTML = '';
            cityDropdown.innerHTML = '';

            if (statesData.hasOwnProperty(countryId)) {
                const states = statesData[countryId];
                Object.keys(states).forEach(function(countryId) {
                    const state = states[countryId];
                    const option = document.createElement('option');
                    option.value = state.name;
                    option.textContent = state.name;
                    stateDropdown.appendChild(option);
                });
            }
        });

        stateDropdown.addEventListener('change', function() {
            const stateId = this.value;

            cityDropdown.innerHTML = '';

            const citiesData = {
                <?php
                $query = "SELECT states.name AS state_name, cities.name AS city_name
            FROM cities
            JOIN states ON cities.state_id = states.id;";

                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                $cities = array();

                while ($row = mysqli_fetch_assoc($result)) {
                    $stateName = $row['state_name'];
                    $cityName = $row['city_name'];

                    $cities[$stateName][$cityName] = array(
                        'id' => $cityName, // Set the name as the ID
                        'name' => $cityName
                    );
                }

                foreach ($cities as $stateName => $stateData) {


                    echo "'" . $stateName . "': " . json_encode($stateData) . ",";
                }
                ?>
            };


            if (citiesData.hasOwnProperty(stateId)) {
                const cities = citiesData[stateId];
                Object.keys(cities).forEach(function(stateId) {
                    const stateCities = cities[stateId];
                    const option = document.createElement('option');
                    option.value = stateCities.id;
                    option.textContent = stateCities.name;
                    cityDropdown.appendChild(option);

                });
            }
        });

        categoryDropdown.addEventListener('click', function() {
            const categoryId = this.value;

            subcategoryDropdown.innerHTML = '';
            const subcategoryData = {
                <?php

                $query = "SELECT category.categoryname AS category_name, subcategory.name AS subcategory_name FROM subcategory JOIN category ON subcategory.category_id = category.id;";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                $subcategories = array();

                while ($row = mysqli_fetch_assoc($result)) {
                    $categoryName = $row['category_name'];
                    $subcategoryName = $row['subcategory_name'];

                    $subcategories[$categoryName][$subcategoryName] = array(
                        'id' => $categoryName, // Set the name as the ID
                        'name' => $subcategoryName
                    );
                }


                foreach ($subcategories as $categoryId => $subcategoriesList) {
                    echo "'" . $categoryId . "': " . json_encode($subcategoriesList) . ",";
                }
                ?>
            };

            if (subcategoryData.hasOwnProperty(categoryId)) {
                const subcategories = subcategoryData[categoryId];
                Object.keys(subcategories).forEach(function(categoryId) {
                    const categorySubcategories = subcategories[categoryId];
                    const option = document.createElement('option');
                    option.value = categorySubcategories.name;
                    option.textContent = categorySubcategories.name;
                    subcategoryDropdown.appendChild(option);

                });
            }
        });
    </script>
</body>

</html>