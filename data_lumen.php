<?php
$mysqli = new mysqli("localhost", "root", "", "iot");
$query = "SELECT waktu, lumen, status_ruangan FROM ruangan_lumen ORDER BY waktu DESC LIMIT 20";
$result = $mysqli->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    // bisa juga cast lumen ke float kalau perlu
    $data[] = [
        'waktu' => $row['waktu'],
        'lumen' => $row['lumen'],
        'status_ruangan' => $row['status_ruangan']
    ];
}

echo json_encode(array_reverse($data));
