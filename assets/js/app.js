// ============================================
// SPAREPART USK - APP.JS
// Aplikasi E-Commerce Statis dengan localStorage
// ============================================

const App = {
    // ============================================
    // INISIALISASI DATA DEFAULT
    // ============================================
    defaultProducts: [
        { id: 1, nama_produk: 'Oli Mesin 1L', kategori: 'Oli & Pelumas', harga: 85000, stok: 50, gambar: '', deskripsi: 'Oli mesin berkualitas tinggi untuk performa mesin optimal.' },
        { id: 2, nama_produk: 'Filter Udara', kategori: 'Filter', harga: 45000, stok: 30, gambar: '', deskripsi: 'Filter udara untuk menjaga kebersihan udara masuk ke mesin.' },
        { id: 3, nama_produk: 'Busi Iridium', kategori: 'Mesin', harga: 120000, stok: 25, gambar: '', deskripsi: 'Busi iridium dengan daya tahan lama dan pembakaran sempurna.' },
        { id: 4, nama_produk: 'Kampas Rem Depan', kategori: 'Rem', harga: 150000, stok: 20, gambar: '', deskripsi: 'Kampas rem depan dengan daya cengkeram kuat.' },
        { id: 5, nama_produk: 'Aki 35Ah', kategori: 'Kelistrikan', harga: 450000, stok: 15, gambar: '', deskripsi: 'Aki kering 35Ah untuk mobil dan motor.' },
        { id: 6, nama_produk: 'Ban Radial 185/65 R15', kategori: 'Ban', harga: 750000, stok: 10, gambar: '', deskripsi: 'Ban radial dengan grip yang baik di segala cuaca.' },
        { id: 7, nama_produk: 'Kabel Busi', kategori: 'Mesin', harga: 25000, stok: 40, gambar: '', deskripsi: 'Kabel busi berkualitas untuk pengapian yang stabil.' },
        { id: 8, nama_produk: 'Shockbreaker Depan', kategori: 'Suspensi', harga: 350000, stok: 12, gambar: '', deskripsi: 'Shockbreaker depan dengan peredaman nyaman.' },
        { id: 9, nama_produk: 'Lampu LED H4', kategori: 'Kelistrikan', harga: 180000, stok: 18, gambar: '', deskripsi: 'Lampu LED H4 dengan cahaya terang dan hemat energi.' },
        { id: 10, nama_produk: 'V-Belt', kategori: 'Mesin', harga: 55000, stok: 35, gambar: '', deskripsi: 'V-belt berkualitas untuk transmisi yang halus.' },
        { id: 11, nama_produk: 'Mineral Oil 1L', kategori: 'Oli & Pelumas', harga: 65000, stok: 45, gambar: '', deskripsi: 'Mineral oil untuk pelumasan optimal.' },
        { id: 12, nama_produk: 'Filter Oli', kategori: 'Filter', harga: 35000, stok: 40, gambar: '', deskripsi: 'Filter oli untuk menjaga kebersihan oli mesin.' },
        { id: 13, nama_produk: 'Kampas Rem Belakang', kategori: 'Rem', harga: 120000, stok: 22, gambar: '', deskripsi: 'Kampas rem belakang dengan daya tahan tinggi.' },
        { id: 14, nama_produk: 'Koil', kategori: 'Kelistrikan', harga: 280000, stok: 14, gambar: '', deskripsi: 'Koil pengapian untuk percikan api yang kuat.' },
        { id: 15, nama_produk: 'Starter Motor', kategori: 'Kelistrikan', harga: 320000, stok: 10, gambar: '', deskripsi: 'Starter motor untuk mesin hidup dengan mudah.' },
        { id: 16, nama_produk: 'Oli Gardan 1L', kategori: 'Oli & Pelumas', harga: 75000, stok: 30, gambar: '', deskripsi: 'Oli gardan untuk transmisi yang halus.' }
    ],

    defaultAdmin: {
        id: 1,
        nama: 'Admin',
        email: 'admin@sparepartusk.com',
        password: 'admin123',
        role: 'admin',
        no_hp: '08123456789',
        alamat: 'Jl. Merdeka No. 123, Banda Aceh'
    },

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    init() {
        this.initStorage();
    },

    initStorage() {
        if (!localStorage.getItem('suk_products')) {
            localStorage.setItem('suk_products', JSON.stringify(this.defaultProducts));
        }
        if (!localStorage.getItem('suk_users')) {
            localStorage.setItem('suk_users', JSON.stringify([this.defaultAdmin]));
        }
        if (!localStorage.getItem('suk_cart')) {
            localStorage.setItem('suk_cart', JSON.stringify([]));
        }
        if (!localStorage.getItem('suk_orders')) {
            localStorage.setItem('suk_orders', JSON.stringify([]));
        }
    },

    getProducts() {
        return JSON.parse(localStorage.getItem('suk_products') || '[]');
    },

    saveProducts(products) {
        localStorage.setItem('suk_products', JSON.stringify(products));
    },

    getUsers() {
        return JSON.parse(localStorage.getItem('suk_users') || '[]');
    },

    saveUsers(users) {
        localStorage.setItem('suk_users', JSON.stringify(users));
    },

    getCart() {
        return JSON.parse(localStorage.getItem('suk_cart') || '[]');
    },

    saveCart(cart) {
        localStorage.setItem('suk_cart', JSON.stringify(cart));
    },

    getOrders() {
        return JSON.parse(localStorage.getItem('suk_orders') || '[]');
    },

    saveOrders(orders) {
        localStorage.setItem('suk_orders', JSON.stringify(orders));
    },

    getCurrentUser() {
        const user = localStorage.getItem('suk_currentUser');
        return user ? JSON.parse(user) : null;
    },

    setCurrentUser(user) {
        localStorage.setItem('suk_currentUser', JSON.stringify(user));
    },

    logout() {
        localStorage.removeItem('suk_currentUser');
        localStorage.removeItem('suk_cart');
        window.location.href = 'index.html';
    },

    formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    },

    isLoggedIn() {
        return this.getCurrentUser() !== null;
    },

    isAdmin() {
        const user = this.getCurrentUser();
        return user && user.role === 'admin';
    },

    requireLogin() {
        if (!this.isLoggedIn()) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    },

    requireAdmin() {
        if (!this.isAdmin()) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    },

    // ============================================
    // AUTH FUNCTIONS
    // ============================================
    register(nama, email, password, no_hp, alamat) {
        const users = this.getUsers();
        if (users.find(u => u.email === email)) {
            return { success: false, message: 'Email sudah terdaftar!' };
        }
        const newUser = {
            id: users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1,
            nama, email, password, no_hp, alamat,
            role: 'customer',
            created_at: new Date().toISOString()
        };
        users.push(newUser);
        this.saveUsers(users);
        return { success: true, message: 'Registrasi berhasil!' };
    },

    login(email, password) {
        const users = this.getUsers();
        const user = users.find(u => u.email === email && u.password === password);
        if (user) {
            this.setCurrentUser(user);
            return { success: true, user };
        }
        return { success: false, message: 'Email atau password salah!' };
    },

    // ============================================
    // CART FUNCTIONS
    // ============================================
    addToCart(productId, qty = 1) {
        if (!this.requireLogin()) return;
        const cart = this.getCart();
        const existing = cart.find(c => c.productId === productId);
        if (existing) {
            existing.qty += qty;
        } else {
            cart.push({ productId, qty });
        }
        this.saveCart(cart);
        this.updateCartBadge();
    },

    removeFromCart(productId) {
        let cart = this.getCart();
        cart = cart.filter(c => c.productId !== productId);
        this.saveCart(cart);
        this.updateCartBadge();
    },

    updateCartQty(productId, qty) {
        const cart = this.getCart();
        const item = cart.find(c => c.productId === productId);
        if (item) {
            item.qty = Math.max(1, qty);
        }
        this.saveCart(cart);
        this.updateCartBadge();
    },

    getCartTotal() {
        const cart = this.getCart();
        const products = this.getProducts();
        let total = 0;
        cart.forEach(c => {
            const product = products.find(p => p.id === c.productId);
            if (product) {
                total += product.harga * c.qty;
            }
        });
        return total;
    },

    getCartCount() {
        return this.getCart().reduce((sum, c) => sum + c.qty, 0);
    },

    updateCartBadge() {
        const badge = document.getElementById('cartBadge');
        if (badge) {
            const count = this.getCartCount();
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        }
    },

    clearCart() {
        this.saveCart([]);
        this.updateCartBadge();
    },

    // ============================================
    // ORDER FUNCTIONS
    // ============================================
    checkout(alamat) {
        if (!this.requireLogin()) return null;
        const cart = this.getCart();
        if (cart.length === 0) return null;

        const products = this.getProducts();
        const user = this.getCurrentUser();
        const orders = this.getOrders();

        const orderNumber = 'ORD-' + Date.now();
        let total = 0;
        const details = [];

        cart.forEach(c => {
            const product = products.find(p => p.id === c.productId);
            if (product) {
                const subtotal = product.harga * c.qty;
                total += subtotal;
                details.push({
                    productId: product.id,
                    nama_produk: product.nama_produk,
                    jumlah: c.qty,
                    harga: product.harga,
                    subtotal
                });
                // Update stok
                product.stok = Math.max(0, product.stok - c.qty);
            }
        });

        const order = {
            id: orders.length > 0 ? Math.max(...orders.map(o => o.id)) + 1 : 1,
            order_number: orderNumber,
            user_id: user.id,
            user_nama: user.nama,
            user_email: user.email,
            total,
            alamat,
            status: 'menunggu',
            details,
            created_at: new Date().toISOString()
        };

        orders.push(order);
        this.saveOrders(orders);
        this.saveProducts(products);
        this.clearCart();

        return order;
    },

    getUserOrders() {
        const user = this.getCurrentUser();
        if (!user) return [];
        return this.getOrders().filter(o => o.user_id === user.id);
    },

    getAllOrders() {
        return this.getOrders();
    },

    updateOrderStatus(orderId, status) {
        const orders = this.getOrders();
        const order = orders.find(o => o.id === orderId);
        if (order) {
            order.status = status;
            this.saveOrders(orders);
        }
    },

    // ============================================
    // PRODUCT FUNCTIONS
    // ============================================
    addProduct(product) {
        const products = this.getProducts();
        product.id = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
        product.created_at = new Date().toISOString();
        products.push(product);
        this.saveProducts(products);
        return product;
    },

    updateProduct(id, data) {
        const products = this.getProducts();
        const index = products.findIndex(p => p.id === id);
        if (index !== -1) {
            products[index] = { ...products[index], ...data };
            this.saveProducts(products);
            return true;
        }
        return false;
    },

    deleteProduct(id) {
        let products = this.getProducts();
        products = products.filter(p => p.id !== id);
        this.saveProducts(products);
    },

    getProduct(id) {
        return this.getProducts().find(p => p.id === parseInt(id));
    },

    searchProducts(query) {
        const products = this.getProducts();
        const q = query.toLowerCase();
        return products.filter(p =>
            p.nama_produk.toLowerCase().includes(q) ||
            p.kategori.toLowerCase().includes(q) ||
            p.deskripsi.toLowerCase().includes(q)
        );
    },

    filterByCategory(kategori) {
        const products = this.getProducts();
        if (!kategori || kategori === 'Semua') return products;
        return products.filter(p => p.kategori === kategori);
    },

    getCategories() {
        const products = this.getProducts();
        const cats = [...new Set(products.map(p => p.kategori))];
        return cats.sort();
    },

    // ============================================
    // STATS
    // ============================================
    getStats() {
        const orders = this.getOrders();
        const products = this.getProducts();
        const users = this.getUsers().filter(u => u.role === 'customer');
        const totalRevenue = orders.reduce((sum, o) => sum + o.total, 0);

        return {
            totalOrders: orders.length,
            totalProducts: products.length,
            totalUsers: users.length,
            totalRevenue,
            pendingOrders: orders.filter(o => o.status === 'menunggu').length,
            processedOrders: orders.filter(o => o.status === 'diproses').length,
            completedOrders: orders.filter(o => o.status === 'selesai').length
        };
    },


};

// Initialize
App.init();
