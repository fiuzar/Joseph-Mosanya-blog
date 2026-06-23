<?php
session_start();

// Redirect automatically if user is already holding a valid active payload validation stamp
if (isset($_SESSION['id']) && $_SESSION['id'] === "admin_logged_in_34354545sfdsfdff") {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access — Editorial Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#1C1917',   // Deep Stone
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-brand-bgLight text-stone-800 antialiased min-h-full flex flex-col justify-between">

    <div class="p-6 max-w-5xl w-full mx-auto">
        <a href="../index.php" class="inline-flex items-center text-xs font-semibold uppercase tracking-wider text-stone-400 hover:text-brand-primary transition-colors gap-2">
            &larr; Return to Public Library
        </a>
    </div>

    <main class="w-full max-w-md mx-auto p-6 flex-grow flex items-center justify-center">
        <div class="w-full bg-white border border-stone-200/60 rounded-2xl p-8 shadow-xs space-y-6">
            
            <div class="text-center">
                <a href="../index.php" class="font-serif text-2xl tracking-tight text-brand-primary block">
                    Joseph Nnamdi Mosanya
                </a>
                <h1 class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mt-2 border-t border-stone-100 pt-2 max-w-[180px] mx-auto">
                    Internal Repository Gateway
                </h1>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-50 border border-red-100 rounded-xl p-3.5 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-700 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-xs text-red-800 font-medium leading-relaxed uppercase tracking-wider text-[11px]">
                        <?php 
                            if($_GET['error'] === 'empty_input') echo "Please populate all security validation metrics.";
                            elseif($_GET['error'] === 'invalid_credentials') echo "The authentication variables could not be verified.";
                            elseif($_GET['error'] === 'not_logged_in') echo "Authorized signature validation token required.";
                            else echo htmlspecialchars($_GET['error']);
                        ?>
                    </div>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3.5 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="text-xs text-emerald-800 font-medium leading-relaxed uppercase tracking-wider text-[11px]">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-stone-50 border border-stone-200/40 rounded-xl p-3.5 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-stone-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8v4m0 5h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-[11px] text-stone-500 font-medium tracking-wide leading-relaxed uppercase">
                        Composition terminal locked. Unauthenticated footprint sequences are audited.
                    </div>
                </div>
            <?php endif; ?>

            <form action="/handlers/auth_handler.php" method="POST" class="space-y-4">
                
                <div>
                    <label for="username" class="block text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-2">Secure User ID</label>
                    <input type="text" id="username" name="username" required autocomplete="username" placeholder="nnamdi@example.com"
                           class="w-full bg-brand-bgLight text-sm text-stone-800 rounded-xl px-4 py-3 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-brand-accent/5 focus:border-brand-accent transition-all placeholder:text-stone-300">
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-2">Secret Passphrase</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••••••"
                           class="w-full bg-brand-bgLight text-sm text-stone-800 rounded-xl px-4 py-3 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-brand-accent/5 focus:border-brand-accent transition-all placeholder:text-stone-300">
                </div>

                <div class="pt-2 font-sans text-xs font-semibold uppercase tracking-wider">
                    <button type="submit" name="login-submit"
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 border border-transparent rounded-xl text-white bg-brand-accent hover:bg-brand-accentHover shadow-xs transition-all duration-200 active:scale-[0.99]">
                        Authenticate and Enter
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="py-6 text-center text-[11px] font-sans font-medium tracking-widest text-stone-400 uppercase">
        <p>&copy; 2026 Joseph Nnamdi Mosanya &mdash; Editorial Desk Environment.</p>
    </footer>

</body>
</html>