$('.partner-slick').slick({
    dots: false,
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 4,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3
            }
        },
        {
            breakpoint: 900,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                arrows: false,
                dots: true
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true
            }
        }
    ]
});

$(function() {
    $(".hover-spin").on({
        mouseenter: function () {
            id = $(this).data('spin');
            $('#' + id).addClass('mdi-spin');
        },
        mouseleave: function () {
            id = $(this).data('spin');
            $('#' + id).removeClass('mdi-spin');
        }
    });

    $(document)
        .on("click", '.set-access', function (e) {
            id = $(this).data('id');
            if ($(this).is(":checked")) {
                $("#" + id).prop("checked", "checked");
                $("#h_" + id).val("1");
            }
            else {
                $("#" + id).prop("checked", "");
                $("#h_" + id).val("0");
            }
        })
});