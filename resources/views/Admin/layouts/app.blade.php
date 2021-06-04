<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@php
$users_array = ['sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt', 'bookings@atravel.pt',
'accounts@atravel.pt'];
@endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('FrontEnd/favicon.ico') }}">
    <title>ATRAVEL</title>

    <!--Font-Awesome-4.7.0-->
    <link href="{{ asset('Admin/css/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('Admin/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />

    <!-- SELECT2 CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <!-- DATATABLE CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('Admin/DataTables/datatables.min.css') }}" />
    <link href="{{ asset('Admin/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('Admin/css/ats.css') }}" rel="stylesheet">
    <link href="{{ asset('Admin/css/w3.css') }}" rel="stylesheet">

    <style>
        .iconsNavebar a i.fa-exclamation-circle {
            color: white;
            text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.5);
            font-size: 25px;
            padding-top: 5px;
            padding-right: 0px;
            /* padding-bottom: 1px; */
            /* padding-left: 25px; */
        }

        .iconsNavebar a {
            padding: inherit !important;
        }

        .fa-exclamation-circle:hover {
            /* color: #ef8a46;  */
            color: rgb(124, 243, 255);
        }

        .insertPusherNotificationTotal {
            float: left;
            margin-bottom: -15px;
            padding: 3px 6px;
            color: #ffffff;
            background: #ef8a46;
            font-size: 10px;
            width: auto;
            font-weight: bold;
            border: 1px solid #d1d1d4;
        }

        .iconsNavebar .dropdown-menu {
            min-width: 500px;
            max-height: 300px;
            overflow-y: scroll;
        }

        .badge-primary {
            background: #23aec9;
        }


        .select2-container--default .select2-selection--single {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 33px;
        }
    </style>
    @stack("css")

    @include('Admin.layouts.scripts')
</head>

<body>
    <div class="loader callow" style="display: none;">
        <i class="iconloader w3-center w3-text-white fa fa-spinner fa-pulse fa-5x fa-fw"></i>
    </div>

    <div id="app">
        @if (Route::has('login'))
        @if (Auth::check())
        <div class="w3-sidebar w3-bar-block w3-card-2 w3-animate-left" style="display:none" id="mySidebar">
            <button class="w3-bar-item w3-button w3-large" onclick="w3_close()">Close &times;</button>
            <a class="w3-bar-item w3-button" href="{{ url('/') }}"> Home </a>
            @if (Route::has('login'))
            @if (Auth::check())
            @if(in_array(Auth::user()->email, $users_array))
            <a href="{{ url('/admin/locais') }}" class="w3-bar-item w3-button">Destination</a>
            <a href="{{ url('/admin/categories') }}" class="w3-bar-item w3-button">Categories</a>
            <a href="{{ url('/admin/extras') }}" class="w3-bar-item w3-button">Extras</a>
            <a href="{{ url('/admin/suppliers') }}" class="w3-bar-item w3-button">Suppliers</a>
            <a href="{{ url('/admin/produtos') }}" class="w3-bar-item w3-button">Products</a>
            <a href="{{ url('/admin/users') }}" class="w3-bar-item w3-button">Users</a>
            <a href="{{ url('/admin/groups') }}" class="w3-bar-item w3-button">Groups</a>
            <a href="{{ route('pedidos.reports.index') }}" class="w3-bar-item w3-button">Reports ADV</a>
            @endif
            <a href="{{ url('/admin/profile') }}" class="w3-bar-item w3-button">Profile</a>
            <a href="{{ url('/admin/main') }}" class="w3-bar-item w3-button">Search Products</a>
            @endif

            @endif
            <a class="w3-bar-item w3-button" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
            </a>
        </div>
        @endif
        @endif
        <div zclass="w3-main" id="main">
            <div class="w3-top  w3-container" style="background-color: #24AEC9">
                <div class="collapse navbar-collapse" id="app-navbar-collapse" style="z-index: 10;">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li>

                            @if(config('app.env') != 'local')
                            <img class="w3-padding" id="" src="{{ asset("FrontEnd/images/logoats.png") }}"
                                alt="Picture">
                            @else
                            <img class="w3-padding" id="" src="{{ asset("FrontEnd/images/logoats.png") }}">
                            @endif
                        </li>
                        @if (Route::has('login'))
                        <li>
                            @if (Auth::check())
                            <button class="w3-button" style="background-color: #24AEC9; color: white;"
                                onclick="w3_open()">&#9776;</button>
                            @endif
                        </li>
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        <li><a href="{{ route('login') }}" style="color: white;">Login</a></li>
                        <li><a href="{{ route('register') }}" style="color: white;">Register</a></li>
                        @else

                        <li class="dropdown iconsNavebar nav-item">
                            <span class="badge badge-pill badge-success insertPusherNotificationTotal"></span>
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                <i class="btn fa fa-exclamation-circle w3-right" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li style="padding: 5px 2px 2px 10px" id="insertPusherNotification">
                                    Sem notificaçoes
                                    <!-- dinamic conteudo injetado pelo pushr -->
                                </li>
                            </ul>
                        </li>

                        <!-- CARRINHO DE COMPRAS -->
                        <li>

                            <div class="w3-dropdown-click">
                                <i style="color: white; text-shadow: 0px 0px 1px rgba(0,0,0, 0.5); font-size: 25px; padding-top: 5px; padding-right: 12px; padding-bottom: 1px; padding-left: 12px;"
                                    class="btn fa fa-shopping-cart w3-right" onclick="bag()" aria-hidden="true"></i>
                                <div>
                                    <div id="total" style="display: none;"></div>
                                </div>
                            </div>
                            <div id="Demo"
                                class="w3-dropdown-content w3-bar-block w3-border w3-leftbar w3-rightbar w3-bottombar w3-topbar"
                                style="border-color: #24AEC9 !important; right: 0; width: 700px;">
                                <div class="w3-center" style=" "><b>Shopping Cart</b></div>
                                <div id="carrinho"></div>
                            </div>
                            <script>
                                var userID = {{ Auth::user()->id }};
                                    retrievedObject = JSON.parse(localStorage.getItem('array'+userID));
                                    if(retrievedObject){
                                      if(retrievedObject.length!=0){
                                        $('#total').css("display", "block");
                                      }
                                    }else{
                                      retrievedObject = [];
                                    }

                                    retrievedObject.forEach(function(entry,key) {
                                        var total= key+1;
                                        $('#total').html('<b>'+total+'</b>');
                                    });
                            </script>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false" style="color: white;">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            @yield('content')
        </div>
    </div>

    <!-- Modal Errors -->
    @if (count($errors)>0)
    <div class="modal fade" id="errorsModal" role="dialog" aria-labelledby="errorsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="errorsModalLabel">System Message</h6>
                </div>
                <div class="modal-body">
                    {{ Html::ul($errors->all()) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
                $('#errorsModal').modal('show');
                setTimeout(function(){$('#errorsModal').modal('hide')},5000);
            });
    </script>
    @endif


</body>


</html>
