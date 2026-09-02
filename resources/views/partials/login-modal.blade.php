{{-- ============ MODAL LOGIN GLOBAL ============ --}}
{{-- Dipanggil lewat openLoginModal() dari tombol "Log In" di navbar (atau di mana pun).
     Muncul instan di atas halaman yang sedang dibuka, tidak pindah halaman. --}}
<div id="login-modal-backdrop" class="hidden fixed inset-0 z-[300] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div id="login-modal-card" class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative overflow-hidden">

        <button type="button" onclick="closeLoginModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 transition flex items-center justify-center text-gray-500 text-sm z-10">
            ✕
        </button>

        <div class="p-8 sm:p-10">
            <div class="flex items-center gap-2.5 font-display font-extrabold text-lg mb-6">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-brand-yellow text-navy-900 text-base">🏠</span>
                <span class="lowercase tracking-tight text-navy-900">guest house</span>
            </div>

            <h2 class="font-display text-xl font-bold text-navy-900 mb-1">Masuk ke Akun</h2>
            <p class="text-sm text-gray-500 mb-6">
                Belum punya akun?
                <button type="button" onclick="switchToRegisterModal()" class="text-brand-blue font-semibold hover:underline">Daftar di sini</button>
            </p>

            <div id="modal-login-error" class="hidden bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-3.5 mb-5"></div>

            {{-- ====== Opsi login sosial (tampilan awal) ====== --}}
            {{-- CATATAN: Google/Apple/Facebook belum terhubung provider asli (perlu Laravel
                 Socialite). Klik tombol ini otomatis buka form email/password di bawah. --}}
            <div id="modal-social-panel" class="space-y-3">
                <button type="button" onclick="showModalEmailForm()"
                        class="w-full flex items-center justify-center gap-3 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-navy-900 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.7-6.1 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.5 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16 19 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.5 29.6 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.5 0 10.4-2.1 14.1-5.6l-6.5-5.5C29.6 34.7 26.9 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.6 5.1C9.6 39.6 16.3 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l6.5 5.5C39.8 37 44 31 44 24c0-1.3-.1-2.7-.4-3.5z"/></svg>
                    Lanjutkan dengan Google
                </button>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="showModalEmailForm()"
                            class="flex items-center justify-center gap-2 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-navy-900 hover:bg-gray-50 transition">
                        <span></span> Apple
                    </button>
                    <button type="button" onclick="showModalEmailForm()"
                            class="flex items-center justify-center gap-2 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-white bg-[#1877F2] hover:bg-[#166fe5] transition">
                        <span>f</span> Facebook
                    </button>
                </div>

                <button type="button" onclick="showModalEmailForm()"
                        class="w-full text-center text-sm font-semibold text-brand-blue hover:underline pt-1">
                    Metode lain
                </button>

                <p class="text-[11px] text-gray-400 leading-relaxed pt-2">
                    Dengan melanjutkan, kamu menyetujui
                    <a href="#" class="text-brand-blue hover:underline">Syarat &amp; Ketentuan</a> ini dan kamu sudah
                    diberi tahu mengenai <a href="#" class="text-brand-blue hover:underline">Pemberitahuan Privasi</a> kami.
                </p>
            </div>

            {{-- ====== Form email/password asli — muncul lewat "Metode lain" ====== --}}
            <form id="modal-email-form" method="POST" action="{{ route('login') }}" class="space-y-4 hidden">
                @csrf

                <button type="button" onclick="hideModalEmailForm()" class="flex items-center gap-1 text-xs text-gray-400 hover:text-navy-900 transition mb-1">
                    ‹ Kembali ke opsi login lain
                </button>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" required
                           placeholder="nama@email.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Password</label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue/30">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-navy-900 hover:bg-navy-800 transition text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-navy-900/20">
                    Masuk
                </button>
            </form>

            <button type="button" onclick="closeLoginModal()"
                    class="w-full text-center text-sm font-semibold text-navy-900 border border-gray-200 rounded-xl py-3 hover:bg-gray-50 transition mt-6">
                Lihat sebagai Tamu
            </button>
        </div>
    </div>
</div>

<script>
    function openLoginModal() {
        document.getElementById('login-modal-backdrop').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeLoginModal() {
        document.getElementById('login-modal-backdrop').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function showModalEmailForm() {
        document.getElementById('modal-social-panel').classList.add('hidden');
        document.getElementById('modal-email-form').classList.remove('hidden');
    }
    function hideModalEmailForm() {
        document.getElementById('modal-email-form').classList.add('hidden');
        document.getElementById('modal-social-panel').classList.remove('hidden');
    }

    // Klik di luar kartu (area backdrop gelap) -> tutup modal
    document.getElementById('login-modal-backdrop').addEventListener('click', function (e) {
        if (e.target === this) closeLoginModal();
    });
    // Tombol Escape -> tutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLoginModal();
    });

    // Kalau halaman ini di-load ulang karena validasi login GAGAL (server-side redirect
    // back dengan errors), otomatis buka modal lagi + tampilkan pesan errornya,
    // supaya user tidak perlu klik "Log In" ulang buat lihat pesan errornya.
    @if ($errors->any() && old('email') !== null)
        openLoginModal();
        showModalEmailForm();
        document.getElementById('modal-login-error').classList.remove('hidden');
        document.getElementById('modal-login-error').innerHTML = @json($errors->first());
        document.querySelector('#modal-email-form input[name="email"]').value = @json(old('email'));
    @endif
</script>