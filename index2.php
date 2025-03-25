<?php
// Initialize variable
$projectName = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input value and sanitize it
    $projectName = htmlspecialchars($_POST["project_name"]);
}


// Database connection details
$servername = "YOUR_SERVER_NAME";
$username = "YOUR_USERNAME";
$password = "YOUR_PASSWORD";
$dbname = "YOUR_DATABASE_NAME";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br>";
}

// SQL query to fetch PId and Title
$sql = "SELECT PId, Title FROM projects";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
} else {
    echo "Query executed successfully!<br>";
}


$titles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row["Title"]; // Store titles in the array
    }
}

// Add this right after fetching titles
if (!empty($projectName)) {
    $data = [
        "query" => $projectName,
        "titles" => $titles
    ];

        // Send the request to FastAPI
        $apiUrl = "http://localhost:8000/search"; // Replace with your API URL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo "<h3>cURL Error:</h3>" . curl_error($ch);
        }
    
        curl_close($ch);
    
        // Decode response for displaying results
        $searchResults = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project List</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Enter Project Name</h2>

<form method="post" action="index2.php">
    <label>Project Name:</label>
    <input type="text" name="project_name">
    <input type="submit" value="Search">
</form>

<?php
// Debugging step: Check if the project name is being submitted
if (isset($_POST['project_name'])) {
    $projectName = $_POST['project_name'];
    echo "<h3>User entered project name: " . htmlspecialchars($projectName) . "</h3>";
} else {
    echo "<h3>No project name submitted.</h3>";
}
?>


    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["PId"] . "</td><td>" . $row["Title"] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No projects found</td></tr>";
    }
    $conn->close();
    ?>
</table>

<?php if (!empty($searchResults)): ?>
    <h2>Search Results</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Similarity Score</th>
        </tr>
        <?php foreach ($searchResults['results'] as $result): ?>
            <tr>
                <td><?php echo $result['title']; ?></td>
                <td><?php echo number_format($result['score'], 4); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
