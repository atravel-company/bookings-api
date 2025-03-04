<script src="{{ asset('FrontEnd/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('FrontEnd/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('FrontEnd/js/modernizr.js') }}"></script>
<script src="{{ asset('FrontEnd/js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('FrontEnd/js/jquery.flexslider-min.js') }}"></script>
<script src="{{ asset('FrontEnd/js/main.js') }}"></script>
<script src="{{ asset('FrontEnd/js/jquery.chocolat.js') }}"></script>

<script src="{{ asset('FrontEnd/js/responsiveslides.min.js') }}"></script>
<script src="{{ asset('FrontEnd/js/jarallax.js') }}"></script>
<script src="{{ asset('FrontEnd/js/SmoothScroll.min.js') }}"></script>

<script src="{{ asset('FrontEnd/js/move-top.js') }}"></script>
<script src="{{ asset('FrontEnd/js/easing.js') }}"></script>

<script>
    setTimeout(function() {
        $(".back").fadeOut();
    });
</script>



<script>
    $('.carousel').carousel({
        interval: 6000
    });

    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-48014931-1', 'codyhouse.co');
    ga('send', 'pageview');

    jQuery(document).ready(function($) {
        $('.close-carbon-adv').on('click', function(event) {
            event.preventDefault();
            $('#carbonads-container').hide();
        });
    });

</script>
<!-- scrolling script -->
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $(function() {
            var header = $(".header");
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();

                if (scroll >= 50) {
                    header.removeClass('header').addClass("scroll_header");
                } else {
                    header.removeClass("scroll_header").addClass('header');
                }
            });
        });

        $(document).ready(function() {
            //$(document).on("scroll", onScroll);

            //smoothscroll
            $('.navbar-nav > li > a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                $(document).off("scroll");

                $('.navbar-nav > li > a').each(function() {
                    $(this).removeClass('active');
                })
                $(this).addClass('active');



                var target = this.hash,
                    menu = target;
                $target = $(target);
                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top
                }, 1000, 'swing', function() {
                    window.location.hash = target;
                });
            });
        });

    });

</script>
<!-- //scrolling script -->


<script type="text/javascript">
    $(document).ready(function() {
        $().UItoTop({
            easingType: 'easeOutQuart'
        });
    });

</script>

<script type="text/javascript">
    $(function() {
        $('.w3portfolioaits-item a').Chocolat();
    });

</script>



<script type="text/javascript">
    $('#ask_info_about').click(function() {
        $("html, body").animate({
            scrollTop: $(".contact-inner-right").offset().top - 50
        }, 1000);
        $('#message').val("Offers");
    });

    $("#info_contact").submit(function(e) {
        $(".back").show();
        $(".load").show();
        e.preventDefault();
        dataValue = $(this).serialize();
        $.ajax({
            url: 'info/resp.php',
            data: dataValue,
            type: 'POST',
            cache: false,
            success: function(data) {
                if (data == 0) {
                    $(".back").hide();
                    $(".load").show();
                    $('.debug-url').html(
                        "The contact information was submitted successfully, please verify the message in email: <b>" +
                        $("#email").val() + "</b>");
                    $('#info_contact')[0].reset();
                    $("#mensagem_ok").trigger('click');
                    setTimeout(function() {
                        $('#Modalok').modal('hide');
                    }, 2500);
                } else {
                    $(".back").hide();
                    $(".load").show();
                    $('.debug-url').html("Please verify the following fields:<br><br>" + data +
                        "<br> and Try Again");
                    $('#Modalko').modal();
                }

            },
            error: function() {
                $(".back").hide();
                $(".load").show();
                $('.debug-url').html(
                    "The contact information was not submitted successfully, please verify WI-FI Connection"
                    );
                $('#Modalko').modal();
            }
        });
    });
</script>
