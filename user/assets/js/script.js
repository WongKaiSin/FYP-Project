$(document).ready(function() {
// slider
if ($(".product-sliders").length) {
    $('.product-sliders').flickity({
        cellAlign: "left",
        imagesLoaded: true,
        wrapAround: true,
        pageDots: false,
        adaptiveHeight: true
    });
}

if ($(".product-sliders-thumb").length) {
    $('.product-sliders-thumb').flickity({
        cellAlign: "left",
        imagesLoaded: true,
        asNavFor: ".product-sliders",
        pageDots: false,
        contain: true,
        groupCells: true,
    });
}
// END slider

// Special
if ($("[data-fancybox]").length) {
    $('[data-fancybox]').fancybox({
        onInit: function(instance) {
            instance.$refs.toolbar.find('.fancybox-zoom').on('click', function() {
                if (instance.isScaledDown()) {
                    instance.scaleToActual();
                } else {
                    instance.scaleToFit();
                }
            });
        }
    });
}

if ($(".selectpicker").length) {
    $('.selectpicker').select2();
}

if ($("[data-title]").length) {
    $('[data-title]').tooltip();
}
// END Special
});