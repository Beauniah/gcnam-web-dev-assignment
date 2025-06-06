<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

function mapAgeGroup($age) {
    return $age < 13 ? "Child" : "Adult";
}

if (!$data || !isset($data["Ages"])) {
    echo json_encode(["error" => "Invalid input"]);
    http_response_code(400);
    exit;
}

// Build guests array
$guests = array_map(function ($age) {
    return ["Age Group" => mapAgeGroup($age)];
}, $data["Ages"]);

// Convert date format
function convertDate($date) {
    $parts = explode("/", $date);
    return "$parts[2]-$parts[1]-$parts[0]";
}

$payload = [
    "Unit Type ID" => -2147483637, // Use as default; can be dynamic later
    "Arrival" => convertDate($data["Arrival"]),
    "Departure" => convertDate($data["Departure"]),
    "Guests" => $guests
];

$ch = curl_init("https://dev.gondwana-collection.com/Web-Store/Rates/Rates.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
