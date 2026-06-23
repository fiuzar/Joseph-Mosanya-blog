<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['id'] !== "admin_logged_in_34354545sfdsfdff") {
    header("Location: ../admin/login.php?error=not_logged_in");
    exit();
}

require_once 'dbh.php';

function slugify(string $text): string {
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^a-z0-9\-\s]+/u', '', $text);
    $text = preg_replace('/[\s_]+/u', '-', $text);
    $text = preg_replace('/-+/u', '-', $text);
    return trim($text, '-') ?: 'post-' . time();
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$title = trim($_POST['title'] ?? '');
$meta_description = trim($_POST['meta_description'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');
$image_url = trim($_POST['image_url'] ?? '');
$status = trim($_POST['status'] ?? 'draft');
$price = trim($_POST['price'] ?? '0.00');
$currency = trim($_POST['currency'] ?? 'NGN');
$purchase_url = trim($_POST['purchase_url'] ?? '');
$page_count = trim($_POST['page_count'] ?? '');
$file_format = trim($_POST['file_format'] ?? 'PDF');
$author = 'Joseph Nnamdi Mosanya';

$errors = [];

if ($title === '' || mb_strlen($title) < 5) {
    $errors[] = 'Title must be at least 5 characters long.';
} elseif (mb_strlen($title) > 255) {
    $errors[] = 'Title must not exceed 255 characters.';
}

if ($meta_description === '' || mb_strlen($meta_description) < 10) {
    $errors[] = 'Meta description must be at least 10 characters long.';
} elseif (mb_strlen($meta_description) > 160) {
    $errors[] = 'Meta description must not exceed 160 characters.';
}

if ($content === '') {
    $errors[] = 'Content cannot be empty.';
}

if ($category === '') {
    $errors[] = 'Category is required.';
} elseif (mb_strlen($category) > 50) {
    $errors[] = 'Category must not exceed 50 characters.';
}

if (!in_array($status, ['published', 'draft'], true)) {
    $status = 'draft';
}

if ($price === '' || !is_numeric($price) || floatval($price) < 0) {
    $price = '0.00';
}
$price = number_format((float)$price, 2, '.', '');

if ($currency === '') {
    $currency = 'NGN';
}
if (mb_strlen($currency) > 10) {
    $currency = mb_substr($currency, 0, 10);
}

if ($purchase_url !== '' && !filter_var($purchase_url, FILTER_VALIDATE_URL)) {
    $errors[] = 'Purchase URL must be a valid URL.';
}

// Handle optional uploaded image; validate and move to /images
$uploadedImage = null;
if (!empty($_FILES['image_upload']) && isset($_FILES['image_upload']['tmp_name']) && $_FILES['image_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image_upload'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Image upload failed. Please try again.';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = 'Uploaded image must be 5MB or smaller.';
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];
        if (!array_key_exists($mimeType, $allowedTypes)) {
            $errors[] = 'Uploaded image must be a JPG, PNG, GIF, or WEBP file.';
        } else {
            $ext = $allowedTypes[$mimeType];
            $baseName = bin2hex(random_bytes(8));
            $destinationName = $baseName . '.' . $ext;
            $imagesDir = dirname(__DIR__) . '/images';
            if (!is_dir($imagesDir) && !mkdir($imagesDir, 0755, true)) {
                $errors[] = 'Unable to create image storage directory.';
            } else {
                $destinationPath = $imagesDir . '/' . $destinationName;
                if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
                    $errors[] = 'Unable to save uploaded image. Please try again.';
                } else {
                    $uploadedImage = '/images/' . $destinationName;
                }
            }
        }
    }
}

// Only validate the provided image URL when no upload is present
if ($image_url !== '' && $uploadedImage === null && !filter_var($image_url, FILTER_VALIDATE_URL)) {
    $errors[] = 'Image URL must be a valid URL.';
}

$page_count = $page_count === '' ? null : filter_var($page_count, FILTER_VALIDATE_INT);
if ($page_count !== null && $page_count < 0) {
    $page_count = null;
}

if ($file_format === '') {
    $file_format = 'PDF';
} elseif (mb_strlen($file_format) > 50) {
    $file_format = mb_substr($file_format, 0, 50);
}

if (!empty($errors)) {
    $error_string = implode(' | ', $errors);
    $redirect = '../admin/write.php';
    if ($id) {
        $redirect .= '?id=' . urlencode($id) . '&error=' . urlencode($error_string);
    } else {
        $redirect .= '?error=' . urlencode($error_string);
    }
    header('Location: ' . $redirect);
    exit();
}

$slugBase = slugify($title);
$slug = $slugBase;
$counter = 0;
while (true) {
    $checkSql = 'SELECT id FROM blogs WHERE url_slug = ?' . ($id ? ' AND id != ?' : '') . ' LIMIT 1';
    $stmt = $conn->prepare($checkSql);
    if (!$stmt) {
        break;
    }
    if ($id) {
        $stmt->bind_param('si', $slug, $id);
    } else {
        $stmt->bind_param('s', $slug);
    }
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    if (!$exists) {
        break;
    }
    $counter++;
    $slug = $slugBase . '-' . $counter;
}

$cleanImageUrl = $uploadedImage ?? ($image_url === '' ? null : $image_url);
$cleanPurchaseUrl = $purchase_url === '' ? null : $purchase_url;
$cleanPageCount = $page_count === null ? null : $page_count;

if ($id) {
    $updateSql = 'UPDATE blogs SET title = ?, meta_description = ?, content = ?, category = ?, price = ?, currency = ?, purchase_url = ?, image_url = ?, author = ?, url_slug = ?, page_count = ?, file_format = ?, status = ?, updated_at = NOW() WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($updateSql);
    if (!$stmt) {
        header('Location: ../admin/write.php?id=' . urlencode($id) . '&error=' . urlencode('Database error.'));
        exit();
    }
    $stmt->bind_param(
        'ssssssssssissi',
        $title,
        $meta_description,
        $content,
        $category,
        $price,
        $currency,
        $cleanPurchaseUrl,
        $cleanImageUrl,
        $author,
        $slug,
        $cleanPageCount,
        $file_format,
        $status,
        $id
    );
    $success = $stmt->execute();
    $stmt->close();
    if ($success) {
        header('Location: ../admin/write.php?id=' . urlencode($id) . '&success=' . urlencode('updated'));
        exit();
    }
    header('Location: ../admin/write.php?id=' . urlencode($id) . '&error=' . urlencode('Update failed.'));
    exit();
}

$insertSql = 'INSERT INTO blogs (title, meta_description, content, category, price, currency, purchase_url, image_url, author, url_slug, page_count, file_format, status, views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())';
$stmt = $conn->prepare($insertSql);
if (!$stmt) {
    header('Location: ../admin/write.php?error=' . urlencode('Database error.'));
    exit();
}
$stmt->bind_param(
    'ssssssssssiss',
    $title,
    $meta_description,
    $content,
    $category,
    $price,
    $currency,
    $cleanPurchaseUrl,
    $cleanImageUrl,
    $author,
    $slug,
    $cleanPageCount,
    $file_format,
    $status
);
$success = $stmt->execute();
$insertedId = $stmt->insert_id;
$stmt->close();

if ($success) {
    header('Location: ../admin/write.php?id=' . urlencode($insertedId) . '&success=' . urlencode('saved'));
    exit();
}

header('Location: ../admin/write.php?error=' . urlencode('Insert failed. Please try again.'));
exit();
