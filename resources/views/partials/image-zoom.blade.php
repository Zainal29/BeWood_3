<div id="zoom-overlay"
     class="fixed inset-0 bg-black/90 z-[100] opacity-0 pointer-events-none flex items-center justify-center p-4 transition-opacity duration-300 cursor-pointer"
     onclick="closeZoom()">
    <img id="zoom-img" src="" alt="Zoom" class="max-w-full max-h-full object-contain cursor-default"
         onclick="event.stopPropagation()" />
    <button onclick="event.stopPropagation(); closeZoom();"
            class="absolute top-4 right-4 text-white/70 hover:text-white text-2xl font-light bg-black/30 rounded-full w-10 h-10 flex items-center justify-center transition-all hover:bg-black/50 z-10">
        ✕
    </button>
</div>

<script>
    // Fungsi-fungsi zoom didefinisikan langsung di sini
    function openZoom() {
        const src = document.getElementById('main-img')?.src;
        const zoomImg = document.getElementById('zoom-img');
        const overlay = document.getElementById('zoom-overlay');
        if (!src || !zoomImg || !overlay) return;
        zoomImg.src = src;
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    }

    function closeZoom() {
        const overlay = document.getElementById('zoom-overlay');
        if (overlay) {
            overlay.classList.add('opacity-0');
            overlay.classList.add('pointer-events-none');
            overlay.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }
    }
</script>
