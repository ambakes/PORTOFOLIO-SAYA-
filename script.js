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
        window.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closeModal();
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
            const isActive = hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
            hamburger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
                hamburger.setAttribute('aria-expanded', 'false');
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

    // 5. DARK / LIGHT MODE TOGGLE (with loading transition)
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeTransition = document.getElementById('themeTransition');
    const themeTransitionText = document.getElementById('themeTransitionText');

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

        let isSwitching = false;
        themeToggleBtn.addEventListener('click', () => {
            if (isSwitching) return;
            isSwitching = true;

            const goingLight = !document.body.classList.contains('light-mode');

            if (themeTransition) {
                if (themeTransitionText) {
                    themeTransitionText.textContent = goingLight
                        ? 'Menyalakan mode terang...'
                        : 'Meredupkan ke mode gelap...';
                }
                themeTransition.classList.add('active');
            }

            // Brief "loading" beat before the theme actually swaps
            setTimeout(() => {
                updateThemeUI(goingLight);
                localStorage.setItem('theme', goingLight ? 'light' : 'dark');

                setTimeout(() => {
                    if (themeTransition) themeTransition.classList.remove('active');
                    isSwitching = false;
                }, 280);
            }, 420);
        });
    }

    // ===================== NEW FEATURES =====================

    // 6. CUSTOM ANIME CURSOR (only on devices with a real mouse)
    const cursorDot = document.getElementById('cursorDot');
    const cursorRing = document.getElementById('cursorRing');
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    if (cursorDot && cursorRing && canHover) {
        document.addEventListener('mousemove', (e) => {
            cursorDot.style.left = e.clientX + 'px';
            cursorDot.style.top = e.clientY + 'px';
            cursorRing.style.left = e.clientX + 'px';
            cursorRing.style.top = e.clientY + 'px';
        });

        document.addEventListener('mousedown', () => {
            cursorRing.style.transform = 'translate(-50%, -50%) scale(0.85)';
        });
        document.addEventListener('mouseup', () => {
            cursorRing.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        const hoverSelector = 'a, button, .project-card-agency, .filter-btn, .service-card, input, textarea';
        document.querySelectorAll(hoverSelector).forEach(el => {
            el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
        });
    }

    // 7. PRELOADER — hide once page is fully loaded
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => preloader.classList.add('loaded'), 400);
        });
    }

    // 8. SCROLL PROGRESS BAR
    const scrollProgress = document.getElementById('scrollProgress');
    function updateScrollProgress() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const percent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        if (scrollProgress) scrollProgress.style.width = percent + '%';
    }
    window.addEventListener('scroll', updateScrollProgress);
    updateScrollProgress();

    // 9. BACK TO TOP BUTTON
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('visible', window.scrollY > 500);
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // 10. ACTIVE NAV LINK ON SCROLL
    const sections = document.querySelectorAll('section[id]');
    const navLinkEls = document.querySelectorAll('.nav-link');

    function updateActiveNavLink() {
        let currentId = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 140;
            if (window.scrollY >= sectionTop) {
                currentId = section.getAttribute('id');
            }
        });
        navLinkEls.forEach(link => {
            link.classList.remove('active-link');
            if (link.getAttribute('href') === '#' + currentId) {
                link.classList.add('active-link');
            }
        });
    }
    window.addEventListener('scroll', updateActiveNavLink);
    updateActiveNavLink();

    // 11. PROJECT FILTER
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.getAttribute('data-filter');

            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                const shouldShow = filter === 'all' || category === filter;
                card.classList.toggle('filtered-out', !shouldShow);
            });
        });
    });

    // 12. CONTACT FORM VALIDATION + KIRIM VIA MAILTO
    const contactForm = document.getElementById('contactForm');
    const formStatus = document.getElementById('formStatus');
    const TUJUAN_EMAIL = 'jhonatanfarles@gmail.com';

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;

            const nameInput = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const messageInput = document.getElementById('messageInput');

            const fields = [
                { input: nameInput, message: 'Nama wajib diisi.' },
                { input: emailInput, message: 'Masukkan email yang valid.' },
                { input: messageInput, message: 'Pesan tidak boleh kosong.' },
            ];

            fields.forEach(({ input, message }) => {
                const group = input.closest('.form-group');
                const errorEl = contactForm.querySelector(`.form-error[data-for="${input.id}"]`);
                let fieldValid = input.value.trim().length > 0;

                if (input.type === 'email' && fieldValid) {
                    fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim());
                }

                if (!fieldValid) {
                    isValid = false;
                    group.classList.add('has-error');
                    if (errorEl) errorEl.textContent = message;
                } else {
                    group.classList.remove('has-error');
                    if (errorEl) errorEl.textContent = '';
                }
            });

            if (!isValid) {
                if (formStatus) {
                    formStatus.textContent = 'Mohon lengkapi form dengan benar.';
                    formStatus.classList.remove('success');
                }
                return;
            }

            const nama = nameInput.value.trim();
            const emailPengirim = emailInput.value.trim();
            const pesan = messageInput.value.trim();

            const subject = `Pesan baru dari ${nama} (Portofolio)`;
            const body = `Nama: ${nama}\nEmail: ${emailPengirim}\n\nPesan:\n${pesan}`;

            const mailtoLink = `mailto:${TUJUAN_EMAIL}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

            window.location.href = mailtoLink;

            if (formStatus) {
                formStatus.textContent = 'Aplikasi email kamu akan terbuka — tinggal klik "Kirim" di sana untuk menyelesaikan.';
                formStatus.classList.add('success');
            }
            contactForm.reset();
        });
    }
});