$(function () {
    "use strict";
  
    const currentUrl = window.location.origin + window.location.pathname;
  
    const element = $("ul#sidebarnav a").filter(function () {
      const menuHref = this.href.split('?')[0];
      return currentUrl.startsWith(menuHref);
    });
  
    element.parentsUntil(".sidebar-nav").each(function () {
      if ($(this).is("li") && $(this).children("a").length !== 0) {
        $(this).children("a").addClass("active");
        $(this).addClass("selected");
      } else if (!$(this).is("ul") && $(this).children("a").length === 0) {
        $(this).addClass("selected");
      } else if ($(this).is("ul")) {
        $(this).addClass("in");
      }
    });
  
    element.addClass("active");
  
    function scrollToSidebarItem($target) {
      const $container = $('.scroll-sidebar .simplebar-content-wrapper');
      if ($target.length && $container.length) {
        const offsetTop = $target.position().top;
        const scrollTop = offsetTop - ($container.height() / 2) + ($target.outerHeight() / 2);
        $container.animate({ scrollTop: scrollTop }, 1000);
      }
    }
  
    if (element.length) {
      scrollToSidebarItem(element.closest("li.sidebar-item"));
    }
  });