/*=============================================
    Custom JavaScript
=============================================*/
// from HFCM transferred to child theme - starts here
jQuery(document).ready(function ($) {

    // Toggle dropdown
    $('.orc-btn-dropdown').on('click', function (e) {
        e.stopPropagation(); // prevent document click
        $('.other-related-centres').fadeToggle(500);
    });
    // Clicking inside dropdown should not close it
    $('.other-related-centres').on('click', function (e) {
        e.stopPropagation();
    });
    // Close when clicking a list item
    $('.other-related-centres li').on('click', function () {
        $('.other-related-centres').fadeOut(500);
    });
    // Close when clicking anywhere outside
    $(document).on('click', function () {
        $('.other-related-centres').fadeOut(500);
    });

    $('.mobile-nav-item.has-submenu').on('click', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $submenu = $this.next('.sub-menu');

        // Close other open submenus
        $('.mobile-nav-item.has-submenu').not($this).removeClass('open');
        $('.sub-menu').not($submenu).slideUp(0);

        // Toggle current submenu
        $this.toggleClass('open');
        $submenu.slideToggle(0);
    });



    function convertTo24Hour(timeStr) {
        var match = timeStr.match(/(\d+)[.:](\d+)(AM|PM)/i);
        if (!match) return null;

        var hours = parseInt(match[1], 10);
        var minutes = parseInt(match[2], 10);
        var modifier = match[3].toUpperCase();

        if (modifier === "PM" && hours !== 12) hours += 12;
        if (modifier === "AM" && hours === 12) hours = 0;

        return hours * 60 + minutes;
    }
    var hoursText = $(".open-hours").text().trim();
    var splitTimes = hoursText.split(/-|–/); // normal dash or long dash

    var openTime = convertTo24Hour(splitTimes[0].trim());
    var closeTime = convertTo24Hour(splitTimes[1].trim());

    var now = new Date();
    var currentMinutes = now.getHours() * 60 + now.getMinutes();

    if (openTime === null || closeTime === null) {
        console.log("Time format error.");
        return;
    }

    if (currentMinutes < openTime || currentMinutes > closeTime) {
        $(".text-label").text("Closed Now");
    } else {
        $(".text-label").text("Open Today");
    }
    
});
// from HFCM transferred to child theme - ends here