{{-- ============ MODAL REGISTER GLOBAL ============ --}}
{{-- Dipanggil lewat openRegisterModal() dari tombol "Register" di navbar
     atau dari link "Daftar di sini" di modal login.
     Registrasi dari modal ini hanya untuk CUSTOMER.
     
     Pendaftaran MITRA dilakukan melalui menu Partnership
     dan mengikuti proses verifikasi Mitra. --}}

<div id="register-modal-backdrop"
     class="hidden fixed inset-0 z-[300] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">

    <div id="register-modal-card"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative my-8">

        {{-- Tombol tutup --}}
        <button type="button"
                onclick="closeRegisterModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 transition flex items-center justify-center text-gray-500 text-sm z-10">
            ✕
        </button>

        <div class="p-8 sm:p-10">

            {{-- Logo --}}
            <div class="flex items-center gap-2.5 font-display font-extrabold text-lg mb-6">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-brand-yellow text-navy-900 text-base">
                    🏠
                </span>

                <span class="lowercase tracking-tight text-navy-900">
                    guest house
                </span>
            </div>

            {{-- Judul --}}
            <h2 class="font-display text-xl font-bold text-navy-900 mb-1">
                Buat Akun Baru
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Sudah punya akun?
                <button type="button"
                        onclick="switchToLoginModal()"
                        class="text-brand-blue font-semibold hover:underline">
                    Masuk di sini
                </button>
            </p>

            {{-- Error --}}
            <div id="modal-register-error"
                 class="hidden bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-3.5 mb-5">
            </div>

            {{-- Form Register Customer --}}
            <form id="modal-register-form"
                  method="POST"
                  action="{{ route('register') }}"
                  class="space-y-4">

                @csrf

                {{-- 
                    Registrasi dari modal ini hanya untuk CUSTOMER.
                    Tidak ada pilihan Mitra di sini.

                    Pendaftaran Mitra tetap melalui:
                    Partnership → Daftar Menjadi Mitra →
                    Verifikasi identitas → Selfie →
                    Menunggu persetujuan Admin/Super Admin.
                --}}

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Nama Lengkap
                    </label>

                    <input type="text"
                           name="name"
                           required
                           placeholder="Nama Anda"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           required
                           placeholder="nama@email.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        No. HP
                    </label>

                    <input type="text"
                           name="phone"
                           placeholder="08xx-xxxx-xxxx"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                            Konfirmasi
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               required
                               placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>

                </div>

                {{-- Tombol daftar --}}
                <button type="submit"
                        class="w-full bg-navy-900 hover:bg-navy-800 transition text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-navy-900/20">
                    Daftar Sekarang
                </button>

            </form>
        </div>
    </div>
</div>

<script>
    function openRegisterModal() {
        closeLoginModal();

        document
            .getElementById('register-modal-backdrop')
            .classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    }

    function closeRegisterModal() {
        document
            .getElementById('register-modal-backdrop')
            .classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    }

    function switchToLoginModal() {
        closeRegisterModal();
        openLoginModal();
    }

    function switchToRegisterModal() {
        openRegisterModal();
    }

    // Klik di luar kartu → tutup modal
    document
        .getElementById('register-modal-backdrop')
        .addEventListener('click', function (e) {
            if (e.target === this) {
                closeRegisterModal();
            }
        });

    // Tombol Escape → tutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeRegisterModal();
        }
    });

    // Kalau register gagal karena validasi server-side,
    // otomatis buka modal kembali dan isi data sebelumnya.
    @if ($errors->any() && old('name') !== null)

        openRegisterModal();

        document
            .getElementById('modal-register-error')
            .classList.remove('hidden');

        document
            .getElementById('modal-register-error')
            .innerHTML = @json($errors->first());

        document.querySelector(
            '#modal-register-form input[name="name"]'
        ).value = @json(old('name'));

        document.querySelector(
            '#modal-register-form input[name="email"]'
        ).value = @json(old('email'));

        document.querySelector(
            '#modal-register-form input[name="phone"]'
        ).value = @json(old('phone'));

    @endif
</script>