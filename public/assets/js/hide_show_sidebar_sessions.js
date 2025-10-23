$(document).on("click", "#close-sidebar", function () {
    // hide sidebar smoothly
    $('.sessions-sidebar').addClass('hidden');

    // animate icons
    $('#close-sidebar').addClass('hidden');
    setTimeout(() => {
        $('#close-sidebar').addClass('d-none');
        $('#open-sidebar').removeClass('d-none').removeClass('hidden');
    }, 300); // wait for fade
});

$(document).on("click", "#open-sidebar", function () {
    // show sidebar smoothly
    $('.sessions-sidebar').removeClass('hidden');

    // animate icons
    $('#open-sidebar').addClass('hidden');
    setTimeout(() => {
        $('#open-sidebar').addClass('d-none');
        $('#close-sidebar').removeClass('d-none').removeClass('hidden');
    }, 300); // wait for fade
});
