/* =========================================================
   KOPOU — premium interaction layer
   Adapted from the static preview to run against real,
   server-rendered Blade markup: product cards are already
   in the DOM (via <x-product-card>), each carrying its data
   in a data-product="{...}" JSON attribute, so this file
   no longer needs (or ships) a hardcoded product array.
========================================================= */

const isTouch = matchMedia("(hover: none), (pointer: coarse)").matches;
const prefersReduced = matchMedia("(prefers-reduced-motion: reduce)").matches;
if (isTouch) document.body.classList.add("touch");
if (prefersReduced) document.body.classList.add("reduced-motion");

const hasGSAP = typeof window.gsap !== "undefined";
if (hasGSAP && window.ScrollTrigger) gsap.registerPlugin(ScrollTrigger);

/* =========================================================
   LENIS SMOOTH SCROLL
========================================================= */
let lenis;
if (!prefersReduced && typeof window.Lenis !== "undefined") {
  lenis = new Lenis({ duration: 1.1, easing: (t) => 1 - Math.pow(1 - t, 3), smoothWheel: true });
  if (hasGSAP) {
    lenis.on("scroll", () => window.ScrollTrigger && ScrollTrigger.update());
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
  } else {
    const raf = (time) => { lenis.raf(time); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initPreloader();
  initHeroSequence();
  initHeroCanvas();
  initNavScroll();
  initMobileNav();
  initSearchOverlay();
  initCartDrawer();
  initScrollReveal();
  initCounters();
  initCustomCursor();
  initMagnetic();
  initProductCards();
  initQuickView();
  initHeritagePin();
  initReviewDrag();
  initToast();

  if (hasGSAP) {
    initSplitHeadings();
    initRevealPrimitives();
    initStaggerGrids();
    initThreadDrift();
    initAmbientDrift();
    initOriginTrackReveal();
    initCardScrollTilt();
    initFooterMarkParallax();
    initCategoryParallax();
  }
});

/* ---------- Heading split + clip reveal ---------- */
function initSplitHeadings() {
  document.querySelectorAll("[data-split-heading]").forEach((h) => {
    const words = h.textContent.trim().split(/\s+/);
    h.innerHTML = words.map((w) => `<span class="split-heading" style="display:inline-block;"><span class="split-inner" style="display:inline-block;">${w}</span></span>`).join(" ");
    const inners = h.querySelectorAll(".split-inner");
    if (prefersReduced) { gsap.set(inners, { y: 0, opacity: 1 }); return; }
    gsap.set(inners, { y: "115%", opacity: 0, rotate: 1.5 });
    gsap.to(inners, {
      y: 0, opacity: 1, rotate: 0,
      duration: 1, ease: "power4.out", stagger: 0.045,
      scrollTrigger: { trigger: h, start: "top 88%", toggleActions: "play none none reverse" }
    });
  });
}

/* ---------- Generic reveal primitives: fade/slide/scale/blur ---------- */
function initRevealPrimitives() {
  const els = document.querySelectorAll("[data-rv]");
  if (!els.length) return;
  if (prefersReduced) { els.forEach((el) => gsap.set(el, { opacity: 1, x: 0, y: 0, scale: 1, filter: "blur(0px)" })); return; }
  els.forEach((el) => {
    const isBlur = el.classList.contains("rv-blur");
    gsap.to(el, {
      opacity: 1, x: 0, y: 0, scale: 1,
      filter: isBlur ? "blur(0px)" : "none",
      duration: 1.1, ease: "power3.out",
      scrollTrigger: { trigger: el, start: "top 90%", toggleActions: "play none none reverse" }
    });
  });
}

/* ---------- Staggered grids: product cards, trust panels ---------- */
function initStaggerGrids() {
  document.querySelectorAll("[data-stagger-grid]").forEach((grid) => {
    const items = Array.from(grid.children);
    if (!items.length) return;
    if (prefersReduced) { gsap.set(items, { opacity: 1, y: 0, scale: 1 }); return; }
    gsap.set(items, { opacity: 0, y: 34, scale: 0.96 });
    ScrollTrigger.batch(items, {
      start: "top 92%",
      onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, scale: 1, duration: 1, ease: "back.out(1.5)", stagger: 0.08, overwrite: true }),
      onLeaveBack: (batch) => gsap.to(batch, { opacity: 0, y: 24, scale: 0.97, duration: 0.5, ease: "power2.in", stagger: 0.04, overwrite: true })
    });
  });
}

/* ---------- Decorative thread rules drift horizontally with scroll ---------- */
function initThreadDrift() {
  if (prefersReduced) return;
  document.querySelectorAll("[data-thread-drift]").forEach((el) => {
    gsap.to(el, {
      backgroundPositionX: "+=120",
      ease: "none",
      scrollTrigger: { trigger: el, start: "top bottom", end: "bottom top", scrub: 0.6 }
    });
  });
}

/* ---------- Ambient gradient drift inside dark sections ---------- */
function initAmbientDrift() {
  if (prefersReduced) return;
  document.querySelectorAll("[data-ambient-drift]").forEach((el) => {
    const section = el.closest("section");
    gsap.to(el, {
      xPercent: 6, yPercent: -4,
      ease: "none",
      scrollTrigger: { trigger: section || el, start: "top bottom", end: "bottom top", scrub: 1.2 }
    });
  });
}

/* ---------- Origin thread: nodes light up progressively as the strip enters view ---------- */
function initOriginTrackReveal() {
  const track = document.querySelector("[data-origin-track]");
  if (!track) return;
  const nodes = gsap.utils.toArray("[data-origin-node]");
  if (!nodes.length) return;
  if (prefersReduced) { nodes.forEach((n) => n.querySelector(".origin-node-dot").style.background = "var(--terracotta)"); return; }
  ScrollTrigger.create({
    trigger: track, start: "top 75%", end: "bottom 60%", scrub: 0.8,
    onUpdate: (self) => {
      const activeCount = Math.round(self.progress * nodes.length);
      nodes.forEach((n, i) => {
        gsap.to(n.querySelector(".origin-node-dot"), { backgroundColor: i < activeCount ? "var(--terracotta)" : "var(--paper)", scale: i < activeCount ? 1.15 : 1, duration: 0.3, overwrite: true });
      });
    }
  });
}

/* ---------- Subtle 3D perspective tilt on product/trust cards while scrolling ---------- */
function initCardScrollTilt() {
  if (prefersReduced || isTouch) return;
  gsap.utils.toArray(".product-grid, .trust-grid").forEach((grid) => {
    gsap.set(grid.querySelectorAll(".product-card, .trust-item"), { transformPerspective: 900, transformOrigin: "center" });
    ScrollTrigger.create({
      trigger: grid, start: "top bottom", end: "bottom top",
      onUpdate: (self) => {
        const dir = self.direction;
        const cards = grid.querySelectorAll(".product-card, .trust-item");
        cards.forEach((c) => {
          if (c.matches(":hover")) return;
          gsap.to(c, { rotateX: dir === 1 ? -1.4 : 1.4, duration: 0.6, ease: "power2.out", overwrite: "auto" });
        });
      },
      onLeave: () => gsap.to(grid.querySelectorAll(".product-card, .trust-item"), { rotateX: 0, duration: 0.6 }),
      onLeaveBack: () => gsap.to(grid.querySelectorAll(".product-card, .trust-item"), { rotateX: 0, duration: 0.6 })
    });
  });
}

/* ---------- Category panel images: slow parallax pan even without hover ---------- */
function initCategoryParallax() {
  if (prefersReduced) return;
  document.querySelectorAll(".cat-panel img").forEach((img) => {
    gsap.fromTo(img, { yPercent: -6 }, {
      yPercent: 6, ease: "none",
      scrollTrigger: { trigger: ".cat-rail", start: "top bottom", end: "bottom top", scrub: 0.7 }
    });
  });
}

/* ---------- Footer wordmark: giant drift + subtle scale on approach ---------- */
function initFooterMarkParallax() {
  const mark = document.querySelector("[data-footer-mark]");
  if (!mark || prefersReduced) return;
  gsap.fromTo(mark, { xPercent: 4, opacity: 0.02 }, {
    xPercent: 0, opacity: 0.045, ease: "none",
    scrollTrigger: { trigger: ".footer", start: "top bottom", end: "top 30%", scrub: 0.8 }
  });
}

/* ---------- Preloader ---------- */
function initPreloader() {
  const pre = document.querySelector("[data-preloader]");
  if (!pre) return;
  const hide = () => setTimeout(() => { pre.classList.add("loaded"); if (window.ScrollTrigger) ScrollTrigger.refresh(); }, 900);
  if (document.readyState === "complete") hide();
  else window.addEventListener("load", hide);
}

/* ---------- Hero cinematic text sequence ---------- */
function initHeroSequence() {
  if (!hasGSAP) return;
  const tl = gsap.timeline({ delay: prefersReduced ? 0 : 1.15, defaults: { ease: "power3.out" } });
  tl.to("[data-hero-anim='eyebrow']", { opacity: 1, duration: 0.6 })
    .to("[data-hero-anim='line']", { y: 0, duration: 1, stagger: 0.12 }, "-=0.3")
    .to("[data-hero-anim='sub']", { opacity: 1, duration: 0.7 }, "-=0.5")
    .to("[data-hero-anim='cta']", { opacity: 1, duration: 0.7 }, "-=0.5")
    .to("[data-hero-anim='stats']", { opacity: 1, duration: 0.7 }, "-=0.5");

  gsap.to(".hero-bg img", { scale: 1, duration: 2.4, ease: "power2.out", delay: prefersReduced ? 0 : 0.6 });

  const hero = document.querySelector("[data-hero]");
  const glow = document.querySelector("[data-hero-spotlight]");
  if (hero && glow && !isTouch) {
    hero.addEventListener("mousemove", (e) => {
      const r = hero.getBoundingClientRect();
      glow.style.setProperty("--gx", ((e.clientX - r.left) / r.width) * 100 + "%");
      glow.style.setProperty("--gy", ((e.clientY - r.top) / r.height) * 100 + "%");
      glow.style.opacity = "1";
    });
    hero.addEventListener("mouseleave", () => { glow.style.opacity = "0"; });
  }

  if (!prefersReduced && window.ScrollTrigger) {
    gsap.to(".hero-bg img", { yPercent: 14, ease: "none", scrollTrigger: { trigger: ".hero", start: "top top", end: "bottom top", scrub: true } });
    gsap.to("[data-hero-canvas]", { yPercent: 8, ease: "none", scrollTrigger: { trigger: ".hero", start: "top top", end: "bottom top", scrub: true } });
    gsap.to(".hero-copy-inner", { yPercent: -18, opacity: 0.35, ease: "none", scrollTrigger: { trigger: ".hero", start: "top top", end: "bottom top", scrub: true } });
    gsap.to(".hero-scrollcue", { opacity: 0, y: 20, ease: "none", scrollTrigger: { trigger: ".hero", start: "top top", end: "20% top", scrub: true } });
  }
}

/* ---------- Hero canvas: floating tea-dust / gold particles ---------- */
function initHeroCanvas() {
  const canvas = document.querySelector("[data-hero-canvas]");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");
  let w, h, particles;
  const COUNT = prefersReduced ? 0 : (isTouch ? 26 : 55);

  function resize() {
    w = canvas.width = canvas.offsetWidth * devicePixelRatio;
    h = canvas.height = canvas.offsetHeight * devicePixelRatio;
  }
  function makeParticles() {
    particles = Array.from({ length: COUNT }, () => ({
      x: Math.random() * w,
      y: Math.random() * h,
      r: (Math.random() * 1.6 + 0.5) * devicePixelRatio,
      vy: (Math.random() * 0.25 + 0.06) * devicePixelRatio,
      vx: (Math.random() - 0.5) * 0.12 * devicePixelRatio,
      o: Math.random() * 0.5 + 0.15,
      gold: Math.random() > 0.5
    }));
  }
  function frame() {
    ctx.clearRect(0, 0, w, h);
    particles.forEach((p) => {
      p.y -= p.vy; p.x += p.vx;
      if (p.y < -10) { p.y = h + 10; p.x = Math.random() * w; }
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = p.gold ? `rgba(216,196,139,${p.o})` : `rgba(246,241,230,${p.o * 0.7})`;
      ctx.fill();
    });
    requestAnimationFrame(frame);
  }
  resize(); makeParticles();
  window.addEventListener("resize", () => { resize(); makeParticles(); });
  if (COUNT > 0) requestAnimationFrame(frame);
}

/* ---------- Nav blur/shrink on scroll ---------- */
function initNavScroll() {
  const nav = document.querySelector("[data-nav]");
  if (!nav) return;
  const update = () => { nav.classList.toggle("scrolled", window.scrollY > 40); };
  document.addEventListener("scroll", update, { passive: true });
  update();
}

/* ---------- Scroll progress bar ---------- */
(function initScrollProgress() {
  const bar = document.querySelector("[data-scroll-bar]");
  if (!bar) return;
  const update = () => {
    const h = document.documentElement;
    const scrollTop = h.scrollTop || document.body.scrollTop;
    const scrollHeight = (h.scrollHeight || document.body.scrollHeight) - h.clientHeight;
    bar.style.width = (scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0) + "%";
  };
  document.addEventListener("scroll", update, { passive: true });
  window.addEventListener("resize", update);
  update();
})();

/* ---------- Mobile drawer ---------- */
function initMobileNav() {
  const burger = document.querySelector("[data-burger]");
  const drawer = document.querySelector("[data-drawer]");
  const closeBtn = document.querySelector("[data-drawer-close]");
  if (!burger || !drawer) return;
  const open = () => drawer.classList.add("open");
  const close = () => drawer.classList.remove("open");
  burger.addEventListener("click", open);
  closeBtn?.addEventListener("click", close);
  drawer.querySelectorAll("a").forEach((a) => a.addEventListener("click", close));
}

/* ---------- Search overlay ---------- */
function initSearchOverlay() {
  const overlay = document.querySelector("[data-search-overlay]");
  const input = document.querySelector("[data-search-input]");
  if (!overlay) return;
  const open = () => { overlay.classList.add("open"); setTimeout(() => input?.focus(), 300); lenis?.stop(); };
  const close = () => { overlay.classList.remove("open"); lenis?.start(); };
  document.querySelectorAll("[data-search-open]").forEach((b) => b.addEventListener("click", open));
  document.querySelector("[data-search-close]")?.addEventListener("click", close);
  overlay.addEventListener("click", (e) => { if (e.target === overlay) close(); });
  document.addEventListener("keydown", (e) => { if (e.key === "Escape") close(); });

  // Live search: submits to the real /search route on Enter.
  const form = overlay.querySelector("[data-search-form]");
  form?.addEventListener("submit", (e) => {
    if (!input.value.trim()) e.preventDefault();
  });
}

/* ---------- Mini cart drawer (client-side preview cart; Phase 6 wires this to the server) ---------- */
const CART = [];
function initCartDrawer() {
  const overlay = document.querySelector("[data-cart-overlay]");
  const drawer = document.querySelector("[data-cart-drawer]");
  if (!overlay || !drawer) return;
  const open = () => { overlay.classList.add("open"); drawer.classList.add("open"); lenis?.stop(); };
  const close = () => { overlay.classList.remove("open"); drawer.classList.remove("open"); lenis?.start(); };
  document.querySelectorAll("[data-cart-open]").forEach((b) => b.addEventListener("click", open));
  document.querySelector("[data-cart-close]")?.addEventListener("click", close);
  overlay.addEventListener("click", close);
}

function addToCart(product) {
  const existing = CART.find((l) => l.id === product.id);
  if (existing) existing.qty += 1;
  else CART.push({ ...product, qty: 1 });
  renderCart();
}

function renderCart() {
  const body = document.querySelector("[data-cart-body]");
  const subtotalEl = document.querySelector("[data-cart-subtotal]");
  const countEl = document.querySelector("[data-cart-count]");
  if (!body) return;

  if (!CART.length) {
    body.innerHTML = '<div class="cart-empty">Your bag is empty. Add something from Assam.</div>';
  } else {
    body.innerHTML = CART.map((l) => {
      const price = l.sale_price || l.price;
      return `<div class="cart-line">
        <img src="${l.image}" alt="">
        <div class="cart-line-body">
          <h4>${l.name}</h4>
          <div class="cart-line-meta">Qty ${l.qty}</div>
          <div class="cart-line-price">&#8377;${(price * l.qty).toLocaleString("en-IN")}</div>
        </div>
      </div>`;
    }).join("");
  }
  const subtotal = CART.reduce((sum, l) => sum + (l.sale_price || l.price) * l.qty, 0);
  if (subtotalEl) subtotalEl.textContent = "₹" + subtotal.toLocaleString("en-IN");
  const totalQty = CART.reduce((s, l) => s + l.qty, 0);
  if (countEl) {
    countEl.textContent = String(totalQty);
    countEl.classList.add("bump");
    setTimeout(() => countEl.classList.remove("bump"), 220);
  }
}

/* ---------- Scroll reveal (fallback elements, non-GSAP) ---------- */
function initScrollReveal() {
  const items = document.querySelectorAll(".reveal");
  if (!items.length) return;
  if (!("IntersectionObserver" in window)) { items.forEach((el) => el.classList.add("in")); return; }
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add("in"); io.unobserve(entry.target); } });
  }, { threshold: 0.15, rootMargin: "0px 0px -60px 0px" });
  items.forEach((el) => io.observe(el));
}

/* ---------- Count-up stats ---------- */
function initCounters() {
  const counters = document.querySelectorAll("[data-counter]");
  if (!counters.length) return;
  if (!("IntersectionObserver" in window)) { counters.forEach(renderFinalCount); return; }
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => { if (entry.isIntersecting) { animateCounter(entry.target); io.unobserve(entry.target); } });
  }, { threshold: 0.4 });
  counters.forEach((el) => io.observe(el));
}
function renderFinalCount(el) {
  const target = parseFloat(el.getAttribute("data-counter"));
  const suffix = el.getAttribute("data-suffix") || "";
  const decimals = el.hasAttribute("data-decimals") ? parseInt(el.getAttribute("data-decimals"), 10) : 0;
  el.textContent = (decimals ? target.toFixed(decimals) : Math.round(target).toLocaleString("en-IN")) + suffix;
}
function animateCounter(el) {
  const target = parseFloat(el.getAttribute("data-counter"));
  const suffix = el.getAttribute("data-suffix") || "";
  const decimals = el.hasAttribute("data-decimals") ? parseInt(el.getAttribute("data-decimals"), 10) : 0;
  const duration = 1300; const start = performance.now();
  function tick(now) {
    const p = Math.min((now - start) / duration, 1);
    const eased = 1 - Math.pow(1 - p, 3);
    const val = target * eased;
    el.textContent = (decimals ? val.toFixed(decimals) : Math.round(val).toLocaleString("en-IN")) + suffix;
    if (p < 1) requestAnimationFrame(tick); else renderFinalCount(el);
  }
  requestAnimationFrame(tick);
}

/* ---------- Custom cursor ---------- */
function initCustomCursor() {
  if (isTouch || prefersReduced) return;
  const dot = document.querySelector("[data-cursor]");
  const ring = document.querySelector("[data-cursor-ring]");
  const label = document.querySelector("[data-cursor-label]");
  if (!dot || !ring) return;
  let rx = 0, ry = 0, tx = 0, ty = 0;
  window.addEventListener("mousemove", (e) => {
    dot.style.left = e.clientX + "px"; dot.style.top = e.clientY + "px";
    tx = e.clientX; ty = e.clientY;
  });
  function ringLoop() {
    rx += (tx - rx) * 0.18; ry += (ty - ry) * 0.18;
    ring.style.left = rx + "px"; ring.style.top = ry + "px";
    requestAnimationFrame(ringLoop);
  }
  ringLoop();
  document.querySelectorAll("[data-hoverable], .product-card, .cat-panel").forEach((el) => {
    el.addEventListener("mouseenter", () => {
      dot.classList.add("cur-hover"); ring.classList.add("cur-hover");
      if (label) label.textContent = el.getAttribute("data-cursor-text") || "";
    });
    el.addEventListener("mouseleave", () => { dot.classList.remove("cur-hover"); ring.classList.remove("cur-hover"); if (label) label.textContent = ""; });
  });
}

/* ---------- Magnetic buttons ---------- */
function initMagnetic() {
  if (isTouch || prefersReduced) return;
  document.querySelectorAll(".hero-cta-row .btn, .newsletter .btn").forEach((el) => {
    el.addEventListener("mousemove", (e) => {
      const r = el.getBoundingClientRect();
      const x = e.clientX - r.left - r.width / 2;
      const y = e.clientY - r.top - r.height / 2;
      el.style.transform = `translate(${x * 0.22}px, ${y * 0.35 - 2}px)`;
    });
    el.addEventListener("mouseleave", () => { el.style.transform = ""; });
  });
}

/* ---------- Product card tilt + light + wishlist + quick add ----------
   Reads product data from the card's own data-product="{...}" JSON
   attribute (rendered server-side by <x-product-card>), rather than
   from a hardcoded array.
------------------------------------------------------------------ */
function initProductCards() {
  document.querySelectorAll(".product-card").forEach(bindCard);
}

function getCardProduct(card) {
  try { return JSON.parse(card.dataset.product || "{}"); } catch (e) { return {}; }
}

function bindCard(card) {
  if (card.dataset.bound) return;
  card.dataset.bound = "1";
  if (!isTouch && !prefersReduced) {
    card.addEventListener("mousemove", (e) => {
      const r = card.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width - 0.5;
      const py = (e.clientY - r.top) / r.height - 0.5;
      const mx = px * 6, my = py * 6;
      card.style.transform = `perspective(900px) translate(${mx.toFixed(1)}px, ${(my - 4).toFixed(1)}px) rotateX(${(-py * 6).toFixed(2)}deg) rotateY(${(px * 8).toFixed(2)}deg)`;
      const light = card.querySelector(".product-media-light");
      if (light) { light.style.setProperty("--px", ((e.clientX - r.left) / r.width) * 100 + "%"); light.style.setProperty("--py", ((e.clientY - r.top) / r.height) * 100 + "%"); }
    });
    card.addEventListener("mouseleave", () => {
      if (hasGSAP) gsap.to(card, { x: 0, y: 0, rotateX: 0, rotateY: 0, duration: 0.6, ease: "power3.out", clearProps: "transform", overwrite: true });
      else card.style.transform = "";
    });
  }
  card.querySelector(".wishlist-btn")?.addEventListener("click", (e) => {
    e.preventDefault(); e.stopPropagation();
    const btn = e.currentTarget;
    const active = btn.classList.toggle("active");
    btn.setAttribute("aria-pressed", String(active));
    showToast(active ? "Added to wishlist" : "Removed from wishlist");
  });
  card.querySelector(".quick-add")?.addEventListener("click", (e) => {
    e.preventDefault(); e.stopPropagation();
    const btn = e.currentTarget;
    const product = getCardProduct(card);
    if (!product.id) return;
    flyToCart(btn);
    addToCart(product);
    showToast(`${product.name} added to cart`);
  });
}

function flyToCart(startEl) {
  const cartIcon = document.querySelector("[data-cart-count]");
  if (!cartIcon || !startEl) return;
  const startRect = startEl.getBoundingClientRect();
  const endRect = cartIcon.getBoundingClientRect();
  const dot = document.createElement("div");
  dot.className = "fly-dot";
  const startX = startRect.left + startRect.width / 2;
  const startY = startRect.top + startRect.height / 2;
  dot.style.left = startX + "px"; dot.style.top = startY + "px";
  dot.style.transform = "translate(-50%, -50%) scale(1)"; dot.style.opacity = "1";
  document.body.appendChild(dot);
  requestAnimationFrame(() => {
    const endX = endRect.left + endRect.width / 2;
    const endY = endRect.top + endRect.height / 2;
    dot.style.transform = `translate(${endX - startX - 7}px, ${endY - startY - 7}px) scale(0.2)`;
    dot.style.opacity = "0.15";
  });
  setTimeout(() => dot.remove(), 650);
}

/* ---------- Toast ---------- */
let toastTimer = null;
function initToast() {
  if (document.querySelector(".toast")) return;
  const toast = document.createElement("div");
  toast.className = "toast";
  toast.innerHTML = '<span class="dot"></span><span data-toast-text></span>';
  document.body.appendChild(toast);
}
function showToast(message) {
  const toast = document.querySelector(".toast");
  const text = document.querySelector("[data-toast-text]");
  if (!toast || !text) return;
  text.textContent = message;
  toast.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
}

/* ---------- Quick View modal ---------- */
function initQuickView() {
  const overlay = document.querySelector("[data-qv-overlay]");
  const modal = document.querySelector("[data-qv-modal]");
  if (!overlay) return;
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".quick-view");
    if (!btn) return;
    e.preventDefault();
    const card = btn.closest(".product-card");
    const p = getCardProduct(card);
    if (!p.id) return;
    renderQuickView(p);
    overlay.classList.add("open");
    lenis?.stop();
  });
  overlay.addEventListener("click", (e) => { if (e.target === overlay) closeQV(); });
  document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeQV(); });
  function closeQV() { overlay.classList.remove("open"); lenis?.start(); }

  function renderQuickView(p) {
    const hasDiscount = p.sale_price && p.sale_price < p.price;
    const pct = hasDiscount ? Math.round((1 - p.sale_price / p.price) * 100) : 0;
    modal.innerHTML = `
      <div class="qv-media"><img src="${p.image}" alt="${p.name}"></div>
      <div class="qv-body">
        <button class="qv-close" data-qv-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        <div class="qv-cat">${p.category || ""}</div>
        <h3 class="qv-name">${p.name}</h3>
        <div class="qv-rating"><span class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span>${Number(p.rating || 0).toFixed(1)} (${p.review_count || 0} reviews)</span></div>
        <div class="qv-price-row">
          <span class="qv-price">&#8377;${Number(hasDiscount ? p.sale_price : p.price).toLocaleString("en-IN")}</span>
          ${hasDiscount ? `<span class="product-price-old">&#8377;${Number(p.price).toLocaleString("en-IN")}</span><span class="product-discount">${pct}% off</span>` : ""}
        </div>
        <p class="qv-desc">${p.short_description || ""}</p>
        ${p.origin ? `<div class="qv-origin">
          <div class="qv-origin-label">Traced Origin</div>
          <div class="qv-origin-path">${p.origin}</div>
        </div>` : ""}
        <div class="qv-actions">
          <div class="qv-qty"><button data-qv-dec aria-label="Decrease quantity">−</button><span data-qv-qty>1</span><button data-qv-inc aria-label="Increase quantity">+</button></div>
          <a class="btn btn-outline" href="/products/${p.slug}" style="flex:0;">View Full Details</a>
          <button class="btn btn-primary" data-qv-add>Add to Cart</button>
        </div>
      </div>`;
    let qty = 1;
    modal.querySelector("[data-qv-close]").addEventListener("click", closeQV);
    modal.querySelector("[data-qv-inc]").addEventListener("click", () => { qty++; modal.querySelector("[data-qv-qty]").textContent = qty; });
    modal.querySelector("[data-qv-dec]").addEventListener("click", () => { qty = Math.max(1, qty - 1); modal.querySelector("[data-qv-qty]").textContent = qty; });
    modal.querySelector("[data-qv-add]").addEventListener("click", () => {
      for (let i = 0; i < qty; i++) addToCart(p);
      showToast(`${p.name} added to cart`);
      closeQV();
    });
  }
}

/* ---------- Heritage pinned storytelling ---------- */
function initHeritagePin() {
  const stage = document.querySelector("[data-heritage-stage]");
  if (!stage || !hasGSAP || !window.ScrollTrigger) return;
  const frames = gsap.utils.toArray(".heritage-media-frame");
  const copies = gsap.utils.toArray(".heritage-frame-copy");
  const dots = gsap.utils.toArray("[data-heritage-progress] span");
  if (isTouch || window.innerWidth <= 820) {
    frames.forEach((f, i) => f.classList.toggle("active", i === 0));
    return;
  }

  // Explicit initial state: frame 0 visible, rest hidden — don't rely on
  // the first ScrollTrigger onUpdate tick, which only fires once the user
  // actually scrolls into the pinned range.
  gsap.set(frames, { opacity: (i) => (i === 0 ? 1 : 0) });

  ScrollTrigger.create({
    trigger: stage,
    start: "top top",
    end: "bottom bottom",
    onUpdate: (self) => {
      const idx = Math.min(frames.length - 1, Math.floor(self.progress * frames.length));
      frames.forEach((f, i) => gsap.to(f, { opacity: i === idx ? 1 : 0, duration: 0.5, overwrite: true }));
      copies.forEach((c, i) => c.classList.toggle("active", i === idx));
      dots.forEach((d, i) => d.classList.toggle("active", i === idx));
    }
  });
}

/* ---------- Draggable testimonial rail ---------- */
function initReviewDrag() {
  const wrap = document.querySelector("[data-review-wrap]");
  const rail = document.querySelector("[data-review-rail]");
  if (!wrap || !rail) return;
  let isDown = false, startX, scrollLeft;

  wrap.addEventListener("pointerdown", (e) => {
    isDown = true;
    wrap.setPointerCapture(e.pointerId);
    startX = e.clientX; scrollLeft = wrap.scrollLeft;
    document.querySelector("[data-cursor-ring]")?.classList.add("cur-drag");
  });
  wrap.addEventListener("pointermove", (e) => {
    if (!isDown) return;
    const dx = e.clientX - startX;
    wrap.scrollLeft = scrollLeft - dx;
  });
  ["pointerup", "pointerleave", "pointercancel"].forEach((evt) => {
    wrap.addEventListener(evt, () => { isDown = false; document.querySelector("[data-cursor-ring]")?.classList.remove("cur-drag"); });
  });
  wrap.style.overflowX = "auto";
  wrap.style.scrollbarWidth = "none";
}
