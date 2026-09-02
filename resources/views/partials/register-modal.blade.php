{{-- ============ MODAL REGISTER GLOBAL ============ --}}
{{-- Dipanggil lewat openRegisterModal() dari tombol "Register" di navbar (atau
     dari link "Daftar di sini" di modal login). Muncul instan, tidak pindah halaman. --}}
<div id="register-modal-backdrop" class="hidden fixed inset-0 z-[300] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="register-modal-card" class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative my-8">

        <button type="button" onclick="closeRegisterModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 transition flex items-center justify-center text-gray-500 text-sm z-10">
            ✕
        </button>

        <div class="p-8 sm:p-10">
            <div class="flex items-center gap-2.5 font-display font-extrabold text-lg mb-6">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-brand-yellow text-navy-900 text-base">🏠</span>
                <span class="lowercase tracking-tight text-navy-900">guest house</span>
            </div>

            <h2 class="font-display text-xl font-bold text-navy-900 mb-1">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500 mb-6">
                Sudah punya akun?
                <button type="button" onclick="switchToLoginModal()" class="text-brand-blue font-semibold hover:underline">Masuk di sini</button>
            </p>

            <div id="modal-register-error" class="hidden bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-3.5 mb-5"></div>

            <form id="modal-register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Pilihan role --}}
                <div class="grid grid-cols-2 gap-3">
                    <label class="border-2 rounded-xl p-3.5 text-sm text-center cursor-pointer transition has-[:checked]:border-navy-900 has-[:checked]:bg-navy-900 has-[:checked]:text-white border-gray-200">
                        <input type="radio" name="role" value="customer" class="hidden" checked>
                        <div class="font-semibold">🧳 Customer</div>
                        <div class="text-xs opacity-70 mt-0.5">Booking properti</div>
                    </label>
                    <label class="border-2 rounded-xl p-3.5 text-sm text-center cursor-pointer transition has-[:checked]:border-navy-900 has-[:checked]:bg-navy-900 has-[:checked]:text-white border-gray-200">
                        <input type="radio" name="role" value="mitra" class="hidden">
                        <div class="font-semibold">🏠 Mitra</div>
                        <div class="text-xs opacity-70 mt-0.5">Sewakan properti</div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required
                           placeholder="Nama Anda"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" required
                           placeholder="nama@email.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">No. HP</label>
                    <input type="text" name="phone"
                           placeholder="08xx-xxxx-xxxx"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-navy-900 hover:bg-navy-800 transition text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-navy-900/20">
                    Daftar Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openRegisterModal(role) {
        closeLoginModal();
        document.getElementById('register-modal-backdrop').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        if (role) {
            const radio = document.querySelector('#modal-register-form input[name="role"][value="' + role + '"]');
            if (radio) radio.checked = true;
        }
    }
    function closeRegisterModal() {
        document.getElementById('register-modal-backdrop').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function switchToLoginModal() {
        closeRegisterModal();
        openLoginModal();
    }
    function switchToRegisterModal() {
        openRegisterModal();
    }

    // Klik di luar kartu -> tutup modal
    document.getElementById('register-modal-backdrop').addEventListener('click', function (e) {
        if (e.target === this) closeRegisterModal();
    });
    // Escape -> tutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeRegisterModal();
    });

    // Kalau register GAGAL (validasi server-side), otomatis buka modal ini lagi
    // + tampilkan errornya, dan isi ulang field yang sudah bener biar user tidak
    // perlu ngetik ulang dari nol.
    @if ($errors->any() && old('name') !== null)
        openRegisterModal();
        document.getElementById('modal-register-error').classList.remove('hidden');
        document.getElementById('modal-register-error').innerHTML = @json($errors->first());
        document.querySelector('#modal-register-form input[name="name"]').value = @json(old('name'));
        document.querySelector('#modal-register-form input[name="email"]').value = @json(old('email'));
        document.querySelector('#modal-register-form input[name="phone"]').value = @json(old('phone'));
        @if (old('role'))
            document.querySelector('#modal-register-form input[name="role"][value="{{ old('role') }}"]').checked = true;
        @endif
    @endif
</script>