<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['id'] !== "admin_logged_in_34354545sfdsfdff") {
    header('Location: login.php?error=not_logged_in');
    exit();
}
include_once '../handlers/dbh.php';

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id','email','created_at']);
    $sql = "SELECT id, email, created_at FROM subscribers ORDER BY created_at DESC";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($out, [$row['id'], $row['email'], $row['created_at']]);
        }
    }
    fclose($out);
    exit();
}

$sql = "SELECT id, email, created_at FROM subscribers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Subscribers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:Inter, sans-serif}</style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full">
    <?php include '../includes/admin-header.php'; ?>

    <div class="max-w-4xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Subscribers</h1>
        <div class="mb-4">
            <a href="?export=csv" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">Export CSV</a>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Subscribed</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="p-3"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td class="p-3" colspan="3">No subscribers yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
