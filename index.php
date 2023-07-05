<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <?php
    include_once 'db.php';
    ?>
    <h1><a href="display_data.php">Display Data</a></h1>
    <h1>Registration Form</h1>
    <p>Please fill out this form with the required information</p>
    <form method="post" action="save_data.php" enctype="multipart/form-data">

        <fieldset>
            <label for="first-name">Enter Your First Name: <input id="first-name" name="first-name" type="text" required /></label>
            <label for="last-name">Enter Your Last Name: <input id="last-name" name="last-name" type="text" required /></label>
            <label for="email">Enter Your Email: <input id="email" name="email" type="email" required /></label>
            <label for="new-password">Create a New Password:</label>
            <input id="new-password" name="new-password" type="password" pattern="^[a-zA-Z0-9]{8,}$" required />
        </fieldset>
        <fieldset>
            <label for="personal-account">
                <input id="personal-account" type="radio" name="account-type" class="inline" value="Personal Account" />
                Personal Account
            </label>
            <label for="business-account">
                <input id="business-account" type="radio" name="account-type" class="inline" value="Business Account" />
                Business Account
            </label>
            <label for="terms-and-conditions">
                <input id="terms-and-conditions" type="checkbox" required name="terms-and-conditions" class="inline" /> I accept
                the <a href="https://www.freecodecamp.org/news/terms-of-service/">terms and conditions</a>
            </label>
        </fieldset>
        <fieldset>
            <label for="profile-picture">Upload a profile picture: <input id="profile-picture" type="file" name="file" /></label>
            <label for="age">Input your age (years): <input id="age" type="number" name="age" min="13" max="120" /></label>
            <label for="referrer">How did you hear about us?
                <select id="referrer" name="referrer">
                    <option value="">(select one)</option>
                    <option value="freeCodeCamp News">freeCodeCamp News</option>
                    <option value="freeCodeCamp YouTube Channel">freeCodeCamp YouTube Channel</option>
                    <option value="freeCodeCamp Forum">freeCodeCamp Forum</option>
                    <option value="Other">Other</option>
                </select>
            </label>
            <label for="bio">Provide a bio:
                <textarea id="bio" name="bio" rows="3" cols="30" placeholder="I like coding on the beach..."></textarea>
            </label>
        </fieldset>
        <fieldset>
            <label for="country">Country:</label>
            <select name="country" id="country">
                <option>select any one</option>

                <?php
                $query = "SELECT * FROM countries";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }

                ?>
            </select>

            <br><br>

            <label for="state">State:</label>
            <select id="state" name="state">
                <option>select any one</option>
            </select>

            <br><br>

            <label for="city">City:</label>
            <select id="city" name="city">
                <option>select any one</option>
            </select>

            <br><br>

        </fieldset>
        <fieldset>
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option>select any one</option>
                <?php
                $query = "SELECT * FROM category";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['categoryname'] . "'>" . $row['categoryname'] . "</option>";
                }
                ?>
            </select>
            <br><br>
            <label for="subcategory">Subcategory:</label>
            <select name="subcategory" id="subcategory">
                <option>select any one</option>
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
        // const citiesData = {
        //     <?php
                //     $query = "SELECT states.name AS state_name, cities.name AS city_name
                //     FROM cities
                //     JOIN states ON cities.state_id = states.id;";

                //     $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                //     $cities = array();

                //     while ($row = mysqli_fetch_assoc($result)) {
                //         $stateName = $row['state_name'];
                //         $cityName = $row['city_name'];

                //         $cities[$stateName][$cityName] = array(
                //             'id' => $stateName, // Set the name as the ID
                //             'name' => $cityName
                //         );
                //     }

                //     foreach ($cities as $stateName => $stateData) {


                //         echo "'". $stateName. "': ". json_encode($stateData). ",";
                //     }
                // 
                ?>
        // };




        countryDropdown.addEventListener('change', function() {
            const countryId = this.value;
            // alert(countryId);
            // console.log(countryId);
            // console.log(statesData);
            console.log(statesData);
            // Clear the state and city dropdowns
            stateDropdown.innerHTML = '';
            cityDropdown.innerHTML = '';

            // Populate the state dropdown
            if (statesData.hasOwnProperty(countryId)) {
                // alert("22");
                const states = statesData[countryId];
                console.log(states);
                // states.forEach(function(state) {
                //     const option = document.createElement('option');
                //     option.value = state.name;
                //     option.textContent = state.name;
                //     stateDropdown.appendChild(option);
                // });
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
            console.log(stateId);

            cityDropdown.innerHTML = '';

            // const citiesData = {
            //     <?php
                    //     $query = "SELECT countries.name AS country_name, states.name AS state_name, cities.name AS city_name
                    //         FROM cities
                    //         JOIN states ON cities.state_id = states.id
                    //         JOIN countries ON states.country_id = countries.id;";

                    //     $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    //     $cities = array();

                    //     while ($row = mysqli_fetch_assoc($result)) {
                    //         $countryName = $row['country_name'];
                    //         $stateName = $row['state_name'];
                    //         $cityName = $row['city_name'];

                    //         $cities[$countryName][$stateName][] = array(
                    //             'id' => $cityName, // Set the name as the ID
                    //             'name' => $cityName
                    //         );
                    //     }

                    //     foreach ($cities as $countryName => $countryData) {
                    //         echo "'" . $countryName . "': {";
                    //         foreach ($countryData as $stateName => $stateData) {
                    //             echo "'" . $stateName . "': " . json_encode($stateData) . ",";
                    //         }
                    //         echo "},";
                    //     }
                    //     
                    ?>
            // };
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
                console.log(cities);
                // alert("hi");
                Object.keys(cities).forEach(function(stateId) {
                    const stateCities = cities[stateId];
                    console.log(stateCities);
                    const option = document.createElement('option');
                    option.value = stateCities.id;
                    option.textContent = stateCities.name;
                    cityDropdown.appendChild(option);

                });
            }
        });

        categoryDropdown.addEventListener('change', function() {
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
            console.log(subcategoryData);
            console.log(categoryId);

            if (subcategoryData.hasOwnProperty(categoryId)) {
                const subcategories = subcategoryData[categoryId];
                console.log(subcategories);
                // alert("hi");
                Object.keys(subcategories).forEach(function(categoryId) {
                    const categorySubcategories = subcategories[categoryId];
                    console.log(categorySubcategories);
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