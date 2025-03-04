<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>ATRAVEL</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8" />
    <meta name="description" content="ATRAVEL" />
    <meta name="keywords" content="ATRAVEL" />
    <meta name="author" content="ATRAVEL" />

    @include('stylesheet')
</head>
<!-- Head -->

<body>
    <style>
        .img_3 {
            background: url(/FrontEnd/images/what-to-do/Praia-algarve-best-ats.jpg);
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.75;
            width: 100%;
            height: auto;
        }
    </style>

    <!-- Loader -->
    <div class="back" style="display:block;">
        <div class="load"></div>
    </div>


    @include("modals")

    <!-- banner -->
    <div class="jarallax">
        <div class="agileinfo-dot">
            <div class="header">
                <div class="container cont_menu">
                    <nav class="navbar navbar-default">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="index.php"><img src="{{ asset('FrontEnd/images/LogotipoAtravelCor.png') }}"
                                    style="margin-top: -13px;max-height: 45px" alt="logotipo - ATRAVEL"
                                    title="logotipo - ATRAVEL"></a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a class="scroll" href="#about">Home</a></li>
                                <li><a href="#contact" class="scroll">Contact Us</a></li>
                                <li><a href="{{ route('login') }}" class="scroll">Agences</a></li>
                            </ul>
                            <div class="clearfix"> </div>
                        </div>
                    </nav>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="banner-info">

                <div id="first-slider">
                    <div id="carousel-example-generic" class="carousel slide carousel-fade">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox" style="height: 540px;">
                            <!-- Item 1 -->
                            <div class="item active slide1">
                                <div class="row">
                                    <div class="container">
                                        <div class="col-md-12 text-left">
                                            <h4 data-animation="animated bounceInUp">TRAVEL</h4>
                                            <h4 data-animation="animated bounceInUp">WITH</h4>
                                            <h4 data-animation="animated bounceInUp">US</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Item 2 -->
                            <div class="item slide2">
                                <div class="row">
                                    <div class="container">
                                        <div class="col-md-12 text-right">
                                            <h4 data-animation="animated bounceInUp">TRAVEL</h4>
                                            <h4 data-animation="animated bounceInUp">WITH</h4>
                                            <h4 data-animation="animated bounceInUp">US</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Wrapper for slides-->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="prev">
                            <i class="fa fa-angle-left"></i><span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="next">
                            <i class="fa fa-angle-right"></i><span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <!-- //banner -->

    <!-- about-->

    <section class="about" id="about">
        <h1 class="heading">ATRAVEL</h3>
            <div class="container">
                <div class="about-top">
                    <p>ATRAVEL is a fully licensed Travel Agency dmc, operating in the Algarve, Portugal, since 1999.
                    </p>
                    <p>We are able to offer a wide variety of solutions for travelers within the Algarve region.</p>
                    <p>Offering Airport & Golf transfers, Sightseeing tours, Hotel & Golf reservations, and much more.
                        From Individual travelers to Tour operators, we have the solution for your requirements.</p>
                </div>
            </div>

            <div class="about-bottom item_ img_3">


                <div class="col-md-6 col-sm-6 col-xs-9 aboutbottomleft">
                    <h3>What we do</h3>
                    <p>Check Our offers and ask for more information</p>
                    <ul class="padder_list">
                        <li type="disc">Hotel & Golf Reservations</li>
                        <li type="disc">Airport & Golf Transfers</li>
                        <li type="disc">Sightseeing Tours</li>
                        <li type="disc">Incoming Services</li>
                        <li type="disc">Rent Car</li>
                        <li type="disc">Other Services</li>
                    </ul>
                    <div class="w3ls-button more">
                        <a href="#" id="ask_info_about">Ask Info</a>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
    </section>

    <!-- //about-->


    <!-- Contact-->
    <div class="contact jarallax" id="contact">
        <div class="container">
            <h3 class="heading"></h3>
            <div class="col-md-6 contact-left">
                <h4>Contact us</h4>
                <p>ATRAVEL contacts are here for you to use</p>
                <div class="address">
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> Av. da Liberdade</p>
                    <p>245, 4ºA,</p>
                    <p>1250-143 Lisboa, Portugal</p>
                </div>
                <div class="number">
                    <p><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:+351282457306">(+351) 282 457 306</a>
                        | <a href="tel:+351917250405">(+351) 912 032 695</a></p>
                    <p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:sales@atravel.pt">
                        sales@atravel.pt</a></p>
                </div>
                <div class="social-icons">
                    <a href="https://www.facebook.com/atstravel1999" target="_blank"><img
                            src="{{ asset('FrontEnd/images/icons/facbook-contacts.png') }}" class="social_net" alt="facebook-contacts"
                            title="facebook-contacts"></a>
                    <a href="https://twitter.com/atstravel_dmc" target="_blank"><img
                            src="{{ asset('FrontEnd/images/icons/tiwwter-contacts.png') }}" class="social_net" alt="twitter-contacts"
                            title="twitter-contacts"></a>
                    <a href="https://www.instagram.com/ats_travel/" target="_blank"><img
                            src="{{ asset('FrontEnd/images/icons/instagramr-contacts.png') }}" class="social_net" alt="instagram-contacts"
                            title="instagram-contacts"></a>

                </div>
            </div>
            <div class="col-md-6 contact-right">
                <div class="contact-inner-right">
                    <h4>Get in touch</h4>
                    <form method="post" id="info_contact" class="contact_form">
                        <input type="text" class="margin-right place" name="name" id="name" placeholder="Name *">
                        <input type="text" id="email" name="email" placeholder="Email *" class="place">
                        <textarea name="message" id="message" placeholder="Message *" class="place"></textarea>
                        <input type="submit" class="more_btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
    <!-- //Contact-->

    <!-- map -->
    <div class="map">
        <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDStLYcYnL_jyIcmrChfZ3rGp71WBHHBmc'>
        </script>
        <div style='overflow:hidden;height:auto;width:100%;'>
            <div id='gmap_canvas' style='height:400px;width:100%;'></div>
            <div>
            </div>
            <div>
            </div>
            <style>
                #gmap_canvas img {
                    max-width: none !important;
                    background: none !important
                }
            </style>
        </div>
        <script type='text/javascript'>
            function init_map(){var myOptions = {zoom:16, scrollwheel: false, navigationControl: false, mapTypeControl: false,scaleControl: false, draggable: false, center:new google.maps.LatLng(37.133896,-8.5391927),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(37.133896,-8.5391927)});infowindow = new google.maps.InfoWindow({content:'<a href="https://www.google.pt/maps/dir//ATS+Travel+dmc,+Rua+D.+Carlos+I+Edf.+P%C3%A9rola+do+Arade,+53C,+1%C2%BAC,+8500-607+Portim%C3%A3o/@37.133896,-8.5391927,17z/data=!4m15!1m6!3m5!1s0xd1b288d20e61713:0x2448fb59d672bf14!2sATS+Travel+dmc!8m2!3d37.133896!4d-8.537004!4m7!1m0!1m5!1m1!1s0xd1b288d20e61713:0x2448fb59d672bf14!2m2!1d-8.537004!2d37.133896" target="_blank" style="color: #000; font-size: 18px;"><strong>ATRAVEL</strong></a>'});google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);
        </script>
    </div>
    <!-- //map -->

    <!-- copy-right -->
    <div class="copy-right agileits-w3layouts">
        <div class="container">
            <div class="social-icons agileits">
                <ul>
                    <li><a target="_blank" href="https://www.facebook.com/atstravel1999"><img
                                src="{{ asset('FrontEnd/images/icons/facbook-rodape.png') }}" class="social_net alt=" facebook-footer"
                                title="facebook-footer"></a></li>
                    <li><a target="_blank" href="https://twitter.com/atstravel_dmc"><img
                                src="{{ asset('FrontEnd/images/icons/tiwwter-rodape.png') }}" class="social_net" alt="twitter-footer"
                                title="twitter-footer"></a></li>
                    <li><a target="_blank" href="https://www.instagram.com/ats_travel/"><img
                                src="{{ asset('FrontEnd/images/icons/instagramr-rodape.png') }}" class="social_net" alt="instagram-footer"
                                title="instagram-footer"></a></li>

                </ul>
                <div class="clearfix"> </div>
            </div>
            <p>Copyright © 2017 All Rights Reserved ATRAVEL by <a href="http://www.oseubackoffice.com"
                    target="_blank">oseubackoffice</a></p>
        </div>
    </div>
    <!-- copy-right -->


    @include("scripts")

</body>

</html>
