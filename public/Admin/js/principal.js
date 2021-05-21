var modal;
var nome;
var alojamento;
var golf;
var transfer;
var car;
var ticket;
var idProduto;
var userID;

var tick = 1;

function criaticket() {
    ctk = tick + 1;
    $('#ticketReservas').append('<div class="w3-row-padding"><div class="w3-col l1 m2 s2">&nbsp;</div><div class="w3-col l3 m5 s12"><label class="ats-label">Date</label><div class="form-group"><div class="input-group date datetimepickerticket' + ctk + '"><div style="width: 330px; position: absolute;" id="dataticket' + ctk + '"></div><input type="text" readonly class="form-control ats-border-color" name="dataticket" placeholder="Date"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-calendar"></span></span></div></div></div><div class="w3-col l2 m5 s12"><label class="ats-label">Hour</label><div class="form-group"><div class="input-group date datetimepickersticket' + ctk + '"><div style="width: 330px; position: absolute;" id="horaticket' + ctk + '"></div><input type="text" readonly class="form-control ats-border-color" name="horaticket" placeholder="Hour"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-clock-o"></span></span></div></div></div><div class="w3-col l2 m4 s6"><label class="ats-label">Adults <span class="w3-tiny">(>11 Years)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="adultsticket" placeholder="Adults"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color"></span></span></div></div></div> <div class="w3-col l2 m4 s6"><label class="ats-label">Children <span class="w3-tiny">(3-10Years)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="childrensticket" placeholder="Children"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color"></span></span></div></div></div><div class="w3-col l2 m4 s6"><label class="ats-label">Babies<span class="w3-tiny">(0-2Years)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="babiesticket" placeholder="Babies"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color glyphicon glyphicon-world"></span></span></div></div></div></div>')

    tick++;

    data = '#dataticket' + tick;
    hora = '#horaticket' + tick;

    $('.datetimepickerticket' + tick).datetimepicker({
        widgetParent: data,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,


    });

    $('.datetimepickersticket' + tick).datetimepicker({
        widgetParent: hora,
        format: 'HH:mm',
        ignoreReadonly: true,

    });

}

var trans = 1;

function criaTransfer() {
    ct = trans + 1;
    $('#tranferReservas').append('<hr style="margin-top: 5px;margin-bottom: 5px;"><div class="w3-row-padding"><div class="w3-col l2 m5 s12"><label class="ats-label">Date</label><div class="form-group"><div class="input-group date datetimepickertransfer' + ct + '"><div style="width: 330px; position: absolute;" id="datatransfer' + ct + '"></div><input type="text"  class="form-control ats-border-color" name="datatransfer" placeholder="Date"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-calendar"></span></span></div></div></div><div class="w3-col l1 m4 s6"><label class="ats-label">Adults <span class="w3-tiny">(>11 Y)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="adultstransfer"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color"></span></span></div></div></div><div class="w3-col l1 m4 s6"><label class="ats-label">Children <span class="w3-tiny">(3-10 Y)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="childrenstransfer"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color"></span></span></div></div></div><div class="w3-col l1 m4 s6"><label class="ats-label">Babies <span class="w3-tiny">(0-2 Y)</span></label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="babiestransfer"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color glyphicon glyphicon-world"></span></span></div></div></div><div class="w3-col l1 m4 s6"><label class="ats-label">Flight Nº</label><div class="form-group"><div class="input-group"><input type="text"  class="form-control ats-border-color" name="flighttransfer"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-plane"></span></span></div></div></div><div class="w3-col l3 m12 s12"><label class="ats-label">Pick-up</label><div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="pickuptransfer" placeholder="Pick-up"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color glyphicon glyphicon-world"></span></span></div></div></div><div class="w3-col l3 m12 s12"><label class="ats-label">Drop Off</label><div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="dropofftransfer" placeholder="Drop Off"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color glyphicon glyphicon-world"></span></span></div></div></div></div>');
    trans++;

    data = '#datatransfer' + trans;
    hora = '#horatransfer' + trans;

    $('.datetimepickertransfer' + trans).datetimepicker({
        widgetParent: data,
        format: 'DD/MM/YYYY HH:mm',
        ignoreReadonly: true,
        defaultDate: moment({ h: 0, m: 0, s: 0 })
    });

    $('.datetimepickerstransfer' + trans).datetimepicker({
        widgetParent: hora,
        format: 'HH:mm',
        ignoreReadonly: true,
        defaultDate: moment({ h: 0, m: 0, s: 0 })
    });
}

var gol = 1;

function criagolf() {

    cg = gol + 1;
    var produto_nome = $(".produto_nome").text();
    $('#golfReservas').append('<div class="w3-row-padding"><div class="w3-col l3 m5 s12"><label class="ats-label">Date</label><div class="form-group"><div class="input-group date datetimepickergolf' + cg + '"><div style="width: 330px; position: absolute;" id="data' + cg + '"></div><input type="text"  class="form-control ats-border-color" name="datagolf" placeholder="Date"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-calendar"></span></span></div></div></div> <div class="w3-col l3 m5 s12"><label class="ats-label">Hour</label><div class="form-group"><div class="input-group date datetimepickersgolf' + cg + '"> <div style="width: 330px; position: absolute;" id="hora' + cg + '"></div><input type="text"  class="form-control ats-border-color" name="horagolf" placeholder="Hour"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-clock-o"></span></span></div></div></div><div class="w3-col l4 m4 s6"><label class="ats-label">Golf Course</label><div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" value="' + produto_nome + '" name="coursegolf" placeholder="Golf Course"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-flag "></span></span></div></div></div><div class="w3-col l2 m4 s6"><label class="ats-label">People</label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color" name="peoplegolf" value="1" placeholder="People"><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color glyphicon glyphicon-world fats fats-people"></span></span></div></div></div></div>')
    gol++;

    data = '#data' + gol;
    hora = '#hora' + gol;

    $('.datetimepickergolf' + gol).datetimepicker({
        widgetParent: data,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,


    });

    $('.datetimepickersgolf' + gol).datetimepicker({
        widgetParent: hora,
        format: 'HH:mm',
        ignoreReadonly: true,

    });


}

function removegolf() {
    $('#golfReservas').children().last().remove();
}

function removetransfer() {
    $('#tranferReservas').children().last().remove();
}

function removequarto() {
    $('#reservas').children().last().remove();
}

function removetickets() {
    $('#ticketReservas').children().last().remove();
}





var s = 1;

function criaquarto() {

    ch = s + 1;

    $('#reservas').append('<div class="w3-row-padding">   <div class="w3-col l2 m4 s12" ><label class="ats-label">Check-In</label><div class="form-group"><div class="input-group date datetimepicker' + ch + '" id="datetimepicker12" style="position: relative;"><div style="width: 330px; position: absolute;" id="checkin' + ch + '"></div><input type="text" name="in" id="in" class="form-control ats-border-color roomCheckin" placeholder="Check-In" /><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-calendar"></span></span></div></div></div>     <div class="w3-col l2 m4 s12"><label class="ats-label">Check-Out</label><div class="form-group"><div class="input-group date datetimepickers' + ch + '" id="datetimepicker13" style="position: relative;"><div style="width: 330px; position: absolute;" id="checkout' + ch + '"></div><input type="text" class="form-control ats-border-color roomCheckout" id="out" name="out" placeholder="Check-Out" onBlur="validaDatas(this)" ><span class="input-group-addon ats-border-color"><span class="w3-large ats-text-color fa fa-calendar"></span></span></div></div></div>     <div class="w3-col l2 m4 s6"><label class="ats-label">Type</label><div class="form-group"><div class="input-group"><input type="text" class="form-control ats-border-color" name="type" placeholder="Double Room"><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input ats-text-color fats fats-type"></i></span></div></div></div>    <div class="w3-col l2 m4 s6"><label class="ats-label">People</label><div class="form-group"><div class="input-group"><input type="number" class="form-control ats-border-color"  id="people' + s + '" onkeyup="peopleChange(' + s + ')" name="people" placeholder="People" required><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input ats-text-color fats fats-people"></i></span></div></div></div>   <div class="w3-col l2 m4 s6"><label class="ats-label">Rooms</label><div class="form-group"><div class="input-group"><input onKeyUp="blablabla(this,' + s + ')" id="room' + s + '" type="number" required class="desativo form-control ats-border-color" name="room" placeholder="Rooms"><span class="input-group-addon ats-border-color" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input ats-text-color fats fats-keys"></i></span></div></div></div>    <div class="w3-col l2 m4 s6"><label class="ats-label">Plan</label><div class="form-group"><div class="btn-group"><div class="input-group"><input type="text" readonly class="form-control ats-border-color plan-' + s + '" value="BB" name="plan"><span class="input-group-addon ats-border-color dropdown-toggle myselectplan" onclick="clickPlan(' + s + ')" style="height: 23px;padding-bottom: 0px;padding-top: 8px;"><i class="fats-correct-input ats-text-color fats fats-plan"></i></span> <ul class="dropdown-menu plan' + s + ' out"><li onclick = "$(\'.plan-' + s + '\').val(\'AI\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')" > AI</li ><li onclick="$(\'.plan-' + s + '\').val(\'BB\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">BB</li><li onclick="$(\'.plan-' + s + '\').val(\'FB\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">FB</li><li onclick="$(\'.plan-' + s + '\').val(\'HB\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">HB</li><li onclick="$(\'.plan-' + s + '\').val(\'RO\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">RO</li><li onclick="$(\'.plan-' + s + '\').val(\'SC\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">SC</li><li onclick="$(\'.plan-' + s + '\').val(\'SEMI AI\');$(\'.plan' + s + '\').addClass(\'out\').css(\'display\',\'none\')">SEMI AI</li></ul >  </div ></div ></div ></div > <div id="name' + s + '"></div></div > ');
    s++;

    checkin = '#checkin' + s;
    checkout = '#checkout' + s;

    $('.datetimepicker' + s).datetimepicker({
        widgetParent: checkin,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
    }).on("dp.change", function(e) {
        $('.datetimepickers' + s).data("DateTimePicker").minDate(e.date);
    });

    $('.datetimepickers' + s).datetimepicker({
        widgetParent: checkout,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
    });

    /* força limpar os valores */
    setTimeout(() => {
        $(checkin).val('');
        $(checkout).val('');
    });
    /* força limpar os valores */

}


function formulario(name, id, alojamento1, golf1, transfer1, car1, ticket1, usuario, imagem) {
    modal = document.getElementById('formModal')
    userID = usuario;
    nome = name;
    alojamento = alojamento1;
    golf = golf1;
    transfer = transfer1;
    car = car1;
    ticket = ticket1;
    console.log(imagem);
    modal = modal.id;
    localStorage.setItem('imagemLogo' + userID, imagem);
    tipoPedido = localStorage.getItem('tipoPedido' + userID);
    leadPedido = localStorage.getItem('leadPedido' + userID);
    responsavelPedido = localStorage.getItem('responsavelPedido' + userID);
    referenciapedido = localStorage.getItem('referenciaPedido' + userID);

    document.getElementById('formModal').style.display = 'block';
    $("html").addClass("modal-open")

    var val = { 'id': id };
    $.ajax({
        type: 'GET',
        url: "../admin/../form/" + id,

        success: function(data) {
            $('#loading').load(assetBaseUrl + 'main/form/' + id, function() {
                $('select[name="tipopedido"]').val(tipoPedido).change();
                $('#leadpedido').val(leadPedido);
                $('#responsavelpedido').val(responsavelPedido);
                $('#referenciapedido').val(referenciapedido);
            });

            setTimeout(function() {
                // $('h1.product_name').val($('.product_name').text());
                $('input[name="coursegolf"]').val($('h1.produto_nome').text());
                $('.title_form_book').text($('h1.produto_nome').text());
                // var produto_nome = $(".produto_nome").text();
                // console.log(produto_nome);
                // $("input[name='coursegolf']").val(produto_nome);
            }, 500)
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
    });
}

function botao(div) {
    var alojamento = document.getElementById('Accommodation');
    var golf = document.getElementById('Golf');
    var transfer = document.getElementById('Transfer');
    var car = document.getElementById('Car');
    var tiket = document.getElementById('Tiket');

    var alojamento1 = document.getElementById('alojamento1');
    var golf1 = document.getElementById('golf1');
    var transfer1 = document.getElementById('transfer1');
    var car1 = document.getElementById('car1');
    var tiket1 = document.getElementById('tiket1');

    if (div == 'Accommodation') {

        alojamento.style.display = 'block';
        golf.style.display = 'none';
        transfer.style.display = 'none';
        car.style.display = 'none';
        tiket.style.display = 'none';
        if (alojamento1) {
            alojamento1.style.backgroundColor = '#f1f1f1';
            alojamento1.style.color = '#24AEC9';
        }
        if (golf1) {
            golf1.style.backgroundColor = '#24AEC9';
            golf1.style.color = '#f1f1f1';
        }
        if (transfer1) {
            transfer1.style.backgroundColor = '#24AEC9';
            transfer1.style.color = '#f1f1f1';
        }
        if (car1) {
            car1.style.backgroundColor = '#24AEC9';
            car1.style.color = '#f1f1f1';
        }
        if (tiket1) {
            tiket1.style.backgroundColor = '#24AEC9';
            tiket1.style.color = '#f1f1f1';
        }
    } else if (div == 'Golf') {

        alojamento.style.display = 'none';
        golf.style.display = 'block';
        transfer.style.display = 'none';
        car.style.display = 'none';
        tiket.style.display = 'none';

        if (alojamento1) {
            alojamento1.style.backgroundColor = '#24AEC9';
            alojamento1.style.color = '#f1f1f1';
        }
        if (golf1) {
            golf1.style.backgroundColor = '#f1f1f1';
            golf1.style.color = '#24AEC9';
        }
        if (transfer1) {
            transfer1.style.backgroundColor = '#24AEC9';
            transfer1.style.color = '#f1f1f1';
        }
        if (car1) {
            car1.style.backgroundColor = '#24AEC9';
            car1.style.color = '#f1f1f1';
        }
        if (tiket1) {
            tiket1.style.backgroundColor = '#24AEC9';
            tiket1.style.color = '#f1f1f1';
        }
    } else if (div == 'Transfer') {
        alojamento.style.display = 'none';
        golf.style.display = 'none';
        transfer.style.display = 'block';
        car.style.display = 'none';
        tiket.style.display = 'none';
        if (alojamento1) {
            alojamento1.style.backgroundColor = '#24AEC9';
            alojamento1.style.color = '#f1f1f1';
        }
        if (golf1) {
            golf1.style.backgroundColor = '#24AEC9';
            golf1.style.color = '#f1f1f1';
        }
        if (transfer1) {
            transfer1.style.backgroundColor = '#f1f1f1';
            transfer1.style.color = '#24AEC9';
        }
        if (car1) {
            car1.style.backgroundColor = '#24AEC9';
            car1.style.color = '#f1f1f1';
        }
        if (tiket1) {
            tiket1.style.backgroundColor = '#24AEC9';
            tiket1.style.color = '#f1f1f1';
        }
    } else if (div == 'Car') {
        alojamento.style.display = 'none';
        golf.style.display = 'none';
        transfer.style.display = 'none';
        car.style.display = 'block';
        tiket.style.display = 'none';
        if (alojamento1) {
            alojamento1.style.backgroundColor = '#24AEC9';
            alojamento1.style.color = '#f1f1f1';
        }
        if (golf1) {
            golf1.style.backgroundColor = '#24AEC9';
            golf1.style.color = '#f1f1f1';
        }
        if (transfer1) {
            transfer1.style.backgroundColor = '#24AEC9';
            transfer1.style.color = '#f1f1f1';
        }
        if (car1) {
            car1.style.backgroundColor = '#f1f1f1';
            car1.style.color = '#24AEC9';
        }
        if (tiket1) {
            tiket1.style.backgroundColor = '#24AEC9';
            tiket1.style.color = '#f1f1f1';
        }
    } else {
        alojamento.style.display = 'none';
        golf.style.display = 'none';
        transfer.style.display = 'none';
        car.style.display = 'none';
        tiket.style.display = 'block';
        if (alojamento1) {
            alojamento1.style.backgroundColor = '#24AEC9';
            alojamento1.style.color = '#f1f1f1';
        }
        if (golf1) {
            golf1.style.backgroundColor = '#24AEC9';
            golf1.style.color = '#f1f1f1';
        }
        if (transfer1) {
            transfer1.style.backgroundColor = '#24AEC9';
            transfer1.style.color = '#f1f1f1';
        }
        if (car1) {
            car1.style.backgroundColor = '#24AEC9';
            car1.style.color = '#f1f1f1';
        }
        if (tiket1) {
            tiket1.style.backgroundColor = '#f1f1f1';
            tiket1.style.color = '#24AEC9';
        }
    }


}







$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();


        //---------------------------------------------------------------------------------------------//
        if (modal == 'formModal') {

            if (document.getElementsByName("tipopedido")[0].value == 0) {
                alert('The field Type is required')
                return false;
            }
            if (!document.getElementById("leadpedido").checkValidity()) {
                alert('The field Lead Name is required')
                return false;
            }
            if (!document.getElementById("responsavelpedido").checkValidity()) {
                alert('The field Responsible is required')
                return false;
            }
            if (!document.getElementById("referenciapedido").checkValidity()) {
                alert('The field Reference is required')
                return false;
            }

            var val = $('#form1').serializeArray();
            tipoPedido = $("select[name='tipopedido']").val();
            leadPedido = $('#leadpedido').val();
            responsavelPedido = $('#responsavelpedido').val();
            referenciaPedido = $('#referenciapedido').val();

            localStorage.setItem('tipoPedido' + userID, tipoPedido);
            localStorage.setItem('leadPedido' + userID, leadPedido);
            localStorage.setItem('responsavelPedido' + userID, responsavelPedido);
            localStorage.setItem('referenciaPedido' + userID, referenciaPedido);


            var armaz = new Array();

            var dat = 1;
            var roo = 1;
            var name = 1;


            if (alojamento == 1) {
                var children = [].slice.call(document.getElementById('reservas').children);
                formpeople[0]
                people = $('#people' + 0).val();

                if (formpeople[0] != people) {

                    // document.getElementById('modalAlertEqual').style.display = 'block'
                    // return false;
                }


                bandeira = false;
                children.forEach(function(entry, key) {
                    chave = key + 1;
                    people = $('#people' + chave).val();
                    if (formpeople[chave] != people) {
                        //document.getElementById('modalAlertEqual').style.display = 'block'
                        //return bandeira = 'true';
                    }
                });

                if (bandeira) {
                    return false;
                }
            }


            $.each(val, function(i, field) {

                // console.log(field.name + ":" + field.value + " ");
                // o = field.name + ":" + field.value + " ";
                //alert(i)
                // j[field.name]=field.value;
                // if(i%6==0 && i != 0){
                //   alert(i)
                //   t.push(j);
                //   j = new Object();
                // }

                if (field.name == 'formulario') {

                    o = new Object();
                    t = new Array();
                    retrievedObject = JSON.parse(localStorage.getItem('array' + userID));
                    if (retrievedObject != null) {
                        //alert('tem algo no localstorage!')
                        armaz = retrievedObject;
                    }
                    o['produto'] = field.value;
                    o['array'] = t;
                    o['nome'] = nome;
                    var checked = new Array(); //aqui
                    o['extras'] = checked;
                    idProduto = field.value;

                    imagemLogo = localStorage.getItem('imagemLogo' + userID);


                    $("input[name='chalojamento" + idProduto + "[]']:checked").each(function() {
                        extraTipo = new Object();
                        extraTipo['tipo'] = 'alojamento';
                        extraTipo['valor'] = parseInt($(this).val());
                        extraTipo['quantidade'] = $('#AlojamentoExtrasQuantidade' + $(this).val()).val()
                        checked.push(extraTipo); //aqui
                    });
                    $("input[name='chgolf" + idProduto + "[]']:checked").each(function() {
                        extraTipo = new Object();
                        extraTipo['tipo'] = 'golf';
                        extraTipo['valor'] = parseInt($(this).val());
                        extraTipo['quantidade'] = $('#GolfExtrasQuantidade' + $(this).val()).val()
                        checked.push(extraTipo); //aqui
                    });
                    $("input[name='chtransfer" + idProduto + "[]']:checked").each(function() {
                        extraTipo = new Object();
                        extraTipo['tipo'] = 'transfer';
                        extraTipo['valor'] = parseInt($(this).val());
                        extraTipo['quantidade'] = $('#TransferExtrasQuantidade' + $(this).val()).val()
                        checked.push(extraTipo); //aqui
                    });
                    $("input[name='chcar" + idProduto + "[]']:checked").each(function() {
                        extraTipo = new Object();
                        extraTipo['tipo'] = 'car';
                        extraTipo['valor'] = parseInt($(this).val());
                        extraTipo['quantidade'] = $('#CarExtrasQuantidade' + $(this).val()).val()
                        checked.push(extraTipo); //aqui
                    });
                    $("input[name='chticket" + idProduto + "[]']:checked").each(function() {
                        extraTipo = new Object();
                        extraTipo['tipo'] = 'ticket';
                        extraTipo['valor'] = parseInt($(this).val());
                        extraTipo['quantidade'] = $('#TicketExtrasQuantidade' + $(this).val()).val()
                        checked.push(extraTipo); //aqui
                    });
                    o['remarkalojamento'] = $('#remarkalojamento').val();
                    o['remarkgolf'] = $('#remarkgolf').val();
                    o['remarktransfer'] = $('#remarktransfer').val();
                    o['remarkcar'] = $('#remarkcar').val();
                    o['remarkticket'] = $('#remarkticket').val();
                    armaz.push(o)

                    console.log(armaz)


                }

                if (alojamento == 1) {

                    if (field.name == 'in') {

                        j = new Object();
                        n = new Array();
                        t.push(j)


                    }
                    if (field.name == 'in') {
                        j['form'] = 'alojamento';
                        j['in'] = field.value;

                    }
                    if (field.name == 'out') {

                        j['out'] = field.value;
                    }
                    if (field.name == 'people') {
                        j['people'] = field.value;
                    }
                    if (field.name == 'plan') {
                        j['plan'] = field.value;
                    }

                    if (field.name == 'room') {
                        j['room'] = field.value;
                    }
                    if (field.name == 'type') {
                        j['type'] = field.value;
                    }
                    if (field.name == 'roomnumber') {
                        g = new Object();
                        n.push(g)
                        j['quartos'] = n;
                        y = new Array();
                    }
                    if (field.name == 'roomnumber') {
                        g['roomnumber'] = field.value;
                    }
                    if (field.name == 'roomremark') {
                        g['roomremark'] = field.value;
                    }

                    if (field.name == 'roomname') {
                        fn = new Object();
                        y.push(fn)
                        g['nomes'] = y;
                    }
                    if (field.name == 'roomname') {
                        fn['roomname'] = field.value;
                    }
                }

                if (golf == 1) {


                    if (field.name == 'datagolf') {
                        golfobject = new Object();
                        t.push(golfobject)


                    }
                    if (field.name == 'datagolf') {
                        golfobject['form'] = 'golf';
                        golfobject['datagolf'] = field.value;

                    }
                    if (field.name == 'horagolf') {

                        golfobject['horagolf'] = field.value;
                    }
                    if (field.name == 'coursegolf') {
                        golfobject['coursegolf'] = field.value;
                    }
                    if (field.name == 'peoplegolf') {
                        golfobject['peoplegolf'] = field.value;
                    }
                }
                if (transfer == 1) {

                    if (field.name == 'datatransfer') {
                        transferobject = new Object();
                        t.push(transferobject)

                        transferobject['form'] = 'transfer';

                        if (field.value == undefined || field.value == null || field.value == '') {
                            transferobject['datatransfer'] = '01/01/2019 00:00';
                        } else {
                            transferobject['datatransfer'] = field.value;
                        }

                    }
                    if (field.name == 'horatransfer') {
                        transferobject['horatransfer'] = field.value;
                    }
                    if (field.name == 'adultstransfer') {
                        transferobject['adultstransfer'] = field.value;
                    }
                    if (field.name == 'childrenstransfer') {
                        transferobject['childrenstransfer'] = field.value;
                    }
                    if (field.name == 'babiestransfer') {
                        transferobject['babiestransfer'] = field.value;
                    }
                    if (field.name == 'flighttransfer') {
                        transferobject['flighttransfer'] = field.value;
                    }
                    if (field.name == 'pickuptransfer') {
                        transferobject['pickuptransfer'] = field.value;
                    }
                    if (field.name == 'dropofftransfer') {
                        transferobject['dropofftransfer'] = field.value;
                    }
                }
                if (car == 1) {


                    if (field.name == 'pickupcar') {
                        carobject = new Object();
                        t.push(carobject)

                        // carobject['pickupcar'] = null;
                        // carobject['pickupdatacar'] = null;
                        //carobject['pickuphoracar'] = null;
                        // carobject['pickupflightcar'] = null;
                        // carobject['pickupcountrycar'] = null;
                        // carobject['pickupairportcar'] = null;
                        // carobject['dropoffcar'] = null;
                        // carobject['dropoffdatacar'] = null;
                        // carobject['dropoffhoracar'] = null;
                        //carobject['dropoffflightcar'] = null;
                        //carobject['dropoffcountrycar'] = null;
                        //carobject['dropoffairportcar'] = null;
                    }
                    if (field.name == 'pickupcar') {
                        carobject['form'] = 'car';
                        carobject['pickupcar'] = field.value;

                    }
                    if (field.name == 'pickupdatacar') {

                        carobject['pickupdatacar'] = field.value;
                    }
                    if (field.name == 'pickuphoracar') {
                        carobject['pickuphoracar'] = field.value;
                    }
                    if (field.name == 'pickupflightcar') {
                        carobject['pickupflightcar'] = field.value;
                    }
                    if (field.name == 'pickupcountrycar') {
                        carobject['pickupcountrycar'] = field.value;
                    }
                    if (field.name == 'pickupairportcar') {
                        carobject['pickupairportcar'] = field.value;
                    }
                    if (field.name == 'dropoffcar') {
                        carobject['dropoffcar'] = field.value;
                    }
                    if (field.name == 'dropoffdatacar') {
                        carobject['dropoffdatacar'] = field.value;
                    }
                    if (field.name == 'dropoffhoracar') {
                        carobject['dropoffhoracar'] = field.value;
                    }
                    if (field.name == 'dropoffflightcar') {
                        carobject['dropoffflightcar'] = field.value;
                    }
                    if (field.name == 'dropoffcountrycar') {
                        carobject['dropoffcountrycar'] = field.value;
                    }
                    if (field.name == 'dropoffairportcar') {
                        carobject['dropoffairportcar'] = field.value;
                    }
                    if (field.name == 'group') {
                        carobject['group'] = field.value;
                    }
                    if (field.name == 'model') {
                        carobject['model'] = field.value;
                    }
                }
                if (ticket == 1) {


                    if (field.name == 'dataticket') {
                        ticketobject = new Object();
                        t.push(ticketobject)


                    }
                    if (field.name == 'dataticket') {
                        ticketobject['form'] = 'ticket';
                        ticketobject['dataticket'] = field.value;

                    }
                    if (field.name == 'horaticket') {

                        ticketobject['horaticket'] = field.value;
                    }
                    if (field.name == 'adultsticket') {
                        ticketobject['adultsticket'] = field.value;
                    }
                    if (field.name == 'childrensticket') {
                        ticketobject['childrensticket'] = field.value;
                    }
                    if (field.name == 'babiesticket') {
                        ticketobject['babiesticket'] = field.value;
                    }
                }

            });
            get = localStorage.setItem('array' + userID, JSON.stringify(armaz));
            // localStorage.removeItem('array'+userID)//LIMPEZA DO LOCAL STORAGE
            val = { 'produtos': armaz };
            retrievedObject = JSON.parse(localStorage.getItem('array' + userID));
            if (retrievedObject.length != 0) {
                $('#total').css("display", "block");
            }
            retrievedObject.forEach(function(entry, key) {
                var total = key + 1;
                $('#total').html('<b>' + total + '</b>')
            });

            document.getElementById('formModal').style.display = 'none';
            $("html").removeClass("modal-open");
            var adress = assetBaseUrl + 'main';
            window.location.href = adress;
        } else {

            var categoria_id = $('#categoria_id').val();
            var destino_id = $('#destino_id').val();

            var val = { 'categoria_id': categoria_id, 'destino_id': destino_id };

            $.ajax({
                type: 'POST',
                url: "main/search",
                data: val,
                success: function(data) {
                    $('#produtos').empty();
                    $('#resultado').empty();
                    if (data.result.suppliers == 'Vazio!') {
                        $('#produtos').append('Empty!');
                    } else {
                        for (var key in data.result.produtos) {
                            /* console.log(data.result.produtos[key].nome);
                            console.log(data.result.suppliers[key].path_image);
                            console.log(data.result.destinos[key].name); */
                            let obj = data.result.suppliers[key];
                            console.log($.isEmptyObject({ obj }), obj);
                            if (typeof(obj) === undefined || typeof(obj) === null) {
                                var supplier_path_image = '';
                            } else {
                                var supplier_path_image = obj.path_image;
                            }
                            $('#resultado').append('<a href="https://dev.atsportugal.com/admin/main/product/' + data.result.produtos[key].id + '/' + categoria_id + '/' + destino_id + '"><div class="w3-col l12" style=" border-color: #eeeeee; border-style: solid; border-width: 1px;"><div class="w3-col l1" style="background-color: #F6F6F6; "><img style="max-height: 35px; max-width: 69px;"  class="w3-block" src="' + assetBaseUrl + 'storage/' + supplier_path_image + '" ></div><div class="w3-col l10" style="background-color: #F6F6F6;"><div style=" padding-top: 7px;"><span style="color: #888888;">' + data.result.produtos[key].nome + '</span><span style=" color: #24AEC9;">&nbsp;-&nbsp;' + data.result.destinos[key].name + '</span></div></div><div class="w3-col l1 " style="background-color: #F6F6F6;"><button style="height: 35px; background-color: #24AEC9; color:white;" class="w3-button w3-tiny w3-block">Details</button></div></div></a>');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
            });
        }
    });
});

window.closeModal = function() {
    $("html").removeClass("modal-open");

}

/* EXECUTADO NA TELA DE SEARCH PRODUCTS  */
$(document).ready(function() {

    $(".roomCheckout").on('blur', function() {


        console.log("VALIDANDO AS DATAS");
        validaDatas(this);
    });
});

function validaDatas(thisCheckout) {

    try {

        const checkout = $(thisCheckout).prev(); //pega o elemento anterior ao input ,que no caso eh uma div
        let checkoutId = $(checkout).attr("id"); // pega o id da div para poder pegar a div do checkin
        let id = checkoutId.replace(/[^0-9]/g, ''); // deixa somente os numeros na variavel
        const checkin = $("#checkin" + id); // pega a div do checkin
        const inputCheckin = $(checkin).next(); // pega o proximo elemento dentro da div , que no caso eh o nosso input

        var tempDate = $(inputCheckin).val().split("/");
        tempDate = tempDate[2] + '-' + tempDate[1] + '-' + tempDate[0];
        const checkinDate = new Date(tempDate); // criamos a data

        var tempDate = $(thisCheckout).val().split("/");
        tempDate = tempDate[2] + '-' + tempDate[1] + '-' + tempDate[0];
        const checkoutDate = new Date(tempDate); // criamos a data


        var diffTime = Math.abs(checkoutDate - checkinDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));


        if (diffDays >= 14) {


            let message = "Atenção , possui a diferença de " + diffDays + " dias entre o checkin e o checkout!";
            console.log(message);
            $.confirm({
                title: 'Ops!',
                content: message,
                theme: 'dark',
                buttons: {
                    confirm: function() {
                        console.log("confirmado");
                    },
                    cancel: function() {
                        $(thisCheckout).val("");
                    }
                }
            });
        }

    } catch (error) {
        console.log("Error ", error.name, error.message);
    }
}
