<?php
// Include database configuration
include '../melibraries-db-config.php';

// Define your query
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

// Execute the query
$result = mysqli_query($con, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($con));
}

// Define the filename for the CSV download
$filename = "library_stats_" . date('Ymd') . ".csv";

// Set headers to download file rather than displayed
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open the file stream
$output = fopen('php://output', 'w');

// Add the CSV header
fputcsv($output, ['Home Library Name', 'Remote Library Name', 'Year', 'Month', 'Operation', 'Operation Count']);

// Loop over the query results and add to the CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['home_library_name'],
        $row['remote_library_name'],
        $row['year'],
        $row['month'],
        $row['operation'],
        $row['operation_count']
    ]);
}

// Close the file stream
fclose($output);

// Close the database connection
mysqli_close($con);

// Prevent further execution
exit();
