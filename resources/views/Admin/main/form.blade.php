<!-- i {
color: #fff;
    font-family: weather;
    font-size: 15px;
    font-weight: normal;
    font-style: normal;
    line-height: 0.1;
}
-->

<link href="{{ asset('Admin/css/font_ats.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('Admin/js/principal.js') }}"></script>


<script type="text/javascript">
    var formpeople = new Array();

    function removeName(id, numb) {

        try {
            formpeople[numb] = parseInt(formpeople[numb]) - 1;
            room = $('#room' + numb).val();

            if (room > formpeople[numb]) {
                formpeople[numb] = parseInt(formpeople[numb]) + 1;
            }

            $('#plusName' + id + 'numb' + numb).children().last().remove();

        } catch (error) {
            console.log(error);
        }

    }

    function plusName(id, numb) {

        try {
            formpeople[numb] = parseInt(formpeople[numb]) + 1;

            people = $('#people' + numb).val();

            if (people >= formpeople[numb]) {
                $('#plusName' + id + 'numb' + numb).append('<div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="roomname" placeholder="Name"><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input w3-text-amber fats fats-people"></i></span></div></div>')
            } else {
                formpeople[numb] = parseInt(formpeople[numb]) - 1;

            }

        } catch (error) {
            console.log(error);
        }


    }

    function peopleChange(numb) {


        try {

            //$('#room'+numb).val(null)

            people = $('#people' + numb).val();


            /* REMOVIDO PELO FELIX 03/2020 */
            // if(people == '' || people ==0){
            //     document.getElementById('room'+numb).readOnly = true;
            //     $('#name'+numb).empty();
            // }
            // else{
            //     document.getElementById('room'+numb).removeAttribute('readonly');
            //     $('#name'+numb).empty();
            // }


            if (people == 0) {
                $('#people' + numb).val('');
            }

            if (people >= parseInt($('#room' + numb).val()) || people == '') {

            } else {

                if ($('#room' + numb).val() <= 0) {
                    return false;
                }

                document.getElementById('modalAlert').style.display = 'block'
                //$('#room'+numb).val(null)
            }

        } catch (error) {
            console.log(error);
        }



    }



    function blablabla(x, numb) {

        try {

            formpeople[numb] = x.value;

            $('#name' + numb).empty();


            people = $('#people' + numb).val();
            contador = x.value;

            /* REMOVIDO PELO FELIX 03/2020 */
            // if(x.value >= 5){
            //     contador = 5;
            // }else{
            // }


            if (people >= parseInt(x.value) || people == '') {
                for (u = 0; u < contador; u++) {

                    v = u + 1;

                    $('#name' + numb).append('<div class="w3-row-padding"><div class="w3-col l2 m2 s6"><label class="ats-label">Room</label><div class="form-group"><div class="btn-group"><div class="input-group"><input type="number" readonly class="form-control ats-border-color room-' + u + '" value="' + v + '" name="roomnumber"><span class="input-group-addon ats-border-color dropdown-toggle"  style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input w3-text-amber fats fats-keys"></i></span></div></div> </div>  </div>                  <div class="w3-col l5 m5 s12"><label class="ats-label">Remark</label><div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="roomremark" placeholder="Remark"><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px"><i class="fats-correct-input w3-text-amber fats fats-edit"></i></span></div></div></div>      <div class="w3-col l3 m5 s12"><label class="ats-label">Name</label>         <div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="roomname" placeholder="Name"><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input w3-text-amber fats fats-people"></i></span></div></div>        <div id="plusName' + u + 'numb' + numb + '"></div>                   </div>          <div class="w3-col l1 m5 s12"><label class="ats-label"></label><div onclick="plusName(' + u + ',' + numb + ')" class="form-group w3-center"><div class="input-group" style="left: 50%;"><i class="fa fa-plus w3-text-amber"></i></div></div></div>              <div class="w3-col l1 m5 s12"><label class="ats-label"></label><div onclick="removeName(' + u + ',' + numb + ')" class="form-group w3-center"><div class="input-group"><i class="fa fa-minus w3-text-red"></i></div></div></div>    </div>');
                }

            } else {


                if (x.value <= 0) {
                    return false;
                }

                document.getElementById('modalAlert').style.display = 'block'
                //$('#room'+numb).val(null)
            }


        } catch (error) {
            console.log(error);
        }


    }



    function clickPlan(id) {
        $('.plan' + id).hasClass('out') ? $('.plan' + id).css('display', 'block').removeClass('out') : $('.plan' + id).addClass('out').css('display', 'none')
    }


    function acordion() {
        var x = document.getElementById('acordion');

        if (x.style.display === 'none') {
            x.style.display = 'block';
        } else {
            x.style.display = 'none';
        }
    }


    i = 0;

    function myFunction() {

        var input = document.createElement("input");
        var label = document.createElement('Label');

        input.type = "text";
        input.id = 'name' + i;
        input.name = 'name' + i;
        label.innerHTML = "Name";
        i++;
        input.placeholder = 'Name ' + i;
        input.className = "w3-input w3-border w3-margin-bottom"; // set the CSS class
        document.getElementById("inputname").appendChild(label);
        document.getElementById("inputname").appendChild(input); // put it into the DOM
        document.getElementById("inputnamenumber").innerHTML = i + ' More People';

    }
    j = 1;

    function myFunction2() {

        var inputtype = document.createElement("input");
        var labeltype = document.createElement('Label');

        inputtype.type = "text";
        inputtype.id = 'type' + j;
        inputtype.name = 'type' + j;
        labeltype.innerHTML = "Type";

        inputtype.className = "w3-input w3-border w3-margin-bottom"; // set the CSS class
        document.getElementById("inputtype").appendChild(labeltype);
        document.getElementById("inputtype").appendChild(inputtype); // put it into the DOM

        var inputamount = document.createElement("input");
        var labelamount = document.createElement('Label');

        inputamount.type = "number";
        inputamount.setAttribute("min", 1);
        inputamount.id = 'amount' + j;
        inputamount.name = 'amount' + j;
        labelamount.innerHTML = "Amount";

        inputamount.className = "w3-input w3-border w3-margin-bottom"; // set the CSS class
        document.getElementById("inputamount").appendChild(labelamount);
        document.getElementById("inputamount").appendChild(inputamount); // put it into the DOM


        var array = ['RO', 'SC', 'BB', 'HB', 'FB', 'SEMI AI', 'AI'];

        var inputplan = document.createElement("select");
        var labelplan = document.createElement('Label');

        inputplan.id = 'plan' + j;
        inputplan.name = 'plan' + j;
        labelplan.innerHTML = "Plan";

        inputplan.className = "w3-input w3-border"; // set the CSS class
        inputplan.style = "margin-bottom: 19px;";
        for (var i = 0; i < array.length; i++) {
            var option = document.createElement("option");
            option.setAttribute("value", array[i]);
            option.text = array[i];
            inputplan.appendChild(option);
        }
        document.getElementById("inputplan").appendChild(labelplan);
        document.getElementById("inputplan").appendChild(inputplan); // put it into the DOM
        j++;
    }



    $(function() {

        try {


            if ($("#room0").length >= 1) {
                $("#room0").removeAttr("readonly");
            }

            $('.datetimepicker1').datetimepicker({
                widgetParent: '#checkin1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true
            , }).on("dp.change", function(e) {
                $('.datetimepickers1').data("DateTimePicker").minDate(e.date);
            });

            $('.datetimepickers1').datetimepicker({
                widgetParent: '#checkout1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true,

            });


            $('.datetimepickergolf1').datetimepicker({
                widgetParent: '#data1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true,


            });

            $('.datetimepickersgolf1').datetimepicker({
                widgetParent: '#hora1'
                , format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datecar').datetimepicker({
                // widgetParent: '#datacar',
                format: 'DD/MM/YYYY'
                , ignoreReadonly: true
            , });

            $('.hourcar').datetimepicker({
                //    widgetParent: '#hourcar',
                format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datecar2').datetimepicker({
                // widgetParent: '#datecar2',
                format: 'DD/MM/YYYY'
                , ignoreReadonly: true,


            });

            $('.hourcar2').datetimepicker({
                //    widgetParent: '#hourcar2',
                format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datetimepickertransfer1').datetimepicker({
                widgetParent: '#datatransfer1'
                , format: 'DD/MM/YYYY HH:mm'
                , ignoreReadonly: true
                , defaultDate: moment({
                    h: 0
                    , m: 0
                    , s: 0
                })
            , });
            var d = new Date();
            var month = d.getMonth();
            var day = d.getDate();
            var year = d.getFullYear();
            $(".datetimepickertransfer1").data('datetimepicker').setLocalDate(new Date(year, month, day, 00, 00));

            $('.datetimepickerstransfer1').datetimepicker({
                widgetParent: '#horatransfer1'
                , format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datetimepickercarup1').datetimepicker({
                widgetParent: '#datacarup1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true,


            });

            $('.datetimepickerscarup1').datetimepicker({
                widgetParent: '#horacarup1'
                , format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datetimepickercaroff1').datetimepicker({
                widgetParent: '#datacaroff1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true,


            });

            $('.datetimepickerscaroff1').datetimepicker({
                widgetParent: '#horacaroff1'
                , format: 'HH:mm'
                , ignoreReadonly: true,

            });

            $('.datetimepickerticket1').datetimepicker({
                widgetParent: '#dataticket1'
                , format: 'DD/MM/YYYY'
                , ignoreReadonly: true,


            });

            $('.datetimepickersticket1').datetimepicker({
                widgetParent: '#horaticket1'
                , format: 'HH:mm'
                , ignoreReadonly: true,

            });
        } catch (error) {
            console.log(error);
        }



    });

</script>



<style>
    [type="checkbox"] {
        vertical-align: middle;
    }


    input.desativo:read-only {
        background-color: #E8E8E8 !important;
    }

    .geral {
        position: relative;
        width: 22.86%;
        height: 35px;
        display: inline-block;
    }

    .alojamento {
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 10;
        color: white;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .golf {
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 8;
        color: white;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .transfer {
        float: right;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 6;
        color: white;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .rent {
        float: right;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 4;
        color: white;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .ticket {
        float: right;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 2;
        color: white;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .shadowA {
        z-index: 9;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        right: -1%;
        top: -1%;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .shadowG {
        z-index: 7;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        right: -1%;
        top: -1%;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .shadowT {
        z-index: 5;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        right: -1%;
        top: -1%;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .shadowR {
        z-index: 3;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        right: -1%;
        top: -1%;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .shadowTI {
        z-index: 1;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 100%;
        right: -1%;
        top: -1%;
        clip-path: polygon(0 0, 81% 0, 90% 100%, 0% 100%);
    }

    .input-group-addon {
        padding: 5px 5px !important;
    }

    .form-control {
        padding: 6px 8px !important;
    }
</style>


<div style="display: flex; padding: 18px 0 18px 0; text-align: center;background-color:#24AEC9;">
    <div class="w3-col l11">

        <div class="w3-row-padding">

            <div class="w3-col l3 m3 s6">
                <label class="ats-label" style="color:#fff!important;">Type <i style="color: red;">*</i> </label>
                <div class="form-group">
                    <div class="input-group" style="margin:0 auto;">
                        <select class="form-control border-radius"
                            style="border: 1px solid #24AEC9!important; width: 150% !important; margin:0 auto;"
                            name="tipopedido">
                            <option value="0" selected>Select</option>
                            <option value="New Booking">New Booking</option>
                            <option value="Cotation">Quotation</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m3 s6">
                <label class="ats-label" style="color:#fff!important;">Lead Name<i style="color: red;">*</i></label>
                <div class="form-group">
                    <div class="input-group" style="margin:0 auto;">
                        <input required type="text" class="form-control border-radius"
                            style="border: 1px solid #24AEC9!important; margin:0 auto;" name="leadpedido"
                            id="leadpedido" placeholder="Type your name">
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m3 s6">
                <label class="ats-label" style="color:#fff!important;">Responsible<i style="color: red;">*</i></label>
                <div class="form-group">
                    <div class="input-group" style="margin:0 auto;">
                        <input required type="text" class="form-control border-radius"
                            style="border: 1px solid #24AEC9!important; margin:0 auto;" name="responsavelpedido"
                            id="responsavelpedido" placeholder="Sale Responsible">
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m3 s6">
                <label class="ats-label" style="color:#fff!important;">Reference</label>
                <div class="form-group">
                    <div class="input-group" style="margin:0 auto;">
                        <input type="text" class="form-control border-radius"
                            style="border: 1px solid #24AEC9!important; margin:0 auto;" name="referenciapedido"
                            id="referenciapedido" placeholder="Type your reference">
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="w3-col l1">
        <div class="w3-center w3-col w3-white">
            <span style="background-color:#d8281b; color:#fff;"
                onclick="document.getElementById('formModal').style.display='none'; $('#bankcreatedestinyerror').empty(); $('html').removeClass('modal-open');"
                class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
        </div>
    </div>

</div>
{!! Form::open(['id'=>'form1']) !!}

<input type="hidden" name="formulario" value="{{$produto->id}}">
<div class="w3-white w3-padding-16 w3-container" style="padding:0!important;">



    <div class="w3-col l12 w3-white">

    </div>

    <!----------------------------------------------------MENU-------------->

    <style>
        body {
            min-width: 275px !important
        }

        .ats-text-color {
            color: #24AEC9 !important
        }

        .ats-color {
            background: #24AEC9
        }

        .ats-border-color {
            border: 1px solid #24AEC9
        }

        .ats-label {
            margin-bottom: 0px;
            font-weight: 500;
            font-size: 13px;
            color: #24AEC9 !important
        }

        .input-group-addon {
            background: #F9F9F9;
            cursor: pointer
        }

        input.form-control {
            border-right: 0px !important
        }

        input {
            background-color: #FFF !important
        }

        .dropdown-menu {
            width: 100% !important;
            min-width: 100%
        }

        .dropdown-menu>li:hover {
            background: #CCC
        }

        .myselectroom1,
        .myselectroom2,
        .myselectplan {
            border-left: 0px;
            border-top-right-radius: 4px !important;
            border-bottom-right-radius: 4px !important
        }

        .dropdown-menu>li {
            padding: 3px 10px
        }

        .w3-border-bottom-ats {
            border-bottom: 1px solid #24AEC9 !important
        }

        .border-radius {
            border-radius: 4px !important
        }

        .w3-padding-4 {
            padding: 4px 0px !important
        }

        .w3-padding-8 {
            padding: 8px 0px !important
        }

        .check-txt {
            margin-left: 3px;
            vertical-align: bottom
        }

        input[type=checkbox] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .mybtn {
            padding: 6px 12px;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0
        }

        input[type="number"] {
            -moz-appearance: textfield
        }

        .fats-correct {
            font-size: 33px;
            margin: -6px -7px -7px -7px
        }

        .fats-correct-input {
            font-size: 31px;
            margin: -6px -7px -9px -7px
        }

        .fl-left {
            float: left
        }



        @media (max-width: 601px) {

            .w3-row-padding,
            .w3-row-padding>.w3-half,
            .w3-row-padding>.w3-third,
            .w3-row-padding>.w3-twothird,
            .w3-row-padding>.w3-threequarter,
            .w3-row-padding>.w3-quarter,
            .w3-row-padding>.w3-col {
                padding: 0 2px;
            }
        }
    </style>


    @if($produto->alojamento==1)
    <div id="alojamento">
        @else
        <div style="display:none;" id="alojamento">
            @endif

            <div class="w3-col l12" style="border-top: solid 3px; border-color: #24AEC9;"></div>

            <div class="w3-light-gray" style="padding: 0 20px 20px 20px;!important">

                <!-- TITLE HEADER -->



                <div class="w3-row-padding">

                    <div class="w3-col l12">
                        <h3 class="w3-center ats-text-color w3-border-bottom-ats w3-padding-16"
                            style="margin-top:0px!important;">{{$produto->nome}}</h3>
                    </div>

                </div>

                <!-- ROW 1 -->

                <div class="w3-row-padding">
                    <div class="w3-col l12">

                        <div class="fl-left" style="margin-right:16px">
                            <label class="ats-label">New</label>
                            <div class="form-group">
                                <span onclick="criaquarto()" class="btn ats-border-color border-radius w3-white mybtn"
                                    style="
                            height: 34px;
                                ">
                                    <i class="fats-correct ats-text-color fats fats-new"></i>
                                </span>
                            </div>
                        </div>
                        <div class="fl-left" style="margin-right:16px">
                            <label class="ats-label">Remove</label>
                            <div class="form-group">
                                <span onclick="removequarto()"
                                    class=" btn ats-border-color border-radius w3-white mybtn" style="height: 34px;">
                                    <i class="fa fa-minus ats-text-color"></i>
                                </span>
                            </div>
                        </div>




                    </div>
                </div>

                <div class="w3-row-padding">

                    <div class="w3-col l2 m4 s12">
                        <label class="ats-label">Check-In<i style="color: red;">*</i></label>
                        <div class="form-group">

                            <div class='input-group date datetimepicker1' id='datetimepicker12'
                                style="position: relative;">
                                <div style="width: 330px; position: absolute;" id="checkin1"></div>
                                <input type='text' name="in" id="in" class="form-control ats-border-color roomCheckin"
                                    placeholder="Check-In" />
                                <span class="input-group-addon ats-border-color">
                                    <span class="w3-large ats-text-color fa fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="w3-col l2 m4 s12">
                        <label class="ats-label">Check-Out<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="input-group date datetimepickers1" id='datetimepicker13'
                                style="position: relative;">
                                <div style="width: 330px; position: absolute;" id="checkout1"></div>
                                <input type="text" class="form-control ats-border-color roomCheckout" id="out"
                                    name="out" placeholder="Check-Out">
                                <span class="input-group-addon ats-border-color">
                                    <span class="w3-large ats-text-color fa fa-calendar"></span>
                                </span>
                            </div>

                        </div>
                    </div>




                    @if($produto->alojamento==1)
                    <div class="w3-col l2 m4 s6">
                        <label class="ats-label">Type<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ats-border-color" name="type"
                                    placeholder="Double Room">
                                <span class="input-group-addon ats-border-color" style="
                                    height: 23px;
                                padding-bottom: 0px;
                                padding-top: 8px;
                                ">
                                    <i class="fats-correct-input ats-text-color fats fats-type"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($produto->alojamento==1)
                    <div class="w3-col l2 m4 s6">
                        <label class="ats-label">People<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input required onkeyup="peopleChange(0)" type="number"
                                    class="form-control ats-border-color" id="people0" name="people"
                                    placeholder="People">
                                <span class="input-group-addon ats-border-color" style="
                                    height: 23px;
                                padding-bottom: 0px;
                                padding-top: 8px;
                                ">
                                    <i class="fats-correct-input ats-text-color fats fats-people"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="w3-col l2 m4 s6">
                        <label class="ats-label">People<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input onkeyup="peopleChange(0)" type="number" class="form-control ats-border-color"
                                    id="people0" name="people" placeholder="People">
                                <span class="input-group-addon ats-border-color" style="
                                    height: 23px;
                                padding-bottom: 0px;
                                padding-top: 8px;
                                ">
                                    <i class="fats-correct-input ats-text-color fats fats-people"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif


                    <div class="w3-col l2 m4 s6">
                        <label class="ats-label">Rooms<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" class="form-control ats-border-color" name="room"
                                    onKeyUp="blablabla(this,0)" id="room0" placeholder="Rooms">
                                <span class="input-group-addon ats-border-color"
                                    style="height: 23px; padding-bottom: 0px; padding-top: 8px;">
                                    <i class="fats-correct-input ats-text-color fats fats-keys"></i>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="w3-col l2 m4 s6">
                        <label class="ats-label">Plan<i style="color: red;">*</i></label>
                        <div class="form-group">
                            <div class="btn-group">
                                <div class="input-group">
                                    <input type="text" readonly class="form-control ats-border-color plan-0" value="BB"
                                        name="plan">
                                    <span class="input-group-addon ats-border-color dropdown-toggle myselectplan"
                                        onclick="clickPlan(0)" style="
                            height: 23px;
                                padding-bottom: 0px;
                                padding-top: 8px;
                                ">
                                        <i class="fats-correct-input ats-text-color fats fats-plan"></i>
                                    </span>
                                    <ul class="dropdown-menu plan0 out">

                                        <li
                                            onclick="$('.plan-0').val('AI');$('.plan0').addClass('out').css('display','none')">
                                            AI</li>
                                        <li
                                            onclick="$('.plan-0').val('BB');$('.plan0').addClass('out').css('display','none')">
                                            BB</li>
                                        <li
                                            onclick="$('.plan-0').val('FB');$('.plan0').addClass('out').css('display','none')">
                                            FB</li>
                                        <li
                                            onclick="$('.plan-0').val('HB');$('.plan0').addClass('out').css('display','none')">
                                            HB</li>
                                        <li
                                            onclick="$('.plan-0').val('RO');$('.plan0').addClass('out').css('display','none')">
                                            RO</li>
                                        <li
                                            onclick="$('.plan-0').val('SC');$('.plan0').addClass('out').css('display','none')">
                                            SC</li>



                                        <li
                                            onclick="$('.plan-0').val('SEMI AI');$('.plan0').addClass('out').css('display','none')">
                                            SEMI AI</li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- ROW 2 -->
                    <div id="name0">

                    </div>
                </div>




                <div id='reservas'>
                </div>

                <!--ROW 3 -->

                <div class="w3-row-padding">
                    <div class="w3-col l12">
                        <label class="ats-label">Extras</label>
                        <div class="form-group">
                            <div class="ats-border-color w3-white border-radius w3-padding-4">
                                <div class="w3-row-padding">
                                    @foreach($produto->extras as $key=>$extra)
                                    @if($extra->pivot->formulario=='alojamento')
                                    <div class="w3-col l3 m4 s6">
                                        {{ Form::checkbox('chalojamento'.$produto->id.'[]',$extra->id,null,['class'=>'w3-checkbox checkbox_extras']) }}&nbsp;&nbsp;&nbsp;<input
                                            class="form-control hidden"
                                            style='width: 40px;display: inline;height: 27px; margin-right:8px; background-color: #e9edf0 !important;'
                                            id="AlojamentoExtrasQuantidade{{$extra->id}}" value="0" style="width: 40px;"
                                            type="number" name=""><span class="check-txt">{{$extra->name}}</span></div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- REMARKS -->

                <div class="w3-row-padding">
                    <div class="w3-col l12">
                        <label class="ats-label">Remark</label>
                        <div class="form-group">
                            <textarea style="width:100%;height:75px" class="form-control ats-border-color border-radius"
                                name="remarkalojamento" id="remarkalojamento" placeholder="Your Remarks"></textarea>
                        </div>
                    </div>
                </div>
            </div>




            <script>
                $('.myselectroom1').click(function() {
                    $('.room1').hasClass('out') ? $('.room1').css('display', 'block').removeClass('out') : $('.room1').addClass('out').css('display', 'none')
                })

            </script>


        </div>

        @if($produto->alojamento!=1)
        @if($produto->golf==1)
        <div id="golf">
            @else
            <div style="display:none;" id="golf">
                @endif
                @else
                <div style="display:none;" id="golf">
                    @endif

                    <div class="w3-light-gray">


                        <!-- TITLE HEADER -->
                        <div class="w3-col l12" style="border-top: solid 3px; border-color: #24AEC9;"></div>

                        <div class="w3-row-padding">

                            <div class="w3-col l12">
                                <h3 class="title_form_book w3-center ats-text-color w3-border-bottom-ats w3-padding-16"
                                    style="margin-top:0px!important;"></h3>
                            </div>

                        </div>

                        <div class="w3-row-padding">

                            <div class="w3-col l12" style="text-align:center;">
                                <span style="display:inline-flex; margin-top:38px; cursor:pointer;" onclick="criagolf()"
                                    class="pull-left ats-border-color border-radius w3-white mybtn">
                                    <span class="w3-large ats-text-color fa fa-plus-circle"></span>
                                </span>
                                <span onclick="removegolf()"
                                    class="pull-left ats-border-color border-radius w3-white mybtn"
                                    style="display:inline-flex; margin-left:5px;  margin-top:38px; cursor:pointer;">
                                    <i class="w3-large ats-text-color fa fa-minus-circle"></i>
                                </span>
                                {{-- <h3 style="display:inline-flex; text-align:center; margin-left:-18px;" class="w3-center ats-text-color w3-border-bottom-ats w3-padding-16 pull-center" style="margin-top:0px!important;">Golf</h3> --}}
                            </div>

                        </div>



                        <!-- DATES-->



                        <div class="w3-row-padding">

                            <div class="w3-col l3 m5 s12">
                                <label class="ats-label">Date</label>
                                <div class="form-group">

                                    <div class='input-group date datetimepickergolf1' style="position: relative;">
                                        <div style="width: 330px; position: absolute;" id="data1"></div>
                                        <input type='text' name="datagolf" id="datagolf"
                                            class="form-control ats-border-color" placeholder="Check-In" />
                                        <span class="input-group-addon ats-border-color">
                                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <div class="w3-col l3 m5 s12">
                                <label class="ats-label">Tee Time Request</label>
                                <div class="form-group">


                                    <div class='input-group date datetimepickersgolf1' style="position: relative;">
                                        <div style="width: 330px; position: absolute;" id="hora1"></div>
                                        <input type='text' name="horagolf" id="horagolf"
                                            class="form-control ats-border-color" placeholder="Check-In" />
                                        <span class="input-group-addon ats-border-color">
                                            <span class="w3-large ats-text-color fa fa-clock-o"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <style type="text/css">

                            </style>
                            <div class="w3-col l4 m4 s6">
                                <label class="ats-label">Golf Course</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control ats-border-color" name="coursegolf"
                                            placeholder="Golf Course">

                                        <span class="input-group-addon ats-border-color">
                                            <span class="w3-large ats-text-color fa fa-flag "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="w3-col l2 m4 s6">
                                <label class="ats-label">People</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" class="form-control ats-border-color" name="peoplegolf"
                                            value="1" placeholder="People">
                                        <span class="input-group-addon ats-border-color">
                                            <span
                                                class="w3-large ats-text-color glyphicon glyphicon-world fats fats-people"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div id="golfReservas">


                        </div>

                        <!--EXTRAS -->

                        <div class="w3-row-padding">
                            <div class="w3-col l12">
                                <label class="ats-label">Extras</label>
                                <div class="form-group">
                                    <div class="ats-border-color w3-white border-radius w3-padding-4">

                                        <div class="w3-row-padding">
                                            @foreach($produto->extras as $key=>$extra)
                                            @if($extra->pivot->formulario=='golf')
                                            <div class="w3-col l3 m4 s6">
                                                {{ Form::checkbox('chgolf'.$produto->id.'[]',$extra->id,null,['class'=>'w3-checkbox checkbox_extras']) }}&nbsp;&nbsp;&nbsp;<input
                                                    class="form-control hidden"
                                                    style='width: 40px;display: inline;height: 27px; margin-right:8px; background-color: #e9edf0 !important;'
                                                    id="GolfExtrasQuantidade{{$extra->id}}" value="0"
                                                    style="width: 40px;" type="number" name=""><span
                                                    class="check-txt">{{$extra->name}}</span></div>
                                            @endif
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- REMARKS -->

                        <div class="w3-row-padding">
                            <div class="w3-col l12">
                                <label class="ats-label">Remark</label>
                                <div class="form-group">
                                    <textarea style="width:100%;height:75px"
                                        class="form-control ats-border-color border-radius" name="remarkgolf"
                                        id="remarkgolf" placeholder="Your Remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @if($produto->alojamento!=1)
                @if($produto->golf!=1)
                @if($produto->transfer==1)

                <div id="transfer">
                    @else
                    <div style="display:none;" id="transfer">
                        @endif
                        @else
                        <div style="display:none;" id="transfer">
                            @endif
                            @else
                            <div style="display:none;" id="transfer">
                                @endif

                                <div class="w3-col l12" style="border-top: solid 3px; border-color: #24AEC9;"></div>
                                <div class="w3-light-gray" style="padding: 0 20px 20px 20px;!important">

                                    <div class="w3-row-padding">

                                        <div class="w3-col l12" style="text-align:center;">
                                            <span style="display:inline-flex; margin-top:38px; cursor:pointer;"
                                                onclick="criaTransfer()"
                                                class="pull-left ats-border-color border-radius w3-white mybtn">
                                                <span class="w3-large ats-text-color fa fa-plus-circle"></span>
                                            </span>
                                            <span onclick="removetransfer()"
                                                class="pull-left ats-border-color border-radius w3-white mybtn"
                                                style="display:inline-flex; margin-left:5px;  margin-top:38px; cursor:pointer;">
                                                <i class="w3-large ats-text-color fa fa-minus-circle"></i>
                                            </span>
                                            <h3 style="display:inline-flex; text-align:center; margin-left:-18px;"
                                                class="title_form_book w3-center ats-text-color w3-border-bottom-ats w3-padding-16 pull-center"
                                                style="margin-top:0px!important;">Transfers</h3>
                                        </div>

                                    </div>

                                    <!-- DATES-->


                                    <div class="w3-row-padding">

                                        <div class="w3-col l2 m5 s12">
                                            <label class="ats-label">Date and Time</label>
                                            <div class="form-group">
                                                <div class="input-group date datetimepickertransfer1">
                                                    <div style="width: 330px; position: absolute;" id="datatransfer1">
                                                    </div>
                                                    <input type="text" class="form-control ats-border-color"
                                                        name="datatransfer" placeholder="Date">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w3-col l1 m4 s6">
                                            <label class="ats-label">Adults <span class="w3-tiny">(>11 Y)</span></label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control ats-border-color"
                                                        name="adultstransfer">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fats fats-people"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w3-col l1 m4 s6">
                                            <label class="ats-label">Children <span class="w3-tiny">(3-10
                                                    Y)</span></label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control ats-border-color"
                                                        name="childrenstransfer">
                                                    <span class="input-group-addon ats-border-color ">
                                                        <span class="w3-large ats-text-color fa fa-child"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w3-col l1 m4 s6">
                                            <label class="ats-label">Babies <span class="w3-tiny">(0-2 Y)</span></label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control ats-border-color"
                                                        name="babiestransfer">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fa fa-child"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w3-col l1 m4 s6">
                                            <label class="ats-label">Flight Nº</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control ats-border-color"
                                                        name="flighttransfer">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fa fa-plane"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w3-col l3 m12 s12">
                                            <label class="ats-label">Pick-up</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control ats-border-color"
                                                        name="pickuptransfer" placeholder="Pick-up">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fa fa-map-marker"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w3-col l3 m12 s12">
                                            <label class="ats-label">Drop Off</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control ats-border-color"
                                                        name="dropofftransfer" placeholder="Drop Off">
                                                    <span class="input-group-addon ats-border-color">
                                                        <span class="w3-large ats-text-color fa fa-map-marker"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tranferReservas">

                                    </div>

                                    <div class="w3-row-padding">
                                        <div class="w3-col l12">
                                            <label class="ats-label">Extras</label>
                                            <div class="form-group">
                                                <div class="ats-border-color w3-white border-radius w3-padding-4">

                                                    <div class="w3-row-padding">
                                                        @foreach($produto->extras as $key=>$extra)
                                                        @if($extra->pivot->formulario=='transfer')
                                                        <div class="w3-col l3 m4 s6">
                                                            {{ Form::checkbox('chtransfer'.$produto->id.'[]',$extra->id,null,['class'=>'w3-checkbox checkbox_extras']) }}&nbsp;&nbsp;&nbsp;<input
                                                                class="form-control hidden"
                                                                style='width: 40px;display: inline;height: 27px; margin-right:8px; background-color: #e9edf0 !important;'
                                                                id="TransferExtrasQuantidade{{$extra->id}}" value="0"
                                                                style="width: 40px;" type="number" name=""><span
                                                                class="check-txt">{{$extra->name}}</span></div>
                                                        @endif
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- REMARKS -->

                                    <div class="w3-row-padding">
                                        <div class="w3-col l12">
                                            <label class="ats-label">Remark</label>
                                            <div class="form-group">
                                                <textarea style="width:100%;height:75px"
                                                    class="form-control ats-border-color border-radius"
                                                    name="remarktransfer" id="remarktransfer"
                                                    placeholder="Your Remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            @if($produto->alojamento!=1)
                            @if($produto->golf!=1)
                            @if($produto->transfer!=1)
                            @if($produto->car==1)
                            <div id="car">
                                @else
                                <div style="display:none;" id="car">
                                    @endif
                                    @else
                                    <div style="display:none;" id="car">
                                        @endif
                                        @else
                                        <div style="display:none;" id="car">
                                            @endif
                                            @else
                                            <div style="display:none;" id="car">
                                                @endif

                                                <div class="w3-light-gray w3-padding-16">

                                                    <!-- TITLE HEADER -->

                                                    <div class="w3-col l12"
                                                        style="border-top: solid 3px; border-color: #24AEC9;"></div>
                                                    <div class="w3-row-padding">
                                                        <div class="w3-col l12">
                                                            <h3 class="w3-center ats-text-color w3-border-bottom-ats w3-padding-16 title_form_book"
                                                                style="margin-top:0px!important;">Rent a Car</h3>
                                                        </div>
                                                    </div>

                                                    <!-- PICK UP -->

                                                    <div class="w3-row-padding">


                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Pick-up Location</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="pickupcar" placeholder="Pick-up Location">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-map-marker"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>




                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Pick-up Date</label>
                                                            <div class="form-group">
                                                                <div class="input-group date datetimepickercaroff">
                                                                    <div style="width: 330px; position: absolute;"
                                                                        id="datacaroff"></div>
                                                                    <input type="text"
                                                                        class="form-control ats-border-color datecar"
                                                                        name="pickupdatacar" placeholder="Pick-up Date">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-calendar"
                                                                            id="datecar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="w3-col l2 m4 s6">
                                                            <label class="ats-label">Pick-up Hour</label>
                                                            <div class="form-group">
                                                                <div class="input-group date">
                                                                    <div style="width: 330px; position: absolute;">
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control ats-border-color hourcar"
                                                                        name="pickuphoracar" placeholder="Pick-up Hour">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-clock-o"
                                                                            id="hourcar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="w3-col l2 m4 s6">
                                                            <label class="ats-label">Flight Nº</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="pickupflightcar" placeholder="Flight Nº">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-plane"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Country Origin</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="pickupcountrycar"
                                                                        placeholder="Country Origin">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-globe"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Airport</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="pickupairportcar" placeholder="Airport">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-plane"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                    <!-- DROP OFF -->

                                                    <div class="w3-row-padding">

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Drop Off Location</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="dropoffcar"
                                                                        placeholder="Drop Off Location">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-map-marker"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Drop Off Date</label>
                                                            <div class="form-group">
                                                                <div class="input-group date datetimepickercaroff1">
                                                                    <div style="width: 330px; position: absolute;"
                                                                        id="datacaroff1"></div>
                                                                    <input type="text"
                                                                        class="form-control ats-border-color datecar2"
                                                                        name="dropoffdatacar"
                                                                        placeholder="Drop Off Date">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-calendar"
                                                                            id="datecar2"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="w3-col l2 m4 s6">
                                                            <label class="ats-label">Drop Off Hour</label>
                                                            <div class="form-group">
                                                                <div class="input-group date datetimepickerscaroff1">
                                                                    <div style="width: 330px; position: absolute;"
                                                                        id="horacaroff1"></div>
                                                                    <input type="text"
                                                                        class="form-control ats-border-color hourcar2"
                                                                        name="dropoffhoracar"
                                                                        placeholder="Drop Off Hour">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-clock-o"
                                                                            id="hourcar2"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="w3-col l2 m4 s6">
                                                            <label class="ats-label">Flight Nº</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="dropoffflightcar" placeholder="Flight Nº">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-plane"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Country Origin</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="dropoffcountrycar"
                                                                        placeholder="Country Origin">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-globe"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Airport</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="dropoffairportcar" placeholder="Airport">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-plane"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="w3-row-padding">

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Group</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="group" placeholder="B">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-car"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w3-col l2 m4 s12">
                                                            <label class="ats-label">Model</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control ats-border-color"
                                                                        name="model" placeholder="BMW Berlina">
                                                                    <span class="input-group-addon ats-border-color">
                                                                        <span
                                                                            class="w3-large ats-text-color fa fa-car"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!--EXTRAS -->

                                                    <div class="w3-row-padding">
                                                        <div class="w3-col l12">
                                                            <label class="ats-label">Extras</label>
                                                            <div class="form-group">
                                                                <div
                                                                    class="ats-border-color w3-white border-radius w3-padding-4">


                                                                    <div class="w3-row-padding">
                                                                        @foreach($produto->extras as $key=>$extra)
                                                                        @if($extra->pivot->formulario=='car')
                                                                        <div class="w3-col l3 m4 s6">
                                                                            {{ Form::checkbox('chcar'.$produto->id.'[]',$extra->id,null,['class'=>'w3-checkbox checkbox_extras']) }}&nbsp;&nbsp;&nbsp;<input
                                                                                class="form-control hidden"
                                                                                style='width: 40px;display: inline;height: 27px; margin-right:8px; background-color: #e9edf0 !important;'
                                                                                id="CarExtrasQuantidade{{$extra->id}}"
                                                                                value="0" style="width: 40px;"
                                                                                type="number" name=""><span
                                                                                class="check-txt">{{$extra->name}}</span>
                                                                        </div>
                                                                        @endif
                                                                        @endforeach
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- REMARKS -->

                                                    <div class="w3-row-padding">
                                                        <div class="w3-col l12">
                                                            <label class="ats-label">Remark</label>
                                                            <div class="form-group">
                                                                <textarea style="width:100%;height:75px"
                                                                    class="form-control ats-border-color border-radius"
                                                                    name="remarkcar" id="remarkcar"
                                                                    placeholder="Your Remarks"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>






                                                </div>



                                                <script>
                                                    $('.myselect').click(function() {

                                                        $('.dropdown-menu').hasClass('out') ? $('.dropdown-menu').css('display', 'block').removeClass('out') : $('.dropdown-menu').addClass('out').css('display', 'none')
                                                    })

                                                </script>



                                            </div>


                                            @if($produto->alojamento!=1)
                                            @if($produto->golf!=1)
                                            @if($produto->transfer!=1)
                                            @if($produto->car!=1)
                                            @if($produto->ticket==1)
                                            <div id="tiket">
                                                @else
                                                <div style="display:none;" id="tiket">
                                                    @endif
                                                    @else
                                                    <div style="display:none;" id="tiket">
                                                        @endif
                                                        @else
                                                        <div style="display:none;" id="tiket">
                                                            @endif
                                                            @else
                                                            <div style="display:none;" id="tiket">
                                                                @endif
                                                                @else
                                                                <div style="display:none;" id="tiket">
                                                                    @endif


                                                                    <div class="w3-light-gray">


                                                                        <!-- TITLE HEADER -->

                                                                        <div class="w3-col l12"
                                                                            style="border-top: solid 3px; border-color: #24AEC9;">
                                                                        </div>
                                                                        <div class="w3-row-padding">

                                                                            <div class="w3-col l12">
                                                                                <h3 class="title_form_book w3-center ats-text-color w3-border-bottom-ats w3-padding-16"
                                                                                    style="margin-top:0px!important;">
                                                                                    Tickets</h3>
                                                                            </div>

                                                                        </div>



                                                                        <!-- DATES-->



                                                                        <div class="w3-row-padding">


                                                                            <div class="w3-col l1 m2 s2">
                                                                                <label class="ats-label">New</label>
                                                                                <div class="form-group">
                                                                                    <span onclick="criaticket()"
                                                                                        class="ats-border-color border-radius w3-white mybtn">
                                                                                        <span
                                                                                            class="w3-large ats-text-color fa fa-map-marker"></span>
                                                                                    </span>
                                                                                    <span onclick="removetickets()"
                                                                                        class="pull-left ats-border-color border-radius w3-white mybtn"
                                                                                        style="display:inline-flex; margin-left:5px;  margin-top:38px; cursor:pointer;">
                                                                                        <i
                                                                                            class="w3-large ats-text-color fa fa-minus-circle"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="w3-col l3 m5 s12">
                                                                                <label class="ats-label">Date</label>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="input-group date datetimepickerticket1">
                                                                                        <div style="width: 330px; position: absolute;"
                                                                                            id="dataticket1"></div>
                                                                                        <input type="text"
                                                                                            class="form-control ats-border-color datecar"
                                                                                            name="dataticket"
                                                                                            placeholder="Date">
                                                                                        <span
                                                                                            class="input-group-addon ats-border-color">
                                                                                            <span
                                                                                                class="w3-large ats-text-color fa fa-calendar"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                            <div class="w3-col l2 m5 s12">
                                                                                <label class="ats-label">Hour</label>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="input-group date datetimepickersticket1">
                                                                                        <div style="width: 330px; position: absolute;"
                                                                                            id="horaticket1"></div>
                                                                                        <input type="text"
                                                                                            class="form-control ats-border-color hourcar"
                                                                                            name="horaticket"
                                                                                            placeholder="Hour">
                                                                                        <span
                                                                                            class="input-group-addon ats-border-color">
                                                                                            <span
                                                                                                class="w3-large ats-text-color fa fa-clock-o"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <div class="w3-col l2 m4 s6">
                                                                                <label class="ats-label">Adults <span
                                                                                        class="w3-tiny">(>11
                                                                                        Years)</span></label>
                                                                                <div class="form-group">
                                                                                    <div class="input-group">
                                                                                        <input type="number"
                                                                                            class="form-control ats-border-color"
                                                                                            name="adultsticket"
                                                                                            placeholder="Adults">
                                                                                        <span
                                                                                            class="input-group-addon ats-border-color">
                                                                                            <span
                                                                                                class="w3-large ats-text-color fats fats-people"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <div class="w3-col l2 m4 s6">
                                                                                <label class="ats-label">Children <span
                                                                                        class="w3-tiny">(3-10Years)</span></label>
                                                                                <div class="form-group">
                                                                                    <div class="input-group">
                                                                                        <input type="number"
                                                                                            class="form-control ats-border-color"
                                                                                            name="childrensticket"
                                                                                            placeholder="Children">
                                                                                        <span
                                                                                            class="input-group-addon ats-border-color">
                                                                                            <span
                                                                                                class="w3-large ats-text-color fa fa-child"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="w3-col l2 m4 s6">
                                                                                <label class="ats-label">Babies<span
                                                                                        class="w3-tiny">(0-2Years)</span></label>
                                                                                <div class="form-group">
                                                                                    <div class="input-group">
                                                                                        <input type="number"
                                                                                            class="form-control ats-border-color"
                                                                                            name="babiesticket"
                                                                                            placeholder="Babies">
                                                                                        <span
                                                                                            class="input-group-addon ats-border-color">
                                                                                            <span
                                                                                                class="w3-large ats-text-color fa fa-child"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>


                                                                        <div id="ticketReservas">

                                                                        </div>
                                                                        <!--EXTRAS -->
                                                                        <div class="w3-row-padding">
                                                                            <div class="w3-col l12">
                                                                                <label class="ats-label">Extras</label>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="ats-border-color w3-white border-radius w3-padding-4">


                                                                                        <div class="w3-row-padding">
                                                                                            @foreach($produto->extras as
                                                                                            $key=>$extra)
                                                                                            @if($extra->pivot->formulario=='ticket')
                                                                                            <div
                                                                                                class="w3-col l3 m4 s6">
                                                                                                {{ Form::checkbox('chticket'.$produto->id.'[]',$extra->id,null,['class'=>'w3-checkbox checkbox_extras']) }}&nbsp;&nbsp;&nbsp;<input
                                                                                                    class="form-control hidden"
                                                                                                    style='width: 40px;display: inline;height: 27px; margin-right:8px; background-color: #e9edf0 !important;'
                                                                                                    id="TicketExtrasQuantidade{{$extra->id}}"
                                                                                                    value="0"
                                                                                                    style="width: 40px;"
                                                                                                    type="number"
                                                                                                    name=""><span
                                                                                                    class="check-txt">{{$extra->name}}</span>
                                                                                            </div>
                                                                                            @endif
                                                                                            @endforeach
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- REMARKS -->

                                                                        <div class="w3-row-padding">
                                                                            <div class="w3-col l12">
                                                                                <label class="ats-label">Remark</label>
                                                                                <div class="form-group">
                                                                                    <textarea
                                                                                        style="width:100%;height:75px"
                                                                                        class="form-control ats-border-color border-radius"
                                                                                        name="remarkticket"
                                                                                        id="remarkticket"
                                                                                        placeholder="Your Remarks"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="w3-col l12  w3-container "
                                                                style="background-color: #24AEC9; padding: 8px; position: sticky;bottom: 0px;">
                                                                {!! Form::submit('Add to Cart', ['class'=>'w3-button
                                                                w3-right', 'style'=>'margin-right: 12px;
                                                                background:#F5AA3B!important; font-size: 18px;
                                                                padding-top: 5px; padding-right:18px; padding-bottom:
                                                                5px; padding-left: 18px; color: white;']) !!}
                                                                <a href="{{ URL::previous() }}">
                                                                    <i class="fats fats-2x fats-back"
                                                                        style="margin-right: 12px; font-size: 30px; padding-top: 5px; padding-right:18px; padding-bottom: 5px; padding-left: 18px; color: white;">
                                                                    </i>
                                                                </a>
                                                            </div>

                                                            {!! Form::close() !!}


                                                            <div id="modalAlert" class="w3-modal"
                                                                style="z-index: 1000;">
                                                                <div class="w3-modal-content">
                                                                    <header class="w3-container"
                                                                        style="background-color: #24AEC9;">
                                                                        <span
                                                                            onclick="document.getElementById('modalAlert').style.display='none'"
                                                                            class="w3-button w3-display-topright">&times;</span>
                                                                        <h4 style="color: #F5AA3B" class="w3-center">
                                                                            Atention!</h4>
                                                                    </header>
                                                                    <div class="w3-container">
                                                                        <h5>
                                                                            The number of rooms exceeded the number of
                                                                            people, please check</h5>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div id="modalAlertEqual" class="w3-modal"
                                                            style="z-index: 1000;">
                                                            <div class="w3-modal-content">
                                                                <header class="w3-container"
                                                                    style="background-color: #24AEC9;">
                                                                    <span
                                                                        onclick="document.getElementById('modalAlertEqual').style.display='none'"
                                                                        class="w3-button w3-display-topright">&times;</span>
                                                                    <h4 style="color: #F5AA3B" class="w3-center">
                                                                        Atention!</h4>
                                                                </header>
                                                                <div class="w3-container">
                                                                    <h5>
                                                                        The number of people does not match the number
                                                                        of fields, please check</h5>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>



                                                    <link rel="stylesheet"
                                                        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
                                                    <script
                                                        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js">
                                                    </script>

                                                    <script>
                                                        $(document).ready(function() {

        $(".selectpicker").select2();
        $('.checkbox_extras').click(function() {
            if ($(this).is(':checked')) {
                $(this).closest('input').next().removeClass('hidden');
            } else {
                $(this).closest('input').next().addClass('hidden');
            }
        });
    });

                                                    </script>
