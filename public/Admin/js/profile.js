var base_url = "https://dev.atsportugal.com/admin/"; //<--MUDAR QUANDO FOR PRODUÇAO REMOVER-> DEV.

$(function() {
    $('.datetimepicker1').datetimepicker({
        widgetParent: '#checkin1',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
        debug: false,


    }).on("dp.change", function(e) {
        $('.datetimepickers1').data("DateTimePicker").minDate(e.date);
    });

    $('.datetimepickers1').datetimepicker({
        widgetParent: '#checkout1',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
        debug: false,

    });
});

$('.select-simple').select2({
    placeholder: 'Select an option...',
});

window.initDateTimePicker = function() {

    $('.timepicker').datetimepicker({
        // minuteStepping: 5,
        format: 'HH:mm',
        ignoreReadonly: true,
        debug: false,
        widgetPositioning: 'bottom auto',
        widgetPositioning: {
            horizontal: "right",
            vertical: "bottom"
        },
    }).on('dp.show', function(e) {
        $(".bootstrap-datetimepicker-widget").css("position", "relative");
        $(".bootstrap-datetimepicker-widget").css("z-index", "9999");
    });

    $('.datetimepicker').datetimepicker({
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
        debug: false,
        widgetPositioning: {
            horizontal: "right",
            vertical: "bottom"
        },
    }).on('dp.show', function(e) {
        $(".bootstrap-datetimepicker-widget").css("position", "relative");
        $(".bootstrap-datetimepicker-widget").css("z-index", "9999");
    });

    $('.datetimepickerFormat').datetimepicker({
        format: 'DD-MM-YYYY',
        ignoreReadonly: true,
        debug: false,
        widgetPositioning: {
            horizontal: "right",
            vertical: "bottom"
        },
    });



}

$(document).ready(function() {
    initDateTimePicker();
    $('#room-pax-names').on('shown.bs.modal', function() {
        // $('#myInput').trigger('focus')
    })

    $('[id^="imprimir-pedido"]').on('click', function() {
        var vid = $(this).attr('data-id');
        var ats = $(this).attr('data-type');

        if (ats == "ats") {
            window.open('/admin/profile/printpedido/' + vid + "/true&modal=true", "Voucher", "width=1000,height=800", "_blank");
        } else {
            window.open('/admin/profile/printpedido/' + vid + "&modal=true", "Voucher", "width=1000,height=800", "_blank");
        }

        /* window.open('/profile/printpedido/'+vid + "&modal=true", "Voucher","width=1000,height=800","_blank"); */
    });

    $('[id^="imprimir-pedido-markup"]').on('click', function() {
        var vid = $(this).attr('data-id');
        window.open('/admin/profile/printpedidomarkup/' + vid + "&modal=true", "Voucher", "width=1000,height=800", "_blank");
        /* window.open('/profile/printpedidomarkup/'+vid + "&modal=true", "Voucher","width=1000,height=800","_blank"); */
    });
});

window.roomDateCalc = function(x, y, z) {

    var checkin = '#checkin' + x + '_' + y + '_' + z + '';
    var checkout = '#checkout' + x + '_' + y + '_' + z + '';


    var CurrentCheckin = '#init' + x + '_' + y + '_' + z + '';
    var CurrentCheckout = '#find' + x + '_' + y + '_' + z + '';

    if (z > 101) {
        var linhadeCimaCheckin = '#init' + x + '_' + y + '_' + (z - 1) + '';
        var linhadeCimaCheckout = '#find' + x + '_' + y + '_' + (z - 1) + '';
    } else {
        var linhadeCimaCheckin = '#init' + x + '_' + y + '_0';
        var linhadeCimaCheckout = '#find' + x + '_' + y + '_0';
    }


    $('.datetimepicker' + x + '_' + y + '_' + z + '').datetimepicker({
        widgetParent: checkin,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
        debug: false,
        widgetPositioning: { vertical: 'bottom' },
    }).on("dp.change", function(e) {
        $('.datetimepickers' + x + '_' + y + '_' + z + '').data("DateTimePicker").minDate(e.date);

        mudadia(document.getElementById('init' + x + '_' + y + '_' + z + '').value, document.getElementById('find' + x + '_' + y + '_' + z + '').value, '' + x + '_' + y + '_' + z + '');
        soma(x, y, z);
    });

    $('.datetimepickers' + x + '_' + y + '_' + z + '').datetimepicker({
        widgetParent: checkout,
        format: 'DD/MM/YYYY',
        ignoreReadonly: true,
        debug: false,
        widgetPositioning: {
            vertical: 'bottom'
        },
    }).on("dp.change", function(e) {
        mudadia(document.getElementById('init' + x + '_' + y + '_' + z + '').value, document.getElementById('find' + x + '_' + y + '_' + z + '').value, '' + x + '_' + y + '_' + z + '');
        soma(x, y, z);
    });

    function parseDate(str, str2) {
        var date1 = new Date(str);
        var date2 = new Date(str2);
        var timeDiff = date2.getTime() - date1.getTime();
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        $('#dias' + x + '_' + y + '_' + z + '').html(diffDays)
    }

    function mudadia(str, str2, chave) {

        try {
            console.log("Teste muda dia ", str, str2, chave);

            strn = str.split("/");
            nd = strn[2] + '-' + strn[1] + '-' + strn[0];
            str2n = str2.split("/");
            nd2 = str2n[2] + '-' + str2n[1] + '-' + str2n[0];

            var date1 = new Date(nd);
            var date2 = new Date(nd2);
            var timeDiff = date2.getTime() - date1.getTime();
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            $("#dias" + chave).html(diffDays)
        } catch (error) {
            console.log("ERRORRRRR", error);
        }


    }

    parseDate(checkin, checkout);


    if (typeof($(linhadeCimaCheckin).val()) != undefined && $(linhadeCimaCheckin).val() != null) {
        $(CurrentCheckin).val('').trigger("change");
        //$(CurrentCheckout).val($(linhadeCimaCheckout).val());
    } else {
        $(CurrentCheckin).val('').trigger("change");
        //$(CurrentCheckout).val($( '#find' + x + '_' + y + '_0' ).val());
    }

}

var acc = document.getElementsByClassName("accordion");
var i;


for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {

            panel.style.maxHeight = (panel.scrollHeight * i) + "px";
        }
    }
}

function soma(x, y, z) {
    // console.log(x, y, z);
    var prev = $('#totaliza' + x + '_' + y + '_' + z).data('val');
    var prev2 = $('#atsProfit' + x + '_' + y + '_' + z).data('val');

    qntd = document.getElementById("quantidade" + x + '_' + y + '_' + z).value;
    dias = $('#dias' + x + '_' + y + '_' + z).html();
    // real = document.getElementById("real"+x+'_'+y+'_'+z).value;
    real = $("#real" + x + '_' + y + '_' + z).val();

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }

    desconto = $("#desconto" + x + '_' + y + '_' + z).val();
    board = $('board' + x + '_' + y + '_' + z).val();
    pessoas = $('pessoas' + x + '_' + y + '_' + z).val();
    price = real - desconto;

    if (board == 'HB' || board == 'FB' || board == 'AI') {
        totalRoom = pessoas * dias * price;
    } else {
        totalRoom = qntd * dias * price;
    }

    if (real) {
        $("#preco" + x + '_' + y + '_' + z).html(parseFloat(price).toFixed(2))
        $('#totaliza' + x + '_' + y + '_' + z).html(jqueryFloatFormat(parseFloat(totalRoom)));
        $('#totaliza' + x + '_' + y + '_' + z).data('val', jqueryFloatFormat(parseFloat(totalRoom)));

    } else {
        $("#preco" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
        $('#totaliza' + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
        $('#totaliza' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }
    unitario = $('#totaliza' + x + '_' + y + '_' + z).html();
    atual = $('#totalAcc' + x + '_' + y).html();
    if (prev != null) {
        montante = ((+atual) + (+unitario)) - prev;
    } else {
        montante = (+atual) + (+unitario)
    }
    $('#totalAcc' + x + '_' + y).html(parseFloat(montante).toFixed(2))
    totalAcc = $('#totalAcc' + x + '_' + y).html()
    totalAccExtra = $('#totalAccExtra' + x + '_' + y).html()

    percent = document.getElementById('kickbackAccInput' + x + '_' + y).value / 100;
    valorKickback = totalAcc * percent;
    $('#kickbackAcc' + x + '_' + y).html(jqueryFloatFormat(parseFloat(valorKickback)));

    percentMarkup = document.getElementById('markupAccInput' + x + '_' + y).value / 100;
    valorMarkup = totalAcc * percentMarkup;
    $('#markupAcc' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2))

    //final
    finalAcc = (+totalAcc) + (+totalAccExtra) - (valorKickback) + (+valorMarkup)
    $('#finalAcc' + x + '_' + y).html(parseFloat(finalAcc).toFixed(2))

    atsRate = document.getElementById("atsRate" + x + '_' + y + '_' + z).value;

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }


    if (board == 'HB' || board == 'FB' || board == 'AI') {
        atsTotalRate = pessoas * dias * atsRate;
    } else {
        atsTotalRate = qntd * dias * atsRate;
    }


    total = $('#totaliza' + x + '_' + y + '_' + z).html();
    atsProfit = total - atsTotalRate;

    $('#atsTotalRate' + x + '_' + y + '_' + z).html(parseFloat(atsTotalRate).toFixed(2));
    if (atsTotalRate == 0) {
        $("#atsProfit" + x + '_' + y + '_' + z).html(parseFloat(total).toFixed(2))
        $('#atsProfit' + x + '_' + y + '_' + z).data('val', parseFloat(total).toFixed(2));
    } else {
        if (atsTotalRate) {
            $("#atsProfit" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2))
            $('#atsProfit' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
        } else {
            $("#atsProfit" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $('#atsProfit' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
        }
    }

    unitario2 = $('#atsProfit' + x + '_' + y + '_' + z).html();
    atual2 = $('#totalProfitAcc' + x + '_' + y).html();

    if (prev2 != null) {
        montante2 = ((+atual2) + (+unitario2)) - prev2;
    } else {
        montante2 = (+atual2) + (+unitario2);
    }

    $('#totalProfitAcc' + x + '_' + y).html(parseFloat(montante2).toFixed(2))
}

function somaGolf(x, y, z) {
    var prev = $('#totalizaGolf' + x + '_' + y + '_' + z).data('val');
    var atsProfitGolf = $('#atsProfitGolf' + x + '_' + y + '_' + z).data('val');

    free = $("#playersFree" + x + '_' + y + '_' + z).val();
    real = $("#realGolf" + x + '_' + y + '_' + z).val();

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }


    var qntd = $("#golfe_people" + x + '_' + y + '_' + z).val();
    price = (real * qntd) - (free * real);

    if (real) {
        //$("#preco"+x+'_'+y+'_'+z).html(parseFloat(price).toFixed(2))
        $('#totalizaGolf' + x + '_' + y + '_' + z).html(parseFloat(price).toFixed(2))
        $('#totalizaGolf' + x + '_' + y + '_' + z).data('val', parseFloat(price).toFixed(2));

        var valor_total = real
    } else {
        //$("#preco"+x+'_'+y+'_'+z).html(parseFloat(0).toFixed(2))
        $('#totalizaGolf' + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
        $('#totalizaGolf' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }

    unitario = $('#totalizaGolf' + x + '_' + y + '_' + z).html();

    atual = $('#totalGolf' + x + '_' + y).html();

    if (prev != null) {
        montante = ((+atual) + (+unitario)) - prev;

    } else {
        montante = (+atual) + (+unitario)
    }

    $('#totalGolf' + x + '_' + y).html(parseFloat(montante).toFixed(2))
    totalGolf = $('#totalGolf' + x + '_' + y).html()
    totalGolfExtra = $('#totalGolfExtra' + x + '_' + y).html()
        //kick-back
    percent = $('#kickbackGolfInput' + x + '_' + y).val() / 100;
    valorKickback = totalGolf * percent;
    $('#kickbackGolf' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2))
        //markup
    percentMarkup = $('#markupGolfInput' + x + '_' + y).val() / 100;
    valorMarkup = totalGolf * percentMarkup;
    $('#markupGolf' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2))
        //final
    finalGolf = (+totalGolf) + (+totalGolfExtra) - (valorKickback) + (+valorMarkup)
    $('#finalGolf' + x + '_' + y).html(parseFloat(finalGolf).toFixed(2))


    atsRate = $("#atsRateGolf" + x + '_' + y + '_' + z).val();

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }

    total = $('#totalizaGolf' + x + '_' + y + '_' + z).html();
    atsTotalRate = (atsRate * qntd) - (free * atsRate);

    atsProfit = total - atsTotalRate;


    $('#atsTotalRateGolf' + x + '_' + y + '_' + z).html(parseFloat(atsTotalRate).toFixed(2));

    if (atsRate == 0) {
        $("#atsProfitGolf" + x + '_' + y + '_' + z).html(parseFloat(total).toFixed(2))
        $('#atsProfitGolf' + x + '_' + y + '_' + z).data('val', parseFloat(total).toFixed(2));
    } else {
        if (atsTotalRate) {
            $("#atsProfitGolf" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2))
            $('#atsProfitGolf' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
        } else {
            $("#atsProfitGolf" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $('#atsProfitGolf' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
        }
    }

    unitario2 = $('#atsProfitGolf' + x + '_' + y + '_' + z).html();

    atual2 = $('#totalProfitGolf' + x + '_' + y).html();

    if (atsProfitGolf != null) {
        montante2 = ((+atual2) + (+unitario2)) - atsProfitGolf;

    } else {
        montante2 = (+atual2) + (+unitario2)
    }

    // montante2 = (+unitario2)-(valorKickback)+(+valorMarkup);
    $('#totalProfitGolf' + x + '_' + y).html(parseFloat(montante2).toFixed(2))

}

function somaTransfer(x, y, z) {
    var prev = $('#realTransfer' + x + '_' + y + '_' + z).data('val');
    var atsProfitTransfer = $('#atsProfitTransfer' + x + '_' + y + '_' + z).data('val');
    real = document.getElementById("realTransfer" + x + '_' + y + '_' + z).value;

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }


    if (real) {

        $('#realTransfer' + x + '_' + y + '_' + z).data('val', parseFloat(real).toFixed(2));
    } else {

        $('#realTransfer' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }

    // unitario=$('#realTransfer'+x+'_'+y+'_'+z).html();
    unitario = document.getElementById("realTransfer" + x + '_' + y + '_' + z).value;

    atual = $('#totalTransfer' + x + '_' + y).html();

    if (prev != null) {
        montante = ((+atual) + (+unitario)) - prev;

    } else {
        montante = (+atual) + (+unitario)

    }

    $('#totalTransfer' + x + '_' + y).html(parseFloat(montante).toFixed(2))
    totalTransfer = $('#totalTransfer' + x + '_' + y).html()

    totalTransferExtra = $('#totalTransferExtra' + x + '_' + y).html()
        //kick-back
    percent = document.getElementById('kickbackTransferInput' + x + '_' + y).value / 100;
    valorKickback = totalTransfer * percent;
    $('#kickbackTransfer' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2))
        //markup
    percentMarkup = document.getElementById('markupTransferInput' + x + '_' + y).value / 100;
    valorMarkup = totalTransfer * percentMarkup;
    $('#markupTransfer' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2))
        //final
    finalTransfer = (+totalTransfer) + (+totalTransferExtra) - (valorKickback) + (+valorMarkup)
    $('#finalTransfer' + x + '_' + y).html(parseFloat(finalTransfer).toFixed(2))

    atsRate = document.getElementById("atsRateTransfer" + x + '_' + y + '_' + z).value;

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }

    atsProfit = real - atsRate;

    console.log("VALOR DE REAL ", real);
    console.log("VALOR DE REAL ", atsRate);

    if (atsRate == 0) {
        $("#atsProfitTransfer" + x + '_' + y + '_' + z).html(parseFloat(real).toFixed(2))
        $('#atsProfitTransfer' + x + '_' + y + '_' + z).data('val', parseFloat(real).toFixed(2));
    } else {
        if (atsRate) {
            $("#atsProfitTransfer" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2))
            $('#atsProfitTransfer' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
        } else {
            $("#atsProfitTransfer" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $('#atsProfitTransfer' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
        }
    }

    unitario2 = $('#atsProfitTransfer' + x + '_' + y + '_' + z).html();

    atual2 = $('#totalProfitTransfer' + x + '_' + y).html();

    if (atsProfitTransfer != null) {
        montante2 = ((+atual2) + (+unitario2)) - atsProfitTransfer;

    } else {
        montante2 = (+atual2) + (+unitario2)
    }
    $('#totalProfitTransfer' + x + '_' + y).html(parseFloat(montante2).toFixed(2))
}

function somaCar(x, y, z) {


    var prev = $('#totalizaCar' + x + '_' + y + '_' + z).data('val');
    var atsProfitTransfer = $('#atsProfitCar' + x + '_' + y + '_' + z).data('val');


    dias = $('#dias' + x + '_' + y + '_' + z).html();
    real = document.getElementById("realCar" + x + '_' + y + '_' + z).value;

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }

    tax = document.getElementById("tax" + x + '_' + y + '_' + z).value;

    price = (+real * dias) + (+tax);

    if (real) {

        $('#totalizaCar' + x + '_' + y + '_' + z).html(parseFloat(price).toFixed(2))
        $('#totalizaCar' + x + '_' + y + '_' + z).data('val', parseFloat(price).toFixed(2));
    } else {

        $('#totalizaCar' + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
        $('#totalizaCar' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }

    unitario = $('#totalizaCar' + x + '_' + y + '_' + z).html();

    atual = $('#totalCar' + x + '_' + y).html();

    if (prev != null) {
        montante = ((+atual) + (+unitario)) - prev;

    } else {
        montante = (+atual) + (+unitario)
    }

    $('#totalCar' + x + '_' + y).html(parseFloat(montante).toFixed(2));
    totalCar = $('#totalCar' + x + '_' + y).html();
    totalCarExtra = $('#totalCarExtra' + x + '_' + y).html();
    //kick-back
    percent = document.getElementById('kickbackCarInput' + x + '_' + y).value / 100;
    valorKickback = totalCar * percent;
    $('#kickbackCar' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));
    //markup
    percentMarkup = document.getElementById('markupCarInput' + x + '_' + y).value / 100;
    valorMarkup = totalCar * percentMarkup;
    $('#markupCar' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));
    //final
    finalCar = (+totalCar) + (+totalCarExtra) - (valorKickback) + (+valorMarkup);
    $('#finalCar' + x + '_' + y).html(parseFloat(finalCar).toFixed(2));


    atsRate = document.getElementById("atsRateCar" + x + '_' + y + '_' + z).value;

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }


    total = $('#totalizaCar' + x + '_' + y + '_' + z).html();
    atsTotalRate = (+dias * atsRate);
    atsProfit = total - (atsTotalRate + (+tax));

    console.log("Valor do ATS RATE CAR", atsRate, atsTotalRate);

    if (atsTotalRate == 0) {
        $("#atsProfitCar" + x + '_' + y + '_' + z).html(parseFloat(total).toFixed(2))
        $('#atsProfitCar' + x + '_' + y + '_' + z).data('val', parseFloat(total).toFixed(2));
    } else {
        if (atsRate) {
            $('#atsTotalRateCar' + x + '_' + y + '_' + z).html(parseFloat(atsTotalRate + (+tax)).toFixed(2))
            $("#atsProfitCar" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2))
            $('#atsProfitCar' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
        } else {
            $('#atsTotalRateCar' + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $("#atsProfitCar" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $('#atsProfitCar' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
        }
    }

    unitario2 = $('#atsProfitCar' + x + '_' + y + '_' + z).html();

    atual2 = $('#totalProfitCar' + x + '_' + y).html();

    if (atsProfitTransfer != null) {
        montante2 = ((+atual2) + (+unitario2)) - atsProfitTransfer;

    } else {
        montante2 = (+atual2) + (+unitario2)
    }
    $('#totalProfitCar' + x + '_' + y).html(parseFloat(montante2).toFixed(2))
}

function somaTicket(x, y, z) {
    var prev = $('#realTicket' + x + '_' + y + '_' + z).data('val');
    var atsProfitTicket = $('#atsProfitTicket' + x + '_' + y + '_' + z).data('val');
    real = document.getElementById("realTicket" + x + '_' + y + '_' + z).value;

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }

    if (real) {

        $('#realTicket' + x + '_' + y + '_' + z).data('val', parseFloat(real).toFixed(2));
    } else {

        $('#realTicket' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }

    // unitario=$('#realTransfer'+x+'_'+y+'_'+z).html();
    unitario = document.getElementById("realTicket" + x + '_' + y + '_' + z).value;

    atual = $('#totalTicket' + x + '_' + y).html();

    if (prev != null) {
        montante = ((+atual) + (+unitario)) - prev;

    } else {
        montante = (+atual) + (+unitario)

    }

    $('#totalTicket' + x + '_' + y).html(parseFloat(montante).toFixed(2));
    totalTicket = $('#totalTicket' + x + '_' + y).html();

    totalTicketExtra = $('#totalTicketExtra' + x + '_' + y).html();
    //kick-back
    percent = document.getElementById('kickbackTicketInput' + x + '_' + y).value / 100;
    valorKickback = totalTicket * percent;
    $('#kickbackTicket' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));
    //markup
    percentMarkup = document.getElementById('markupTicketInput' + x + '_' + y).value / 100;
    valorMarkup = totalTicket * percentMarkup;
    $('#markupTicket' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));
    //final
    finalTicket = (+totalTicket) + (+totalTicketExtra) - (valorKickback) + (+valorMarkup)
    $('#finalTicket' + x + '_' + y).html(parseFloat(finalTicket).toFixed(2));

    atsRate = document.getElementById("atsRateTicket" + x + '_' + y + '_' + z).value;

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }

    atsProfit = real - atsRate;

    if (atsRate == 0) {
        $("#atsProfitTicket" + x + '_' + y + '_' + z).html(parseFloat(real).toFixed(2))
        $('#atsProfitTicket' + x + '_' + y + '_' + z).data('val', parseFloat(real).toFixed(2));
    } else {
        if (atsRate) {

            $("#atsProfitTicket" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2))
            $('#atsProfitTicket' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
        } else {

            $("#atsProfitTicket" + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2))
            $('#atsProfitTicket' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
        }
    }

    unitario2 = $('#atsProfitTicket' + x + '_' + y + '_' + z).html();

    atual2 = $('#totalProfitTicket' + x + '_' + y).html();

    if (atsProfitTicket != null) {
        montante2 = ((+atual2) + (+unitario2)) - atsProfitTicket;

    } else {
        montante2 = (+atual2) + (+unitario2)
    }
    $('#totalProfitTicket' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
}

function somaExtra(qntd, x, y, z, tag) {
    // console.log(qntd, x, y, z, tag);
    if (!qntd) {
        if (tag == 'Acc') {
            var qntd = $('#room_extra_amount' + x + '_' + y + '_' + z).val();
        } else if (tag == 'Golf') {
            var qntd = $('#golf_extra_amount' + x + '_' + y + '_' + z).val();

        } else if (tag == 'Transfer') {
            var qntd = $('#transfer_extra_amount' + x + '_' + y + '_' + z).val();

        } else if (tag == 'Car') {
            var qntd = $('#car_extra_amount' + x + '_' + y + '_' + z).val();

        } else if (tag == 'Ticket') {
            var qntd = $('#ticket_extra_amount' + x + '_' + y + '_' + z).val();
        }
    }
    var prev = $('#extraTotal' + x + '_' + y + '_' + z).data('val');
    var atsExtraProfit = $('#atsExtraProfit' + x + '_' + y + '_' + z).data('val');
    real = $("#extraRate" + x + '_' + y + '_' + z).val();

    if (isNaN(real) || real == "" || real == null) {
        real = 0;
    }


    if (real) {
        $('#extraTotal' + x + '_' + y + '_' + z).html(parseFloat(qntd * real).toFixed(2))
        $('#extraTotal' + x + '_' + y + '_' + z).data('val', parseFloat(qntd * real).toFixed(2));
        var result = qntd * real;
        // console.log(qntd, real, result);
    } else {
        $('#extraTotal' + x + '_' + y + '_' + z).html(parseFloat(0).toFixed(2));
        $('#extraTotal' + x + '_' + y + '_' + z).data('val', parseFloat(0).toFixed(2));
    }
    unitario = $('#extraTotal' + x + '_' + y + '_' + z).html();

    atualAcc = $('#total' + tag + 'Extra' + x + '_' + y).html();

    if (prev != null) {
        montanteAcc = ((+atualAcc) + (+unitario)) - prev;
    } else {
        montanteAcc = (+atualAcc) + (+unitario);
    }

    $('#total' + tag + 'Extra' + x + '_' + y).html(parseFloat(montanteAcc).toFixed(2));

    totalAcc = $('#total' + tag + x + '_' + y).html();

    //kick-back
    percent = $('#kickback' + tag + 'Input' + x + '_' + y).val() / 100;
    valorKickback = totalAcc * percent;
    $('#kickback' + tag + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));
    //markup
    percentMarkup = $('#markup' + tag + 'Input' + x + '_' + y).val() / 100;
    valorMarkup = totalAcc * percentMarkup;
    $('#markup' + tag + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalAccExtra = $('#total' + tag + 'Extra' + x + '_' + y).html();

    finalAcc = (+totalAcc) + (+totalAccExtra) - (valorKickback) + (+valorMarkup);
    $('#final' + tag + x + '_' + y).html(parseFloat(finalAcc).toFixed(2));

    atsRate = $("#atsExtraRate" + x + '_' + y + '_' + z).val();

    if (isNaN(atsRate) || atsRate == "" || atsRate == null) {
        atsRate = 0;
    }


    total = $('#extraTotal' + x + '_' + y + '_' + z).html();
    atsTotalRate = qntd * atsRate;
    atsProfit = total - atsTotalRate;
    // console.log(qntd, atsRate, atsTotalRate);

    $('#atsTotalExtraRate' + x + '_' + y + '_' + z).html(parseFloat(atsTotalRate).toFixed(2));
    /*if(atsTotalRate){*/
    $("#atsExtraProfit" + x + '_' + y + '_' + z).html(parseFloat(atsProfit).toFixed(2));
    $('#atsExtraProfit' + x + '_' + y + '_' + z).data('val', parseFloat(atsProfit).toFixed(2));
    /* }else{
         $("#atsExtraProfit"+x+'_'+y+'_'+z).html(parseFloat(0).toFixed(2))
         $('#atsExtraProfit'+x+'_'+y+'_'+z).data('val',parseFloat(0).toFixed(2) );
     }*/

    unitario2 = $('#atsExtraProfit' + x + '_' + y + '_' + z).html();
    if (tag == 'Acc') {
        atual2 = $('#totalProfitAcc' + x + '_' + y).html();

        if (atsExtraProfit != null) {
            montante2 = ((+atual2) + (+unitario2)) - atsExtraProfit;
        } else {
            montante2 = (+atual2) + (+unitario2);
        }
        $('#totalProfitAcc' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
    }
    if (tag == 'Golf') {
        atual2 = $('#totalProfitGolf' + x + '_' + y).html();

        if (atsExtraProfit != null) {
            montante2 = ((+atual2) + (+unitario2)) - atsExtraProfit;
        } else {
            montante2 = (+atual2) + (+unitario2);
        }
        $('#totalProfitGolf' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
    }
    if (tag == 'Transfer') {
        atual2 = $('#totalProfitTransfer' + x + '_' + y).html();

        if (atsExtraProfit != null) {
            montante2 = ((+atual2) + (+unitario2)) - atsExtraProfit;
        } else {
            montante2 = (+atual2) + (+unitario2);
        }
        $('#totalProfitTransfer' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
    }
    if (tag == 'Car') {
        atual2 = $('#totalProfitCar' + x + '_' + y).html();

        if (atsExtraProfit != null) {
            montante2 = ((+atual2) + (+unitario2)) - atsExtraProfit;
        } else {
            montante2 = (+atual2) + (+unitario2);
        }
        $('#totalProfitCar' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
    }
    if (tag == 'Ticket') {
        atual2 = $('#totalProfitTicket' + x + '_' + y).html();

        if (atsExtraProfit != null) {
            montante2 = ((+atual2) + (+unitario2)) - atsExtraProfit;
        } else {
            montante2 = (+atual2) + (+unitario2);
        }
        $('#totalProfitTicket' + x + '_' + y).html(parseFloat(montante2).toFixed(2));
    }
}

function kickbackAcc(x, y, z) {
    percent = document.getElementById('kickbackAccInput' + x + '_' + y).value / 100;
    totalAcc = $('#totalAcc' + x + '_' + y).html();
    valorKickback = totalAcc * percent;
    // $('#kickbackAcc' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));
    $('#kickbackAcc' + x + '_' + y).html(jqueryFloatFormat(parseFloat(valorKickback)));

    totalAccExtra = $('#totalAccExtra' + x + '_' + y).html();
    finalAcc = (+totalAcc) + (+totalAccExtra) - (valorKickback) + (+valorMarkup);
    $('#finalAcc' + x + '_' + y).html(parseFloat(finalAcc).toFixed(2));
}

function markupAcc(x, y, z) {
    percentMarkup = document.getElementById('markupAccInput' + x + '_' + y).value / 100;
    totalAcc = $('#totalAcc' + x + '_' + y).html();
    valorMarkup = totalAcc * percentMarkup;
    $('#markupAcc' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalAccExtra = $('#totalAccExtra' + x + '_' + y).html();
    finalAcc = (+totalAcc) + (+totalAccExtra) - (valorKickback) + (+valorMarkup);
    $('#finalAcc' + x + '_' + y).html(parseFloat(finalAcc).toFixed(2));

}

function kickbackGolf(x, y, z) {
    percent = document.getElementById('kickbackGolfInput' + x + '_' + y).value / 100;
    totalGolf = $('#totalGolf' + x + '_' + y).html();
    valorKickback = totalGolf * percent;
    $('#kickbackGolf' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));

    totalGolfExtra = $('#totalGolfExtra' + x + '_' + y).html();
    finalGolf = (+totalGolf) + (+totalGolfExtra) - (valorKickback) + (+valorMarkup);
    $('#finalGolf' + x + '_' + y).html(parseFloat(finalGolf).toFixed(2));

}

function markupGolf(x, y, z) {
    percentMarkup = document.getElementById('markupGolfInput' + x + '_' + y).value / 100;
    totalGolf = $('#totalGolf' + x + '_' + y).html();
    valorMarkup = totalGolf * percentMarkup;
    $('#markupGolf' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalGolfExtra = $('#totalGolfExtra' + x + '_' + y).html();
    finalGolf = (+totalGolf) + (+totalGolfExtra) - (valorKickback) + (+valorMarkup);
    $('#finalGolf' + x + '_' + y).html(parseFloat(finalGolf).toFixed(2));

}

function kickbackTransfer(x, y, z) {
    percent = document.getElementById('kickbackTransferInput' + x + '_' + y).value / 100;
    totalTransfer = $('#totalTransfer' + x + '_' + y).html();
    valorKickback = totalTransfer * percent;
    $('#kickbackTransfer' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));

    totalTransferExtra = $('#totalTransferExtra' + x + '_' + y).html();
    finalTransfer = (+totalTransfer) + (+totalTransferExtra) - (valorKickback) + (+valorMarkup);
    $('#finalTransfer' + x + '_' + y).html(parseFloat(finalTransfer).toFixed(2));

}

function markupTransfer(x, y, z) {
    percentMarkup = document.getElementById('markupTransferInput' + x + '_' + y).value / 100;
    totalTransfer = $('#totalTransfer' + x + '_' + y).html();
    valorMarkup = totalTransfer * percentMarkup;
    $('#markupTransfer' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalTransferExtra = $('#totalTransferExtra' + x + '_' + y).html()
    finalTransfer = (+totalTransfer) + (+totalTransferExtra) - (valorKickback) + (+valorMarkup)
    $('#finalTransfer' + x + '_' + y).html(parseFloat(finalTransfer).toFixed(2));

}

function kickbackCar(x, y, z) {
    percent = document.getElementById('kickbackCarInput' + x + '_' + y).value / 100;
    totalCar = $('#totalCar' + x + '_' + y).html();
    valorKickback = totalCar * percent;
    $('#kickbackCar' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));

    totalCarExtra = $('#totalCarExtra' + x + '_' + y).html();
    finalCar = (+totalCar) + (+totalCarExtra) - (valorKickback) + (+valorMarkup);
    $('#finalCar' + x + '_' + y).html(parseFloat(finalCar).toFixed(2));

}

function markupCar(x, y, z) {
    percentMarkup = document.getElementById('markupCarInput' + x + '_' + y).value / 100;
    totalCar = $('#totalCar' + x + '_' + y).html();
    valorMarkup = totalCar * percentMarkup;
    $('#markupCar' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalCarExtra = $('#totalCarExtra' + x + '_' + y).html();
    finalCar = (+totalCar) + (+totalCarExtra) - (valorKickback) + (+valorMarkup);
    $('#finalCar' + x + '_' + y).html(parseFloat(finalCar).toFixed(2));

}

function kickbackTicket(x, y, z) {
    percent = document.getElementById('kickbackTicketInput' + x + '_' + y).value / 100;
    totalTicket = $('#totalTicket' + x + '_' + y).html();
    valorKickback = totalTicket * percent;
    $('#kickbackTicket' + x + '_' + y).html(parseFloat(valorKickback).toFixed(2));

    totalTicketExtra = $('#totalTicketExtra' + x + '_' + y).html();
    finalTicket = (+totalTicket) + (+totalTicketExtra) - (valorKickback) + (+valorMarkup);
    $('#finalTicket' + x + '_' + y).html(parseFloat(finalTicket).toFixed(2));

}

function markupTicket(x, y, z) {
    percentMarkup = document.getElementById('markupTicketInput' + x + '_' + y).value / 100;
    totalTicket = $('#totalTicket' + x + '_' + y).html();
    valorMarkup = totalTicket * percentMarkup;
    $('#markupTicket' + x + '_' + y).html(parseFloat(valorMarkup).toFixed(2));

    totalTicketExtra = $('#totalTicketExtra' + x + '_' + y).html();
    finalTicket = (+totalTicket) + (+totalTicketExtra) - (valorKickback) + (+valorMarkup);
    $('#finalTicket' + x + '_' + y).html(parseFloat(finalTicket).toFixed(2));

}

function mail(prod, produto_id, pedido_id) {
    var id = '#email_' + produto_id + '_' + pedido_id + '';

    console.log(produto_id, pedido_id, id);
    console.log(prod);
    console.log("aqui aqui");

    var mail = $(id).val();

    var val = { 'email': mail, 'pedido_geral_id': pedido_id, 'prod': prod, };
    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/mail",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            if (data.result == 1) {
                alert('Dont exist reservation e-mail for this product, please check!');
            } else {
                alert('Sucess: email send');
            }

            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            location.reload();
        }
    });
}

function mailConf(prod) {
    var val = { 'prod': prod };
    $.ajax({
        type: 'GET',
        url: assetBaseUrl + "profile/mailConf",
        data: val,
        async: false,
        success: function(data) {
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function envia(id, key) {

    $('.loader').show("fast", function() {

        var total = $('.grandTotal' + key).html();
        var profit = $('#grandProfit' + key).html();
        var status = 'Waiting Confirmation'
        var quartos = $('[id^=form_rooms_' + key + ']').serialize();
        var golfe = $('[id^=form_golf_' + key + ']').serialize();
        var transfers = $('[id^=form_transfers_' + key + ']').serialize();
        var cars = $('[id^=form_cars_' + key + ']').serialize();
        var tickets = $('[id^=form_tickets_' + key + ']').serialize();
        var val = { 'id': id, 'total': total, 'profit': profit, 'status': status, 'quartos': quartos, 'golfe': golfe, 'transfers': transfers, 'cars': cars, 'tickets': tickets, 'key': key };

        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/create?profileAction=ChangeToEdited",
            data: val,
            dataType: 'JSON',
            async: false,
            beforeSend: function() {
                $('.loader').show();
            },
            sucess: function(json) {
                localStorage.setItem("callback_create", json);
            },
            complete: function() {
                $(".buttonn" + key).click();
                $(".mail" + key).click();
            },
            error: function(jqXHR, textStatus, errorThrown) {

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);

            },
        });

    });
}

function edita(id, key, element) {

    $('.loader').show("fast", function() {

        var total = $('.grandTotal' + key).html();
        var profit = $('#grandProfit' + key).html();
        var status = 'Edited';
        var quartos = $('[id^=form_rooms_' + key + ']').serialize();
        var golfe = $('[id^=form_golf_' + key + ']').serialize();
        var transfers = $('[id^=form_transfers_' + key + ']').serialize();
        var cars = $('[id^=form_cars_' + key + ']').serialize();
        var tickets = $('[id^=form_tickets_' + key + ']').serialize();
        var extras = $('[id^=form_extras' + key + ']').serialize();
        console.log(total, key);
        var val = { 'id': id, 'total': total, 'profit': profit, 'status': status, 'quartos': quartos, 'golfe': golfe, 'transfers': transfers, 'cars': cars, 'tickets': tickets, 'extras': extras, 'key': key };

        $.ajax({

            type: 'POST',
            url: assetBaseUrl + "profile/create",
            data: val,
            dataType: 'Json',
            async: false,
            success: function(data) {
                var adress = assetBaseUrl + 'profile';
                location.reload();
                localStorage.setItem("callback_create", data);
            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('.loader').hide();

                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
            complete: function() {

                $(".buttonn" + key).click();
                location.reload();
            },
        });


    });
}

function recalcular(id, key, element) {

    $('.loader').show("fast", function() {

        var total = $('.grandTotal' + key).html();
        var profit = $('#grandProfit' + key).html();
        var status = 'In Progress';
        var quartos = $('[id^=form_rooms_' + key + ']').serialize();
        var golfe = $('[id^=form_golf_' + key + ']').serialize();
        var transfers = $('[id^=form_transfers_' + key + ']').serialize();
        var cars = $('[id^=form_cars_' + key + ']').serialize();
        var tickets = $('[id^=form_tickets_' + key + ']').serialize();
        var extras = $('[id^=form_extras' + key + ']').serialize();
        console.log(total, key);
        var val = {
            'id': id,
            'total': total,
            'profit': profit,
            'status': status,
            'quartos': quartos,
            'golfe': golfe,
            'transfers': transfers,
            'cars': cars,
            'tickets': tickets,
            'extras': extras,
            'key': key
        };

        $.ajax({

            type: 'POST',
            url: assetBaseUrl + "profile/create",
            data: val,
            dataType: 'Json',
            async: false,
            success: function(data) {
                var adress = assetBaseUrl + 'profile';
                location.reload();
                localStorage.setItem("callback_create", data);
            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('.loader').hide();

                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
            complete: function() {

                $(".buttonn" + key).click();
                location.reload();
            },
        });


    });

}

function removeRow(id, key, element, tipo) {
    // $(".buttonn" + key).click();
    var total = $('.grandTotal' + key).html();
    var profit = $('#grandProfit' + key).html();
    var val = { 'id': id, 'total': total, 'profit': profit, 'tipo': tipo };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/delete",
        data: val,
        success: function(data) {
            var adress = assetBaseUrl + 'profile';

            console.log(data);
            window.setTimeout(function() {

                location.reload();
                removeTR(element);
            }, 1000);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}

function confirma(id, key) {

    var status = 'Waiting Confirmation';
    var val = { 'id': id, 'status': status };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/confirm",
        data: val,
        dataType: 'Json',
        async: false,
        beforeSend: function() {
            $('.loader').show();
        },
        success: function(data) {
            var adress = assetBaseUrl + 'profile';
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('.loader').hide();

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}

function cancel(id, key) {

    var status = 'Cancelled';
    var cancel = true;
    var val = { 'id': id, 'status': status, 'cancel': cancel };

    if (id != null && typeof id != "undefined") {
        $.ajax({
            type: 'GET',
            url: assetBaseUrl + "admin/profile/confirm",
            data: val,
            success: function(data) {
                try {
                    $.ajax({
                        type: 'post',
                        url: assetBaseUrl + "profile/cancelar_booking_api",
                        data: val,
                        success: function() {
                            alert("Pedido cancelado com sucesso!");
                            window.setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                    });

                } catch (error) {
                    console.log(error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

}

function cancelSendMail(id, key) {

    var status = 'Cancelled';
    var val = { 'id': id, 'status': status, 'mail': true };

    $.ajax({
        type: 'GET',
        url: assetBaseUrl + "profile/confirm",
        data: val,
        success: function(data) {
            var adress = assetBaseUrl + 'profile';
            // location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}

function confirmaAts(id, key) {

    $('.loader').show("fast", function() {

        //var total = $('.grandTotal' + key).html();
        //var profit = $('#grandProfit' + key).html();

        var total = $('.grandTotal' + key).html();
        var profit = $('#grandProfit' + key).html();
        var status = 'Confirmed';

        var quartos = $('[id^=form_rooms_' + key + ']').serialize();
        var golfe = $('[id^=form_golf_' + key + ']').serialize();
        var transfers = $('[id^=form_transfers_' + key + ']').serialize();
        var cars = $('[id^=form_cars_' + key + ']').serialize();
        var tickets = $('[id^=form_tickets_' + key + ']').serialize();
        var extras = $('[id^=form_extras' + key + ']').serialize();

        var val = { 'id': id, 'total': total, 'profit': profit, 'status': status, 'quartos': quartos, 'golfe': golfe, 'transfers': transfers, 'cars': cars, 'tickets': tickets, 'extras': extras, 'key': key };

        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/confirm",
            data: val,
            dataType: "Json",
            success: function(data) {
                var adress = assetBaseUrl + 'profile';
            },
            complete: function() {
                $(".buttonn" + key).click();
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });

    });
}

function enviaProduct(id, key, key1) {

    var total = $('#totalProduct' + key + '_' + key1).html();
    var profit = $('#profitProduct' + key + '_' + key1).html();

    var val = { 'id': id, 'total': total, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProduct",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log("exibindo dados create product");
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}

function enviaQuartos(id, key, key1) {

    var room = $('#totalAcc' + key + '_' + key1).html();
    var extra = $('#totalAccExtra' + key + '_' + key1).html();
    var kick = document.getElementById('kickbackAccInput' + key + '_' + key1).value;
    var markup = document.getElementById('markupAccInput' + key + '_' + key1).value;
    var total = $('#finalAcc' + key + '_' + key1).html();
    var profit = $('#totalProfitAcc' + key + '_' + key1).html();

    var val = { 'id': id, 'room': room, 'extra': extra, 'kick': kick, 'markup': markup, 'total': total, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductRooms",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {

        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);


        },
    });
}

function enviaQuartosEsp(id, key, key1, key2) {


    var dias = $('#dias' + key + '_' + key1 + '_' + key2).html();
    var night = document.getElementById('real' + key + '_' + key1 + '_' + key2).value;
    var offer_name = document.getElementById('oferta' + key + '_' + key1 + '_' + key2).value;
    var offer = document.getElementById('desconto' + key + '_' + key1 + '_' + key2).value;
    var price = $('#preco' + key + '_' + key1 + '_' + key2).html();
    var total = $('#totaliza' + key + '_' + key1 + '_' + key2).html();
    var ats_rate = document.getElementById('atsRate' + key + '_' + key1 + '_' + key2).value;
    var ats_total_rate = $('#atsTotalRate' + key + '_' + key1 + '_' + key2).html();
    var profit = $('#atsProfit' + key + '_' + key1 + '_' + key2).html();
    var val = { 'id': id, 'days': dias, 'night': night, 'offer_name': offer_name, 'offer': offer, 'price': price, 'total': total, 'ats_rate': ats_rate, 'ats_total_rate': ats_total_rate, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductRoomsEsp",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });

            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });

}

function enviaGolfs(id, key, key1) {

    var valor_golf = $('#totalGolf' + key + '_' + key1).html();
    var valor_extra = $('#totalGolfExtra' + key + '_' + key1).html();
    var kick = document.getElementById('kickbackGolfInput' + key + '_' + key1).value;
    var markup = document.getElementById('markupGolfInput' + key + '_' + key1).value;
    var total = $('#finalGolf' + key + '_' + key1).html();
    var profit = $('#totalProfitGolf' + key + '_' + key1).html();
    var val = { 'id': id, 'valor_golf': valor_golf, 'valor_extra': valor_extra, 'kick': kick, 'markup': markup, 'total': total, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductGolfs",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {

            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);


        },
    });

}

function enviaGolfsEsp(id, key, key1, key2) {

    var free = document.getElementById('playersFree' + key + '_' + key1 + '_' + key2).value;
    var rate = document.getElementById('realGolf' + key + '_' + key1 + '_' + key2).value;
    var total = $('#totalizaGolf' + key + '_' + key1 + '_' + key2).html();
    var ats_rate = document.getElementById('atsRateGolf' + key + '_' + key1 + '_' + key2).value;
    var ats_total_rate = $('#atsTotalRateGolf' + key + '_' + key1 + '_' + key2).html();
    var profit = $('#atsProfitGolf' + key + '_' + key1 + '_' + key2).html();
    var val = { 'id': id, 'free': free, 'rate': rate, 'total': total, 'ats_rate': ats_rate, 'ats_total_rate': ats_total_rate, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductGolfsEsp",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);


        },
    });

}

function enviaTransfers(id, key, key1) {


    var valor_transfer = $('#totalTransfer' + key + '_' + key1).html();
    var valor_extra = $('#totalTransferExtra' + key + '_' + key1).html();
    var kick = document.getElementById('kickbackTransferInput' + key + '_' + key1).value;
    var markup = document.getElementById('markupTransferInput' + key + '_' + key1).value;
    var total = $('#finalTransfer' + key + '_' + key1).html();
    var profit = $('#totalProfitTransfer' + key + '_' + key1).html();
    var val = { 'id': id, 'valor_transfer': valor_transfer, 'valor_extra': valor_extra, 'kick': kick, 'markup': markup, 'total': total, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductTransfers",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}

function enviaTransfersEsp(id, key, key1, key2) {

    console.log('vou enviar o transfer');

    var company = document.getElementById('company' + key + '_' + key1 + '_' + key2).value;
    var total = document.getElementById('realTransfer' + key + '_' + key1 + '_' + key2).value;
    var ats_rate = document.getElementById('atsRateTransfer' + key + '_' + key1 + '_' + key2).value;
    var profit = $('#atsProfitTransfer' + key + '_' + key1 + '_' + key2).html();
    var val = { 'id': id, 'company': company, 'total': total, 'ats_rate': ats_rate, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductTransfersEsp",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);


        },
    });

}

function enviaCars(id, key, key1) {


    var valor_car = $('#totalCar' + key + '_' + key1).html();
    var valor_extra = $('#totalCarExtra' + key + '_' + key1).html();
    var kick = document.getElementById('kickbackCarInput' + key + '_' + key1).value;
    var markup = document.getElementById('markupCarInput' + key + '_' + key1).value;
    var total = $('#finalCar' + key + '_' + key1).html();
    var profit = $('#totalProfitCar' + key + '_' + key1).html();
    var val = { 'id': id, 'valor_car': valor_car, 'valor_extra': valor_extra, 'kick': kick, 'markup': markup, 'total': total, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductCars",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });


}

function enviaCarsEsp(id, key, key1, key2) {

    var rate = document.getElementById('realCar' + key + '_' + key1 + '_' + key2).value;
    var days = $('#days' + key + '_' + key1 + '_' + key2).html();
    var tax = document.getElementById('tax' + key + '_' + key1 + '_' + key2).value;
    // var taxType = document.getElementById('taxType' + key + '_' + key1 + '_' + key2).value;
    var total = $('#totalizaCar' + key + '_' + key1 + '_' + key2).html();
    var ats_rate = document.getElementById('atsRateCar' + key + '_' + key1 + '_' + key2).value;
    var ats_total_rate = $('#atsTotalRateCar' + key + '_' + key1 + '_' + key2).html();
    var profit = $('#atsProfitCar' + key + '_' + key1 + '_' + key2).html();


    var val = { 'id': id, 'rate': rate, 'days': days, 'tax': tax, 'total': total, 'ats_rate': ats_rate, 'ats_total_rate': ats_total_rate, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductCarsEsp",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });


}

function enviaTickets(id, key, key1) {

    var valor_ticket = $('#totalTicket' + key + '_' + key1).html();
    var valor_extra = $('#totalTicketExtra' + key + '_' + key1).html();
    var kick = document.getElementById('kickbackTicketInput' + key + '_' + key1).value;
    var markup = document.getElementById('markupTicketInput' + key + '_' + key1).value;
    var total = $('#finalTicket' + key + '_' + key1).html();
    var profit = $('#totalProfitTicket' + key + '_' + key1).html();
    var val = { 'id': id, 'valor_ticket': valor_ticket, 'valor_extra': valor_extra, 'kick': kick, 'markup': markup, 'total': total, 'profit': profit };
    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductTickets",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });

}

function enviaTicketsEsp(id, key, key1, key2) {


    var total = document.getElementById('realTicket' + key + '_' + key1 + '_' + key2).value;
    var ats_rate = document.getElementById('atsRateTicket' + key + '_' + key1 + '_' + key2).value;
    var profit = $('#atsProfitTicket' + key + '_' + key1 + '_' + key2).html();

    var val = { 'id': id, 'total': total, 'ats_rate': ats_rate, 'profit': profit };

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductTicketsEsp",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {
            console.log(data);

        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);


        },
    });


}

function enviaExtra(id, key, key1, key2, tipo) {

    var rate = $('#extraRate' + key + '_' + key1 + '_' + key2).val();
    var total = $('#extraTotal' + key + '_' + key1 + '_' + key2).html();
    var ats_rate = $('#atsExtraRate' + key + '_' + key1 + '_' + key2).val();
    var ats_total_rate = $('#atsTotalExtraRate' + key + '_' + key1 + '_' + key2).html();
    var profit = $('#atsExtraProfit' + key + '_' + key1 + '_' + key2).html();
    var extra_name = $('#extra_name' + key + '_' + key1 + '_' + key2).val();
    var pedido_produto_id = $($('#extra_name' + key + '_' + key1 + '_' + key2)).closest("form").find(".pedido_produto_id").val();
    if (tipo == 1) { //Rooms
        var amount = $('#room_extra_amount' + key + '_' + key1 + '_' + key2).val();
    } else if (tipo == 2) { //Golf
        var amount = $('#golf_extra_amount' + key + '_' + key1 + '_' + key2).val();
    } else if (tipo == 3) { //Transfers
        var amount = $('#transfer_extra_amount' + key + '_' + key1 + '_' + key2).val();
    } else if (tipo == 4) { //Cars
        var amount = $('#car_extra_amount' + key + '_' + key1 + '_' + key2).val();
    } else if (tipo == 5) { //Tickets
        var amount = $('#ticket_extra_amount' + key + '_' + key1 + '_' + key2).val();
    }


    var val = { 'id': id, 'pedido_produto_id': pedido_produto_id, 'extra_name': extra_name, 'tipo': tipo, 'rate': rate, 'total': total, 'ats_rate': ats_rate, 'ats_total_rate': ats_total_rate, 'profit': profit, 'amount': amount };

    console.log('Pedido Produto ID: ' + pedido_produto_id + " | " + 'Room Extra Name: ' + extra_name + " | " + "ID: " + id);
    console.log(key, key1, key2, tipo);
    console.log(val);

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/createProductExtra",
        data: val,
        dataType: 'Json',
        async: false,
        success: function(data) {

        },
        error: function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            var errors = jqXHR.responseJSON;
            var errorsHtml = '';
            $.each(errors, function(key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        },
    });
}



$(document).ready(function() {
    $(".loaddd").change();

});

$(document).ready(function() {
    $('.accordion-agency').each(function() {
        var checkin = $(this).next().find('.checkin_date').val();
        var checkin_golf = $(this).next().find('.checkin_date_golf').val();
        var checkin_transfer = $(this).next().find('.checkin_date_transfer').val();
        var checkin_car = $(this).next().find('.checkin_date_car').val();
        var checkin_ticket = $(this).next().find('.checkin_date_ticket').val();
        if (checkin) {
            $(this).find('.first_checkin').text(checkin);
        } else if (checkin_golf) {
            $(this).find('.first_checkin').text(checkin_golf);
        } else if (checkin_transfer) {
            $(this).find('.first_checkin').text(checkin_transfer);
        } else if (checkin_car) {
            $(this).find('.first_checkin').text(checkin_car);
        } else if (checkin_ticket) {
            $(this).find('.first_checkin').text(checkin_ticket);
        }
    });

    $('select').click(function($event) {
        $event.stopPropagation();
        $event.preventDefault();
    });

    $('.summernote').summernote({
        placeholder: 'Hello bootstrap 4',
        tabsize: 2,
        height: 100
    });

    $('.editremark_button').click(function() {
        let remarks = $(this).closest('div').find('.remark-box').html();
        let pedido_id = $(this).data('pedido_id');
        let type = $(this).data('type');
        //$('.note-editable').val(remarks);
        console.log(remarks);
        $(".summernote").summernote("code", remarks)
        $('#remark_product_pedido_geral_id').val(pedido_id);
        $('#remark_type').val(type);
    });


});

function editRemark(remark, type, pedido_id) {

    $.ajax({
        type: 'POST',
        url: assetBaseUrl + "profile/editremark",
        data: $.param({
            "remark": remark,
            "type": type,
            "pedido_id": pedido_id,
        }),
        success: function(data) {
            console.log('funciona');
            $('#remark-box' + pedido_id + '').html(remark);
        },
        error: function(jqXHR, textStatus, errorThrown) {

        }
    });
}

$('.edit_remark_btn').click(function() {
    //let type = "room";
    let pedido_id = $(this).closest('span').find('#remark_product_pedido_geral_id').val();
    let type = $(this).closest('span').find('#remark_type').val();
    let remark = $('.summernote').summernote('code');
    $('.edit_remark_btn').closest('input').find('#remark_product_pedido_geral_id').val();
    //console.log(quarto_id, remark);

    editRemark(remark, type, pedido_id);

});


function jqueryFloatFormat(nr) {

    var negativo = false;
    if (Math.sign(parseFloat(nr)) == '-1' || Math.sign(parseFloat(nr)) == '-0') {
        negativo = true;
        nr = nr * -1;
    }

    numeroFormatado = nr.toString();

    if (numeroFormatado.indexOf(',') != -1) { numeroFormatado = numeroFormatado.replace(',', '.'); }
    numeroFormatado = parseFloat(numeroFormatado) * 100;
    numeroFormatado = Math.floor(numeroFormatado) / 100;

    if (negativo == true) {
        numeroFormatado = numeroFormatado * -(1);
    }

    return numeroFormatado;
}



/* ADD PELO FELIX - VALIDACAO DAS DATAS DE CHECKIN E CHECKOUT */
$(document).ready(function() {

    $(".roomCheckout").on('blur change', function() {
        validaDatas(this);
    });
});


function validaDatas(thisCheckout) {

    try {


        let checkoutId = $(thisCheckout).attr("name"); // pega o id da div para poder pegar a div do checkin

        let id = checkoutId.replace("checkout", ''); // deixa somente os numeros na variavel
        const checkin = $("input[name='init" + id + "']"); // pega a div do checkin


        var tempDate = $(checkin).val().split("/");
        tempDate = tempDate[2] + '-' + tempDate[1] + '-' + tempDate[0];
        const checkinDate = new Date(tempDate); // criamos a data

        var tempDate = $(thisCheckout).val().split("/");
        tempDate = tempDate[2] + '-' + tempDate[1] + '-' + tempDate[0];
        const checkoutDate = new Date(tempDate); // criamos a data


        const diffTime = Math.abs(checkoutDate - checkinDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

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
