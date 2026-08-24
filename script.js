document.addEventListener("DOMContentLoaded", () => {
    // 1. MODAL POPUP FOR PROJECTS
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const modalCaption = document.getElementById("modalCaption");
    const closeBtn = document.querySelector(".modal-close");
    const projectCards = document.querySelectorAll(".project-card-agency");

    if (modal && closeBtn) {
        projectCards.forEach(card => {
            card.addEventListener("click", () => {
                const imgSrc = card.getAttribute("data-img");
                const caption = card.getAttribute("data-caption");

                if (imgSrc) {
                    modal.style.display = "flex";
                    modalImg.src = imgSrc;
                    modalCaption.textContent = caption;
                }
            });
        });

        const closeModal = () => { modal.style.display = "none"; };
        closeBtn.addEventListener("click", closeModal);
        window.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
    }

    // 2. SMOOTH NAVBAR SCROLL EFFECT
    const navbar = document.querySelector(".navbar");
    if (navbar) {
        window.addEventListener("scroll", () => {
            navbar.style.padding = window.scrollY > 50 ? "15px 8%" : "20px 8%";
        });
    }

    // 3. TOGGLE HAMBURGER MENU (MOBILE)
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // 4. SCROLL REVEAL ANIMATION
    const reveals = document.querySelectorAll('.project-card-agency, .service-card, .section-header-large, .profile-card, .education-card, .quote-terminal-card');

    function revealOnScroll() {
        const windowHeight = window.innerHeight;
        reveals.forEach(el => {
            el.classList.add('reveal');
            const elementTop = el.getBoundingClientRect().top;
            if (elementTop < windowHeight - 100) {
                el.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // 5. DARK / LIGHT MODE TOGGLE
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    if (themeToggleBtn && themeIcon) {
        const updateThemeUI = (isLight) => {
            if (isLight) {
                document.body.classList.add('light-mode');
                themeIcon.textContent = '🌙';
            } else {
                document.body.classList.remove('light-mode');
                themeIcon.textContent = '☀️';
            }
        };

        const currentTheme = localStorage.getItem('theme');
        updateThemeUI(currentTheme === 'light');

        themeToggleBtn.addEventListener('click', () => {
            const isLight = !document.body.classList.contains('light-mode');
            updateThemeUI(isLight);
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        });
    }
});