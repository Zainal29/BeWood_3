/**
 * ============================================================
 * BEWOOD FRONTEND JAVASCRIPT
 * ============================================================
 * Fungsi-fungsi interaktif untuk:
 * - Keranjang belanja (localStorage)
 * - Wishlist (localStorage)
 * - Quick view modal
 * - Zoom gambar produk
 * - Pencarian (modal)
 * - Toggle tab, quantity, swatch, finishing, dll.
 * - Efek scroll, mobile menu, theme toggle
 * ============================================================
 */

// ============================================================
// 1. UTILITIES
// ============================================================

/** Format angka ke Rupiah */
function formatRupiah(n) {
    return "Rp " + n.toLocaleString("id-ID");
}

/** Tampilkan notifikasi toast */
function showToast(message, type = "success") {
    const container = document.getElementById("toast-container");
    if (!container) return;
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerHTML = `
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      ${
          type === "success"
              ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
              : type === "error"
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
      }
    </svg>
    <span class="text-sm font-sans">${message}</span>
  `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(10px)";
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// ============================================================
// 2. GLOBAL STATE (Cart & Wishlist)
// ============================================================

let state = {
    cart: JSON.parse(localStorage.getItem("vokta_cart")) || [],
    wishlist: JSON.parse(localStorage.getItem("vokta_wishlist")) || [],
    quickViewProduct: null,
};

function saveCart() {
    localStorage.setItem("vokta_cart", JSON.stringify(state.cart));
}

function saveWishlist() {
    localStorage.setItem("vokta_wishlist", JSON.stringify(state.wishlist));
}

// ============================================================
// 3. KERANJANG (CART)
// ============================================================

/** Perbarui tampilan keranjang di sidebar */
function updateCartUI() {
    const badge = document.getElementById("cart-badge");
    const emptyEl = document.getElementById("cart-empty");
    const footerEl = document.getElementById("cart-footer");
    const itemsEl = document.getElementById("cart-items");
    const totalEl = document.getElementById("cart-total");

    if (!itemsEl || !emptyEl || !footerEl || !totalEl) return;

    const totalQty = state.cart.reduce((s, i) => s + i.qty, 0);
    const totalPrice = state.cart.reduce((s, i) => s + i.price * i.qty, 0);

    if (badge) {
        if (totalQty > 0) {
            badge.textContent = totalQty > 9 ? "9+" : totalQty;
            badge.classList.remove("hidden");
        } else {
            badge.classList.add("hidden");
        }
    }

    if (state.cart.length === 0) {
        emptyEl.classList.remove("hidden");
        footerEl.classList.add("hidden");
    } else {
        emptyEl.classList.add("hidden");
        footerEl.classList.remove("hidden");
    }
    totalEl.textContent = formatRupiah(totalPrice);

    // Hapus semua item selain empty state
    Array.from(itemsEl.children).forEach((child) => {
        if (child.id !== "cart-empty") child.remove();
    });

    // Render ulang setiap item di keranjang
    state.cart.forEach((item, idx) => {
        const div = document.createElement("div");
        div.className =
            "flex gap-4 items-start py-4 border-b border-sage-100 last:border-0";
        div.innerHTML = `
      <img src="${item.img}" class="w-20 h-20 object-cover rounded bg-sage-100 shrink-0" alt="${item.name}" loading="lazy" />
      <div class="flex-1 min-w-0">
        <p class="font-sans text-sm text-sage-900 font-medium truncate">${item.name}</p>
        <p class="font-sans text-xs text-sage-400 mb-2">${formatRupiah(item.price)}</p>
        <div class="flex items-center gap-2">
          <button class="qty-btn w-7 h-7 border border-sage-200 rounded text-sage-600 flex items-center justify-center hover:bg-sage-100 text-sm transition-colors" data-idx="${idx}" data-action="dec">−</button>
          <span class="font-sans text-sm text-sage-800 w-6 text-center font-medium">${item.qty}</span>
          <button class="qty-btn w-7 h-7 border border-sage-200 rounded text-sage-600 flex items-center justify-center hover:bg-sage-100 text-sm transition-colors" data-idx="${idx}" data-action="inc">+</button>
          <button class="remove-btn ml-auto text-sage-300 hover:text-red-500 transition-colors p-1" data-idx="${idx}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </div>
    `;
        itemsEl.insertBefore(div, emptyEl);
    });

    // Attach event listener untuk +/- dan hapus item
    document.querySelectorAll(".qty-btn").forEach((btn) => {
        btn.removeEventListener("click", handleCartQty);
        btn.addEventListener("click", handleCartQty);
    });
    document.querySelectorAll(".remove-btn").forEach((btn) => {
        btn.removeEventListener("click", handleCartRemove);
        btn.addEventListener("click", handleCartRemove);
    });
}

/** Ubah jumlah item di keranjang */
function handleCartQty(e) {
    e.stopPropagation();
    const idx = parseInt(this.dataset.idx);
    if (this.dataset.action === "inc") {
        state.cart[idx].qty++;
    } else {
        state.cart[idx].qty--;
        if (state.cart[idx].qty <= 0) state.cart.splice(idx, 1);
    }
    saveCart();
    updateCartUI();
}

/** Hapus item dari keranjang (dengan SweetAlert) */
function handleCartRemove(e) {
    e.stopPropagation();
    const idx = parseInt(this.dataset.idx);
    const productName = state.cart[idx]?.name || 'produk';
    Swal.fire({
        title: `Hapus ${productName}?`,
        text: "Produk akan dihapus dari keranjang.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#456945',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            state.cart.splice(idx, 1);
            saveCart();
            updateCartUI();
            showToast('Produk dihapus dari keranjang', 'info');
        }
    });
}

/** Buka sidebar keranjang */
function openCart() {
    const sidebar = document.getElementById("cart-sidebar");
    const overlay = document.getElementById("cart-overlay");
    if (sidebar && overlay) {
        sidebar.classList.remove("translate-x-full");
        overlay.classList.remove("opacity-0", "pointer-events-none");
        overlay.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    }
}

/** Tutup sidebar keranjang */
function closeCart() {
    const sidebar = document.getElementById("cart-sidebar");
    const overlay = document.getElementById("cart-overlay");
    if (sidebar && overlay) {
        sidebar.classList.add("translate-x-full");
        overlay.classList.add("opacity-0", "pointer-events-none");
        overlay.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    }
}

/** Tambah dari card produk (di halaman landing) */
function addToCartFromCard(btn) {
    const card = btn.closest("[data-name]");
    if (!card) return;
    const product = {
        name: card.dataset.name,
        price: parseInt(card.dataset.price),
        img: card.dataset.img,
        qty: 1,
        id: Date.now(),
    };
    const existing = state.cart.find((i) => i.name === product.name);
    if (existing) {
        existing.qty++;
        showToast("Jumlah produk diperbarui", "success");
    } else {
        state.cart.push(product);
        showToast("Produk ditambahkan ke keranjang", "success");
    }
    saveCart();
    updateCartUI();
    openCart();
}

/** Tambah dari halaman detail produk (menggunakan window.currentProduct) */
function addToCartMain() {
    let name = "",
        price = 0,
        img = "";
    if (window.currentProduct) {
        name = window.currentProduct.name;
        price = window.currentProduct.price;
        img = window.currentProduct.image;
    } else {
        // Fallback (jika tidak ada window.currentProduct)
        const titleEl = document.querySelector(
            ".font-serif.text-3xl.lg\\:text-4xl",
        );
        if (titleEl) name = titleEl.innerText.split("\n")[0].trim();
        const priceEl = document.querySelector(
            ".font-serif.text-4xl.text-sage-900",
        );
        if (priceEl) {
            let priceText = priceEl.innerText
                .replace("Rp", "")
                .replace(/\./g, "")
                .trim();
            price = parseInt(priceText);
        }
        img = document.getElementById("main-img")?.src || "";
    }
    if (!name || !price) {
        showToast("Gagal menambahkan produk", "error");
        return;
    }
    const qty = parseInt(
        document.getElementById("qty-display")?.innerText || "1",
    );
    const existing = state.cart.find((i) => i.name === name);
    if (existing) {
        existing.qty += qty;
        showToast("Jumlah produk diperbarui", "success");
    } else {
        state.cart.push({ name, price, img, qty, id: Date.now() });
        showToast("Produk ditambahkan ke keranjang", "success");
    }
    saveCart();
    updateCartUI();
    openCart();
}

// ============================================================
// 4. WISHLIST
// ============================================================

/** Perbarui badge wishlist */
function updateWishlistUI() {
    const badge = document.getElementById("wishlist-badge");
    if (badge) {
        if (state.wishlist.length > 0) {
            badge.textContent =
                state.wishlist.length > 9 ? "9+" : state.wishlist.length;
            badge.classList.remove("hidden");
        } else {
            badge.classList.add("hidden");
        }
    }
}

/** Toggle wishlist dari card produk */
function toggleWishlist(btn) {
    const card = btn.closest("[data-name]");
    if (!card) return;
    const product = {
        name: card.dataset.name,
        price: parseInt(card.dataset.price),
        img: card.dataset.img,
        id: Date.now(),
    };
    const idx = state.wishlist.findIndex((i) => i.name === product.name);
    if (idx === -1) {
        state.wishlist.push(product);
        showToast("Ditambahkan ke wishlist", "success");
        btn.classList.add("text-gold");
    } else {
        state.wishlist.splice(idx, 1);
        showToast("Dihapus dari wishlist", "info");
        btn.classList.remove("text-gold");
    }
    saveWishlist();
    updateWishlistUI();
}

/** Toggle wishlist dari halaman detail produk */
function toggleWishlistDetail(e) {
    e.stopPropagation();
    const icon = document.getElementById("heart-icon-detail");
    if (!icon) return;
    const isWishlisted = icon.getAttribute("fill") === "#f87171";
    if (!isWishlisted) {
        if (!window.currentProduct) return;
        state.wishlist.push({
            name: window.currentProduct.name,
            price: window.currentProduct.price,
            img: window.currentProduct.image,
            id: Date.now(),
        });
        icon.setAttribute("fill", "#f87171");
        icon.setAttribute("stroke", "#f87171");
        showToast("Ditambahkan ke wishlist", "success");
    } else {
        state.wishlist = state.wishlist.filter(
            (i) => i.name !== (window.currentProduct?.name || ""),
        );
        icon.setAttribute("fill", "none");
        icon.setAttribute("stroke", "currentColor");
        showToast("Dihapus dari wishlist", "info");
    }
    saveWishlist();
    updateWishlistUI();
}

// ============================================================
// 5. QUICK VIEW MODAL
// ============================================================

/** Buka quick view dari card (event handler) */
function openQuickViewFromCard(btn) {
    const card = btn.closest("[data-name]");
    if (!card) return;
    openQuickView({
        name: card.dataset.name,
        price: parseInt(card.dataset.price),
        img: card.dataset.img,
        desc: card.dataset.desc,
        category: card.dataset.category,
        reviews: card.dataset.reviews,
    });
}

/** Tampilkan modal quick view */
function openQuickView(product) {
    const modal = document.getElementById("quick-view-modal");
    if (!modal) return;
    document.getElementById("qv-image").src = product.img;
    document.getElementById("qv-title").textContent = product.name;
    document.getElementById("qv-price").textContent = formatRupiah(
        product.price,
    );
    document.getElementById("qv-desc").textContent =
        product.desc || "Produk berkualitas premium.";
    document.getElementById("qv-category").textContent =
        product.category || "Furniture";
    document.getElementById("qv-reviews").textContent =
        `(${product.reviews || 0} ulasan)`;
    state.quickViewProduct = product;
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
}

/** Tutup modal quick view */
function closeQuickView() {
    const modal = document.getElementById("quick-view-modal");
    if (modal) modal.classList.add("hidden");
    document.body.style.overflow = "";
    state.quickViewProduct = null;
}

/** Ubah jumlah di quick view */
function quickViewChangeQty(delta) {
    const qtySpan = document.getElementById("qv-qty");
    if (!qtySpan) return;
    let q = parseInt(qtySpan.textContent);
    q = Math.max(1, q + delta);
    qtySpan.textContent = q;
}

/** Tambah dari quick view ke keranjang */
function addQuickViewToCart() {
    if (!state.quickViewProduct) return;
    const qty = parseInt(document.getElementById("qv-qty")?.textContent || "1");
    const existing = state.cart.find(
        (i) => i.name === state.quickViewProduct.name,
    );
    if (existing) {
        existing.qty += qty;
    } else {
        state.cart.push({ ...state.quickViewProduct, qty, id: Date.now() });
    }
    saveCart();
    updateCartUI();
    closeQuickView();
    openCart();
    showToast(`${qty}x ${state.quickViewProduct.name} ditambahkan`, "success");
}

// ============================================================
// 6. DETAIL PRODUK (Gallery, Tab, Swatch, Zoom, Qty)
// ============================================================

/** Ganti gambar utama saat thumbnail diklik */
function switchImg(thumbElement) {
    document
        .querySelectorAll(".thumb-item")
        .forEach((t) => t.classList.remove("active"));
    thumbElement.classList.add("active");
    const mainImg = document.getElementById("main-img");
    const newSrc =
        thumbElement.querySelector("img")?.dataset.src ||
        thumbElement.querySelector("img")?.src;
    if (mainImg && newSrc) {
        mainImg.classList.add("fade");
        setTimeout(() => {
            mainImg.src = newSrc;
            mainImg.classList.remove("fade");
        }, 200);
    }
}

/** Ubah jumlah produk di halaman detail */
function changeQty(delta) {
    let currentQty = parseInt(
        document.getElementById("qty-display")?.innerText || "1",
    );
    currentQty = Math.max(1, Math.min(5, currentQty + delta));
    document.getElementById("qty-display").innerText = currentQty;
}

/** Pindah tab di halaman detail (Spesifikasi, Deskripsi, Ulasan) */
function switchTab(name, btn) {
    document
        .querySelectorAll(".tab-content")
        .forEach((t) => t.classList.add("hidden"));
    document
        .querySelectorAll(".tab-btn")
        .forEach((b) => b.classList.remove("active"));
    document.getElementById("tab-" + name).classList.remove("hidden");
    if (btn) btn.classList.add("active");
}

/** Pilih varian warna (swatch) */
function selectSwatch(el) {
    document
        .querySelectorAll(".swatch")
        .forEach((s) => s.classList.remove("active"));
    el.classList.add("active");
    document.getElementById("swatch-label").textContent = el.dataset.name;
}

/** Pilih varian finishing */
function selectFinish(el) {
    document.querySelectorAll(".finish-btn").forEach((b) => {
        b.classList.remove("border-sage-700", "bg-sage-700", "text-white");
        b.classList.add("border-sage-200", "text-sage-600");
    });
    el.classList.add("border-sage-700", "bg-sage-700", "text-white");
    el.classList.remove("border-sage-200", "text-sage-600");
    document.getElementById("finish-label").textContent = el.dataset.name;
}

/** Buka modal zoom gambar */
function openZoom() {
    const src = document.getElementById("main-img")?.src;
    const zoomImg = document.getElementById("zoom-img");
    const overlay = document.getElementById("zoom-overlay");
    if (!src || !zoomImg || !overlay) return;
    zoomImg.src = src;
    overlay.classList.remove("opacity-0", "pointer-events-none");
    overlay.classList.add("opacity-100");
    document.body.style.overflow = "hidden";
}

/** Tutup modal zoom */
function closeZoom() {
    const overlay = document.getElementById("zoom-overlay");
    if (overlay) {
        overlay.classList.add("opacity-0");
        overlay.classList.add("pointer-events-none");
        document.body.style.overflow = "";
    }
}

// ============================================================
// 7. SEARCH MODAL
// ============================================================

/** Buka modal pencarian */
function openSearch() {
    const modal = document.getElementById("search-modal");
    const input = document.getElementById("search-input");
    if (modal && input) {
        modal.classList.remove("hidden");
        input.focus();
        document.body.style.overflow = "hidden";
    }
}

/** Tutup modal pencarian */
function closeSearch() {
    const modal = document.getElementById("search-modal");
    const input = document.getElementById("search-input");
    if (modal) modal.classList.add("hidden");
    if (input) input.value = "";
    document.body.style.overflow = "";
}

/** Handle input pencarian – redirect ke halaman pencarian (opsional) */
function handleSearchInput(e) {
    const query = e.target.value.toLowerCase().trim();
    const resultsEl = document.getElementById("search-results");
    if (!resultsEl) return;
    if (query.length < 2) {
        resultsEl.innerHTML =
            '<p class="text-sm text-sage-500 text-center py-8">Ketik minimal 2 karakter untuk mencari...</p>';
        return;
    }
    // Redirect ke halaman produk dengan parameter search
    window.location.href = `/products?search=${encodeURIComponent(query)}`;
}

// ============================================================
// 8. INITIALIZATION (DOMContentLoaded)
// ============================================================

document.addEventListener("DOMContentLoaded", () => {
    // ========== LOADING SCREEN ==========
    const loadingScreen = document.getElementById("loading-screen");
    if (loadingScreen)
        setTimeout(() => loadingScreen.classList.add("hidden"), 1200);

    // ========== SCROLL PROGRESS & BACK TO TOP ==========
    const progressBar = document.getElementById("scroll-progress");
    const backToTop = document.getElementById("back-to-top");
    window.addEventListener("scroll", () => {
        const scrolled =
            (window.scrollY /
                (document.documentElement.scrollHeight - window.innerHeight)) *
            100;
        if (progressBar) progressBar.style.width = `${scrolled}%`;
        if (backToTop)
            backToTop.classList.toggle("visible", window.scrollY > 400);
    });
    backToTop?.addEventListener("click", () =>
        window.scrollTo({ top: 0, behavior: "smooth" }),
    );

    // ========== MOBILE MENU ==========
    const mobileMenuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener("click", () => {
            const expanded =
                mobileMenuBtn.getAttribute("aria-expanded") === "true";
            mobileMenuBtn.setAttribute("aria-expanded", !expanded);
            mobileMenu.classList.toggle("hidden");
        });
        mobileMenu.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                mobileMenu.classList.add("hidden");
                mobileMenuBtn.setAttribute("aria-expanded", "false");
            });
        });
    }

    // ========== SCROLL REVEAL ANIMATION ==========
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: "0px 0px -50px 0px" },
    );
    document
        .querySelectorAll(".reveal")
        .forEach((el) => revealObserver.observe(el));

    // ========== CART SIDEBAR CONTROLS ==========
    document.getElementById("open-cart")?.addEventListener("click", openCart);
    document.getElementById("close-cart")?.addEventListener("click", closeCart);
    document
        .getElementById("cart-overlay")
        ?.addEventListener("click", closeCart);

    // ========== EMPTY CART BUTTON ==========
    document.getElementById("empty-cart-btn")?.addEventListener("click", () => {
        Swal.fire({
            title: 'Yakin ingin mengosongkan keranjang?',
            text: "Semua produk akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#456945',
            confirmButtonText: 'Ya, kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                state.cart = [];
                saveCart();
                updateCartUI();
                showToast('Keranjang dikosongkan', 'info');
            }
        });
    });

    // ========== QUICK VIEW CONTROLS ==========
    document
        .getElementById("qv-add-to-cart")
        ?.addEventListener("click", addQuickViewToCart);
    document.querySelectorAll(".qv-qty-btn").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const delta = btn.dataset.action === "inc" ? 1 : -1;
            quickViewChangeQty(delta);
        });
    });

    // ========== SEARCH MODAL ==========
    document
        .getElementById("search-btn")
        ?.addEventListener("click", openSearch);
    document
        .getElementById("search-input")
        ?.addEventListener("keypress", (e) => {
            if (e.key === "Enter") handleSearchInput(e);
        });

    // ========== ESCAPE KEY ==========
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeCart();
            closeQuickView();
            closeSearch();
            closeZoom();
        }
    });

    // ========== THEME TOGGLE (DARK MODE) ==========
    if (localStorage.getItem("vokta_theme") === "dark")
        document.documentElement.classList.add("dark");
    const themeToggles = [
        document.getElementById("theme-toggle"),
        document.getElementById("theme-toggle-mobile"),
    ].filter(Boolean);
    themeToggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            document.documentElement.classList.toggle("dark");
            localStorage.setItem(
                "vokta_theme",
                document.documentElement.classList.contains("dark")
                    ? "dark"
                    : "light",
            );
        });
    });

    // ========== INITIAL UI ==========
    updateCartUI();
    updateWishlistUI();
});

// ============================================================
// 9. EXPOSE GLOBALS (agar bisa dipanggil dari inline onclick)
// ============================================================

window.formatRupiah = formatRupiah;
window.showToast = showToast;
window.addToCartFromCard = addToCartFromCard;
window.addToCartMain = addToCartMain;
window.toggleWishlist = toggleWishlist;
window.toggleWishlistDetail = toggleWishlistDetail;
window.openQuickViewFromCard = openQuickViewFromCard;
window.closeQuickView = closeQuickView;
window.quickViewChangeQty = quickViewChangeQty;
window.switchImg = switchImg;
window.changeQty = changeQty;
window.switchTab = switchTab;
window.selectSwatch = selectSwatch;
window.selectFinish = selectFinish;
window.openSearch = openSearch;
window.closeSearch = closeSearch;
window.openZoom = openZoom;
window.closeZoom = closeZoom;
