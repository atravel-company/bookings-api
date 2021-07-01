$(function () {

  $('.select-simple').select2({
    placeholder: 'Select an option...',
  });

$('.datetimepicker1').datetimepicker({
        widgetParent: '#checkin1',
        format: 'MM/YYYY',
        ignoreReadonly: true,


    }).on("dp.change", function (e) {
      $('.datetimepickers1').data("DateTimePicker").minDate(e.date);
    });

    $('.datetimepickers1').datetimepicker({
       widgetParent: '#checkout1',
       format: 'MM/YYYY',
       ignoreReadonly: true,

    });
});

function analise(){
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

  var inicial = document.getElementById('in').value;
  if(!inicial){
  alert('Please select Start date!')
}

  inicial='01/'+inicial;
  var final = document.getElementById('out').value;
  if(!final){
  alert('Please select End date!')
}
  final='01/'+final;
  var produtosAlojamentos = document.getElementById('produtosAlojamentos').value;
  var produtosGolfs = document.getElementById('produtosGolfs').value;
  var usuarios = document.getElementById('usuarios').value;
if(usuarios==-1){
  alert('Please select 1 operator, or select All operators!')
}
  var val = {'inicial':inicial,'final':final, 'produtosAlojamentos':produtosAlojamentos, 'produtosGolfs':produtosGolfs, 'usuarios':usuarios};

      $.ajax({
        type:'GET',
        url:"reports/search",
        data:val,
        success: function(data){

          // console.log(data.result.pedidos)
          // console.log(data.result.produtos)
          // console.log(data.result.quartos)
          // console.log(data.result.period)
          // console.log(data.result.dias)

          $('#loading').load(assetBaseUrl+'reports/report',{ 'period':data.result.period,'pedidos': data.result.pedidos, 'produtos':data.result.produtos, 'quartos':data.result.quartos },function(){


          });

        },
        error: function (jqXHR, textStatus, errorThrown) {

          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);

          var errors = jqXHR.responseJSON;
          var errorsHtml= '';
          $.each( errors, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>';
          });
          $('#contactediterror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);

        },
      });
}

$(document).ready(function() {
   var table = $('#datatable-reports').DataTable( {
      "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ?	i : 0;
              };

              // room nights total
              rnts = api.column( 2 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              },0 );

              // bed nights total
              bedn = api.column( 3 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );



              // room nights total
              room = api.column( 7 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // golf nights total
              golf = api.column( 8 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // transfer nights total
              trans = api.column( 9 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // car nights total
              car = api.column( 10 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // extras nights total
              extras = api.column( 11 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // kback nights total
              kback = api.column( 12 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // total nights total
              total = api.column( 13 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // vpaid nights total
              vpaid = api.column( 14 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              // unpaid nights total
              unpaid = api.column( 15 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
              }, 0 );

              //  // adr nights total
              //  adr = api.column( 4 ).data().reduce( function (a, b) {
              //   return intVal(a) + intVal(b);
              // }, 0 );

              adr = parseFloat(room) / parseFloat(rnts);

              // ATS

              if($('#ats_profit').data('condition') == true){
                // unpaid nights total
                room_ats = api.column( 16 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                golf_ats = api.column( 17 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                transfer_ats = api.column( 18 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                car_ats = api.column( 19 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                extras_ats = api.column( 20 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                total_ats = api.column( 21 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
                // unpaid nights total
                profit_ats = api.column( 22 ).data().reduce( function (a, b) {
                  return intVal(a) + intVal(b);
                }, 0 );
              }

              rnts = parseFloat(rnts);
              bedn = parseFloat(bedn);
              adr = parseFloat(adr);
              room = parseFloat(room);
              golf = parseFloat(golf);
              trans = parseFloat(trans);
              car = parseFloat(car);
              extras = parseFloat(extras);
              kback = parseFloat(kback);
              total = parseFloat(total);
              vpaid = parseFloat(vpaid);
              unpaid = parseFloat(unpaid);

              if($('#ats_profit').data('condition') == true){
                // ATS
                room_ats = parseFloat(room_ats);
                golf_ats = parseFloat(golf_ats);
                transfer_ats = parseFloat(transfer_ats);
                car_ats = parseFloat(car_ats);
                extras_ats = parseFloat(extras_ats);
                total_ats = parseFloat(total_ats);
                profit_ats = parseFloat(profit_ats);
              }
              // Update footer
            // Update footer
            $( api.column( 2 ).footer() ).html(rnts.toFixed(2));
            $( api.column( 3 ).footer() ).html(bedn.toFixed(2));
            $( api.column( 4 ).footer() ).html(adr.toFixed(2) +' €');
            $( api.column( 5 ).footer() ).html('-');
            $( api.column( 6 ).footer() ).html('-');
            $( api.column( 7 ).footer() ).html(room.toFixed(2) +' €');
            $( api.column( 8 ).footer() ).html(golf.toFixed(2) +' €');
            $( api.column( 9 ).footer() ).html(trans.toFixed(2) +' €');
            $( api.column( 10 ).footer() ).html(car.toFixed(2) +' €');
            $( api.column( 11 ).footer() ).html(extras.toFixed(2) +' €');
            $( api.column( 12 ).footer() ).html(kback.toFixed(2) +' €');
            $( api.column( 13 ).footer() ).html(total.toFixed(2) +' €');
            $( api.column( 14 ).footer() ).html(vpaid.toFixed(2) +' €');
            $( api.column( 15 ).footer() ).html(unpaid.toFixed(2) +' €');

            if($('#ats_profit').data('condition') == true){
              // ATS
              $( api.column( 16 ).footer() ).html(room_ats.toFixed(2) +' €');
              $( api.column( 17 ).footer() ).html(golf_ats.toFixed(2) +' €');
              $( api.column( 18 ).footer() ).html(transfer_ats.toFixed(2) +' €');
              $( api.column( 19 ).footer() ).html(car_ats.toFixed(2) +' €');
              $( api.column( 20 ).footer() ).html(extras_ats.toFixed(2) +' €');
              $( api.column( 21 ).footer() ).html(total_ats.toFixed(2) +' €');
              $( api.column( 22 ).footer() ).html(profit_ats.toFixed(2) +' €');
            }
        },
        dom: 'Bfrtip',
        buttons: [
             'colvis', 'print', 'excel',
        ],
        format: 'd/m/y',
        pageLength: 20,
        autoWidth: true,
        responsive: true,
        drawCallback: function(settings) {
            functionAfterRenderDatatable();
        }
    } );



    $(".reset").click(function() {
      $(this).closest('form').find("input[type=text], textarea").val("");
    });

    $('#print-prof').on('click', function(){

      var start = $("#start").val();
      var end = $("#end").val();

      window.open('/admin/reports/pedidosreports/print/dados?start='+start+'&end='+end, "Voucher", "width=1000,height=800","_blank");
     /*  window.open('/reports/pedidosreports/print/'+vid + "&modal=true", "Voucher","width=1000,height=800","_blank"); */
   });
} );

  $( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
  } );
  $( function() {
    $( ".datepicker2" ).datepicker({ dateFormat: 'dd/mm/yy' });
  } );
