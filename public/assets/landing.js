const fallbackProducts = [
  {
    name: "Premium Sesame Seed",
    category: "Agriculture",
    exporter: "Ganye Agro Cooperative",
    origin: "Ganye, Adamawa",
    moq: "10 MT",
    price: "Request quote",
    readiness: "92%",
    image: "https://images.unsplash.com/photo-1508747703725-719777637510?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Phytosanitary", "HS 120740"],
  },
  {
    name: "Dried Hibiscus Flower",
    category: "Food Processing",
    exporter: "Mubi Agro Exporters",
    origin: "Mubi, Adamawa",
    moq: "5 MT",
    price: "$1,180 / MT",
    readiness: "88%",
    image: "https://images.unsplash.com/photo-1597481499750-3e6b22637e12?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Organic", "Sorted"],
  },
  {
    name: "Finished Leather Hides",
    category: "Textiles",
    exporter: "Yola Leather Works",
    origin: "Yola, Adamawa",
    moq: "500 pcs",
    price: "Request quote",
    readiness: "81%",
    image: "https://images.unsplash.com/photo-1528404021824-577c0f3b0f4a?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Quality Report", "Packed"],
  },
  {
    name: "Raw Shea Butter",
    category: "Food Processing",
    exporter: "Numan Shea Processors",
    origin: "Numan, Adamawa",
    moq: "2 MT",
    price: "$2,450 / MT",
    readiness: "76%",
    image: "https://images.unsplash.com/photo-1611071526480-f6f8613f7d4b?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Lab Tested", "Batch Trace"],
  },
  {
    name: "Export Grade Sorghum",
    category: "Agriculture",
    exporter: "Guyuk Grain Cluster",
    origin: "Guyuk, Adamawa",
    moq: "25 MT",
    price: "$390 / MT",
    readiness: "84%",
    image: "https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Moisture Tested", "Bulk"],
  },
  {
    name: "Industrial Kaolin",
    category: "Minerals",
    exporter: "Adamawa Minerals Desk",
    origin: "Fufore, Adamawa",
    moq: "30 MT",
    price: "Request quote",
    readiness: "73%",
    image: "https://images.unsplash.com/photo-1518709268805-4e9042af2176?auto=format&fit=crop&w=900&q=80",
    badges: ["Verified", "Lab Analysis", "Permit Review"],
  },
];

const products = Array.isArray(window.marketplaceProducts)
  ? window.marketplaceProducts
  : fallbackProducts;

const productGrid = document.querySelector("#productGrid");
const searchInput = document.querySelector("#productSearch");
const categoryFilter = document.querySelector("#categoryFilter");
const heroMedia = document.querySelector(".hero-media");
const heroEyebrow = document.querySelector("[data-hero-eyebrow]");
const heroTitle = document.querySelector("[data-hero-title]");
const heroCopy = document.querySelector("[data-hero-copy]");
const heroPointA = document.querySelector("[data-hero-point-a]");
const heroPointB = document.querySelector("[data-hero-point-b]");
const heroPointC = document.querySelector("[data-hero-point-c]");
const heroDots = document.querySelector("[data-hero-dots]");

const heroSlides = [
  {
    eyebrow: "Adamawa State Export Facilitation",
    title: "Grow Adamawa exports with one trusted trade platform",
    copy: "Connect verified local exporters with global buyers through a cleaner digital trade flow for product discovery, quote requests, orders, logistics, and compliance.",
    image: "https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=1800&q=80",
    points: ["Verified exporters", "Compliance-first workflow", "Shipment visibility"],
  },
  {
    eyebrow: "Marketplace Access",
    title: "Show approved local products to regional and global buyers",
    copy: "Give Adamawa producers and SMEs a stronger online shelf with export-ready listings, richer product visibility, and better buyer discovery.",
    image: "https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=1800&q=80",
    points: ["Farmer-led production", "Approved product catalog", "Trade visibility"],
  },
  {
    eyebrow: "Trade Operations",
    title: "Move from quote to order and shipment with less friction",
    copy: "Keep RFQs, direct orders, shipment updates, and document review connected in one flow so exporters and buyers can work with more confidence.",
    image: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1800&q=80",
    points: ["RFQ response workflow", "Order coordination", "Admin oversight"],
  },
];

let heroSlideIndex = 0;
let heroSlideTimer = null;

function productCard(product) {
  return `
    <article class="product-card">
      <div class="product-image" style="background-image: url('${product.image}')"></div>
      <div class="product-body">
        <div class="product-title">
          <strong>${product.name}</strong>
          <span class="status active">${product.readiness}</span>
        </div>
        <small>${product.exporter}</small>
        <div class="badge-row">
          ${product.badges.map((badge) => `<span class="badge">${badge}</span>`).join("")}
        </div>
        <div class="product-meta">
          <span><strong>Origin</strong><br>${product.origin}</span>
          <span><strong>MOQ</strong><br>${product.moq}</span>
          <span><strong>Category</strong><br>${product.category}</span>
          <span><strong>Price</strong><br>${product.price}</span>
        </div>
        <div class="product-actions">
          <a class="btn primary" href="${product.quoteUrl || "#buyer"}">Request Quote</a>
          <a class="btn secondary" href="${product.orderUrl || "#buyer"}">Place Order</a>
        </div>
      </div>
    </article>
  `;
}

function renderProducts() {
  if (!productGrid) return;

  const query = searchInput?.value.trim().toLowerCase() || "";
  const category = categoryFilter?.value || "all";

  const filtered = products.filter((product) => {
    const matchesQuery = [product.name, product.exporter, product.origin]
      .join(" ")
      .toLowerCase()
      .includes(query);
    const matchesCategory = category === "all" || product.category === category;
    return matchesQuery && matchesCategory;
  });

  productGrid.innerHTML = filtered.length
    ? filtered.map(productCard).join("")
    : '<div class="empty-state"><strong>No approved products found</strong><span>Approved product listings will appear here after admin review.</span></div>';
}

function setActiveView() {
  const hash = window.location.hash.replace("#", "") || "home";
  const target = document.getElementById(hash) ? hash : "home";

  document.querySelectorAll(".view").forEach((view) => {
    view.classList.toggle("active", view.id === target);
  });

  document.querySelectorAll(".main-nav a").forEach((link) => {
    link.classList.toggle("active", link.getAttribute("href") === `#${target}`);
  });

  window.scrollTo({ top: 0, behavior: "auto" });
}

function renderHeroSlider() {
  if (!heroMedia || !heroTitle || !heroCopy || !heroDots) return;

  const slide = heroSlides[heroSlideIndex];
  heroMedia.style.backgroundImage = `url("${slide.image}")`;
  if (heroEyebrow) heroEyebrow.textContent = slide.eyebrow;
  heroTitle.textContent = slide.title;
  heroCopy.textContent = slide.copy;
  if (heroPointA) heroPointA.innerHTML = '<i data-lucide="badge-check"></i> ' + slide.points[0];
  if (heroPointB) heroPointB.innerHTML = '<i data-lucide="shield-check"></i> ' + slide.points[1];
  if (heroPointC) heroPointC.innerHTML = '<i data-lucide="ship"></i> ' + slide.points[2];

  heroDots.innerHTML = heroSlides
    .map(
      (_, index) =>
        `<button class="hero-dot${index === heroSlideIndex ? " active" : ""}" type="button" aria-label="Go to slide ${
          index + 1
        }" data-hero-dot="${index}"></button>`
    )
    .join("");

  heroDots.querySelectorAll("[data-hero-dot]").forEach((dot) => {
    dot.addEventListener("click", () => {
      heroSlideIndex = Number(dot.getAttribute("data-hero-dot")) || 0;
      renderHeroSlider();
      startHeroSlider();
    });
  });

  if (window.lucide) {
    window.lucide.createIcons();
  }
}

function startHeroSlider() {
  if (!heroMedia) return;
  window.clearInterval(heroSlideTimer);
  heroSlideTimer = window.setInterval(() => {
    heroSlideIndex = (heroSlideIndex + 1) % heroSlides.length;
    renderHeroSlider();
  }, 6000);
}

window.addEventListener("hashchange", setActiveView);
searchInput?.addEventListener("input", renderProducts);
categoryFilter?.addEventListener("change", renderProducts);

renderProducts();
setActiveView();
renderHeroSlider();
startHeroSlider();

window.addEventListener("load", () => {
  if (window.lucide) {
    window.lucide.createIcons();
  }
});
