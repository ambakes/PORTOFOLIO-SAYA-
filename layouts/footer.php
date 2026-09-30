</main>
</div>

<script>
  (function () {
    var btn = document.getElementById('themeToggleAdmin');
    function apply(light) { document.body.classList.toggle('light-mode', light); }
    try { apply(localStorage.getItem('theme') === 'light'); } catch (e) {}
    if (btn) {
      btn.addEventListener('click', function () {
        var light = !document.body.classList.contains('light-mode');
        apply(light);
        try { localStorage.setItem('theme', light ? 'light' : 'dark'); } catch (e) {}
      });
    }
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
      f.addEventListener('submit', function (ev) {
        if (!confirm(f.getAttribute('data-confirm'))) ev.preventDefault();
      });
    });
  })();
</script>
</body>
</html>