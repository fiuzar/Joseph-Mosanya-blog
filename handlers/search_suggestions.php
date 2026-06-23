<?php
header('Content-Type: application/json; charset=utf-8');

include 'dbh.php';

$search = trim($_GET['q'] ?? '');
if ($search === '') {
    echo json_encode(['results' => []]);
    exit();
}

$searchTerm = '%' . $search . '%';
$sql = "SELECT title, url_slug, category FROM blogs WHERE status = 'published' AND (title LIKE ? OR meta_description LIKE ? OR category LIKE ?) ORDER BY created_at DESC LIMIT 7";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['results' => []]);
    exit();
}
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = [
        'title' => $row['title'],
        'url_slug' => $row['url_slug'],
        'category' => $row['category']
    ];
}
$stmt->close();

echo json_encode(['results' => $results]);
