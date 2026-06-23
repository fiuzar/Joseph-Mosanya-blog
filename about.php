<?php include_once 'includes/og.php';
$ogTitle = 'About the Author — Nnamdi Joseph Mosanya';
$ogDescription = 'Learn about Nnamdi Joseph Mosanya, an author, publisher, and digital editor committed to sharing moral clarity, inspiration, and structured knowledge through the written word.';
$ogImage = getAbsoluteUrl('images/logo.png');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
<?php outputOgMeta($ogTitle, $ogDescription, $ogImage); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About the Author — Nnamdi Joseph Mosanya</title>
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
        <!-- 1. EDITORIAL INTRO HERO -->
        <section class="bg-white border-b border-stone-200/60 py-16 lg:py-24 shadow-xs">
            <div class="max-w-4xl mx-auto text-center px-6">
                <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full text-[10px] font-bold bg-indigo-50/60 text-brand-accent uppercase tracking-widest mb-5">
                    Behind the Publisher
                </span>
                <h1 class="font-serif text-3xl sm:text-5xl font-normal text-brand-primary tracking-tight leading-tight mb-6">
                    <span class="italic font-serif">Knowledge and Wisdow</span> is Life
                </h1>
                <p class="text-base sm:text-lg text-stone-500 font-light max-w-2xl mx-auto leading-relaxed">
                    Crafting beautifully formatted, scannable digital eBooks designed to help readers around the world to learn and grow in life
                </p>
            </div>
        </section>

        <!-- 2. BIOGRAPHY AND MISSION CORE -->
        <section class="max-w-5xl mx-auto px-6 py-16 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16 items-start">
                
                <div class="md:col-span-2 space-y-6 text-stone-600 leading-relaxed text-base font-light">
                    <!-- <h2 class="font-serif text-2xl font-normal text-brand-primary tracking-tight mb-4">The Journey & The Desk</h2> -->
                    <p>
                        Nnamdi Joseph Mosanya is an author, publisher, and digital editor dedicated to spreading knowledge through the power of written word. 
                    </p>
                    <p>
                        Nnamdi Joseph Mosanya is a native of Adaba in the Uzo-Uwani Local Government Area in Enugu State, Nigeria. Nnamdi’s path has been driven by discipline  to service and administrative excellence. He earned his degree in Business Administration at Enugu State University of Science and Technology (ESUT).
                    </p>
                    
                    <div class="py-6 border-y border-stone-200/60 my-8">
                        <span class="block text-[9px] font-semibold text-stone-400 uppercase tracking-widest mb-2">Our Core Perspective</span>
                        <p class="italic font-serif text-lg text-stone-700 leading-relaxed">
                            "To reach out to the world with books that will help people to learn a variety of things across disciplines."
                        </p>
                    </div>
                </div>

                <!-- STYLISH AUTHOR PROFILE CARD PLACEHOLDER -->
                <div class="bg-white border border-stone-200/60 rounded-2xl p-4 shadow-xs md:sticky md:top-24">
                    <!-- Clean, artistic block ready for a photo frame setup later -->
                    <div class="aspect-[3/4] w-full rounded-xl bg-gradient-to-br from-brand-primary via-stone-800 to-indigo-950 flex flex-col items-center justify-between text-white p-6 shadow-inner relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-xl"></div>
                        <span class="text-[9px] tracking-widest font-bold text-indigo-200 uppercase self-start">Author Portrait</span>
                        
                        <!-- Symbolic Initial Badge in place of missing picture -->
                        <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center font-serif text-3xl font-normal border border-white/15 my-auto shadow-md">
                            NM
                        </div>
                        
                        <span class="text-xs font-serif italic text-stone-300 tracking-wider text-center">Nnamdi Joseph Mosanya</span>
                    </div>
                    <div class="mt-4 pt-2 border-t border-stone-100 text-center">
                        <h3 class="font-serif font-normal text-brand-primary text-base">Nnamdi Joseph Mosanya</h3>
                        <p class="text-[10px] text-stone-400 uppercase tracking-widest mt-0.5">Author & Publisher</p>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>