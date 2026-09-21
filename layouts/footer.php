</main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // THEME
      const themeToggleBtn = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const updateThemeUI = (isLight) => {
        document.body.classList.toggle('light-mode', isLight);
        if (themeIcon) themeIcon.textContent = isLight ? '🌙' : '☀️';
      };
      updateThemeUI(localStorage.getItem('theme') === 'light');
      if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
          const goingLight = !document.body.classList.contains('light-mode');
          updateThemeUI(goingLight);
          localStorage.setItem('theme', goingLight ? 'light' : 'dark');
        });
      }

      // SIDEBAR (MOBILE)
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebarOverlay = document.getElementById('sidebarOverlay');
      const setSidebar = (open) => {
        sidebar.classList.toggle('open', open);
        sidebarOverlay.classList.toggle('show', open);
      };
      sidebarToggle.addEventListener('click', () => setSidebar(!sidebar.classList.contains('open')));
      sidebarOverlay.addEventListener('click', () => setSidebar(false));
      document.querySelectorAll('.side-link').forEach(link => {
        link.addEventListener('click', () => setSidebar(false));
      });
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setSidebar(false); });

      // CUSTOM ANIME CURSOR
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
        const hoverSelector = 'a, button, input, textarea, .action-card, .stat-card';
        document.querySelectorAll(hoverSelector).forEach(el => {
          el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
          el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
        });
      }
    });
  </script>
</body>
</html>