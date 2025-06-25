let darkMode = false;
const userTheme = localStorage.getItem('theme');

$(function () {
    
    const $btn = $('#themeBtn');
    const $html = $('#htmlPage');
    
    if ($btn.length) {

        if (userTheme === 'dark') {
            $btn.text('Off');
            darkMode = true;
        } else if (userTheme === 'light') {
            $btn.text('On');
            darkMode = false;
        }
        
        $btn.on('click', function () {
            if (!darkMode) {
                clickDarkMode();
            } else {
                clickLightMode();
            }
        });

        function clickDarkMode() {
            $btn.text('Off');
            localStorage.setItem("theme", "dark");
            $html.attr("data-bs-theme", "dark");
            darkMode = true;
        }

        function clickLightMode() {
            $btn.text('On');
            localStorage.setItem("theme", "light");
            $html.attr("data-bs-theme", "light");
            darkMode = false;
        }
    }
});