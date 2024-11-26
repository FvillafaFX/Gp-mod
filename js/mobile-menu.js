(function ($) {
    function createMobileSubmenuDetails() {
        $('.menu-item-has-children').each(function () {
            var $menuItem = $(this);
            var $submenu = $menuItem.children('.sub-menu');
            var $link = $menuItem.children('a');

            // Check if we're on a mobile device based on screen width
            if (window.matchMedia('(max-width: 1024px)').matches) {
                // Apply the mobile submenu structure only if it hasn't been applied yet
                if (!$menuItem.find('details').length) {
                    // Create 'details' and 'summary' elements
                    var $details = $('<details></details>');
                    var $summary = $('<summary></summary>');

                    $summary.append($link); // Move the link inside 'summary'
                    $details.append($summary).append($submenu); // Add 'summary' and submenu into 'details'
                    $menuItem.prepend($details); // Insert the 'details' back into the menu item
                }
            } else {
                // Revert changes if resizing to desktop width
                var $details = $menuItem.children('details');
                if ($details.length) {
                    var $summary = $details.children('summary');
                    var $originalLink = $summary.children('a');

                    // Move link and submenu back to their original positions
                    $menuItem.prepend($originalLink);
                    $menuItem.append($submenu);

                    // Remove 'details' element
                    $details.remove();
                }
            }
        });
    }

    // Trigger the submenu toggle function on document ready and on resize
    $(document).ready(function () {
        createMobileSubmenuDetails();

        // Handle window resizing
        $(window).on('resize', function () {
            createMobileSubmenuDetails();
        });
    });
})(jQuery);
