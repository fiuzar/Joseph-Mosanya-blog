<?php
session_start();

// Verify user validation state
if (!isset($_SESSION['id']) || $_SESSION['id'] !== "admin_logged_in_34354545sfdsfdff") {
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Bring in your global mysqli connection
include_once '../handlers/dbh.php';

// Catch parameter variables from URL path
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$successMessage = trim($_GET['success'] ?? '');
$errorMessage = trim($_GET['error'] ?? '');
$pageTitle = $id ? 'Edit Entry — Editorial Desk' : 'Compose Entry — Editorial Desk';

// Initialize variables for the form
$row = [
    'title' => '', 
    'meta_description' => '', 
    'content' => '', 
    'category' => '', 
    'image_url' => '', 
    'status' => 'draft', 
    'price' => 0.00, 
    'currency' => 'NGN',
    'purchase_url' => '', 
    'page_count' => 0, 
    'file_format' => 'PDF'
];

// If an ID exists, fetch existing data for the edit view
if ($id) {
    // Database table is named 'blogs'
    $sql = "SELECT title, meta_description, content, category, image_url, status, price, currency, purchase_url, page_count, file_format FROM blogs WHERE id = ? LIMIT 1";
    
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                header("Location: index.php?error=" . urlencode("Record not found."));
                exit();
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tiny.cloud/1/ll6muv6g2mn3amisizd93ljl1h3sv1da7as6vw6za0wwx1j7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="font-sans antialiased text-stone-800">
    <?php include '../includes/admin-header.php'; ?>
    <main class="max-w-7xl mx-auto px-6 py-10">
            <?php if ($successMessage): ?>
                <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-sm text-emerald-800">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-6 py-4 text-sm text-red-800">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>
    <form action="../handlers/save_post.php" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white border border-stone-200 rounded-2xl p-8 shadow-sm space-y-6">
        <input type="hidden" name="id" value="<?php echo $id ? htmlspecialchars($id) : ''; ?>">
        <h2 class="text-xl font-serif text-stone-900 pb-4 border-b border-stone-100">Editorial Metadata</h2>

        <div class="grid grid-cols-2 gap-6">
            <input name="title" type="text" value="<?php echo htmlspecialchars($row['title'] ?? ''); ?>" placeholder="Ebook Title" class="col-span-2 w-full border border-stone-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500">
            <input name="category" type="text" value="<?php echo htmlspecialchars($row['category'] ?? ''); ?>" placeholder="Category" class="w-full border border-stone-200 rounded-xl px-4 py-3">
            <select name="status" class="w-full border border-stone-200 rounded-xl px-4 py-3">
                <option value="draft" <?php echo $row['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?php echo $row['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
            <input name="meta_description" type="text" value="<?php echo htmlspecialchars($row['meta_description'] ?? ''); ?>" placeholder="Meta Description (SEO)" class="col-span-2 w-full border border-stone-200 rounded-xl px-4 py-3">
            <input name="image_url" type="text" value="<?php echo htmlspecialchars($row['image_url'] ?? ''); ?>" placeholder="Cover Image URL" class="col-span-2 w-full border border-stone-200 rounded-xl px-4 py-3">
            <div class="col-span-2">
                <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Or Upload Cover Image</label>
                <input name="image_upload" type="file" accept="image/*" class="w-full">
                <?php if (!empty($row['image_url'])): ?>
                    <div class="mt-3">
                        <img src="<?php echo htmlspecialchars($row['image_url'] ?? ''); ?>" alt="cover" class="h-28 object-cover rounded-lg border">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-6 pt-6 border-t border-stone-100">
            <div>
                <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Price</label>
                <input name="price" type="number" step="0.01" value="<?php echo $row['price']; ?>" class="w-full border border-stone-200 rounded-xl px-4 py-3">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Currency</label>
                <input name="currency" type="text" value="<?php echo htmlspecialchars($row['currency'] ?? ''); ?>" class="w-full border border-stone-200 rounded-xl px-4 py-3">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Page Count</label>
                <input name="page_count" type="number" value="<?php echo $row['page_count']; ?>" class="w-full border border-stone-200 rounded-xl px-4 py-3">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Format</label>
                <input name="file_format" type="text" value="<?php echo htmlspecialchars($row['file_format'] ?? ''); ?>" class="w-full border border-stone-200 rounded-xl px-4 py-3">
            </div>
        </div>

        <div>
            <label class="block text-[10px] uppercase font-bold text-stone-400 mb-2">Checkout URL</label>
            <input name="purchase_url" type="text" value="<?php echo htmlspecialchars($row['purchase_url'] ?? ''); ?>" class="w-full border border-stone-200 rounded-xl px-4 py-3">
        </div>

        <textarea id="editor-content" name="content" rows="10" placeholder="Write your content here..." class="w-full border border-stone-200 rounded-xl px-4 py-3"><?php echo htmlspecialchars($row['content'] ?? ''); ?></textarea>

        <script>
            tinymce.init({
                selector: '#editor-content',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \n                alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                content_css: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap',
                default_link_target: '_blank'
            });
        </script>

        <button type="submit" class="w-full bg-indigo-900 text-white py-4 rounded-xl font-semibold uppercase tracking-wider text-sm hover:bg-indigo-800">
            Save Changes
        </button>
    </form>
        </main>

    </div>
    
</body>
</html>