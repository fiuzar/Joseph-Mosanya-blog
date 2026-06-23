<?php
include 'handlers/dbh.php';

// Accept both 'slug' from clean .htaccess rules, and fallback to legacy 'id' queries safely
$slug = '';
if (!empty($_GET['slug'])) {
    $slug = trim($_GET['slug']);
} elseif (!empty($_GET['id'])) {
    $slug = trim($_GET['id']);
}

if ($slug === '') {
    header('Location: ../index.php');
    exit();
}

// Fetch eBook dynamically from your 'blogs' table
$stmt = $conn->prepare("SELECT * FROM blogs WHERE url_slug = ? AND status = 'published' LIMIT 1");
$stmt->bind_param('s', $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Fail gracefully back to your custom detours or homepage
    header('Location: ../index.php');
    exit();
}

$book = $result->fetch_assoc();
$stmt->close();

$book_title = $book['title'];
$book_category = $book['category'];
$book_author = $book['author'] ?: 'N.J. Mosanya';
$book_date = date('F j, Y', strtotime($book['created_at']));
$book_content = $book['content'];
$book_image = !empty($book['image_url']) ? $book['image_url'] : null;

// Retail and specification variables
$priceVal = floatval($book['price'] ?? 0);
$isFree = ($priceVal <= 0);
$currencySymbol = ($book['currency'] === 'NGN') ? '₦' : (($book['currency'] === 'USD') ? '$' : ($book['currency'] ?? '') . ' ');
$priceDisplay = $isFree ? 'Free' : $currencySymbol . number_format($priceVal, 2);
$checkoutUrl = !empty($book['purchase_url']) ? $book['purchase_url'] : '#';
$pageCount = !empty($book['page_count']) ? intval($book['page_count']) : null;
$fileFormat = !empty($book['file_format']) ? htmlspecialchars($book['file_format']) : 'PDF';

$ogTitle = $book_title . ' — Nnamdi Joseph Mosanya';
$ogDescription = !empty($book['meta_description']) ? $book['meta_description'] : $book_content;
?>
<?php include_once 'includes/og.php'; ?>
<?php $ogImage = $book_image ? getAbsoluteUrl($book_image) : getAbsoluteUrl('images/6df3233a40e81208.png'); ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
<?php outputOgMeta($ogTitle, $ogDescription, $ogImage); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book_title); ?> — Nnamdi Joseph Mosanya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#1C1917',   // Deep Charcoal
                            accent: '#312E81',    // Premium Indigo
                            accentHover: '#1E1B4B',
                            bgLight: '#FAF9F6',   // Alabaster Paper
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full flex flex-col font-sans">

    <?php include 'includes/header.php'; ?>

    <main class="flex-grow py-12 lg:py-20">
        <div class="max-w-5xl mx-auto px-6">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                <!-- LEFT COLUMN: BOOK COVER PRESENTATION (5 Cols) -->
                <div class="md:col-span-5 lg:sticky lg:top-24">
                    <div class="relative group bg-white p-4 rounded-2xl border border-stone-200/60 shadow-xs">
                        
                        <!-- Book Cover Frame simulating a real book jacket with a 3D Spine effect -->
                        <div class="aspect-[3/4] w-full rounded-xl overflow-hidden bg-stone-100 relative flex flex-col justify-between p-6 text-center border border-stone-200/40 shadow-inner">
                            
                            <?php if ($book_image): ?>
                                <img src="<?php echo htmlspecialchars($book_image); ?>" alt="<?php echo htmlspecialchars($book_title); ?>" class="absolute inset-0 w-full h-full object-cover">
                            <?php else: ?>
                                <!-- Stylized fallback representing a luxury typographic leatherbound print -->
                                <div class="absolute inset-0 bg-gradient-to-br from-brand-accent to-stone-900 opacity-95"></div>
                                
                                <!-- Subtle 3D shadow spine running down the left edge -->
                                <div class="absolute left-0 top-0 bottom-0 w-3 bg-black/30 blur-[1px] z-20"></div>
                                <div class="absolute left-3 top-0 bottom-0 w-[1px] bg-white/10 z-20"></div>
                                
                                <div class="relative z-10 flex flex-col justify-between h-full text-stone-100 p-2">
                                    <span class="text-[9px] tracking-widest font-bold uppercase text-indigo-200/90"><?php echo $fileFormat; ?> Digital Release</span>
                                    <h2 class="font-serif text-lg sm:text-xl font-normal leading-tight px-3 my-auto text-white">
                                        <?php echo htmlspecialchars($book_title); ?>
                                    </h2>
                                    <span class="text-xs font-serif italic text-stone-300">N. J. Mosanya</span>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    
                    <!-- Quick Specifications Panel -->
                    <div class="mt-6 bg-white/60 backdrop-blur-md rounded-2xl p-5 border border-stone-200/60 space-y-3.5 text-xs text-stone-500 font-medium font-sans shadow-2xs">
                        <div class="flex justify-between border-b border-stone-100 pb-2">
                            <span>File Format:</span>
                            <span class="text-stone-800 font-bold"><?php echo $fileFormat; ?> Digital Edition</span>
                        </div>
                        <?php if($pageCount): ?>
                        <div class="flex justify-between border-b border-stone-100 pb-2">
                            <span>Length:</span>
                            <span class="text-stone-800 font-bold"><?php echo $pageCount; ?> pages</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span>Category Segment:</span>
                            <span class="text-stone-800 font-bold"><?php echo htmlspecialchars($book_category); ?></span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: OVERVIEW AND CHECKOUT GATEWAY (7 Cols) -->
                <div class="md:col-span-7 space-y-8">
                    
                    <!-- Headings & Classifications -->
                    <div>
                        <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-50/60 text-brand-accent mb-4 border border-indigo-100/30">
                            <?php echo htmlspecialchars($book_category); ?>
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-serif font-normal text-brand-primary tracking-tight leading-tight mb-2">
                            <?php echo htmlspecialchars($book_title); ?>
                        </h1>
                        <p class="text-xs text-stone-400 font-medium">
                            Published by <span class="text-stone-700 font-semibold"><?php echo htmlspecialchars($book_author); ?></span> &bull; 
                            <time><?php echo htmlspecialchars($book_date); ?></time>
                        </p>
                    </div>

                    <!-- Dynamic Retail Action Panel -->
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-6 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div>
                            <span class="block text-[10px] text-stone-400 uppercase tracking-widest font-bold">Instant Digital Access</span>
                            <div class="flex items-baseline gap-1.5 mt-1">
                                <span class="text-3xl font-serif text-brand-primary font-medium">
                                    <?php echo $priceDisplay; ?>
                                </span>
                                <?php if (!$isFree): ?>
                                <span class="text-xs text-stone-400 font-bold uppercase tracking-wider"><?php echo htmlspecialchars($book['currency'] ?? 'NGN'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="w-full sm:w-auto">
                            <!-- Direct Purchase Checkout or Free Download links -->
                            <a href="<?php echo htmlspecialchars($checkoutUrl); ?>" target="_blank"
                               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-xs font-semibold uppercase tracking-widest rounded-xl text-white bg-brand-accent hover:bg-brand-accentHover shadow-xs transition-all duration-200 text-center active:scale-[0.98]">
                                <?php echo $isFree ? 'Download for Free' : 'Buy and Download Now'; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Book Content/Synopsis Overview Prose -->
                    <div class="space-y-4">
                        <h3 class="font-serif text-lg font-normal text-brand-primary border-b border-stone-200/60 pb-2">Description</h3>
                        
                        <!-- Elegant typography settings for rich HTML paragraphs from your editor -->
                        <div class="prose prose-stone max-w-none text-stone-600 text-[15px] sm:text-[16px] leading-relaxed font-light space-y-4 prose-headings:font-serif prose-headings:font-normal prose-headings:text-brand-primary prose-blockquote:border-l-brand-accent prose-blockquote:bg-stone-50 prose-blockquote:font-serif prose-blockquote:italic prose-blockquote:text-stone-700 prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-r-xl">
                            <?php echo $book_content; ?>
                        </div>
                    </div>

                    <!-- Footer Utilities -->
                    <div class="pt-8 border-t border-stone-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs font-sans font-semibold uppercase tracking-wider">
                        <div class="flex items-center gap-3 text-stone-400">
                            <span>Share Book:</span>
                            <a href="#" class="text-stone-500 hover:text-brand-accent transition-colors">LinkedIn</a>
                            <span class="text-stone-200">/</span>
                            <a href="#" class="text-stone-500 hover:text-brand-accent transition-colors">Twitter</a>
                        </div>
                        
                        <div>
                            <a href="archive.php" class="inline-flex items-center text-brand-accent hover:text-brand-accentHover transition-colors gap-2">
                                &larr; Return to Library shelf
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>