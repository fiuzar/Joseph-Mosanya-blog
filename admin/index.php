<?php
session_start();

// Verify user validation state
if (!isset($_SESSION['id']) || $_SESSION['id'] !== "admin_logged_in_34354545sfdsfdff") {
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Bring in your global mysqli connection
include_once '../handlers/dbh.php';

// Fetch the literary collection sorted by latest release date
$sql = "SELECT id, title, category, created_at, url_slug, status FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Index — Editorial Desk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#1C1917',   // Deep Stone/Charcoal
                            accent: '#312E81',    // Premium Spiritual Indigo
                            accentHover: '#1E1B4B',
                            bgLight: '#FAF9F6',   // Soft Alabaster Theme Background
                        }
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased text-stone-800">
    <?php include '../includes/admin-header.php'; ?>
    <main class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-serif text-brand-primary">Collection Index</h2>
                    <p class="text-stone-500 mt-1">Manage your published library and drafts.</p>
                </div>
                <a href="write.php" class="bg-brand-accent text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-accentHover transition">
                    + Compose New Ebook
                </a>
            </div>
            <?php if (!empty($_GET['success'])): ?>
                <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-sm text-emerald-800">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_GET['error'])): ?>
                <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-6 py-4 text-sm text-red-800">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white border border-stone-200/60 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-100 uppercase tracking-wider text-[10px] text-stone-400">
                            <th class="px-8 py-5">Title</th>
                            <th class="px-8 py-5">Category</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-stone-50 transition">
                            <td class="px-8 py-6 font-medium text-brand-primary"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="px-8 py-6 text-stone-500"><?php echo htmlspecialchars($row['category']); ?></td>
                            <td class="px-8 py-6">
                                <?php if ($row['status'] === 'published'): ?>
                                    <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Published</span>
                                <?php else: ?>
                                    <span class="bg-stone-100 text-stone-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 text-right space-x-4">
                                <a href="write.php?id=<?php echo $row['id']; ?>" class="text-brand-accent font-semibold hover:underline">Edit</a>
                                <a href="../post.php?slug=<?php echo urlencode($row['url_slug']); ?>" target="_blank" class="text-stone-400 hover:text-stone-800">View</a>
                                <a href="../handlers/post_delete.php?id=<?php echo urlencode($row['id']); ?>" class="text-red-500 hover:text-red-700 font-semibold" onclick="return confirm('Delete this post permanently?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
</body>
</html>