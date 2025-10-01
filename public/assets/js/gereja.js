// ==================== GLOBAL VARIABLES AND CONFIG ====================
const App = {
    // Configuration
    config: {
        debug: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1',
        apiTimeout: 30000,
        notificationDuration: 3000
    },
    // Current page context
    currentPage: window.location.pathname,
    currentRoute: window.location.pathname.split('/')[1] || 'dashboard',
    // DOM Elements Cache
    elements: {},
    // Initialize app
    init() {
        this.log('App initialized');
        this.loadRequiredAssets();
        this.log('Application initialized successfully');
    },
    // Logging function
    log(message, type = 'info') {
        if (this.config.debug) {
            console.log(`[${type.toUpperCase()}]`, message);
        }
    },
    // Load required assets
    loadRequiredAssets() {
        // Load CSS
        this.loadStylesheet('/assets/css/perfect-scrollbar.css');
        // Load JS based on DOM elements
        if (document.querySelector("nav [navbar-trigger]")) {
            this.loadJS('/assets/js/navbar-collapse.js', true);
        }
        if (document.querySelector("[data-target='tooltip']")) {
            this.loadJS('/assets/js/tooltips.js', true);
            this.loadStylesheet('/assets/css/tooltips.css');
        }
        if (document.querySelector("[nav-pills]")) {
            this.loadJS('/assets/js/nav-pills.js', true);
        }
        if (document.querySelector("[dropdown-trigger]")) {
            this.loadJS('/assets/js/dropdown.js', true);
        }
        if (document.querySelector("[fixed-plugin]")) {
            this.loadJS('/assets/js/fixed-plugin.js', true);
        }
        if (document.querySelector("[navbar-main]")) {
            this.loadJS('/assets/js/sidenav-burger.js', true);
            this.loadJS('/assets/js/navbar-sticky.js', true);
        }
        if (document.querySelector("canvas")) {
            this.loadJS('/assets/js/chart-1.js', true);
            this.loadJS('/assets/js/chart-2.js', true);
        }
    },
    loadJS(FILE_URL, async = false) {
        const script = document.createElement("script");
        script.src = FILE_URL;
        script.type = "text/javascript";
        if (async) script.async = true;
        document.head.appendChild(script);
    },
    loadStylesheet(FILE_URL) {
        const link = document.createElement("link");
        link.href = FILE_URL;
        link.type = "text/css";
        link.rel = "stylesheet";
        document.head.appendChild(link);
    }
};

// ==================== UTILITY FUNCTIONS ====================
const Utils = {
    // Show notification
    showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(notif => notif.remove());
        // Create new notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            type === 'info' ? 'bg-blue-500 text-white' :
            'bg-gray-500 text-white'
        } notification`;
        notification.textContent = message;
        document.body.appendChild(notification);
        // Auto remove
        setTimeout(() => {
            notification.style.transition = 'opacity 0.3s ease';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, App.config.notificationDuration);
    },
    // Format currency
    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
    },
    // Format date
    formatDate(date, format = 'short') {
        const options = format === 'short'
            ? { day: 'numeric', month: 'short', year: 'numeric' }
            : { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(date).toLocaleDateString('id-ID', options);
    },
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    // Get CSRF token
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : null;
    },
    // Confirm dialog
    confirm(message, callback) {
        if (confirm(message)) {
            callback();
        }
    }
};

// ==================== AJAX HANDLER ====================
const Ajax = {
    // Generic AJAX request
    async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        // Add CSRF token for POST/PUT/DELETE
        if (['POST', 'PUT', 'DELETE'].includes(options.method?.toUpperCase())) {
            const csrfToken = Utils.getCSRFToken();
            if (csrfToken) {
                defaultOptions.headers['X-CSRF-TOKEN'] = csrfToken;
            }
        }
        const mergedOptions = { ...defaultOptions, ...options };
        try {
            const response = await fetch(url, mergedOptions);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            App.log('AJAX request failed:', 'error');
            App.log(error, 'error');
            throw error;
        }
    },
    // POST form data
    async postForm(url, form) {
        const formData = new FormData(form);
        return this.request(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }
};

// ==================== MODAL MANAGEMENT ====================
const ModalManagement = {
    // Toggle category modal
    toggleCategoryModal() {
        const modal = document.getElementById('categoryModal');
        if (!modal) {
            App.log('Modal element not found!', 'error');
            Utils.showNotification('Modal element tidak ditemukan!', 'error');
            return;
        }

        if (modal.style.display === 'none' || modal.style.display === '') {
            this.showCategoryModal();
        } else {
            this.closeCategoryModal();
        }
    },

    // Show category modal
    showCategoryModal() {
        const modal = document.getElementById('categoryModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.style.zIndex = '99999';
            document.body.style.overflow = 'hidden';

            // Focus on input
            setTimeout(() => {
                const input = document.getElementById('kategori_nama');
                if (input) input.focus();
            }, 100);
        }
    },

    // Close category modal
    closeCategoryModal() {
        const modal = document.getElementById('categoryModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';

            // Reset form
            const form = document.getElementById('quickAddCategory');
            if (form) form.reset();

            // Clear errors
            this.clearErrorStates();
        }
    },

    // Clear error states in modal
    clearErrorStates() {
        const inputs = document.querySelectorAll('#categoryModal input, #categoryModal textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        });
        const errorMessages = document.querySelectorAll('#categoryModal .error-message');
        errorMessages.forEach(msg => {
            msg.style.display = 'none';
            msg.textContent = '';
        });
    },

    // Submit new category
    async submitNewCategory() {
        try {
            console.log('submitNewCategory called');

            const form = document.getElementById('quickAddCategory');
            const namaInput = document.getElementById('kategori_nama');
            const errorDiv = document.getElementById('nama-error');

            console.log('Form elements:', { form, namaInput, errorDiv });

            if (!form || !namaInput || !errorDiv) {
                throw new Error('Form elements tidak ditemukan');
            }

            const submitBtn = document.getElementById('submitCategoryBtn');
            console.log('Submit button:', submitBtn);

            if (!submitBtn) {
                throw new Error('Submit button tidak ditemukan');
            }

            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Clear previous errors
            errorDiv.style.display = 'none';
            namaInput.classList.remove('border-red-500');

            // Validate
            if (!namaInput.value.trim()) {
                namaInput.classList.add('border-red-500');
                errorDiv.textContent = 'Nama kategori harus diisi';
                errorDiv.style.display = 'block';
                namaInput.focus();
                return;
            }

            // Show loading
            if (btnText && btnLoading) {
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline';
            }
            submitBtn.disabled = true;

            const formData = new FormData(form);
            console.log('Form data:', Object.fromEntries(formData));

            // Gunakan URL admin untuk kategori
            const response = await fetch('/admin/kategori', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': Utils.getCSRFToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response content-type:', response.headers.get('content-type'));

            // Cek apakah response adalah JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Jika bukan JSON, ambil teks response untuk debugging
                const text = await response.text();
                console.error('Server returned non-JSON response:', text.substring(0, 500));

                // Cek apakah response adalah redirect ke login
                if (text.includes('login') || response.redirected) {
                    throw new Error('Sesi Anda telah berakhir. Silakan login kembali.');
                }

                throw new Error('Server mengembalikan respons tidak valid. Silakan coba lagi.');
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                // Add to select dropdown
                const select = document.getElementById('kategori_select');
                console.log('Select element:', select);

                if (select) {
                    const option = new Option(data.kategori.nama, data.kategori.id, true, true);
                    select.add(option);
                    console.log('Option added to select');
                }

                Utils.showNotification(data.message, 'success');
                this.closeCategoryModal();
            } else {
                throw new Error(data.message || 'Gagal menambahkan kategori');
            }
        } catch (error) {
            console.error('Submit category error:', error);
            App.log('Submit category error:', 'error');
            Utils.showNotification(error.message || 'Terjadi kesalahan saat menambahkan kategori', 'error');
        } finally {
            // Reset button
            const submitBtn = document.getElementById('submitCategoryBtn');
            if (submitBtn) {
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoading = submitBtn.querySelector('.btn-loading');
                if (btnText && btnLoading) {
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                }
                submitBtn.disabled = false;
            }
        }
    },

    // Initialize modal management
    init() {
        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            const categoryModal = document.getElementById('categoryModal');
            if (categoryModal && e.target === categoryModal) {
                this.closeCategoryModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const categoryModal = document.getElementById('categoryModal');
                if (categoryModal && categoryModal.style.display === 'flex') {
                    this.closeCategoryModal();
                }
            }
        });

        // Enter key to submit in modal
        const namaInput = document.getElementById('kategori_nama');
        if (namaInput) {
            namaInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.submitNewCategory();
                }
            });
        }
    }
};

// ==================== KAS FORM MANAGEMENT ====================
const KasFormManagement = {
    init() {
        // Hanya jalankan jika ini adalah halaman form kas
        if (!document.getElementById('jenisTransaksi')) {
            console.log('Bukan halaman form kas, melewati inisialisasi form kas');
            return;
        }

        console.log('JavaScript untuk form kas dimuat'); // Debug log

        const jenisSelect = document.getElementById('jenisTransaksi');
        const sumberField = document.getElementById('sumberField');
        const tujuanField = document.getElementById('tujuanField');
        const sumberInput = document.getElementById('sumberInput');
        const tujuanInput = document.getElementById('tujuanInput');
        const kasForm = document.getElementById('kasForm');
        const submitBtn = document.getElementById('submitBtn');

        // Debug log untuk memastikan elemen ditemukan
        console.log('jenisSelect:', jenisSelect);
        console.log('sumberField:', sumberField);
        console.log('tujuanField:', tujuanField);

        // Fungsi untuk menampilkan/menyembunyikan field berdasarkan jenis transaksi
        function toggleFields() {
            const selectedJenis = jenisSelect.value;
            console.log('Jenis transaksi dipilih:', selectedJenis); // Debug log

            if (selectedJenis === 'masuk') {
                // Tampilkan field sumber, sembunyikan field tujuan
                if (sumberField) sumberField.style.display = 'block';
                if (tujuanField) tujuanField.style.display = 'none';
                if (sumberInput) {
                    sumberInput.required = true;
                    setTimeout(() => sumberInput.focus(), 100);
                }
                if (tujuanInput) {
                    tujuanInput.required = false;
                    tujuanInput.value = '';
                }
                console.log('Menampilkan field sumber'); // Debug log
            } else if (selectedJenis === 'keluar') {
                // Tampilkan field tujuan, sembunyikan field sumber
                if (sumberField) sumberField.style.display = 'none';
                if (tujuanField) tujuanField.style.display = 'block';
                if (sumberInput) {
                    sumberInput.required = false;
                    sumberInput.value = '';
                }
                if (tujuanInput) {
                    tujuanInput.required = true;
                    setTimeout(() => tujuanInput.focus(), 100);
                }
                console.log('Menampilkan field tujuan'); // Debug log
            } else {
                // Sembunyikan kedua field jika belum memilih jenis
                if (sumberField) sumberField.style.display = 'none';
                if (tujuanField) tujuanField.style.display = 'none';
                if (sumberInput) {
                    sumberInput.required = false;
                    sumberInput.value = '';
                }
                if (tujuanInput) {
                    tujuanInput.required = false;
                    tujuanInput.value = '';
                }
                console.log('Menyembunyikan kedua field'); // Debug log
            }
        }

        // Pastikan event listener terpasang dengan benar
        if (jenisSelect) {
            jenisSelect.addEventListener('change', function() {
                console.log('Event change tertrigger'); // Debug log
                toggleFields();
            });

            // Panggil toggleFields saat halaman dimuat jika ada nilai terpilih
            if (jenisSelect.value) {
                toggleFields();
            }
        }

        // Form validation before submit
        if (kasForm) {
            kasForm.addEventListener('submit', function(e) {
                const selectedJenis = jenisSelect.value;

                if (selectedJenis === 'masuk') {
                    if (!sumberInput || !sumberInput.value.trim()) {
                        e.preventDefault();
                        alert('Sumber pemasukan harus diisi');
                        if (sumberInput) sumberInput.focus();
                        return false;
                    }

                    // Validate minimum length
                    if (sumberInput.value.trim().length < 3) {
                        e.preventDefault();
                        alert('Sumber pemasukan terlalu pendek, minimal 3 karakter');
                        if (sumberInput) sumberInput.focus();
                        return false;
                    }
                }

                if (selectedJenis === 'keluar') {
                    if (!tujuanInput || !tujuanInput.value.trim()) {
                        e.preventDefault();
                        alert('Tujuan pengeluaran harus diisi');
                        if (tujuanInput) tujuanInput.focus();
                        return false;
                    }

                    // Validate minimum length
                    if (tujuanInput.value.trim().length < 3) {
                        e.preventDefault();
                        alert('Tujuan pengeluaran terlalu pendek, minimal 3 karakter');
                        if (tujuanInput) tujuanInput.focus();
                        return false;
                    }
                }

                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                }
            });
        }

        // Auto-resize textarea
        const keteranganTextarea = document.querySelector('textarea[name="keterangan"]');
        if (keteranganTextarea) {
            keteranganTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    },

    // Get saldo kas info - UPDATED VERSION
    getSaldoInfo() {
        const saldoElement = document.getElementById('saldo-kas');
        if (!saldoElement) {
            console.log('Element saldo-kas tidak ditemukan');
            return;
        }

        console.log('Mengambil data saldo kas...');

        // Gunakan route tunggal yang sudah diperbaiki
        fetch('/kas/get-saldo')
            .then(response => {
                console.log('Response status:', response.status);

                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Response bukan JSON, kemungkinan redirect ke login');
                    }
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            })
            .then(data => {
                if (data.success) {
                    // Format saldo dengan pemisah ribuan
                    const formattedSaldo = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(data.saldo);

                    saldoElement.textContent = formattedSaldo;

                    // Tambahkan tooltip untuk memberikan informasi lebih detail
                    saldoElement.title = 'Saldo kas saat ini: ' + formattedSaldo;

                    // Tambahkan warna berdasarkan jumlah saldo
                    if (data.saldo >= 8000000) {
                        saldoElement.className = 'font-semibold text-green-600';
                    } else if (data.saldo >= 4000000) {
                        saldoElement.className = 'font-semibold text-yellow-600';
                    } else {
                        saldoElement.className = 'font-semibold text-red-600';
                    }
                } else {
                    throw new Error(data.message || 'Gagal mengambil data saldo');
                }
            })
            .catch(error => {
                console.error('Error fetching saldo data:', error);
                saldoElement.textContent = 'Tidak tersedia';
                saldoElement.classList.add('text-red-500');

                // Jika error karena unauthorized, jangan tampilkan error ke user
                if (error.message.includes('403') || error.message.includes('redirect') || error.message.includes('login')) {
                    saldoElement.textContent = 'Akses terbatas';
                    saldoElement.className = 'font-semibold text-gray-500';
                    saldoElement.title = 'Anda tidak memiliki akses untuk melihat saldo kas';
                }
            });
    }
};

// ==================== AUDIT MANAGEMENT ====================
const AuditManagement = {
    // Open modal untuk menyelesaikan jadwal audit
    openSelesaikanModal(id, title) {
        console.log('Opening modal for jadwal audit ID:', id, 'title:', title);

        // Hapus semua modal yang mungkin masih terbuka
        this.closeAllModals();

        // Set judul dan action form
        const titleElement = document.getElementById('jadwal-title');
        if (titleElement) titleElement.textContent = title;

        const form = document.getElementById('selesaikanForm');
        if (form) {
            // Buat URL dinamis tanpa menggunakan Blade template
            form.action = `/pengurus/audit/selesaikan-jadwal/${id}`;
        }

        // Tampilkan modal
        const modal = document.getElementById('selesaikanModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            modal.style.zIndex = '99999';

            // Pastikan backdrop juga memiliki z-index yang tepat
            const backdrop = modal.querySelector('.fixed.inset-0.transition-opacity');
            if (backdrop) {
                backdrop.style.zIndex = '99998';
            }

            // Fokus ke modal
            setTimeout(() => {
                const firstInput = modal.querySelector('input, select, textarea');
                if (firstInput) firstInput.focus();
            }, 100);
        }
    },

    // Close modal untuk menyelesaikan jadwal audit
    closeSelesaikanModal() {
        const modal = document.getElementById('selesaikanModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';

            // Reset form
            const form = document.getElementById('selesaikanForm');
            if (form) {
                form.reset();
            }
        }
    },

    // Close delete modal
    closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    },

    // Close all modals
    closeAllModals() {
        this.closeSelesaikanModal();
        this.closeDeleteModal();
        ModalManagement.closeCategoryModal();
    },

    // Handle form submission
    handleSelesaikanForm() {
        const form = document.getElementById('selesaikanForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': Utils.getCSRFToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Utils.showNotification(data.message, 'success');
                    this.closeSelesaikanModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    Utils.showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Utils.showNotification('Terjadi kesalahan saat memproses permintaan', 'error');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    },

    // Initialize audit functionality
    init() {
        this.handleSelesaikanForm();

        // Close modals when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.id === 'selesaikanModal' || e.target.id === 'deleteModal') {
                this.closeAllModals();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }
};

// ==================== USER MANAGEMENT ====================
const UserManagement = {
    // Delete user
    async deleteUser(userId, userName) {
        Utils.confirm(
            `⚠️ Yakin ingin menghapus user "${userName}"?\n\nUser yang dihapus tidak akan dapat mengakses sistem lagi.`,
            async () => {
                try {
                    const form = document.getElementById(`deleteForm-${userId}`);
                    if (!form) {
                        throw new Error('Form tidak ditemukan');
                    }
                    const button = form.querySelector('button[type="button"]');
                    if (!button) {
                        throw new Error('Tombol tidak ditemukan');
                    }
                    // Show loading state
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    const response = await Ajax.postForm(form.action, form);
                    if (response.success) {
                        // Remove row with animation
                        const row = form.closest('tr');
                        if (row) {
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                Utils.showNotification(response.message, 'success');
                                // Check if table is empty
                                const tbody = document.querySelector('tbody');
                                if (tbody && tbody.children.length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        throw new Error(response.message || 'Gagal menghapus user');
                    }
                } catch (error) {
                    App.log('Delete user error:', 'error');
                    Utils.showNotification(error.message || 'Terjadi kesalahan saat menghapus user', 'error');
                    // Reset button state
                    const form = document.getElementById(`deleteForm-${userId}`);
                    const button = form?.querySelector('button[type="button"]');
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-trash"></i>';
                    }
                }
            }
        );
    },
    // Toggle user active status
    async toggleUserActive(userId, userName) {
        const action = document.querySelector(`#deleteForm-${userId}`)?.closest('tr')?.querySelector('.fa-user-check')
            ? 'mengaktifkan'
            : 'menonaktifkan';
        Utils.confirm(
            `⚠️ Yakin ingin ${action} user "${userName}"?`,
            async () => {
                try {
                    const response = await Ajax.request(`/admin/users/${userId}/toggle-active`, {
                        method: 'POST'
                    });
                    if (response.success) {
                        Utils.showNotification(response.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        throw new Error(response.message || 'Gagal mengubah status user');
                    }
                } catch (error) {
                    App.log('Toggle user active error:', 'error');
                    Utils.showNotification(error.message || 'Terjadi kesalahan saat mengubah status user', 'error');
                }
            }
        );
    },
    // Reset user password
    async resetUserPassword(userId, userName) {
        const password = prompt(`Masukkan password baru untuk user "${userName}":`);
        if (!password || password.length < 8) return;
        const confirmPassword = prompt('Konfirmasi password baru:');
        if (password !== confirmPassword) {
            Utils.showNotification('Password dan konfirmasi tidak cocok.', 'error');
            return;
        }
        try {
            const formData = new FormData();
            formData.append('password', password);
            formData.append('password_confirmation', confirmPassword);
            const response = await Ajax.request(`/admin/users/${userId}/reset-password`, {
                method: 'POST',
                body: formData
            });
            Utils.showNotification(response.message || 'Password berhasil direset!', 'success');
        } catch (error) {
            App.log('Reset password error:', 'error');
            Utils.showNotification(error.message || 'Terjadi kesalahan saat mereset password', 'error');
        }
    }
};

// ==================== INVENTORY MANAGEMENT ====================
const InventoryManagement = {
    // Delete barang
    async deleteBarang(barangId, barangName) {
        Utils.confirm(
            `⚠️ Yakin ingin mengarsipkan barang "${barangName}"?\n\nBarang yang diarsipkan tidak akan muncul di daftar utama.`,
            async () => {
                try {
                    const form = document.getElementById(`deleteForm-${barangId}`);
                    if (!form) {
                        throw new Error('Form tidak ditemukan');
                    }
                    const button = form.querySelector('button[type="button"]');
                    if (!button) {
                        throw new Error('Tombol tidak ditemukan');
                    }
                    // Show loading state
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    const response = await Ajax.postForm(form.action, form);
                    if (response.success) {
                        // Remove row with animation
                        const row = form.closest('tr');
                        if (row) {
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                Utils.showNotification(response.message, 'success');
                                const tbody = document.querySelector('tbody');
                                if (tbody && tbody.children.length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        throw new Error(response.message || 'Gagal mengarsipkan barang');
                    }
                } catch (error) {
                    App.log('Delete barang error:', 'error');
                    Utils.showNotification(error.message || 'Terjadi kesalahan saat mengarsipkan barang', 'error');
                    // Reset button state
                    const form = document.getElementById(`deleteForm-${barangId}`);
                    const button = form?.querySelector('button[type="button"]');
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-trash"></i>';
                    }
                }
            }
        );
    },
    // Process barang masuk
    async processBarangMasuk(formId) {
        try {
            const form = document.getElementById(formId);
            if (!form) {
                throw new Error('Form tidak ditemukan');
            }
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
            const response = await Ajax.postForm(form.action, form);
            if (response.success) {
                Utils.showNotification(response.message, 'success');
                // Update stock display if exists
                const stockElement = document.getElementById(`stock-${form.barang_id.value}`);
                if (stockElement) {
                    stockElement.textContent = response.new_stock;
                }
            } else {
                throw new Error(response.message || 'Gagal mencatat barang masuk');
            }
        } catch (error) {
            App.log('Barang masuk error:', 'error');
            Utils.showNotification(error.message || 'Terjadi kesalahan saat mencatat barang masuk', 'error');
            // Reset button state
            const form = document.getElementById(formId);
            const button = form?.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = false;
                button.innerHTML = 'Simpan';
            }
        }
    },
    // Process barang keluar
    async processBarangKeluar(formId) {
        try {
            const form = document.getElementById(formId);
            if (!form) {
                throw new Error('Form tidak ditemukan');
            }
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
            const response = await Ajax.postForm(form.action, form);
            if (response.success) {
                Utils.showNotification(response.message, 'success');
                // Update stock display if exists
                const stockElement = document.getElementById(`stock-${form.barang_id.value}`);
                if (stockElement) {
                    stockElement.textContent = response.new_stock;
                }
            } else {
                throw new Error(response.message || 'Gagal mencatat barang keluar');
            }
        } catch (error) {
            App.log('Barang keluar error:', 'error');
            Utils.showNotification(error.message || 'Terjadi kesalahan saat mencatat barang keluar', 'error');
            // Reset button state
            const form = document.getElementById(formId);
            const button = form?.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = false;
                button.innerHTML = 'Simpan';
            }
        }
    }
};

// ==================== FORM MANAGEMENT ====================
const FormManagement = {
    // Initialize search and filter auto-submit
    initializeSearchAndFilter() {
        // Search form auto-submit on typing with delay
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', Utils.debounce((e) => {
                const form = e.target.closest('form');
                if (form) form.submit();
            }, 500));
        }
        // Filter change auto-submit untuk role (halaman pengguna)
        const roleFilter = document.getElementById('roleFilter');
        if (roleFilter) {
            roleFilter.addEventListener('change', (e) => {
                const form = e.target.closest('form');
                if (form) form.submit();
            });
        }
        // Filter change auto-submit untuk kategori (halaman inventori)
        const kategoriFilter = document.getElementById('kategoriFilter');
        if (kategoriFilter) {
            kategoriFilter.addEventListener('change', (e) => {
                const form = e.target.closest('form');
                if (form) form.submit();
            });
        }
        // Filter change auto-submit untuk stok status (halaman inventori)
        const stokStatusFilter = document.getElementById('stokStatusFilter');
        if (stokStatusFilter) {
            stokStatusFilter.addEventListener('change', (e) => {
                const form = e.target.closest('form');
                if (form) form.submit();
            });
        }
    }
};

// ==================== EVENT LISTENERS ====================
const EventListeners = {
    // Initialize all event listeners
    init() {
        this.setupGlobalListeners();
        this.setupPageSpecificListeners();
    },
    // Setup global event listeners
    setupGlobalListeners() {
        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('categoryModal');
            if (modal && e.target === modal) {
                ModalManagement.closeCategoryModal();
            }
        });
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('categoryModal');
                if (modal && modal.style.display === 'flex') {
                    ModalManagement.closeCategoryModal();
                }
            }
        });
        // Enter key to submit in modal
        const namaInput = document.getElementById('kategori_nama');
        if (namaInput) {
            namaInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    ModalManagement.submitNewCategory();
                }
            });
        }
    },
    // Setup page-specific event listeners using event delegation
    setupPageSpecificListeners() {
        // Delete button event delegation
        document.addEventListener('click', (e) => {
            const deleteButton = e.target.closest('button[onclick*="confirmDelete"]');
            if (deleteButton) {
                e.preventDefault();
                const onclickAttr = deleteButton.getAttribute('onclick');
                const match = onclickAttr.match(/confirmDelete\((\d+),\s*'([^']+)'\)/);
                if (match) {
                    const id = match[1];
                    const name = match[2];
                    // Determine function based on current page
                    const currentPage = App.currentRoute;
                    if (currentPage === 'admin') {
                        UserManagement.deleteUser(id, name);
                    } else if (currentPage === 'pengurus') {
                        InventoryManagement.deleteBarang(id, name);
                    }
                }
            }
        });
        // Form submission event delegation
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.id && form.id.includes('barang-masuk-form')) {
                e.preventDefault();
                InventoryManagement.processBarangMasuk(form.id);
            } else if (form.id && form.id.includes('barang-keluar-form')) {
                e.preventDefault();
                InventoryManagement.processBarangKeluar(form.id);
            }
        });
    }
};

// ==================== SIDEBAR FUNCTIONALITY ====================
const SidebarManagement = {
    init() {
        var sidenav = document.querySelector("aside");
        var sidenav_trigger = document.querySelector("[sidenav-trigger]");
        var sidenav_close_button = document.querySelector("[sidenav-close]");
        if (!sidenav || !sidenav_trigger) {
            console.error('Sidenav elements not found');
            return;
        }
        var burger = sidenav_trigger.firstElementChild;
        if (!burger) {
            console.error('Burger element not found');
            return;
        }
        var top_bread = burger.firstElementChild;
        var bottom_bread = burger.lastElementChild;
        sidenav_trigger.addEventListener("click", function (e) {
            e.preventDefault();
            if (sidenav_close_button) sidenav_close_button.classList.toggle("hidden");
            sidenav.classList.toggle("translate-x-0");
            sidenav.classList.toggle("shadow-soft-xl");
            if (top_bread && bottom_bread) {
                top_bread.classList.toggle("translate-x-[5px]");
                bottom_bread.classList.toggle("translate-x-[5px]");
            }
        });
        if (sidenav_close_button) {
            sidenav_close_button.addEventListener("click", function () {
                sidenav_trigger.click();
            });
        }
        // Close sidenav when clicking outside
        window.addEventListener("click", function (e) {
            if (!sidenav.contains(e.target) && !sidenav_trigger.contains(e.target)) {
                if (sidenav.classList.contains("translate-x-0")) {
                    sidenav_trigger.click();
                }
            }
        });
    }
};

// ==================== PASSWORD RESET MODAL ====================
const PasswordResetManagement = {
    showResetPasswordModal(userId, userName) {
        const userIdElement = document.getElementById('resetUserId');
        const userNameElement = document.getElementById('resetUserName');
        const modal = document.getElementById('resetPasswordModal');

        if (userIdElement) userIdElement.value = userId;
        if (userNameElement) userNameElement.textContent = userName;
        if (modal) {
            modal.classList.remove('hidden');
        }
    },

    closeResetPasswordModal() {
        const modal = document.getElementById('resetPasswordModal');
        const form = document.getElementById('resetPasswordForm');

        if (modal) {
            modal.classList.add('hidden');
        }
        if (form) {
            form.reset();
        }
    },

    init() {
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const userId = document.getElementById('resetUserId').value;
                const formData = new FormData(this);
                fetch('/admin/users/' + userId + '/reset-password', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Utils.showNotification(data.message, 'success');
                        PasswordResetManagement.closeResetPasswordModal();
                    } else {
                        Utils.showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Utils.showNotification('Terjadi kesalahan saat mereset password', 'error');
                });
            });
        }
    }
};

// ==================== GLOBAL FUNCTIONS ====================
// Make functions globally accessible
window.toggleCategoryModal = () => ModalManagement.toggleCategoryModal();
window.closeCategoryModal = () => ModalManagement.closeCategoryModal();
window.submitNewCategory = () => ModalManagement.submitNewCategory();
window.openSelesaikanModal = (id, title) => AuditManagement.openSelesaikanModal(id, title);
window.closeSelesaikanModal = () => AuditManagement.closeSelesaikanModal();
window.closeDeleteModal = () => AuditManagement.closeDeleteModal();
window.confirmDelete = (id, name) => {
    const nameElement = document.getElementById('item-name');
    const modal = document.getElementById('deleteModal');

    if (nameElement) nameElement.textContent = name;
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        modal.style.zIndex = '99999';
    }
    window.currentDeleteId = id;
};
window.confirmDeleteAction = () => {
    if (window.currentDeleteId) {
        const form = document.getElementById(`deleteForm-${window.currentDeleteId}`);
        if (form) form.submit();
    }
};
window.showResetPasswordModal = (userId, userName) => PasswordResetManagement.showResetPasswordModal(userId, userName);
window.closeResetPasswordModal = () => PasswordResetManagement.closeResetPasswordModal();

// ==================== BARANG MASUK MANAGEMENT ====================
const BarangMasukManagement = {
    init() {
        // Hanya jalankan jika ini adalah halaman form barang masuk
        if (!document.getElementById('kategori_id')) {
            return;
        }

        const kategoriSelect = document.getElementById('kategori_id');
        const barangSelect = document.getElementById('barang_id');
        const barangDetails = document.getElementById('barangDetails');
        const barangPlaceholder = document.getElementById('barangPlaceholder');

        let allBarangs = [];

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                const kategoriId = this.value;

                // Reset dropdown barang
                barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                barangSelect.disabled = true;

                // Hide barang details
                if (barangDetails) barangDetails.classList.add('hidden');
                if (barangPlaceholder) barangPlaceholder.classList.remove('hidden');

                if (kategoriId) {
                    // Fetch barang by kategori via AJAX
                    fetch(`/pengurus/barang/masuk/get-barang-by-kategori/${kategoriId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                allBarangs = data.data;

                                // Populate dropdown barang
                                data.data.forEach(barang => {
                                    const option = document.createElement('option');
                                    option.value = barang.id;
                                    option.textContent = `${barang.nama} (${barang.kode_barang})`;
                                    barangSelect.appendChild(option);
                                });

                                // Enable dropdown
                                barangSelect.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        }

        if (barangSelect) {
            barangSelect.addEventListener('change', function() {
                const barangId = this.value;

                if (barangId) {
                    // Find selected barang from allBarangs array
                    const barang = allBarangs.find(b => b.id == barangId);

                    if (barang) {
                        // Update image
                        const imageContainer = document.getElementById('barangImageContainer');
                        if (imageContainer) {
                            if (barang.gambar) {
                                imageContainer.innerHTML = `<img src="/storage/barang/${barang.gambar}" class="h-12 w-12 rounded-xl object-cover border border-gray-200">`;
                            } else {
                                imageContainer.innerHTML = `<div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600"><i class="ni ni-box-2 text-lg text-white"></i></div>`;
                            }
                        }

                        // Update other details
                        const barangNama = document.getElementById('barangNama');
                        const barangKode = document.getElementById('barangKode');
                        const barangKategori = document.getElementById('barangKategori');
                        const barangSatuan = document.getElementById('barangSatuan');
                        const barangStok = document.getElementById('barangStok');
                        const barangHarga = document.getElementById('barangHarga');

                        if (barangNama) barangNama.textContent = barang.nama;
                        if (barangKode) barangKode.textContent = barang.kode_barang;
                        if (barangKategori) barangKategori.textContent = barang.kategori_nama;
                        if (barangSatuan) barangSatuan.textContent = barang.satuan;
                        if (barangStok) barangStok.textContent = barang.stok + ' ' + barang.satuan;
                        if (barangHarga) barangHarga.textContent = 'Rp ' + parseInt(barang.harga).toLocaleString('id-ID');

                        // Show details, hide placeholder
                        if (barangDetails) barangDetails.classList.remove('hidden');
                        if (barangPlaceholder) barangPlaceholder.classList.add('hidden');
                    }
                } else {
                    // Show placeholder, hide details
                    if (barangDetails) barangDetails.classList.add('hidden');
                    if (barangPlaceholder) barangPlaceholder.classList.remove('hidden');
                }
            });
        }
    }
};

// ==================== INITIALIZATION ====================
// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    App.log('DOM loaded, initializing application...');

    // Initialize app
    App.init();

    // Initialize event listeners
    EventListeners.init();

    // Initialize form management
    FormManagement.initializeSearchAndFilter();

    // Initialize audit management
    AuditManagement.init();

    // Initialize modal management
    ModalManagement.init();

    // Initialize kas form management hanya jika elemen yang diperlukan ada
    if (document.getElementById('jenisTransaksi')) {
        KasFormManagement.init();
    }

    // Get saldo info jika elemen saldo-kas ada
    if (document.getElementById('saldo-kas')) {
        KasFormManagement.getSaldoInfo();
    }

    // Initialize barang masuk form management
    BarangMasukManagement.init();

    // Initialize sidebar management
    SidebarManagement.init();

    // Initialize password reset management
    PasswordResetManagement.init();

    App.log('Application initialized successfully');
});
