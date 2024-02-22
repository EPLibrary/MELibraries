<?php
session_start();
$pageTitle="ME Libraries | Statistics";
include 'header.php';

// Include database configuration
include '../melibraries-db-config.php';

// Get the list of libraries
// Query to fetch library names
$query = "SELECT record_index, library_name FROM library ORDER BY library_name ASC";
$librariesResult = mysqli_query($con, $query);

if (!$librariesResult) {
    die('Query failed: ' . mysqli_error($con));
}

$_POST['library'] = isset($_POST['library']) ? $_POST['library'] : '';

// Define the query

// Check if $_POST['library'] is set and not empty
if (isset($_POST['library']) && is_numeric($_POST['library'])) {
    // Sanitize the input to prevent SQL injection
    $libraryId = mysqli_real_escape_string($con, $_POST['library']);
    $libraryCondition = "AND hl.record_index = " . $libraryId;
} else {
    $libraryCondition = ""; // No additional condition if $_POST['library'] is empty
}

$query = "
SELECT
  hl.library_name AS home_library_name,
  rl.library_name AS remote_library_name,
  YEAR(ml.activity_datetime) AS year,
  MONTH(ml.activity_datetime) AS month,
  ml.operation,
  COUNT(*) AS operation_count
FROM
  membership_log ml
INNER JOIN library hl ON ml.home_library_record_index = hl.record_index
INNER JOIN library rl ON ml.remote_library_record_index = rl.record_index
WHERE
  ml.operation IN ('C', 'U')
  $libraryCondition
GROUP BY
  home_library_name,
  remote_library_name,
  YEAR(ml.activity_datetime),
  MONTH(ml.activity_datetime),
  ml.operation
ORDER BY
  home_library_name,
  year DESC,
  month DESC,
  remote_library_name,
  ml.operation;
";

// Proceed with executing the query as needed


$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

// Execute the query
$result = mysqli_query($con, $query);

// Check for errors
if (!$result) {
    die('Query failed: ' . mysqli_error($con));
}

// Start rendering the page
echo '
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
    
    

    <div class="mainContent" id="mainContent" style="padding:20px 0px;">

    <a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
    <h1 class="pageTitle greenbg">Statistics</h1>

    <div class="subContent">
    <h2 style="margin-bottom:20px;">ME Libraries Statistics</h2>

    <form method="post">
        <label for="library">Choose a library:</label>
        <select name="library" id="library">
            <option value="all">All Libraries</option>
';

// Check if there's a POST request and set the selected library ID
$selectedLibraryId = isset($_POST['library']) && is_numeric($_POST['library']) ? (int)$_POST['library'] : null;

// Dynamically populate the options from the database
while ($row = mysqli_fetch_assoc($librariesResult)) {
    $isSelected = $row['record_index'] == $selectedLibraryId ? ' selected' : '';
    echo '<option value="' . htmlspecialchars($row['record_index']) . '"' . $isSelected . '>' . htmlspecialchars($row['library_name']) . '</option>';
}


echo '
        </select>
        <input type="submit" value="Submit">
    </form>
    <br>

    <table style="font-family: sans-serif">
        <tr>
            <th>Remote Library</th>
            <th>Year</th>
            <th>Month</th>
            <th style="color: green;">Create</th>
            <th style="color: darkblue;">Update</th>
        </tr>';

// Initialize a variable to keep track of the current home library name
$currentHomeLibraryName = '';

// Initialize an empty array to hold the grouped data
$groupedData = [];

// Fetch and group data by remote_library_name, year, and month
while ($row = mysqli_fetch_assoc($result)) {
    // Construct a unique key for each group
    $key = $row['home_library_name'] . '|' . $row['remote_library_name'] . '|' . $row['year'] . '|' . $row['month'];

    // Initialize the group if it doesn't exist
    if (!isset($groupedData[$key])) {
        $groupedData[$key] = [
            'home_library_name' => $row['home_library_name'],
            'remote_library_name' => $row['remote_library_name'],
            'year' => $row['year'],
            'month' => $row['month'],
            'C' => 0, // Initialize 'C' operation count
            'U' => 0, // Initialize 'U' operation count
        ];
    }

    // Accumulate counts for 'C' and 'U' operations
    $groupedData[$key][$row['operation']] += $row['operation_count'];
}

// Initialize variable to track the current home library name for display purposes
$currentHomeLibraryName = '';

// Display the data
foreach ($groupedData as $data) {
    // Check if the home library name has changed
    if ($data['home_library_name'] !== $currentHomeLibraryName) {
        // Update the current home library name
        $currentHomeLibraryName = $data['home_library_name'];
        // Display the home library name with a colspan
        echo '<tr>
                <td colspan="6" style="background-color: #f0f0f0; text-align: center; font-weight: bold;">' . htmlspecialchars($currentHomeLibraryName) . '</td>
              </tr>';
    }

    // Display the data with 'C' and 'U' counts on the same row
    echo '<tr>
            <td>' . htmlspecialchars($data['remote_library_name']) . '</td>
            <td>' . htmlspecialchars($data['year']) . '</td>
            <td>' . htmlspecialchars($months[$data['month']]) . '</td>
            <td style="color: green; text-align:center;">' . $data['C'] . '</td>
            <td style="color: darkblue; text-align:center;">' . $data['U'] . '</td>
         </tr>';
}



// Close the table and the HTML document
echo '    </table>';
echo '<div style="margin: 80px 0 0 0;"></div>';

include 'footer.php';

// Close the database connection
mysqli_close($con);

