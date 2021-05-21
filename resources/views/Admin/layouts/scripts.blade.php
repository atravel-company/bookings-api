<!-- Jquery-3.2.1 -->

<script type="text/javascript" src="{{ URL::asset('Admin/js/jquery-3.2.1.min.js') }}" ></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>


<script type="text/javascript" src="{{ URL::asset('Admin/js/pagination.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/js/list.min.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/bower_components/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}" >
</script>
<script type="text/javascript" src="{{ URL::asset('Admin/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js" ></script>

<script type="text/javascript" src="{{ URL::asset('Admin/DataTables/datatables.min.js') }}" defer ></script>
<!-- SELECT2 js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" ></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
<!-- DATATABLE js-->


<script src="https://js.pusher.com/5.0/pusher.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>


<script src="{{ asset('Admin/js/ats.js') }}"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    function myAccFunc() {
        var x = document.getElementById("demoAcc");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-green";
        } else {
            x.className = x.className.replace(" w3-show", "");
            x.previousElementSibling.className = x.previousElementSibling.className.replace(" w3-green", "");
        }
    }
</script>


<script>
    $(document).ready(function(){

          // Enable pusher logging - don't include this in production
          Pusher.logToConsole = "{{config('app.pusher_debug')}}";

          var pusher = new Pusher('37abc5efe9e26526a020', {
            cluster: 'eu',
            forceTLS: true
          });

          var channel = pusher.subscribe('{{ config("app.pusherChannel") }}');

          channel.bind('get-proform-pendenting', function(data) {

            console.log('ESTOU AQUI');
            console.log(data);


            $(".iconsNavebar a i").css("padding-top", '0px');

            $("#insertPusherNotification").html('');

            $("#insertPusherNotification").append("<ul><li>"+data.message+"</li></ul>");

            if(typeof data.callbacks != 'undefined'){

              var html = "<ul>";
              $.each(data.callbacks, function(index, value){

                    html += "<li>"+value.text+" <a title='click to see' data-toggle='tooltip' href='"+value.url+"'> <i class='fa fa-edit'></i></a></li>";
              });

              html += "</ul>";

              $("#insertPusherNotification").append(html);
              $('[data-toggle="tooltip"]').tooltip();
            }

            $(".insertPusherNotificationTotal").html(data.total);
          });

      });

</script>

@stack("javascript")


{{-- <script src="{{ asset('Admin/js/app.js') }}" defer></script> --}}
