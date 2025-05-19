(function () {
  function createEnhancedSubmenu() {
    // Target all menu items with children, including nested ones in #toggle-mn
    jQuery('.menu-item-has-children').each(function () {
      var menuItem = jQuery(this);
      var submenu = menuItem.children('.sub-menu');
      var link = menuItem.children('a');
      
      // Check if the item is inside #toggle-mn (any nesting level)
      var isInToggleMenu = menuItem.closest('#toggle-mn').length > 0;
      var isMobile = window.matchMedia('(max-width: 1024px)').matches;

      // Apply details if mobile OR in #toggle-mn (including nested items)
      if (isMobile || isInToggleMenu) {
        if (!menuItem.find('details').length) {
          var details = jQuery('<details></details>');
          var summary = jQuery('<summary></summary>');

          // Clone elements to avoid DOM conflicts
          summary.append(link.clone());
          
          // Process nested sub-menus recursively
          if (submenu.length) {
            var clonedSubmenu = submenu.clone();
            // Apply the same treatment to nested sub-menu items
            clonedSubmenu.find('.menu-item-has-children').each(function() {
              var nestedItem = jQuery(this);
              var nestedSubmenu = nestedItem.children('.sub-menu');
              var nestedLink = nestedItem.children('a');
              
              if (nestedSubmenu.length) {
                var nestedDetails = jQuery('<details></details>');
                var nestedSummary = jQuery('<summary></summary>');
                nestedSummary.append(nestedLink.clone());
                nestedDetails.append(nestedSummary).append(nestedSubmenu.clone());
                nestedItem.html(nestedDetails);
              }
            });
            
            details.append(summary).append(clonedSubmenu);
          } else {
            details.append(summary);
          }
          
          menuItem.html(details); // Replace content
        }
      } else {
        // Revert only if not in #toggle-mn
        if (!isInToggleMenu) {
          var detailsElement = menuItem.children('details');
          if (detailsElement.length) {
            var summaryElement = detailsElement.children('summary');
            var originalLink = summaryElement.children('a').first();

            // Restore original structure
            menuItem.prepend(originalLink);
            
            // Revert nested sub-menus
            detailsElement.find('details').each(function() {
              var nestedDetails = jQuery(this);
              var nestedSummary = nestedDetails.children('summary');
              var nestedOriginalLink = nestedSummary.children('a').first();
              var nestedSubmenu = nestedDetails.children('.sub-menu');
              
              nestedDetails.before(nestedOriginalLink);
              if (nestedSubmenu.length) {
                nestedDetails.after(nestedSubmenu);
              }
              nestedDetails.remove();
            });
            
            menuItem.append(submenu);
            detailsElement.remove();
          }
        }
      }
    });
  }

  // Initialize on DOM ready
  jQuery(document).ready(function () {
    createEnhancedSubmenu();
    // Handle window resizing
    jQuery(window).on('resize', createEnhancedSubmenu);
  });
})();
