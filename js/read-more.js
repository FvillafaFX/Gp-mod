jQuery(document).ready(function() {
    function convertToDetails() {
        jQuery('.read-mr').each(function() {
            var content = jQuery(this).html();
            var summary = '<summary>Read More</summary>';
            var details = jQuery('<details>').append(summary, content);
            jQuery(this).replaceWith(details);
        });
    }

    function convertBack() {
        jQuery('details').each(function() {
            var content = jQuery(this).children().not('summary').clone();
            var span = jQuery('<span class="read-mr">').html(content);
            jQuery(this).replaceWith(span);
        });
    }

    // Convert to details on screens smaller than 400px
    function handleScreenSize() {
        if (jQuery(window).width() < 400) {
            convertToDetails();
        } else {
            convertBack();
        }
    }

    // Initial call
    handleScreenSize();

    // Recalculate on window resize
    jQuery(window).resize(function() {
        handleScreenSize();
    });
});
