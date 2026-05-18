/**
 * ============================================================
 * BEWOOD FRONTEND JAVASCRIPT (NO QUICK VIEW - FINAL)
 * ============================================================
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
        MAX_QTY: 99,
        TOAST_DURATION: 4000,
        DEBOUNCE_DELAY: 300,
        SCROLL_THRESHOLD: 400
    };

    const SELECTORS = {
        CART_BADGE: '#cart-badge',
        CART_ITEMS: '#cart-items',
        CART_EMPTY: '#cart-empty',
        CART_FOOTER: '#cart-footer',
        CART_TOTAL: '#cart-total',
        CART_SIDEBAR: '#cart-sidebar',
        CART_OVERLAY: '#cart-overlay',
        WISHLIST_BADGE: '#wishlist-badge',
        MAIN_IMG: '#main-img',
        QTY_DISPLAY: '#qty-display',
        SEARCH_MODAL: '#search-modal',
        SEARCH_INPUT: '#search-input',
        SEARCH_RESULTS: '#search-results',
        TOAST_CONTAINER: '#toast-container',
        SCROLL_PROGRESS: '#scroll-progress',
        BACK_TO_TOP: '#back-to-top',
        LOADING_SCREEN: '#loading-screen',
        MOBILE_MENU_BTN: '#mobile-menu-btn',
        MOBILE_MENU: '#mobile-menu',
        THEME_TOGGLES: '#theme-toggle, #theme-toggle-mobile'
    };

    // ============================================================
    // 2. UTILITIES
    // ============================================================
    const formatRupiah = (num) =>
        new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

    const sanitize = (str) => {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    };

    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    const getStorage = (key, fallback = []) => {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : fallback;
        } catch (e) {
            console.warn(`[Storage Error] ${key}:`, e);
            return fallback;
        }
    };

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
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, CONFIG.TOAST_DURATION);
    };

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
    // 3. STATE MANAGEMENT (Store)
    // ============================================================
    class Store {
        constructor(cartKey, wishlistKey) {
            this.cartKey = cartKey;
            this.wishlistKey = wishlistKey;
            this.cart = getStorage(cartKey, []);
            this.wishlist = getStorage(wishlistKey, []);
            this.listeners = { cart: [], wishlist: [] };
        }

        getCart() { return [...this.cart]; }

        addToCart(product, qty = 1) {
            const existing = this.cart.find(i => i.id == product.id);
            if (existing) {
                existing.qty = Math.min(existing.qty + qty, CONFIG.MAX_QTY);
            } else {
                this.cart.push({ ...product, qty: Math.min(qty, CONFIG.MAX_QTY), id: product.id || Date.now() });
            }
            this._saveCart();
            this._emit('cart');
            return true;
        }

        updateQtyById(productId, delta) {
            const index = this.cart.findIndex(i => i.id == productId);
            if (index === -1) return false;
            let newQty = this.cart[index].qty + delta;
            if (newQty <= 0) {
                this.cart.splice(index, 1);
            } else {
                this.cart[index].qty = Math.min(newQty, CONFIG.MAX_QTY);
            }
            this._saveCart();
            this._emit('cart');
            return true;
        }

        removeFromCartById(productId) {
            const index = this.cart.findIndex(i => i.id == productId);
            if (index === -1) return null;
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
                price: this.cart.reduce((s, i) => s + (parseFloat(i.price) || 0) * i.qty, 0)
            };
        }

        getWishlist() { return [...this.wishlist]; }

        toggleWishlist(product) {
            const idx = this.wishlist.findIndex(i => i.id == product.id);
            if (idx === -1) {
                this.wishlist.push({ ...product, id: product.id || Date.now() });
                this._saveWishlist();
                this._emit('wishlist');
                return true;
            } else {
                this.wishlist.splice(idx, 1);
                this._saveWishlist();
                this._emit('wishlist');
                return false;
            }
        }

        isInWishlist(productId) {
            return this.wishlist.some(i => i.id == productId);
        }

        on(type, callback) {
            if (!this.listeners[type]) return () => {};
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

            if (badge) {
                badge.textContent = totalQty > 9 ? '9+' : totalQty;
                badge.classList.toggle('hidden', totalQty === 0);
            }

            const isEmpty = store.getCart().length === 0;
            if (empty) empty.classList.toggle('hidden', !isEmpty);
            if (footer) footer.classList.toggle('hidden', isEmpty);
            if (total) total.textContent = formatRupiah(totalPrice);
            if (items && !isEmpty) this.renderItems(items);
            else if (items && isEmpty) {
                items.innerHTML = '';
                if (empty) items.appendChild(empty);
            }
        },

        renderItems(container) {
            const fragment = document.createDocumentFragment();
            const cartItems = store.getCart();

            cartItems.forEach((item) => {
                // Cek semua kemungkinan key gambar
                let imageUrl = item.img || item.image || '';
                if (!imageUrl || imageUrl === 'undefined' || imageUrl === 'null') {
                    imageUrl = '/images/placeholder.jpg';
                }

                const div = document.createElement('div');
                div.className = 'flex gap-4 items-start py-4 border-b border-slate-100 last:border-0';
                div.setAttribute('data-product-id', item.id);
                div.innerHTML = `
                    <img src="${sanitize(imageUrl)}"
                         class="w-20 h-20 object-cover rounded-lg bg-slate-100 shrink-0"
                         alt="${sanitize(item.name)}"
                         loading="lazy"
                         onerror="this.src='/images/placeholder.jpg'; this.onerror=null;">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-slate-800 truncate">${sanitize(item.name)}</p>
                        <p class="text-xs text-slate-500 mb-2">${formatRupiah(item.price)}</p>
                        <div class="flex items-center gap-2">
                            <button class="qty-btn w-7 h-7 border border-slate-200 rounded-lg text-slate-600
                                         flex items-center justify-center hover:bg-slate-50 text-sm transition"
                                    data-product-id="${item.id}" data-action="dec">&minus;</button>
                            <span class="qty-value text-sm font-medium text-slate-700 w-6 text-center">${item.qty}</span>
                            <button class="qty-btn w-7 h-7 border border-slate-200 rounded-lg text-slate-600
                                         flex items-center justify-center hover:bg-slate-50 text-sm transition"
                                    data-product-id="${item.id}" data-action="inc">+</button>
                            <button class="remove-btn ml-auto text-slate-400 hover:text-red-500 transition p-1"
                                    data-product-id="${item.id}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                fragment.appendChild(div);
            });

            container.innerHTML = '';
            container.appendChild(fragment);
        },

        bindEvents() {
            const itemsContainer = this.elements.items;
            if (!itemsContainer) return;

            itemsContainer.addEventListener('click', (e) => {
                const btn = e.target.closest('.qty-btn, .remove-btn');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();

                const productId = btn.dataset.productId;
                if (btn.classList.contains('remove-btn')) {
                    this.handleRemove(productId);
                } else if (btn.classList.contains('qty-btn')) {
                    const delta = btn.dataset.action === 'inc' ? 1 : -1;
                    store.updateQtyById(productId, delta);
                }
            });

            const emptyCartBtn = document.getElementById('empty-cart-btn');
            if (emptyCartBtn) {
                emptyCartBtn.addEventListener('click', () => {
                    const swal = window.Swal;
                    if (swal && typeof swal.fire === 'function') {
                        swal.fire({
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
                    } else if (confirm('Kosongkan keranjang?')) {
                        store.clearCart();
                        showToast('Keranjang dikosongkan', 'info');
                    }
                });
            }
        },

        handleRemove(productId) {
            const item = store.getCart().find(i => i.id == productId);
            if (!item) return;

            const swal = window.Swal;
            if (swal && typeof swal.fire === 'function') {
                swal.fire({
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
                        store.removeFromCartById(productId);
                        showToast('Produk dihapus dari keranjang', 'info');
                    }
                });
            } else if (confirm(`Hapus "${item.name}"?`)) {
                store.removeFromCartById(productId);
                showToast('Produk dihapus dari keranjang', 'info');
            }
        },

        open() {
            const { sidebar, overlay } = this.elements;
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.setAttribute('aria-hidden', 'false');
            BodyScroll.lock();
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
        elements: null,

        init() {
            this.cacheElements();
            this.render();
            this.updateBadge();
            store.on('wishlist', () => {
                this.updateBadge();
                this.render();
            });

            const clearBtn = document.getElementById('clear-wishlist-btn');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    const swal = window.Swal;
                    if (swal && typeof swal.fire === 'function') {
                        swal.fire({
                            title: 'Kosongkan wishlist?',
                            text: 'Semua produk akan dihapus dari wishlist.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, kosongkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                store.wishlist = [];
                                store._saveWishlist();
                                store._emit('wishlist');
                                showToast('Wishlist dikosongkan', 'info');
                            }
                        });
                    }
                });
            }

            document.body.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.wishlist-remove');
                if (removeBtn) {
                    e.preventDefault();
                    const id = removeBtn.dataset.productId;
                    const product = store.getWishlist().find(i => String(i.id) === String(id));
                    if (!product) return;
                    store.toggleWishlist(product);
                    showToast('Dihapus dari wishlist', 'info');
                    return;
                }

                const addBtn = e.target.closest('.wishlist-add-to-cart');
                if (addBtn) {
                    e.preventDefault();
                    const id = addBtn.dataset.productId;
                    const product = store.getWishlist().find(i => String(i.id) === String(id));
                    if (!product) return;
                    store.addToCart(product);
                    CartUI.open();
                    showToast('Produk ditambahkan ke keranjang', 'success');
                }
            });
        },

        cacheElements() {
            this.elements = {
                items: document.getElementById('wishlist-items'),
                empty: document.getElementById('wishlist-empty'),
                footer: document.getElementById('wishlist-footer')
            };
        },

        render() {
            const { items, empty, footer } = this.elements || {};
            if (!items) return;

            const wishlist = store.getWishlist();
            const isEmpty = wishlist.length === 0;

            if (empty) empty.classList.toggle('hidden', !isEmpty);
            if (footer) footer.classList.toggle('hidden', isEmpty);

            items.innerHTML = '';
            if (isEmpty) {
                if (empty) empty.classList.remove('hidden');
                return;
            }

            const fragment = document.createDocumentFragment();
            wishlist.forEach((item) => {
                const imgSrc = item.img || item.image || '/images/placeholder.jpg';
                const row = document.createElement('div');
                row.className = 'cart-item animate-fade-in';
                row.innerHTML = `
                    <img src="${sanitize(imgSrc)}"
                         class="w-20 h-20 object-cover rounded-lg bg-slate-100 shrink-0"
                         alt="${sanitize(item.name)}" loading="lazy"
                         onerror="this.src='/images/placeholder.jpg'; this.onerror=null;" />
                    <div class="flex-1 min-w-0 cart-item-details">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="cart-item-title truncate">${sanitize(item.name)}</p>
                                <p class="cart-item-price">${formatRupiah(item.price)}</p>
                            </div>
                            <button class="remove-btn wishlist-remove ml-auto text-slate-400 hover:text-red-500 transition p-1"
                                    aria-label="Hapus dari wishlist"
                                    data-product-id="${sanitize(String(item.id))}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn-outline-sage mt-3 w-full py-2 text-xs font-sans font-medium rounded-none wishlist-add-to-cart"
                                    data-product-id="${sanitize(String(item.id))}">
                                TAMBAHKAN KE KERANJANG
                            </button>
                        </div>
                    </div>
                `;
                fragment.appendChild(row);
            });
            items.appendChild(fragment);
        },

        updateBadge() {
            const badge = document.querySelector(SELECTORS.WISHLIST_BADGE);
            if (!badge) return;
            const count = store.getWishlist().length;
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.toggle('hidden', count === 0);
        },

        toggleFromCard(btn) {
            if (!btn) return;
            const priceStr = btn.dataset.productPrice;
            let price = 0;
            if (priceStr) {
                const cleaned = priceStr.toString().replace(/[^0-9]/g, '');
                price = parseInt(cleaned);
                if (isNaN(price)) price = 0;
            }

            const product = {
                id: btn.dataset.productId,
                name: btn.dataset.productName,
                price: price,
                img: btn.dataset.productImg
            };
            if (!product.name && btn.closest('[data-product-id]')) {
                const card = btn.closest('[data-product-id]');
                product.name = card.dataset.name;
                const cardPrice = card.dataset.price;
                product.price = cardPrice ? parseInt(cardPrice.toString().replace(/[^0-9]/g, '')) : 0;
                product.img = card.dataset.img;
            }
            const added = store.toggleWishlist(product);
            const svg = btn.querySelector('svg');
            if (added) {
                btn.classList.add('text-red-500', 'bg-red-50');
                btn.classList.remove('text-sage-600', 'bg-sage-100');
                svg?.setAttribute('fill', 'currentColor');
                svg?.setAttribute('stroke', 'none');
            } else {
                btn.classList.remove('text-red-500', 'bg-red-50');
                btn.classList.add('text-sage-600', 'bg-sage-100');
                svg?.setAttribute('fill', 'none');
                svg?.setAttribute('stroke', 'currentColor');
            }
            showToast(added ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist', added ? 'success' : 'info');
        },

        toggleFromDetail(productId, iconEl) {
            if (!window.currentProduct) return;
            const product = {
                id: productId,
                name: window.currentProduct.name,
                price: window.currentProduct.price,
                img: window.currentProduct.image || window.currentProduct.img
            };
            const added = store.toggleWishlist(product);
            if (iconEl) {
                const svg = iconEl.querySelector('svg');
                if (added) {
                    iconEl.classList.add('text-red-500');
                    svg?.setAttribute('fill', 'currentColor');
                } else {
                    iconEl.classList.remove('text-red-500');
                    svg?.setAttribute('fill', 'none');
                }
            }
            showToast(added ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist', added ? 'success' : 'info');
        }
    };

    // ============================================================
    // 6. PRODUCT DETAIL MODULE
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
            // Fungsi global untuk onclick di blade
            window.switchImg = (el) => {
                document.querySelectorAll('.thumb-item').forEach(t =>
                    t.classList.remove('active', 'ring-2', 'ring-sage-500'));
                el.classList.add('active', 'ring-2', 'ring-sage-500');

                const mainImg = document.querySelector('#main-img');
                const newSrc = el.dataset.src || el.querySelector('img')?.src;
                if (mainImg && newSrc) {
                    mainImg.style.opacity = '0';
                    setTimeout(() => {
                        mainImg.src = newSrc;
                        mainImg.style.opacity = '1';
                        mainImg.style.transition = 'opacity 0.15s ease';
                    }, 150);
                }
            };

            // Event listener approach (backup)
            document.querySelectorAll('.thumb-item').forEach(thumb => {
                thumb.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.switchImg(thumb);
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
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.getElementById(`tab-${tabName}`)?.classList.remove('hidden');
                document.querySelectorAll('.tab-btn').forEach(b =>
                    b.classList.remove('active', 'text-emerald-600', 'border-emerald-600'));
                btn?.classList.add('active', 'text-emerald-600', 'border-emerald-600');
            };
        },
        bindVariants() {
            window.selectSwatch = (el) => {
                document.querySelectorAll('.swatch').forEach(s =>
                    s.classList.remove('active', 'ring-2', 'ring-offset-2'));
                el.classList.add('active', 'ring-2', 'ring-offset-2', 'ring-emerald-500');
                const label = document.getElementById('swatch-label');
                if (label) label.textContent = el.dataset.name;
            };
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
    // 7. SEARCH MODULE
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
            const searchBtn = document.getElementById('search-btn');
            if (searchBtn) {
                searchBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.openSearch();
                });
            }
            const closeSearchBtn = document.getElementById('search-close');
            if (closeSearchBtn) {
                closeSearchBtn.addEventListener('click', () => window.closeSearch());
            }
            const searchOverlay = document.querySelector('#search-modal .modal-overlay');
            if (searchOverlay) {
                searchOverlay.addEventListener('click', () => window.closeSearch());
            }
        },
        bindInput() {
            const input = document.querySelector(SELECTORS.SEARCH_INPUT);
            if (!input) return;
            const handleSearch = debounce((query) => {
                if (query.length < 2) return;
                const safeQuery = encodeURIComponent(query.trim().toLowerCase());
                window.location.href = `/produk?search=${safeQuery}`;
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
    // 8. UI UTILITIES
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
                if (progressBar) {
                    const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
                    progressBar.style.width = `${Math.min(100, scrolled)}%`;
                }
                if (backToTop) {
                    backToTop.classList.toggle('visible', window.scrollY > CONFIG.SCROLL_THRESHOLD);
                }
            };
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
                if (!expanded) menu.classList.add('animate-slide-down');
            });
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                });
            });
        },
        initReveal() {
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
                document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
            }
        },
        initTheme() {
            const savedTheme = localStorage.getItem(CONFIG.STORAGE_KEYS.THEME);
            if (savedTheme === 'dark') document.documentElement.classList.add('dark');
            document.querySelectorAll(SELECTORS.THEME_TOGGLES).forEach(toggle => {
                toggle?.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem(CONFIG.STORAGE_KEYS.THEME, isDark ? 'dark' : 'light');
                });
            });
        }
    };

    // ============================================================
    // 9. GLOBAL EVENT HANDLERS
    // ============================================================
    const GlobalHandlers = {
        init() {
            this.bindCart();
            this.bindKeyboard();
            this.bindClickOutside();
        },

        bindCart() {
            const openCartBtn = document.getElementById('open-cart');
            if (openCartBtn) {
                openCartBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    CartUI.open();
                });
            }

            const closeCartBtn = document.getElementById('close-cart');
            if (closeCartBtn) {
                closeCartBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    CartUI.close();
                });
            }

            const cartOverlay = document.querySelector(SELECTORS.CART_OVERLAY);
            if (cartOverlay) {
                cartOverlay.addEventListener('click', () => CartUI.close());
            }

            // Handle data-add-to-cart attribute buttons (HTML-based approach)
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-add-to-cart]');
                if (!btn) return;
                e.preventDefault();

                const card = btn.closest('[data-product-id]');
                if (!card) return;

                const parsePrice = (val) => {
                    if (!val || val === 'null' || val === 'undefined' || val === '') return 0;
                    const cleaned = val.toString().replace(/[^0-9]/g, '');
                    const parsed = parseInt(cleaned);
                    return isNaN(parsed) ? 0 : parsed;
                };

                const product = {
                    id: card.dataset.productId || Date.now(),
                    name: card.dataset.name || 'Produk',
                    price: parsePrice(card.dataset.price),
                    img: card.dataset.img || '/images/placeholder.jpg',
                    category: card.dataset.category || 'Umum'
                };

                store.addToCart(product);
                CartUI.open();
                showToast(`"${product.name}" ditambahkan ke keranjang`, 'success');
            });

            // addToCartMain: dipakai di halaman detail produk
            window.addToCartMain = () => {
                if (!window.currentProduct) {
                    showToast('Data produk tidak ditemukan', 'error');
                    return;
                }
                const qty = parseInt(document.querySelector(SELECTORS.QTY_DISPLAY)?.textContent) || 1;
                store.addToCart(window.currentProduct, qty);
                CartUI.open();
                showToast(`${qty}x "${window.currentProduct.name}" ditambahkan`, 'success');
            };

            // addToCart: dipakai via onclick di blade (product-card, dll)
            window.addToCart = (product) => {
                if (!product || !product.name) {
                    showToast('Data produk tidak valid', 'error');
                    return;
                }

                const parsePrice = (val) => {
                    if (typeof val === 'string') {
                        return parseInt(val.replace(/[^0-9]/g, '')) || 0;
                    }
                    return parseInt(val) || 0;
                };

                const validProduct = {
                    ...product,
                    price: parsePrice(product.price),
                    id: product.id || Date.now(),
                    img: product.img || product.image || '/images/placeholder.jpg',
                };

                store.addToCart(validProduct);
                CartUI.open();
                showToast(`"${validProduct.name}" ditambahkan ke keranjang`, 'success');
            }; // <-- CLOSING BRACE YANG HILANG SEBELUMNYA
        },

        bindKeyboard() {
            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                const searchModal = document.querySelector(SELECTORS.SEARCH_MODAL);
                if (searchModal && !searchModal.classList.contains('hidden')) {
                    window.closeSearch();
                } else if (!document.querySelector(SELECTORS.CART_SIDEBAR)?.classList.contains('translate-x-full')) {
                    CartUI.close();
                } else {
                    window.closeZoom?.();
                }
            });
        },

        bindClickOutside() {
            document.addEventListener('click', (e) => {
                const searchModal = document.querySelector(SELECTORS.SEARCH_MODAL);
                if (searchModal && !searchModal.classList.contains('hidden')) {
                    const content = searchModal.querySelector('.modal-content');
                    if (content && !content.contains(e.target)) window.closeSearch();
                }
            });
        }
    };

    // ============================================================
    // 10. EXPOSE PUBLIC API
    // ============================================================
    window.Bewood = {
        addToCart: (product, qty) => store.addToCart(product, qty),
        openCart: () => CartUI.open(),
        closeCart: () => CartUI.close(),
        toggleWishlist: (product) => store.toggleWishlist(product),
        isInWishlist: (id) => store.isInWishlist(id),
        formatRupiah,
        showToast
    };

    Object.assign(window, {
        formatRupiah,
        showToast,
        addToCartMain: () => window.addToCartMain?.(),
        addToCart: (product) => window.addToCart?.(product),
        toggleWishlistDetail: (id, icon) => WishlistUI.toggleFromDetail(id, icon),
        toggleWishlistFromCard: (btn) => WishlistUI.toggleFromCard(btn),
        changeQty: (delta) => window.changeQty?.(delta),
        switchTab: (tabName, btn) => window.switchTab?.(tabName, btn),
        selectSwatch: (el) => window.selectSwatch?.(el),
        selectFinish: (el) => window.selectFinish?.(el),
        openZoom: () => window.openZoom?.(),
        closeZoom: () => window.closeZoom?.(),
        openSearch: () => window.openSearch?.(),
        closeSearch: () => window.closeSearch?.()
    });

    // ============================================================
    // 11. INITIALIZATION
    // ============================================================
    function init() {
        CartUI.init();
        WishlistUI.init();
        ProductDetail.init();
        Search.init();
        UIUtils.init();
        GlobalHandlers.init();
        console.log('✅ BeWood Frontend initialized');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
