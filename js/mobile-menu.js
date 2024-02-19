(function($) {
    function createMobileSubmenuDetails() {
        $('.menu-item-has-children').each(function() {
            var $menuItem = $(this);
            var $submenu = $menuItem.children('.sub-menu');
            var $link = $menuItem.children('a');

            // Check if we're on a mobile device based on screen width
            if (window.matchMedia('(max-width: 1024px)').matches) {
                // Only apply the wrapping if it hasn't been applied yet
                if (!$link.parent().is('details')) {
                    // Wrap the link and submenu within 'details' and 'summary'
                    $link.add($submenu).wrapAll('<details></details>');
                    $link.wrap('<summary></summary>');
                }
            } else {
                // Revert changes if the screen is resized back to desktop width
                if ($link.parent().is('summary')) {
                    var $details = $link.closest('details');
                    $link.unwrap('summary'); // Remove 'summary' wrapper from 'a'
                    $link.next('.sub-menu').andSelf().unwrap('details'); // Remove 'details' wrapper
                }
            }
        });
    }

    // Trigger the submenu toggle function on document ready and on resize
    $(document).ready(createMobileSubmenuDetails);
    $(window).on('resize', createMobileSubmenuDetails);
})(jQuery);