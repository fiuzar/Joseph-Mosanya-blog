<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Pages — Nnamdi Joseph Mosanya</title>
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
                            bgLight: '#FAF9F6',   // Alabaster/Fine Book Page
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
    <!-- Google Fonts: Inter and Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full flex flex-col font-sans">

    <!-- 1. HEADER INCLUDE -->
    <?php include './includes/header.php'; ?>

    <!-- 2. LOST PAGES CONTENT CANVAS -->
    <main class="flex-grow flex items-center justify-center py-16 lg:py-24">
        <section class="max-w-xl mx-auto px-6 text-center space-y-8">
            
            <!-- po_etic tag -->
            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-semibold bg-indigo-50/60 text-brand-accent uppercase tracking-wider">
                A Quiet Detour
            </span>

            <!-- Large Elegant Title -->
            <h1 class="text-4xl sm:text-5xl font-serif font-normal text-brand-primary tracking-tight leading-tight">
                An Uncharted Path
            </h1>

            <!-- Philosophical/Contemplative Quote -->
            <div class="py-6 border-y border-stone-200/60 my-6">
                <p class="font-serif italic text-stone-600 text-lg sm:text-xl leading-relaxed">
                    "We sometimes find ourselves on paths we did not intend to tread. In the silence of the detour, let us take a breath, look back, and re-anchor our steps."
                </p>
            </div>

            <!-- Explanatory Text -->
            <p class="text-sm sm:text-base text-stone-500 font-light leading-relaxed max-w-md mx-auto">
                The page or reflection you are looking for has either been gently renamed, moved to another part of the archive, or has not yet been committed to the library.
            </p>

            <!-- Navigation Redirect Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4 font-sans text-xs font-semibold uppercase tracking-wider">
                <a href="index.php" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 border border-transparent rounded-xl text-white bg-brand-accent hover:bg-brand-accentHover shadow-sm transition-all duration-200 active:scale-[0.98]">
                    Return Home
                </a>
                <a href="archive.php" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 border border-stone-200 text-stone-500 bg-white hover:bg-stone-50 rounded-xl transition-all duration-200">
                    Browse the Library
                </a>
            </div>

        </section>
    </main>

    <!-- 3. FOOTER INCLUDE -->
    <?php include './includes/footer.php'; ?>

</body>
</html>