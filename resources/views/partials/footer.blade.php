<footer class="bg-navy-900 text-gray-300 mt-16 relative overflow-hidden">
    <div class="absolute inset-0 batik-texture pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 grid grid-cols-1 md:grid-cols-4 gap-10 text-sm">
        <div class="md:col-span-1">
            <div class="flex items-center gap-2 font-display font-extrabold text-white text-lg mb-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-yellow text-navy-900 text-base">🏠</span>
                NGINEP<span class="text-brand-yellow">.</span>
            </div>
            <p class="text-gray-400 leading-relaxed">
                Platform booking guest house dan kost harian terpercaya untuk pelancong di seluruh Indonesia.
            </p>
        </div>

        <div>
            <div class="font-display font-semibold text-white mb-3">Jelajah</div>
            <ul class="space-y-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-brand-yellow transition">Cari Properti</a></li>
                <li><a href="{{ route('articles.index') }}" class="hover:text-brand-yellow transition">Artikel & Tips</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-brand-yellow transition">Jadi Mitra</a></li>
            </ul>
        </div>

        <div>
            <div class="font-display font-semibold text-white mb-3">Untuk Mitra</div>
            <ul class="space-y-2 text-gray-400">
                <li><a href="{{ route('register') }}" class="hover:text-brand-yellow transition">Daftarkan Properti</a></li>
                <li><span>Skema Mandiri (komisi 15%)</span></li>
                <li><span>Skema Dikelola (komisi 45%, termasuk pajak)</span></li>
            </ul>
        </div>

        <div>
            <div class="font-display font-semibold text-white mb-3">Kontak</div>
            <ul class="space-y-2 text-gray-400">
                <li>📧 support@nginep.co.id</li>
                <li>💬 WhatsApp: 08xx-xxxx-xxxx</li>
                <li>💳 Pembayaran aman via QRIS</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
            <span>&copy; {{ date('Y') }} Nginep. All rights reserved.</span>
            <span>Dibuat dengan ❤️ di Indonesia</span>
        </div>
    </div>
</footer>
