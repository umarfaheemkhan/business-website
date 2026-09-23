
<?php

// 1. Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "roswell_hotel";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);

// 2. Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Please submit the contact form.");
}

// 4. Get form data
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$message = trim($_POST["message"] ?? "");

// 5. Validate required fields
if ($name === "" || $email === "" || $message === "") {
    exit("Please fill in all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Please enter a valid email address.");
}

// 6. Prepare SQL query
$stmt = $conn->prepare(
    "INSERT INTO contacts (name, email, phone, message)
     VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    exit("SQL prepare error: " . $conn->error);
}

// 7. Insert data
$stmt->bind_param("ssss", $name, $email, $phone, $message);

if ($stmt->execute()) {
    echo "Message saved successfully!";
} else {
    echo "Insert error: " . $stmt->error;
}

// 8. Close connection
$stmt->close();
$conn->close();

?>