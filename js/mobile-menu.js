// Vanilla JS Version
(function () {
  function createMobileSubmenuDetails() {
    const isMobile = window.matchMedia("(max-width: 1024px)").matches;

    document.querySelectorAll(".menu-item-has-children").forEach((menuItem) => {
      const submenu = menuItem.querySelector(":scope > .sub-menu");
      const link = menuItem.querySelector(":scope > a");

      if (!submenu || !link) return;

      if (isMobile) {
        // Apply the mobile submenu structure only if it hasn't been applied yet
        if (!menuItem.querySelector("details")) {
          const details = document.createElement("details");
          const summary = document.createElement("summary");

          // Move the link into summary
          summary.appendChild(link);

          // Put summary + submenu inside details
          details.appendChild(summary);
          details.appendChild(submenu);

          // Insert details at the beginning of the menu item
          menuItem.insertBefore(details, menuItem.firstChild);
        }
      } else {
        // Revert changes if resizing to desktop width
        const details = menuItem.querySelector(":scope > details");
        if (details) {
          const summary = details.querySelector(":scope > summary");
          const originalLink = summary ? summary.querySelector(":scope > a") : null;

          if (originalLink) {
            // Put link back as first child (like original behavior)
            menuItem.insertBefore(originalLink, menuItem.firstChild);
          }

          // Put submenu back at the end of menu item
          menuItem.appendChild(submenu);

          // Remove details wrapper
          details.remove();
        }
      }
    });
  }

  // Run on DOM ready + on resize
  document.addEventListener("DOMContentLoaded", () => {
    createMobileSubmenuDetails();

    window.addEventListener("resize", () => {
      createMobileSubmenuDetails();
    });
  });
})();
