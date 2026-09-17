/* ============================================
   PREMIUM ANIMATIONS - GSAP + ScrollTrigger
   Jago Bangun Persada Landing Page
   ============================================ */

lucide.createIcons();

gsap.registerPlugin(ScrollTrigger);

// Respect prefers-reduced-motion
const prefersReducedMotion = window.matchMedia(
  "(prefers-reduced-motion: reduce)"
).matches;

if (!prefersReducedMotion) {

  /* ------------------------------------------
     1. HERO SECTION
     - Parallax on hero background image
     - Staggered entrance for hero content
     ------------------------------------------ */

  // Parallax effect on hero background
  gsap.to(".hero-bg", {
    y: 120,
    ease: "none",
    scrollTrigger: {
      trigger: ".hero",
      start: "top top",
      end: "bottom top",
      scrub: 0.5,
    },
  });

  // Hero content entrance timeline
  const heroTl = gsap.timeline({
    defaults: { ease: "power3.out" },
  });

  heroTl
    .from(".hero-tag", {
      y: 40,
      opacity: 0,
      duration: 1,
    })
    .from(
      ".hero h1",
      {
        y: 60,
        opacity: 0,
        duration: 1.2,
      },
      "-=0.7"
    )
    .from(
      ".hero p",
      {
        y: 40,
        opacity: 0,
        duration: 1,
      },
      "-=0.8"
    )
    .from(
      ".hero-buttons",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.6"
    );

  /* ------------------------------------------
    2. ABOUT SECTION
     - Image fade-left with scale
     - Content fade-right staggered
     ------------------------------------------ */

  const aboutTl = gsap.timeline({
    defaults: { ease: "power3.out" },
    scrollTrigger: {
      trigger: ".about-section",
      start: "top 75%",
    },
  });

  aboutTl
    .from(".about-image", {
      x: -80,
      opacity: 0,
      scale: 0.95,
      duration: 1.2,
    })
    .from(
      ".about-label",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.8"
    )
    .from(
      ".about-content h2",
      {
        x: 50,
        opacity: 0,
        duration: 1,
      },
      "-=0.6"
    )
    .from(
      ".about-content p",
      {
        x: 40,
        opacity: 0,
        duration: 0.8,
        stagger: 0.15,
      },
      "-=0.6"
    )
    .from(
      ".about-btn",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.4"
    );

  /* ------------------------------------------
     4. CORE VALUES
     - Entire values bar fades up as one unit
     - Dividers scale in from center
     ------------------------------------------ */

  gsap.from(".values-wrapper", {
    y: 50,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".core-values",
      start: "top 85%",
    },
  });

  gsap.from(".value-divider", {
    scaleY: 0,
    opacity: 0,
    duration: 0.5,
    stagger: 0.1,
    ease: "power4.out",
    transformOrigin: "center center",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".core-values",
      start: "top 85%",
    },
  });

  /* ------------------------------------------
     5. PROJECTS SECTION
     - Header fade-up
     - Cards fade up as one unit
     ------------------------------------------ */

  const projectsHeaderTl = gsap.timeline({
    defaults: { ease: "power3.out" },
    scrollTrigger: {
      trigger: ".projects-section",
      start: "top 80%",
    },
  });

  projectsHeaderTl
    .from(".projects-header .section-label", {
      y: 30,
      opacity: 0,
      duration: 0.8,
    })
    .from(
      ".projects-header h2",
      {
        y: 40,
        opacity: 0,
        duration: 1,
      },
      "-=0.5"
    )
    .from(
      ".projects-btn",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.6"
    );

  // Project cards - entire grid fades up as one unit
  gsap.from(".projects-grid", {
    y: 80,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".projects-grid",
      start: "top 85%",
    },
  });

  /* ------------------------------------------
     5B. VISI MISI SECTION
     - Header fade-up
     - Cards reveal as one unit
     ------------------------------------------ */

  const visiMisiTl = gsap.timeline({
    defaults: { ease: "power3.out" },
    scrollTrigger: {
      trigger: ".visi-misi",
      start: "top 75%",
    },
  });

  visiMisiTl
    .from(".visi-misi-label", {
      y: 20,
      opacity: 0,
      duration: 0.6,
    })
    .from(
      ".visi-misi-header h2",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.4"
    )
    .from(
      ".visi-misi-subtitle",
      {
        y: 20,
        opacity: 0,
        duration: 0.6,
      },
      "-=0.5"
    )
    .from(
      ".visi-misi-cards",
      {
        y: 60,
        opacity: 0,
        duration: 1,
      },
      "-=0.4"
    );

  /* ------------------------------------------
     6. ESTATE SECTION
     - Parallax on background
     - Info fade-left
     - Features stagger fade-up
     - CTA fade-up
     ------------------------------------------ */

  // Parallax on estate section background
  gsap.to(".estate-section", {
    backgroundPosition: "center 60%",
    ease: "none",
    scrollTrigger: {
      trigger: ".estate-section",
      start: "top bottom",
      end: "bottom top",
      scrub: 0.5,
    },
  });

  const estateTl = gsap.timeline({
    defaults: { ease: "power3.out" },
    scrollTrigger: {
      trigger: ".estate-section",
      start: "top 70%",
    },
  });

  estateTl
    .from(".estate-label", {
      y: 30,
      opacity: 0,
      duration: 0.8,
    })
    .from(
      ".estate-info h2",
      {
        x: -50,
        opacity: 0,
        duration: 1,
      },
      "-=0.5"
    )
    .from(
      ".estate-info p",
      {
        x: -40,
        opacity: 0,
        duration: 0.8,
      },
      "-=0.6"
    );

  // Estate features - entire grid fades up as one unit
  gsap.from(".estate-features", {
    y: 60,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".estate-features",
      start: "top 80%",
    },
  });

  // Estate CTA
  gsap.from(".estate-cta", {
    y: 60,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".estate-cta",
      start: "top 90%",
    },
  });

  /* ------------------------------------------
     7. FOOTER
     - Staggered columns reveal
     - Footer bottom fade-up
     ------------------------------------------ */

  const footerTl = gsap.timeline({
    defaults: { ease: "power3.out" },
    scrollTrigger: {
      trigger: ".footer",
      start: "top 85%",
    },
  });

  footerTl
    .from(".footer-brand", {
      y: 50,
      opacity: 0,
      duration: 0.9,
    })
    .from(
      ".footer-contact",
      {
        y: 50,
        opacity: 0,
        duration: 0.9,
      },
      "-=0.7"
    )
    .from(
      ".footer-links",
      {
        y: 50,
        opacity: 0,
        duration: 0.9,
      },
      "-=0.7"
    )
    .from(
      ".footer-social",
      {
        y: 50,
        opacity: 0,
        duration: 0.9,
      },
      "-=0.7"
    );

  // Footer links - entire container fades up as one unit
  gsap.from(".footer-links", {
    x: -20,
    opacity: 0,
    duration: 0.6,
    ease: "power3.out",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".footer-links",
      start: "top 90%",
    },
  });

  // Social icons - entire container fades up as one unit
  gsap.from(".social-icons", {
    y: 20,
    opacity: 0,
    duration: 0.6,
    ease: "power3.out",
    clearProps: "all",
    scrollTrigger: {
      trigger: ".footer-social",
      start: "top 90%",
    },
  });

  // Footer bottom
  gsap.from(".footer-bottom", {
    y: 20,
    opacity: 0,
    duration: 0.8,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".footer-bottom",
      start: "top 95%",
    },
  });
}

/* ============================================
   INTERSECTION OBSERVER - Reveal on scroll
   ============================================ */

const observer = new IntersectionObserver((entries) => {
  entries.forEach((e) => {
    if (e.isIntersecting) e.target.classList.add("show");
  });
});
document
  .querySelectorAll(".reveal")
  .forEach((el) => observer.observe(el));

/* ============================================
   COUNTER ANIMATION
   ============================================ */

document.querySelectorAll("[data-count]").forEach((el) => {
  let target = +el.dataset.count,
    current = 0;
  const step = Math.max(1, Math.ceil(target / 50));
  const run = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(run);
    }
    el.textContent =
      current +
      (target === 100
        ? "%"
        : target === 13
          ? "+"
          : target === 451
            ? "+"
            : "");
  }, 30);
});
