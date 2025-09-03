  (function () {
    const theme = localStorage.getItem('theme');
    document.documentElement.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light');
  })();