<?php
include 'handlers/dbh.php';
include_once 'includes/og.php';

$ogTitle = 'The Library Collection — Nnamdi Joseph Mosanya';
$ogDescription = 'Browse a comprehensive catalogue of inspirational eBooks, short stories, and digital publications designed to empower your daily walk and financial intelligence.';
$ogImage = getAbsoluteUrl('images/logo.png');

$category = trim($_GET['category'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');
$sort = ($sort === 'oldest') ? 'oldest' : 'newest';
$searchQuery = trim($_GET['search'] ?? '');

// Upgraded categories to represent professional ebook segments
$categoryOptions = ['Essays', 'Liturgies', 'Wisdom', 'Reflections', 'Business', 'Strategy', 'Engineering'];

// Include pricing, format, and page metrics in query selections
$query = "SELECT id, title, meta_description, category, url_slug, created_at, price, currency, file_format, page_count FROM blogs WHERE status = 'published'";
$params = [];
$types = '';

if ($category !== '' && in_array($category, $categoryOptions, true)) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

if ($searchQuery !== '') {
    $query .= " AND (title LIKE ? OR meta_description LIKE ? OR category LIKE ? )";
    $searchTerm = '%' . $searchQuery . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'sss';
}

$query .= $sort === 'oldest' ? " ORDER BY created_at ASC" : " ORDER BY created_at DESC";

$searchQueryForLinks = $searchQuery;

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Database prepare error: ' . $conn->error);
}

if (!empty($params)) {
    $bind_params = array_merge([$types], $params);
    $tmp = [];
    foreach ($bind_params as $key => $value) {
        $tmp[$key] = &$bind_params[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $tmp);
}

$stmt->execute();
$result = $stmt->get_result();

$postsByYear = [];
while ($row = $result->fetch_assoc()) {
    $year = date('Y', strtotime($row['created_at']));
    $postsByYear[$year][] = $row;
}
$stmt->close();

$selectedCategory = $category;
$selectedSort = $sort;

function buildCategoryUrl($category, $sort, $search = '') {
    $params = [];
    if ($category !== '') {
        $params['category'] = $category;
    }
    if ($sort !== '') {
        $params['sort'] = $sort;
    }
    if ($search !== '') {
        $params['search'] = $search;
    }
    return 'archive.php' . (!empty($params) ? '?' . http_build_query($params) : '');
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
<?php outputOgMeta($ogTitle, $ogDescription, $ogImage); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Library Collection — Nnamdi Joseph Mosanya</title>
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
                            bgLight: '#FAF9F6',   // Warm alabaster paper background
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
    <!-- Google Fonts: Inter for interface text, Playfair Display for book typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full flex flex-col">
    
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow">
        <!-- 1. EDITORIAL HEADER -->
        <section class="bg-white border-b border-stone-200/60 py-16 lg:py-20 shadow-xs">
            <div class="max-w-4xl mx-auto px-6">
                <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-bold bg-stone-100 text-stone-800 uppercase tracking-widest mb-4">
                    Complete Catalogue
                </span>
                <h1 class="font-serif text-4xl font-normal text-brand-primary tracking-tight mb-4">
                    The Library Archive
                </h1>
                <p class="text-base sm:text-lg text-stone-500 font-light leading-relaxed max-w-2xl">
                    A comprehensive index of inspirational eBooks
                </p>
            </div>
        </section>

        <!-- 2. DIRECTORY FILTERS & CONTROLS -->
        <div class="max-w-4xl mx-auto px-6 py-12">
            
            <!-- <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200/60 pb-6 mb-10">
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo buildCategoryUrl('', $selectedSort, $searchQueryForLinks); ?>" class="px-4 py-2 text-xs font-semibold rounded-full <?php echo $selectedCategory === '' ? 'bg-brand-accent text-white border-transparent' : 'bg-white text-stone-600 border-stone-200/60'; ?> border hover:border-brand-accent hover:text-brand-accent transition-colors">All Works</a>
                    <?php foreach ($categoryOptions as $cat): ?>
                        <a href="<?php echo buildCategoryUrl($cat, $selectedSort, $searchQueryForLinks); ?>" class="px-4 py-2 text-xs font-semibold rounded-full <?php echo $selectedCategory === $cat ? 'bg-brand-accent text-white border-transparent' : 'bg-white text-stone-600 border-stone-200/60'; ?> border hover:border-brand-accent hover:text-brand-accent transition-colors"><?php echo htmlspecialchars($cat); ?></a>
                    <?php endforeach; ?>
                </div>

                <div class="flex items-center gap-2">
                    <label for="sort" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Sort By:</label>
                    <select id="sort" onchange="window.location = this.value" class="bg-white border border-stone-200/60 rounded-xl text-xs font-medium text-stone-700 px-3 py-2 focus:outline-none focus:border-brand-accent transition-colors">
                        <option value="<?php echo buildCategoryUrl($selectedCategory, 'newest', $searchQueryForLinks); ?>" <?php echo $selectedSort === 'newest' ? 'selected' : ''; ?>>Recent Releases</option>
                        <option value="<?php echo buildCategoryUrl($selectedCategory, 'oldest', $searchQueryForLinks); ?>" <?php echo $selectedSort === 'oldest' ? 'selected' : ''; ?>>Earlier Works</option>
                    </select>
                </div>
            </div> -->

            <!-- 3. CHRONOLOGICAL RELEASES LIST -->
            <div class="space-y-12">
                <?php if (empty($postsByYear)): ?>
                    <div class="bg-white rounded-2xl border border-stone-200/60 shadow-xs p-10 text-center text-stone-400 italic">
                        No published catalogue entries were found<?php echo $selectedCategory ? ' for ' . htmlspecialchars($selectedCategory) : ''; ?>.
                    </div>
                <?php else: ?>
                    <?php
                        if ($selectedSort === 'newest') {
                            krsort($postsByYear);
                        } else {
                            ksort($postsByYear);
                        }

                        $badgeStyles = [
                            'Essays' => 'bg-indigo-50 text-brand-accent border-indigo-100',
                            'Liturgies' => 'bg-stone-100 text-stone-800 border-stone-200/40',
                            'Wisdom' => 'bg-amber-55/10 text-amber-900 border-amber-200/40',
                            'Reflections' => 'bg-rose-50 text-rose-700 border-rose-100'
                        ];
                    ?>

                    <?php foreach ($postsByYear as $year => $posts): ?>
                        <div>
                            <h2 class="font-serif text-xl font-normal text-brand-primary mb-6 flex items-center gap-3">
                                <span class="italic"><?php echo htmlspecialchars($year); ?></span>
                                <span class="h-px bg-stone-200/60 flex-grow"></span>
                            </h2>
                            <div class="space-y-6 pl-2 sm:pl-4">
                                <?php foreach ($posts as $post): ?>
                                    <?php 
                                        $badgeClass = $badgeStyles[$post['category']] ?? 'bg-stone-50 text-stone-600 border-stone-200/40'; 
                                        
                                        // Formulate dynamic price display string (incorporating free check)
                                        $priceVal = floatval($post['price']);
                                        if ($priceVal <= 0) {
                                            $priceDisplay = 'Free';
                                            $priceBadgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-100';
                                        } else {
                                            $currencySymbol = ($post['currency'] === 'NGN') ? '₦' : (($post['currency'] === 'USD') ? '$' : $post['currency'] . ' ');
                                            $priceDisplay = $currencySymbol . number_format($priceVal, 0);
                                            $priceBadgeClass = 'bg-stone-900 text-[#FAF9F6] border-stone-800';
                                        }
                                    ?>
                                    <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 group pt-5 pb-5 border-b border-stone-100 last:border-b-0">
                                        
                                        <!-- Release Date -->
                                        <time class="text-xs font-semibold uppercase tracking-wider text-stone-400 w-20 shrink-0">
                                            <?php echo date('M j', strtotime($post['created_at'])); ?>
                                        </time>
                                        
                                        <!-- Core Content Column -->
                                        <div class="flex-grow">
                                            <h3 class="font-serif text-lg font-normal leading-snug text-brand-primary group-hover:text-brand-accent transition-colors">
                                                <!-- Dynamic Clean URL slug path mapping -->
                                                <a href="/post/<?php echo htmlspecialchars($post['url_slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                            </h3>
                                            
                                            <!-- Sub-meta: description, page count, formats -->
                                            <p class="text-sm text-stone-500 mt-1.5 max-w-2xl line-clamp-2 font-light">
                                                <?php echo htmlspecialchars($post['meta_description']); ?>
                                            </p>
                                            
                                            <div class="flex flex-wrap items-center gap-3 text-[11px] text-stone-400 mt-2">
                                                <span><?php echo htmlspecialchars($post['file_format']); ?> Format</span>
                                                <?php if(!empty($post['page_count'])): ?>
                                                    <span>&bull;</span>
                                                    <span><?php echo intval($post['page_count']); ?> Pages</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Right-aligned details badges -->
                                        <div class="flex items-center gap-2.5 shrink-0 self-start md:self-center">
                                            <!-- Classification badge -->
                                            <span class="px-2.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider border <?php echo $badgeClass; ?>">
                                                <?php echo htmlspecialchars($post['category']); ?>
                                            </span>
                                            
                                            <!-- Pricing Tag -->
                                            <span class="px-2.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider border <?php echo $priceBadgeClass; ?>">
                                                <?php echo $priceDisplay; ?>
                                            </span>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- 4. EDITORIAL PAGINATION (Kept clean as visual navigation) -->
            <div class="mt-16 pt-6 border-t border-stone-200/60 flex items-center justify-center gap-2">
                <a href="#" class="inline-flex items-center justify-center px-4 py-2 border border-stone-200/60 text-xs font-semibold rounded-xl text-stone-300 bg-white cursor-not-allowed select-none">
                    &larr; Newer
                </a>
                <a href="archive.php?page=1" class="w-9 h-9 inline-flex items-center justify-center text-xs font-bold rounded-xl bg-brand-accent text-white shadow-xs">1</a>
                <a href="#" class="inline-flex items-center justify-center px-4 py-2 border border-stone-200/60 text-xs font-semibold rounded-xl text-stone-400 bg-white hover:border-brand-accent hover:text-brand-accent transition-colors">
                    Older &rarr;
                </a>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>