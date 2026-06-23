<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['id'] !== "admin_logged_in_34354545sfdsfdff") {
    header('Location: login.php?error=not_logged_in');
    exit();
}
include_once '../handlers/dbh.php';

// Mark reply status and save admin note if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contact_id'])) {
    $contactId = filter_var($_POST['contact_id'], FILTER_VALIDATE_INT);
    $replyText = trim($_POST['reply'] ?? '');
    $newStatus = $replyText !== '' ? 'replied' : 'pending';

    if ($contactId) {
        $stmt = $conn->prepare('UPDATE contacts SET reply = ?, status = ?, replied_at = NOW() WHERE id = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('ssi', $replyText, $newStatus, $contactId);
            $stmt->execute();
            $stmt->close();
            header('Location: messages.php?success=1');
            exit();
        }
    }
}

$sql = 'SELECT id, name, email, subject, status, created_at FROM contacts ORDER BY created_at DESC';
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:Inter, sans-serif}</style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full">
    <?php include '../includes/admin-header.php'; ?>

    <div class="max-w-6xl mx-auto mt-10 px-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-serif text-brand-primary">Messages</h1>
                <p class="text-sm text-stone-500 mt-2">View incoming contact requests and add replies or status updates.</p>
            </div>
            <?php if (!empty($_GET['success'])): ?>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-800">Message updated successfully.</div>
            <?php endif; ?>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-500 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Received</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <?php if ($result && $result->num_rows): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-6 py-5 font-medium text-brand-primary"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="px-6 py-5 text-stone-500"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-5"><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td class="px-6 py-5">
                                    <?php if ($row['status'] === 'replied'): ?>
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-[10px] uppercase font-semibold text-emerald-700">Replied</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-[10px] uppercase font-semibold text-amber-700">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-stone-500"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="px-6 py-5 text-right">
                                    <a href="message.php?id=<?php echo urlencode($row['id']); ?>" class="text-brand-accent font-semibold hover:underline">Reply</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td class="p-6 text-stone-500" colspan="6">No messages yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
