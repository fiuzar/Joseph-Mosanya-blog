<?php
    // Include database connection
    include 'handlers/dbh.php';
    include_once 'includes/og.php';
    
    $subscribeSuccess = isset($_GET['subscribe_success']);
    $subscribeError = trim($_GET['subscribe_error'] ?? '');
    $ogTitle = 'Nnamdi Joseph Mosanya — Ebook Editorial & Publisher';
    $ogDescription = 'Explore a curation of high-quality digital publications covering profound guidelines, deep collections of poetry, and blueprints on everyday wisdom.';
    $ogImage = getAbsoluteUrl('images/logo.png');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
<?php outputOgMeta($ogTitle, $ogDescription, $ogImage); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nnamdi Joseph Mosanya — Ebook Editorial & Publisher</title>
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
    <!-- Google Fonts: Inter for clean UI metrics, Playfair Display for gorgeous book typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full flex flex-col font-sans">

    <?php include 'includes/header.php'; ?>

    <main class="flex-grow">
        
        <!-- 1. HERO HEADER: WELCOME & PLATFORM MISSION -->
        <section class="bg-white border-b border-stone-200/60 py-20 lg:py-24">
            <div class="max-w-4xl mx-auto text-center px-6">
                <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-indigo-50/60 text-brand-accent uppercase tracking-wider mb-5">
                    Nnamdi Joseph Mosanya Ebook Editorial
                </span>
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-normal text-brand-primary tracking-tight leading-tight mb-6">
                    Books that will Help You to <span class="italic font-serif font-bold text-brand-accent">Learn and Grow</span> in Life
                </h1>
                <p class="text-lg sm:text-xl text-stone-500 font-light max-w-2xl mx-auto mb-8 leading-relaxed">
                    Explore a curation of high-quality digital publications covering profound guidelines, deep collections of books, and blueprints on everyday wisdom.
                </p>
                <div>
                    <a href="#book-catalog" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-sm font-semibold uppercase tracking-wider rounded-xl text-white bg-brand-accent hover:bg-brand-accentHover shadow-sm transition-all duration-200 active:scale-[0.98]">
                        Browse Entire Storefront
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. THE BRAND PERSPECTIVE (MISSION STATEMENT STRIP) -->
        <section class="bg-stone-50 border-b border-stone-200/40 py-10">
            <div class="max-w-5xl mx-auto px-6 text-center">
                <span class="block text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-2">Our Core Perspective</span>
                <p class="font-serif italic text-lg sm:text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed">
                    "To reach out to the world with books that will help people to learn a variety of things across disciplines."
                </p>
            </div>
        </section>

        <!-- 3. CORE CATALOG AND CONTENT WORKSPACE -->
        <div id="book-catalog" class="max-w-7xl mx-auto px-6 py-16 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">
                
                <!-- LEFT COLUMN: MAIN DIGITAL BOOK SHOWCASE FEED -->
                <section class="lg:col-span-2 space-y-10">
                    <h2 class="font-serif text-2xl font-normal text-brand-primary tracking-tight border-b border-stone-200/60 pb-4 mb-8">
                        The Editorial Library Shelf
                    </h2>

                    <?php
                        // Fetch books dynamically from your existing blogs table with new retail metrics
                        $sql = "SELECT id, title, meta_description, content, category, price, currency, file_format, url_slug, status FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 6";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            // High-end book spine gradients representing beautiful print jackets
                            $gradients = [
                                'bg-gradient-to-br from-indigo-950 to-slate-900',
                                'bg-gradient-to-br from-stone-900 to-stone-950',
                                'bg-gradient-to-br from-teal-950 to-slate-900',
                                'bg-gradient-to-br from-indigo-900 to-purple-950',
                                'bg-gradient-to-br from-stone-800 to-indigo-950'
                            ];
                            $gIndex = 0;
                            
                            while($row = $result->fetch_assoc()) {
                                $gradient = $gradients[$gIndex % count($gradients)];
                                $gIndex++;
                                
                                $isFree = (floatval($row['price']) <= 0);
                                $currencySymbol = ($row['currency'] === 'USD') ? '$' : '₦';
                                $formattedPrice = $isFree ? 'Free' : $currencySymbol . number_format($row['price'], 0);
                    ?>
                                <article class="bg-white rounded-2xl overflow-hidden border border-stone-200/60 shadow-xs hover:shadow-sm transition-shadow duration-300 flex flex-col sm:flex-row">
                                    
                                    <!-- Dynamic 3D Book Jacket Mock Frame -->
                                    <div class="sm:w-1/3 <?php echo $gradient; ?> min-h-[250px] flex flex-col justify-between p-6 text-stone-100 relative border-r border-stone-100/10">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9px] uppercase font-bold tracking-widest text-indigo-200/90"><?php echo htmlspecialchars($row['file_format'] ?: 'PDF'); ?> Format</span>
                                            <!-- Subtle 3D shadow spine effect on the book edge -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-black/20 blur-[1px]"></div>
                                        </div>
                                        
                                        <h4 class="font-serif text-lg font-normal leading-tight mt-4 line-clamp-3">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </h4>
                                        
                                        <div class="mt-auto pt-4 border-t border-stone-100/10">
                                            <p class="text-[10px] text-stone-400 font-sans tracking-wider uppercase">Publisher Desk</p>
                                            <p class="text-[11px] text-stone-300 font-serif italic">N. J. Mosanya</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Book Meta Details & Direct Checkouts -->
                                    <div class="p-6 sm:p-8 sm:w-2/3 flex flex-col justify-between bg-white">
                                        <div>
                                            <div class="flex items-center gap-2 text-xs mb-3">
                                                <span class="text-brand-accent font-semibold uppercase tracking-wider text-[10px]">
                                                    <?php echo htmlspecialchars($row['category']); ?>
                                                </span>
                                                <span class="text-stone-300">•</span>
                                                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md text-[10px] uppercase tracking-wider">
                                                    Available Now
                                                </span>
                                            </div>
                                            
                                            <h3 class="font-serif text-xl sm:text-2xl font-normal text-brand-primary mb-3 hover:text-brand-accent transition-colors leading-snug">
                                                <a href="post/<?php echo htmlspecialchars($row['url_slug']); ?>">
                                                    <?php echo htmlspecialchars($row['title']); ?>
                                                </a>
                                            </h3>
                                            
                                            <p class="text-stone-500 text-sm leading-relaxed mb-6 line-clamp-3 font-light">
                                                <?php echo htmlspecialchars($row['meta_description']); ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Footer Actions with Explicit Pricing -->
                                        <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                                            <a href="post/<?php echo htmlspecialchars($row['url_slug']); ?>" class="inline-flex items-center text-xs font-semibold uppercase tracking-wider text-brand-accent hover:text-brand-accentHover transition-colors gap-1 group font-sans">
                                                Read Synopsis 
                                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </a>
                                            
                                            <div class="flex items-baseline gap-1 bg-stone-50 px-4 py-2 rounded-xl border border-stone-200/40">
                                                <span class="text-sm font-bold text-brand-primary font-serif">
                                                    <?php echo $formattedPrice; ?>
                                                </span>
                                                <?php if (!$isFree): ?>
                                                <span class="text-[9px] text-stone-400 font-bold uppercase tracking-wider">
                                                    <?php echo htmlspecialchars($row['currency']); ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </article>
                    <?php 
                            }
                        } else {
                            echo '<div class="bg-white rounded-2xl p-8 border border-stone-200/40 text-center text-stone-500 font-serif italic">No published eBooks available inside the storefront directory yet.</div>';
                        }
                    ?>

                    <div class="pt-6 text-center sm:text-left">
                        <a href="/archive" class="inline-flex items-center justify-center px-6 py-3.5 border border-stone-200 text-xs font-semibold uppercase tracking-wider rounded-xl text-stone-600 bg-white hover:bg-stone-50 transition-all duration-200">
                            Browse Complete Directory Index
                        </a>
                    </div>
                </section>

                <!-- RIGHT COLUMN: BIOGRAPHY & SYSTEM SIDEBAR ACCENTS -->
                <aside class="space-y-10 lg:sticky lg:top-28 self-start">
                    
                    <!-- THE AUTHOR BIO BLOCK (Nnamdi's Real Background Details) -->
                    <div class="bg-white rounded-2xl border border-stone-200/60 p-6 shadow-xs">
                        <h3 class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-4">The Publisher</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-brand-accent to-stone-900 flex items-center justify-center text-white font-serif font-semibold text-base shadow-inner">
                                NM
                            </div>
                            <div>
                                <h4 class="font-serif font-normal text-brand-primary text-base">Nnamdi Joseph Mosanya</h4>
                                <p class="text-xs text-stone-400 font-medium">Author & Digital Editor</p>
                            </div>
                        </div>
                        <p class="text-stone-500 text-sm leading-relaxed font-light space-y-2">
                            A native of Adaba in the Uzo-Uwani LGA, Enugu State, Nigeria. Nnamdi studied Business Administration at Enugu State University of Science and Technology (ESUT). He blends his deep administrative background with a passion for writing beautifully structured eBooks.
                        </p>
                    </div>

                    <!-- POPULAR TITLES SIDEBAR -->
                    <div class="bg-white rounded-2xl border border-stone-200/60 p-6 shadow-xs">
                        <h3 class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-4">Terms and Conditions of my Website</h3>
                        <ul class="space-y-4">
                            <li>
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Wisdom</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">To build a solid learning foundation</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">To make the less educated people to become educated</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">To help the world to know the truth about knowledge</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">To facilate education</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">To find joy in learning</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-2xl border border-stone-200/60 p-6 shadow-xs">
                        <h3 class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-4">Rules and Regulation</h3>
                        <ul class="space-y-4">
                            <li>
                                <a href="#" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Wisdom</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">Stop being afraid</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">Think before you make a decision</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">Don't relax when you are doing something serious</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">Create a reading skills</span>
                                </a>
                            </li>
                            <li class="border-t border-stone-100 pt-3">
                                <a href="" class="group block">
                                    <!-- <span class="text-[9px] font-semibold text-brand-accent uppercase tracking-wider block mb-0.5">Faith</span> -->
                                    <span class="font-serif text-sm text-brand-primary group-hover:text-brand-accent transition-colors line-clamp-2">Stop playing around and be serious with your life</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- EBOOK RELEASE ALERTS SIGNUP -->
                    <div class="bg-stone-900 text-white rounded-2xl p-6 shadow-xs relative overflow-hidden">
                        <div class="relative z-10 space-y-4">
                            <div>
                                <h3 class="font-serif text-lg font-normal mb-1">Join the Reader List</h3>
                                <p class="text-stone-400 text-xs leading-relaxed font-light">
                                    Receive instant email notifications whenever a brand new eBook is published.
                                </p>
                            </div>
                            
                            <?php if ($subscribeSuccess): ?>
                                <div class="bg-indigo-950/60 text-indigo-200 border border-indigo-800 rounded-xl p-3 text-xs font-semibold">Thanks — you have been subscribed.</div>
                            <?php elseif ($subscribeError): ?>
                                <div class="bg-red-950/60 text-red-200 border border-red-800 rounded-xl p-3 text-xs font-semibold"><?php echo htmlspecialchars($subscribeError); ?></div>
                            <?php endif; ?>
                            
                            <form action="handlers/subscribe.php" method="POST" class="space-y-3">
                                <input name="email" type="email" placeholder="Your email address" required 
                                       class="w-full bg-white/10 text-white placeholder-stone-500 border border-white/15 text-xs rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all">
                                <button type="submit" class="w-full bg-brand-accent hover:bg-brand-accentHover text-white text-xs font-semibold uppercase tracking-wider py-3.5 transition-colors rounded-xl shadow-xs">
                                    Join List
                                </button>
                            </form>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>