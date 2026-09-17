/**
 * Product Detail Page JavaScript
 * Handles gallery, variant tabs, and panzoom functionality
 */

// Global variables (will be set from blade template)
let PD_VARIANTS = [];
let PD_PRODUCT_NAME = "";
let PD_PRODUCT_TITLE = "";
let PD_WA = "";
let pdActive = 0;
let pdGalIndex = 0;

/**
 * Initialize product detail data from JSON
 */
function pdInitData(variants, productName, productTitle, waNumber) {
    PD_VARIANTS = variants;
    PD_PRODUCT_NAME = productName;
    PD_PRODUCT_TITLE = productTitle;
    PD_WA = waNumber;
}

/**
 * Render gallery images and thumbnails
 */
function pdRenderGallery() {
    const v = PD_VARIANTS[pdActive];
    const imgs = v.images && v.images.length ? v.images : [];
    const main = document.getElementById("galMain");
    const thumbs = document.getElementById("galThumbs");

    if (imgs.length) {
        main.src = imgs[pdGalIndex] || imgs[0];
    }

    if (window.pdResetZoom) window.pdResetZoom();
    document.getElementById("galBadge").textContent = v.eyebrow || "";

    thumbs.innerHTML = "";
    imgs.forEach((src, i) => {
        const t = document.createElement("div");
        t.className = "pd-thumb" + (i === pdGalIndex ? " active" : "");

        const img = document.createElement("img");
        img.src = src;
        img.alt = "thumb";
        t.appendChild(img);

        t.onclick = () => {
            pdGalIndex = i;
            pdRenderGallery();
        };
        thumbs.appendChild(t);
    });
}

/**
 * Navigate gallery to next/previous image
 */
function pdGalStep(dir) {
    const imgs = PD_VARIANTS[pdActive].images || [];
    if (!imgs.length) return;
    pdGalIndex = (pdGalIndex + dir + imgs.length) % imgs.length;
    pdRenderGallery();
}

/**
 * Render product information
 */
function pdRenderInfo() {
    const v = PD_VARIANTS[pdActive];
    document.getElementById("infoEyebrow").textContent = v.eyebrow || "";
    document.getElementById("infoTitle").textContent = v.title || v.name || "";

    // Use textContent for description to prevent XSS
    const descEl = document.getElementById("infoDesc");
    descEl.textContent = v.description || "";

    const bc = document.getElementById("bcVariant");
    if (bc) bc.textContent = v.name || "";

    // Use DOM manipulation instead of innerHTML for specs
    const specs = document.getElementById("infoSpecs");
    specs.textContent = ""; // Clear content safely
    (v.facilities || []).forEach((f) => {
        const row = document.createElement("div");
        row.className = "pd-spec";

        const iconDiv = document.createElement("div");
        iconDiv.className = "pd-spec-ic";
        const icon = document.createElement("i");
        icon.className = "fa-solid " + f.icon;
        iconDiv.appendChild(icon);

        const nameDiv = document.createElement("div");
        nameDiv.className = "pd-spec-name";
        nameDiv.textContent = f.name;

        const valDiv = document.createElement("div");
        valDiv.className = "pd-spec-val";
        valDiv.textContent = f.value || "";

        row.appendChild(iconDiv);
        row.appendChild(nameDiv);
        row.appendChild(valDiv);
        specs.appendChild(row);
    });

    const cta = document.getElementById("infoCta");
    const msg =
        "Halo, saya tertarik dengan " +
        PD_PRODUCT_NAME +
        " tipe " +
        (v.name || "");
    cta.href = "https://wa.me/" + PD_WA + "?text=" + encodeURIComponent(msg);
}

/**
 * Select variant tab
 */
function pdSelectVariant(i) {
    pdActive = i;
    pdGalIndex = 0;
    document
        .querySelectorAll(".pd-vtab")
        .forEach((b) => b.classList.toggle("active", +b.dataset.index === i));
    pdRenderInfo();
    pdRenderGallery();
}

/**
 * Initialize Panzoom for main gallery image
 */
function pdInitPanzoom() {
    const el = document.getElementById("galMain");
    const wrap = el ? el.closest(".pd-gallery-main") : null;
    if (!el || !wrap || typeof Panzoom === "undefined") return;

    const pz = Panzoom(el, {
        maxScale: 4,
        minScale: 1,
        contain: "outside",
        cursor: "zoom-in",
        step: 0.4,
    });

    function syncZoomState() {
        const scale = pz.getScale();
        wrap.classList.toggle("is-zoomed", scale > 1.01);
    }

    // Zoom dengan scroll mouse (pakai wheel parent)
    wrap.addEventListener(
        "wheel",
        function (e) {
            e.preventDefault();
            pz.zoomWithWheel(e);
            syncZoomState();
        },
        {
            passive: false,
        },
    );

    // Double click / double tap -> reset
    el.addEventListener("dblclick", function () {
        pz.reset();
        syncZoomState();
    });

    el.addEventListener("panzoomchange", syncZoomState);

    // Reset saat gambar berganti (slide/thumb/variant)
    window.pdResetZoom = function () {
        pz.reset({
            animate: false,
        });
        syncZoomState();
    };
}

/**
 * Auto-hide zoom hint after 3 seconds
 */
function pdAutoHideZoomHint() {
    const zoomHint = document.querySelector(".pd-zoom-hint");
    if (zoomHint) {
        setTimeout(function () {
            zoomHint.style.transition = "opacity 0.5s ease";
            zoomHint.style.opacity = "0";
            setTimeout(function () {
                zoomHint.style.display = "none";
            }, 500);
        }, 3000);
    }
}

// Initialize on DOM ready
document.addEventListener("DOMContentLoaded", function () {
    pdRenderGallery();
    pdAutoHideZoomHint();
    pdInitPanzoom();
});
