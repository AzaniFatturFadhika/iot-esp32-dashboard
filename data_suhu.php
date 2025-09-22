<?php
$mysqli = new mysqli("localhost", "root", "", "iot");
$query = "SELECT waktu, suhu FROM suhu_ruangan ORDER BY waktu DESC LIMIT 20";
$result = $mysqli->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(array_reverse($data)); // Urutkan dari lama ke baru