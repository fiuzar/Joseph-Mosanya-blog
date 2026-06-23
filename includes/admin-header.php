<nav class="bg-[#FAF9F6] border-b border-stone-200/60 sticky top-0 z-40 backdrop-blur-md bg-opacity-90">
    <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="flex items-center gap-12">
            <!-- Literary Brand Signature -->
            <a href="/admin" class="font-serif text-xl tracking-tight text-stone-900 group">
                Nnamdi Joseph Mosanya <span class="text-xs font-sans font-medium tracking-widest text-stone-400 uppercase ml-3 border-l border-stone-200 pl-3 group-hover:text-stone-600 transition-colors">Library Desk</span>
            </a>
            
            <!-- Navigation Action Anchors -->
            <div class="hidden sm:flex items-center space-x-2 text-xs font-sans font-semibold uppercase tracking-wider">
                <a href="/admin/" class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-2 rounded-lg">
                    Manage Collection
                </a>
                <a href="/admin/write.php" class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-2 rounded-lg">
                    Compose Entry
                </a>
                <a href="/admin/subscribers.php" class="text-stone-500 hover:text-stone-900 transition-colors px-3 py-2 rounded-lg">
                    Subscribers
                </a>
            </div>
        </div>

        <!-- Right Side Context (Optional: Quick Logout Anchor) -->
        <div class="text-xs font-sans font-semibold uppercase tracking-wider text-stone-400 hover:text-stone-900 transition-colors">
            <a href="/handlers/auth_handler.php?action=logout">Sign Out</a>
        </div>
    </div>
</nav>