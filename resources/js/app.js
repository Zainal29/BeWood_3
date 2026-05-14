/**
 * ============================================================
 * BEWOOD FRONTEND JAVASCRIPT (OPTIMIZED)
 * ============================================================
 * Features:
 * - Cart & Wishlist dengan localStorage + error handling
 * - Event delegation untuk performa lebih baik
 * - Debounce untuk search input
 * - Focus trap & accessibility untuk modal
 * - Sanitization untuk mencegah XSS
 * - Modular structure dengan IIFE
 */

(function() {
    'use strict';

    // ============================================================
    // 1. CONFIG & CONSTANTS
    // ============================================================
    const CONFIG = {
        STORAGE_KEYS: {
            CART: 'bewood_cart',
            WISHLIST: 'bewood_wishlist',
            THEME: 'bewood_theme'
        },
        MAX_QTY: 5,
        TOAST_DURATION: 4000,
        DEBOUNCE_DELAY: 300,
        SCROLL_THRESHOLD: 400
    };

    const SELECTORS = {
        // Cart
        CART_BADGE: '#cart-badge',
        CART_ITEMS: '#cart-items',
        CART_EMPTY: '#cart-empty',
        CART_FOOTER: '#cart-footer',
        CART_TOTAL: '#cart-total',
        CART_SIDEBAR: '#cart-sidebar',
        CART_OVERLAY: '#cart-overlay',

        // Wishlist
        WISHLIST_BADGE: '#wishlist-badge',

        // Quick View
        QV_MODAL: '#quick-view-modal',
        QV_IMAGE: '#qv-image',
        QV_TITLE: '#qv-title',
        QV_PRICE: '#qv-price',
        QV_DESC: '#qv-desc',
        QV_CATEGORY: '#qv-category',
        QV_REVIEWS: '#qv-reviews',
        QV_QTY: '#qv-qty',

        // Product Detail
        MAIN_IMG: '#main-img',
        QTY_DISPLAY: '#qty-display',

        // Search
        SEARCH_MODAL: '#search-modal',
        SEARCH_INPUT: '#search-input',
        SEARCH_RESULTS: '#search-results',

        // UI
        TOAST_CONTAINER: '#toast-container',
        SCROLL_PROGRESS: '#scroll-progress',
        BACK_TO_TOP: '#back-to-top',
        LOADING_SCREEN: '#loading-screen',

        // Mobile
        MOBILE_MENU_BTN: '#mobile-menu-btn',
        MOBILE_MENU: '#mobile-menu',

        // Theme
        THEME_TOGGLES: '#theme-toggle, #theme-toggle-mobile'
    };

    // ============================================================
    // 2. UTILITIES
    // ============================================================

    /** Format angka ke Rupiah */
    const formatRupiah = (num) =>
        new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

    /** Sanitize string untuk mencegah XSS */
    const sanitize = (str) => {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    };

    /** Debounce function untuk optimasi input */
    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    /** Safe localStorage getter dengan fallback */
    const getStorage = (key, fallback = []) => {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : fallback;
        } catch (e) {
            console.warn(`[Storage Error] ${key}:`, e);
            return fallback;
        }
    };

    /** Safe localStorage setter dengan error handling */
    const setStorage = (key, value) => {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.error(`[Storage Full] ${key}:`, e);
            showToast('Penyimpanan penuh, hapus beberapa item', 'error');
            return false;
        }
    };

    /** Tampilkan notifikasi toast */
    const showToast = (message, type = 'success') => {
        const container = document.querySelector(SELECTORS.TOAST_CONTAINER);
        if (!container) return;

        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type} fixed bottom-4 right-4 z-[100] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg bg-white border border-slate-200 animate-slide-up`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <svg class="w-5 h-5 shrink-0 ${type === 'success' ? 'text-emerald-500' : type === 'error' ? 'text-red-500' : 'text-blue-500'}"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                ${icons[type] || icons.info}
            </svg>
            <span class="text-sm font-medium text-slate-700">${sanitize(message)}</span>
            <button class="ml-2 text-slate-400 hover:text-slate-600" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        container.appendChild(toast);

        // Auto remove dengan animasi
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, CONFIG.TOAST_DURATION);
    };

    /** Manage body scroll lock untuk modal */
    const BodyScroll = {
        lock: () => {
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = `${window.innerWidth - document.documentElement.clientWidth}px`;
        },
        unlock: () => {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    };

    // ============================================================
    // 3. STATE MANAGEMENT (Class-based)
    // ============================================================

    class Store {
        constructor(cartKey, wishlistKey) {
            this.cartKey = cartKey;
            this.wishlistKey = wishlistKey;
            this.cart = getStorage(cartKey, []);
            this.wishlist = getStorage(wishlistKey, []);
            this.quickViewProduct = null;
            this.listeners = { cart: [], wishlist: [] };
        }

        // Cart Methods
        getCart() { return [...this.cart]; }

        addToCart(product, qty = 1) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                existing.qty = Math.min(existing.qty + qty, CONFIG.MAX_QTY * 10);
            } else {
                this.cart.push({ ...product, qty, id: product.id || Date.now() });
            }
            this._saveCart();
            this._emit('cart');
            return true;
        }

        updateQty(index, delta) {
            if (!this.cart[index]) return false;
            this.cart[index].qty = Math.max(1, this.cart[index].qty + delta);
            if (this.cart[index].qty <= 0) this.cart.splice(index, 1);
            this._saveCart();
            this._emit('cart');
            return true;
        }

        removeFromCart(index) {
            const removed = this.cart.splice(index, 1);
            this._saveCart();
            this._emit('cart');
            return removed[0];
        }

        clearCart() {
            this.cart = [];
            this._saveCart();
            this._emit('cart');
        }

        getCartTotal() {
            return {
                qty: this.cart.reduce((s, i) => s + i.qty, 0),
                price: this.cart.reduce((s, i) => s + i.price * i.qty, 0)
            };
        }

        // Wishlist Methods
        getWishlist() { return [...this.wishlist]; }

        toggleWishlist(product) {
            const idx = this.wishlist.findIndex(i => i.id === product.id);
            if (idx === -1) {
                this.wishlist.push({ ...product, id: product.id || Date.now() });
                this._saveWishlist();
                this._emit('wishlist');
                return true; // Added
            } else {
                this.wishlist.splice(idx, 1);
                this._saveWishlist();
                this._emit('wishlist');
                return false; // Removed
            }
        }

        isInWishlist(productId) {
            return this.wishlist.some(i => i.id === productId);
        }

        // Event System
        on(type, callback) {
            if (!this.listeners[type]) return;
            this.listeners[type].push(callback);
            return () => {
                this.listeners[type] = this.listeners[type].filter(cb => cb !== callback);
            };
        }

        _emit(type) {
            this.listeners[type]?.forEach(cb => cb());
        }

        _saveCart() { setStorage(this.cartKey, this.cart); }
        _saveWishlist() { setStorage(this.wishlistKey, this.wishlist); }
    }

    // Initialize global store
    const store = new Store(CONFIG.STORAGE_KEYS.CART, CONFIG.STORAGE_KEYS.WISHLIST);

    // ============================================================
    // 4. CART MODULE
    // ============================================================

    const CartUI = {
        elements: {},

        init() {
            this.cacheElements();
            this.render();
            this.bindEvents();
            // Subscribe to store changes
            store.on('cart', () => this.render());
        },

        cacheElements() {
            this.elements = {
                badge: document.querySelector(SELECTORS.CART_BADGE),
                items: document.querySelector(SELECTORS.CART_ITEMS),
                empty: document.querySelector(SELECTORS.CART_EMPTY),
                footer: document.querySelector(SELECTORS.CART_FOOTER),
                total: document.querySelector(SELECTORS.CART_TOTAL),
                sidebar: document.querySelector(SELECTORS.CART_SIDEBAR),
                overlay: document.querySelector(SELECTORS.CART_OVERLAY)
            };
        },

        render() {
            const { badge, items, empty, footer, total } = this.elements;
            const { qty: totalQty, price: totalPrice } = store.getCartTotal();

            // Update badge
            if (badge) {
                badge.textContent = totalQty > 9 ? '9+' : totalQty;
                badge.classList.toggle('hidden', totalQty === 0);
            }

            // Toggle empty state
            const isEmpty = store.getCart().length === 0;
            empty?.classList.toggle('hidden', !isEmpty);
            footer?.classList.toggle('hidden', isEmpty);

            // Update total
            if (total) total.textContent = formatRupiah(totalPrice);

            // Render items (hanya jika ada perubahan)
            if (items && !isEmpty) {
                this.renderItems(items);
            }
        },

        renderItems(container) {
            // Gunakan DocumentFragment untuk performa
            const fragment = document.createDocumentFragment();
            const cartItems = store.getCart();

            cartItems.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'flex gap-4 items-start py-4 border-b border-slate-100 last:border-0 animate-fade-in';
                div.innerHTML = `
                    <img src="${sanitize(item.img)}"
                         class="w-20 h-20 object-cover rounded-lg bg-slate-100 shrink-0"
                         alt="${sanitize(item.name)}"
                         loading="lazy">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-slate-800 truncate">${sanitize(item.name)}</p>
                        <p class="text-xs text-slate-500 mb-2">${formatRupiah(item.price)}</p>
                        <div class="flex items-center gap-2">
                            <button class="qty-btn w-7 h-7 border border-slate-200 rounded-lg text-slate-600
                                         flex items-center justify-center hover:bg-slate-50 text-sm transition"
                                    data-idx="${idx}" data-action="dec" aria-label="Kurangi jumlah">−</button>
                            <span class="text-sm font-medium text-slate-700 w-6 text-center">${item.qty}</span>
                            <button class="qty-btn w-7 h-7 border border-slate-200 rounded-lg text-slate-600
                                         flex items-center justify-center hover:bg-slate-50 text-sm transition"
                                    data-idx="${idx}" data-action="inc" aria-label="Tambah jumlah">+</button>
                            <button class="remove-btn ml-auto text-slate-400 hover:text-red-500 transition p-1"
                                    data-idx="${idx}" aria-label="Hapus produk">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                fragment.appendChild(div);
            });

            // Clear & append (pertahankan empty state jika ada)
            const emptyEl = container.querySelector('#cart-empty');
            container.innerHTML = '';
            if (emptyEl) container.appendChild(emptyEl);
            container.appendChild(fragment);
        },

        bindEvents() {
            // Event delegation untuk cart items (lebih efisien)
            this.elements.items?.addEventListener('click', (e) => {
                const btn = e.target.closest('.qty-btn, .remove-btn');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();

                const idx = parseInt(btn.dataset.idx);
                if (btn.classList.contains('remove-btn')) {
                    this.handleRemove(idx);
                } else {
                    const delta = btn.dataset.action === 'inc' ? 1 : -1;
                    store.updateQty(idx, delta);
                }
            });

            // Empty cart button
            document.getElementById('empty-cart-btn')?.addEventListener('click', () => {
                Swal.fire({
                    title: 'Kosongkan keranjang?',
                    text: 'Semua produk akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, kosongkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        store.clearCart();
                        showToast('Keranjang dikosongkan', 'info');
                    }
                });
            });
        },

        handleRemove(idx) {
            const item = store.getCart()[idx];
            if (!item) return;

            Swal.fire({
                title: `Hapus "${sanitize(item.name)}"?`,
                text: 'Produk akan dihapus dari keranjang.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    store.removeFromCart(idx);
                    showToast('Produk dihapus dari keranjang', 'info');
                }
            });
        },

        open() {
            const { sidebar, overlay } = this.elements;
            if (!sidebar || !overlay) return;

            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.setAttribute('aria-hidden', 'false');
            BodyScroll.lock();

            // Focus trap: fokus ke close button
            setTimeout(() => {
                sidebar.querySelector('[id*="close"]')?.focus();
            }, 300);
        },

        close() {
            const { sidebar, overlay } = this.elements;
            if (!sidebar || !overlay) return;

            sidebar.classList.add('translate-x-full');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.setAttribute('aria-hidden', 'true');
            BodyScroll.unlock();
        }
    };

    // ============================================================
    // 5. WISHLIST MODULE
    // ============================================================

    const WishlistUI = {
        init() {
            this.updateBadge();
            store.on('wishlist', () => this.updateBadge());
        },

        updateBadge() {
            const badge = document.querySelector(SELECTORS.WISHLIST_BADGE);
            if (!badge) return;

            const count = store.getWishlist().length;
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.toggle('hidden', count === 0);
        },

        toggleFromCard(btn) {
            const card = btn.closest('[data-product-id]');
            if (!card) return;

            const product = {
                id: card.dataset.productId,
                name: card.dataset.name,
                price: parseInt(card.dataset.price),
                img: card.dataset.img
            };

            const added = store.toggleWishlist(product);
            btn.classList.toggle('text-red-500', added);
            btn.classList.toggle('fill-red-500', added);
            showToast(added ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist', added ? 'success' : 'info');
        },

        toggleFromDetail(productId, iconEl) {
            if (!window.currentProduct) return;

            const product = {
                id: productId,
                name: window.currentProduct.name,
                price: window.currentProduct.price,
                img: window.currentProduct.image
            };

            const added = store.toggleWishlist(product);

            if (iconEl) {
                iconEl.classList.toggle('text-red-500', added);
                iconEl.classList.toggle('fill-red-500', added);
            }
            showToast(added ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist', added ? 'success' : 'info');
        }
    };

    // ============================================================
    // 6. QUICK VIEW MODULE
    // ============================================================

    const QuickView = {
        elements: {},

        init() {
            this.cacheElements();
            this.bindEvents();
        },

        cacheElements() {
            this.elements = {
                modal: document.querySelector(SELECTORS.QV_MODAL),
                image: document.querySelector(SELECTORS.QV_IMAGE),
                title: document.querySelector(SELECTORS.QV_TITLE),
                price: document.querySelector(SELECTORS.QV_PRICE),
                desc: document.querySelector(SELECTORS.QV_DESC),
                category: document.querySelector(SELECTORS.QV_CATEGORY),
                reviews: document.querySelector(SELECTORS.QV_REVIEWS),
                qty: document.querySelector(SELECTORS.QV_QTY)
            };
        },

        open(product) {
            const { elements } = this;
            if (!elements.modal) return;

            // Populate data dengan sanitization
            elements.image.src = sanitize(product.img);
            elements.image.alt = sanitize(product.name);
            elements.title.textContent = sanitize(product.name);
            elements.price.textContent = formatRupiah(product.price);
            elements.desc.textContent = sanitize(product.desc || 'Produk berkualitas premium.');
            elements.category.textContent = sanitize(product.category || 'Furniture');
            elements.reviews.textContent = `(${product.reviews || 0} ulasan)`;

            // Reset qty
            if (elements.qty) elements.qty.textContent = '1';

            // Show modal
            elements.modal.classList.remove('hidden');
            BodyScroll.lock();
            store.quickViewProduct = product;

            // Focus trap
            setTimeout(() => {
                elements.modal.querySelector('button')?.focus();
            }, 100);
        },

        close() {
            this.elements.modal?.classList.add('hidden');
            BodyScroll.unlock();
            store.quickViewProduct = null;
        },

        changeQty(delta) {
            const qtyEl = this.elements.qty;
            if (!qtyEl) return;

            let qty = parseInt(qtyEl.textContent) || 1;
            qty = Math.max(1, Math.min(99, qty + delta));
            qtyEl.textContent = qty;
        },

        addToCart() {
            const product = store.quickViewProduct;
            if (!product) return;

            const qty = parseInt(this.elements.qty?.textContent) || 1;
            store.addToCart(product, qty);

            this.close();
            CartUI.open();
            showToast(`${qty}x ${sanitize(product.name)} ditambahkan`, 'success');
        },

        bindEvents() {
            // Qty buttons
            document.querySelectorAll('.qv-qty-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.changeQty(btn.dataset.action === 'inc' ? 1 : -1);
                });
            });

            // Add to cart
            document.getElementById('qv-add-to-cart')?.addEventListener('click', (e) => {
                e.preventDefault();
                this.addToCart();
            });
        }
    };

    // ============================================================
    // 7. PRODUCT DETAIL MODULE
    // ============================================================

    const ProductDetail = {
        init() {
            this.bindGallery();
            this.bindQty();
            this.bindTabs();
            this.bindVariants();
            this.bindZoom();
        },

        bindGallery() {
            // Ganti gambar utama dari thumbnail
            document.querySelectorAll('.thumb-item').forEach(thumb => {
                thumb.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Update active state
                    document.querySelectorAll('.thumb-item').forEach(t =>
                        t.classList.remove('active', 'ring-2', 'ring-emerald-500'));
                    thumb.classList.add('active', 'ring-2', 'ring-emerald-500');

                    // Ganti gambar dengan fade effect
                    const mainImg = document.querySelector(SELECTORS.MAIN_IMG);
                    const newSrc = thumb.querySelector('img')?.dataset.src || thumb.querySelector('img')?.src;

                    if (mainImg && newSrc) {
                        mainImg.style.opacity = '0';
                        setTimeout(() => {
                            mainImg.src = newSrc;
                            mainImg.style.opacity = '1';
                        }, 150);
                    }
                });
            });
        },

        bindQty() {
            const display = document.querySelector(SELECTORS.QTY_DISPLAY);
            if (!display) return;

            window.changeQty = (delta) => {
                let qty = parseInt(display.textContent) || 1;
                qty = Math.max(1, Math.min(CONFIG.MAX_QTY, qty + delta));
                display.textContent = qty;
            };
        },

        bindTabs() {
            window.switchTab = (tabName, btn) => {
                // Hide all, show target
                document.querySelectorAll('.tab-content').forEach(el =>
                    el.classList.add('hidden'));
                document.getElementById(`tab-${tabName}`)?.classList.remove('hidden');

                // Update button state
                document.querySelectorAll('.tab-btn').forEach(b =>
                    b.classList.remove('active', 'text-emerald-600', 'border-emerald-600'));
                btn?.classList.add('active', 'text-emerald-600', 'border-emerald-600');
            };
        },

        bindVariants() {
            // Swatch colors
            window.selectSwatch = (el) => {
                document.querySelectorAll('.swatch').forEach(s =>
                    s.classList.remove('active', 'ring-2', 'ring-offset-2'));
                el.classList.add('active', 'ring-2', 'ring-offset-2', 'ring-emerald-500');

                const label = document.getElementById('swatch-label');
                if (label) label.textContent = el.dataset.name;
            };

            // Finishing options
            window.selectFinish = (el) => {
                document.querySelectorAll('.finish-btn').forEach(b => {
                    b.classList.remove('border-emerald-600', 'bg-emerald-600', 'text-white');
                    b.classList.add('border-slate-200', 'text-slate-600');
                });
                el.classList.remove('border-slate-200', 'text-slate-600');
                el.classList.add('border-emerald-600', 'bg-emerald-600', 'text-white');

                const label = document.getElementById('finish-label');
                if (label) label.textContent = el.dataset.name;
            };
        },

        bindZoom() {
            window.openZoom = () => {
                const src = document.querySelector(SELECTORS.MAIN_IMG)?.src;
                const overlay = document.getElementById('zoom-overlay');
                const zoomImg = document.getElementById('zoom-img');

                if (!src || !overlay || !zoomImg) return;

                zoomImg.src = src;
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100');
                BodyScroll.lock();
            };

            window.closeZoom = () => {
                const overlay = document.getElementById('zoom-overlay');
                if (!overlay) return;

                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                BodyScroll.unlock();
            };
        }
    };

    // ============================================================
    // 8. SEARCH MODULE (with debounce)
    // ============================================================

    const Search = {
        init() {
            this.bindModal();
            this.bindInput();
        },

        bindModal() {
            window.openSearch = () => {
                const modal = document.querySelector(SELECTORS.SEARCH_MODAL);
                const input = document.querySelector(SELECTORS.SEARCH_INPUT);

                if (modal && input) {
                    modal.classList.remove('hidden');
                    BodyScroll.lock();

                    // Auto focus dengan delay untuk animasi
                    setTimeout(() => input.focus(), 200);
                }
            };

            window.closeSearch = () => {
                const modal = document.querySelector(SELECTORS.SEARCH_MODAL);
                const input = document.querySelector(SELECTORS.SEARCH_INPUT);

                modal?.classList.add('hidden');
                if (input) input.value = '';
                BodyScroll.unlock();
            };
        },

        bindInput() {
            const input = document.querySelector(SELECTORS.SEARCH_INPUT);
            if (!input) return;

            // Debounced search handler
            const handleSearch = debounce((query) => {
                if (query.length < 2) return;

                // Redirect ke halaman search dengan parameter sanitized
                const safeQuery = encodeURIComponent(query.trim().toLowerCase());
                window.location.href = `/products?search=${safeQuery}`;
            }, CONFIG.DEBOUNCE_DELAY);

            input.addEventListener('input', (e) => handleSearch(e.target.value));
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(input.value);
                }
            });
        }
    };

    // ============================================================
    // 9. UI UTILITIES (Scroll, Mobile Menu, Theme)
    // ============================================================

    const UIUtils = {
        init() {
            this.initLoading();
            this.initScroll();
            this.initMobileMenu();
            this.initReveal();
            this.initTheme();
        },

        initLoading() {
            const loading = document.querySelector(SELECTORS.LOADING_SCREEN);
            if (loading) {
                // Hide loading screen setelah page load
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        loading.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => loading.remove(), 300);
                    }, 800);
                });
            }
        },

        initScroll() {
            const progressBar = document.querySelector(SELECTORS.SCROLL_PROGRESS);
            const backToTop = document.querySelector(SELECTORS.BACK_TO_TOP);

            const onScroll = () => {
                // Progress bar
                if (progressBar) {
                    const scrolled = (window.scrollY /
                        (document.documentElement.scrollHeight - window.innerHeight)) * 100;
                    progressBar.style.width = `${Math.min(100, scrolled)}%`;
                }

                // Back to top button
                if (backToTop) {
                    backToTop.classList.toggle('visible', window.scrollY > CONFIG.SCROLL_THRESHOLD);
                }
            };

            // Throttle scroll event untuk performa
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        onScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            // Back to top click
            backToTop?.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        initMobileMenu() {
            const btn = document.querySelector(SELECTORS.MOBILE_MENU_BTN);
            const menu = document.querySelector(SELECTORS.MOBILE_MENU);

            if (!btn || !menu) return;

            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !expanded);
                menu.classList.toggle('hidden');

                // Animate menu
                if (!expanded) {
                    menu.classList.add('animate-slide-down');
                }
            });

            // Close menu when clicking link
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                });
            });
        },

        initReveal() {
            // Intersection Observer untuk scroll reveal animation
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    },
                    { threshold: 0.1, rootMargin: '0px 0px -50px 0px' }
                );

                document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            } else {
                // Fallback: show all if no IntersectionObserver
                document.querySelectorAll('.reveal').forEach(el =>
                    el.classList.add('visible'));
            }
        },

        initTheme() {
            // Load saved theme
            const savedTheme = localStorage.getItem(CONFIG.STORAGE_KEYS.THEME);
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }

            // Bind toggle buttons
            document.querySelectorAll(SELECTORS.THEME_TOGGLES).forEach(toggle => {
                toggle?.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem(CONFIG.STORAGE_KEYS.THEME, isDark ? 'dark' : 'light');

                    // Optional: showToast theme changed
                    // showToast(`Mode ${isDark ? 'gelap' : 'terang'} diaktifkan`, 'info');
                });
            });
        }
    };

    // ============================================================
    // 10. GLOBAL EVENT HANDLERS
    // ============================================================

    const GlobalHandlers = {
        init() {
            this.bindCart();
            this.bindQuickView();
            this.bindSearch();
            this.bindKeyboard();
            this.bindClickOutside();
        },

        bindCart() {
            // Open/close cart
            document.getElementById('open-cart')?.addEventListener('click', (e) => {
                e.preventDefault();
                CartUI.open();
            });

            document.getElementById('close-cart')?.addEventListener('click', (e) => {
                e.preventDefault();
                CartUI.close();
            });

            document.querySelector(SELECTORS.CART_OVERLAY)?.addEventListener('click', () => {
                CartUI.close();
            });

            // Add to cart from product cards (event delegation)
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-add-to-cart]');
                if (!btn) return;
                e.preventDefault();

                const card = btn.closest('[data-product-id]');
                if (!card) return;

                const product = {
                    id: card.dataset.productId,
                    name: card.dataset.name,
                    price: parseInt(card.dataset.price),
                    img: card.dataset.img
                };

                store.addToCart(product);
                CartUI.open();
                showToast('Produk ditambahkan ke keranjang', 'success');
            });

            // Add to cart from product detail page
            window.addToCartMain = () => {
                if (!window.currentProduct) {
                    showToast('Data produk tidak ditemukan', 'error');
                    return;
                }

                const qty = parseInt(document.querySelector(SELECTORS.QTY_DISPLAY)?.textContent) || 1;
                store.addToCart(window.currentProduct, qty);
                CartUI.open();
                showToast(`${qty}x produk ditambahkan`, 'success');
            };
        },

        bindQuickView() {
            // Open from card
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-quick-view]');
                if (!btn) return;
                e.preventDefault();

                const card = btn.closest('[data-product-id]');
                if (!card) return;

                QuickView.open({
                    id: card.dataset.productId,
                    name: card.dataset.name,
                    price: parseInt(card.dataset.price),
                    img: card.dataset.img,
                    desc: card.dataset.desc,
                    category: card.dataset.category,
                    reviews: card.dataset.reviews
                });
            });

            // Close quick view
            document.getElementById('qv-close')?.addEventListener('click', (e) => {
                e.preventDefault();
                QuickView.close();
            });

            document.querySelector('#quick-view-modal .modal-overlay')?.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) QuickView.close();
            });
        },

        bindSearch() {
            // Close search modal
            document.getElementById('search-close')?.addEventListener('click', (e) => {
                e.preventDefault();
                window.closeSearch();
            });

            document.querySelector('#search-modal .modal-overlay')?.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) window.closeSearch();
            });
        },

        bindKeyboard() {
            // ESC to close modals
            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;

                // Close in order of z-index
                if (!document.querySelector(SELECTORS.QV_MODAL)?.classList.contains('hidden')) {
                    QuickView.close();
                } else if (!document.querySelector(SELECTORS.SEARCH_MODAL)?.classList.contains('hidden')) {
                    window.closeSearch();
                } else if (!document.querySelector(SELECTORS.CART_SIDEBAR)?.classList.contains('translate-x-full')) {
                    CartUI.close();
                } else {
                    window.closeZoom?.();
                }
            });
        },

        bindClickOutside() {
            // Close modals when clicking outside content
            document.addEventListener('click', (e) => {
                // Quick view
                const qvModal = document.querySelector(SELECTORS.QV_MODAL);
                if (qvModal && !qvModal.classList.contains('hidden')) {
                    const content = qvModal.querySelector('.modal-content');
                    if (content && !content.contains(e.target)) {
                        QuickView.close();
                    }
                }

                // Search
                const searchModal = document.querySelector(SELECTORS.SEARCH_MODAL);
                if (searchModal && !searchModal.classList.contains('hidden')) {
                    const content = searchModal.querySelector('.modal-content');
                    if (content && !content.contains(e.target)) {
                        window.closeSearch();
                    }
                }
            });
        }
    };

    // ============================================================
    // 11. EXPOSE PUBLIC API (untuk inline handlers jika diperlukan)
    // ============================================================

    window.Bewood = {
        // Cart
        addToCart: (product, qty) => store.addToCart(product, qty),
        openCart: () => CartUI.open(),
        closeCart: () => CartUI.close(),

        // Wishlist
        toggleWishlist: (product) => store.toggleWishlist(product),
        isInWishlist: (id) => store.isInWishlist(id),

        // Quick View
        openQuickView: (product) => QuickView.open(product),
        closeQuickView: () => QuickView.close(),

        // Utils
        formatRupiah,
        showToast
    };

    // Backwards compatibility for inline onclick attributes
    Object.assign(window, {
        formatRupiah,
        showToast,
        addToCartMain: () => window.Bewood.addToCart(window.currentProduct),
        toggleWishlistDetail: (id, icon) => {
            if (!window.currentProduct) return;
            const added = window.Bewood.toggleWishlist({
                id,
                name: window.currentProduct.name,
                price: window.currentProduct.price,
                img: window.currentProduct.image
            });
            if (icon) {
                icon.classList.toggle('text-red-500', added);
                icon.classList.toggle('fill-red-500', added);
            }
        },
        changeQty: ProductDetail.bindQty,
        switchTab: ProductDetail.bindTabs,
        selectSwatch: (el) => window.selectSwatch?.(el),
        selectFinish: (el) => window.selectFinish?.(el),
        openZoom: () => window.openZoom?.(),
        closeZoom: () => window.closeZoom?.(),
        openSearch: () => window.openSearch?.(),
        closeSearch: () => window.closeSearch?.()
    });

    // ============================================================
    // 12. INITIALIZATION
    // ============================================================

    function init() {
        // Initialize modules
        CartUI.init();
        WishlistUI.init();
        QuickView.init();
        ProductDetail.init();
        Search.init();
        UIUtils.init();
        GlobalHandlers.init();

        // Initial render
        CartUI.render();
        WishlistUI.updateBadge();

        console.log('✅ BeWood Frontend initialized');
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
