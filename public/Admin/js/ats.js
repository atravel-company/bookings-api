var assetBaseUrl = window.location.origin + "/admin/";

$(document).ready(function () {
    $(window).ready(function () {
        $('.loader').hide();
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function w3_open() {
    document.getElementById("main").style.marginLeft = "25%";
    document.getElementById("mySidebar").style.width = "25%";
    document.getElementById("mySidebar").style.display = "block";
    /*  document.getElementById("openNav").style.display = 'none';*/
}

function w3_close() {
    document.getElementById("main").style.marginLeft = "0%";
    document.getElementById("mySidebar").style.display = "none";
    /*  document.getElementById("openNav").style.display = "inline-block";*/
}

function bag() {

    console.log(" TO NA FUNCAO ", assetBaseUrl);
    var x = document.getElementById("Demo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        $('#carrinho').load(assetBaseUrl + '/bag/', function () {

        });
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

/************ Add by Netto ************/
$(document).ready(function () {
    var people_number;

    window.makeDiv = function (quarto_id, quartoRoomName, key, select) {


        var tempDiv = '<div class="row rowRooms">';
        tempDiv += '<div class="col-md-7 col-xs-7 col-sm-7 col-lg-7">';
        tempDiv += '<label> Name ' + (parseInt(key) + 1) + '</label>';
        tempDiv += '<input type="text" name="quarto[room_name][]" class="room_pax_name_edit form-control w3-block" value="' + quartoRoomName.name + '">';
        tempDiv += '<input type="hidden" value="' + quartoRoomName.id + '" name="quarto[room_name_id][]">';
        tempDiv += '</div>';
        tempDiv += '<div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">';
        tempDiv += '<label>Room</label>';
        tempDiv += select.prop('outerHTML');
        tempDiv += '</div>';

        tempDiv += '<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">';
        tempDiv += '<button style="margin-top:25px;" type="button" class="btn btn-danger btn-circle btnRemoveNewPax" data-pedidoquartoroomname-id="' + quartoRoomName.id + '"><i class="fa fa-trash"></i></button>';
        tempDiv += '</div>';
        tempDiv += '</div>';

        return tempDiv;
    }

    /* :henrique */
    window.getNewRooms = function (produto_room_id) {

        var pedido_quarto_id = $('#quarto_id').val();
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/get/rooms",
            dataType: "json",
            data: $.param({
                "quarto_id": $('#quarto_id').val(),
            }),
            success: function (pedidoQuartoRoom) {

                var divPai = $("#room_pax_names_div"); /* div pricnipal onde sera feito o append */

                divPai.append("<input type='hidden' name='quartos_pedido_quarto_id' value='" + pedido_quarto_id + "'>");

                var totalQuartos = pedidoQuartoRoom.originalPedidoQuarto.rooms;
                var maxPeople = pedidoQuartoRoom.originalPedidoQuarto.people;

                // if(totalQuartos !== pedidoQuartoRoom.rooms.length){
                //     totalQuartos = pedidoQuartoRoom.rooms.length;
                // }

                var selectQuartosTotal = $('<select name="quarto[room_id][]" class="form-control"></select>');
                var option = $('<option></option>');
                option.attr('value', '-1');
                option.text("Select a room");
                selectQuartosTotal.append(option);

                for (i = 0; i < totalQuartos; i++) {

                    var indice = i + 1;

                    var option = $('<option></option>');

                    if (pedidoQuartoRoom.rooms[i] !== null && typeof pedidoQuartoRoom.rooms[i] != 'undefined' && typeof pedidoQuartoRoom.rooms[i] != undefined) {
                        option.attr('value', pedidoQuartoRoom.rooms[i].id);
                        //option.text("Quarto " + '( '+ pedidoQuartoRoom[i].id +' )'  );
                        option.text("Quarto " + '( ' + indice + ' )');
                    } else {
                        option.attr('value', 'new');
                        option.text("Quarto " + indice + ' ( to create )');
                    }

                    console.log("valor de I::" + i + '  ' + totalQuartos);
                    selectQuartosTotal.append(option);
                }

                var roomNamesTotal = 0;

                $.each(pedidoQuartoRoom.rooms, function (indice, element) {

                    roomNamesTotal += element.pedidoquartoroomname.length;

                    var roomsNames = element.pedidoquartoroomname;
                    var quarto_id = element.id;

                    $.each(roomsNames, function (key, roomName) {
                        var pedido_quarto_room_id = roomName.pedido_quarto_room_id;

                        selectQuartosTotal.children('[value="' + pedido_quarto_room_id + '"]').attr('selected', true);
                        divPai.append(makeDiv(quarto_id, roomName, key, selectQuartosTotal));
                    });
                });

                console.log(parseInt(maxPeople), parseInt(roomNamesTotal));
                if (parseInt(maxPeople) > parseInt(roomNamesTotal)) {

                    var faltaIncrement = parseInt(maxPeople) - parseInt(roomNamesTotal);
                    for (i = 0; i < faltaIncrement; i++) {
                        roomName = new Object();
                        roomName.id = null;
                        roomName.name = 'create new pax';

                        selectQuartosTotal.children('[value="-1"]').attr('selected', true);
                        divPai.append(makeDiv("null", roomName, i, selectQuartosTotal));
                    }
                }

            }, error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            }, complete: function () {

                $(".btnRemoveNewPax").click(function (event) {

                    event.preventDefault();
                    var btn = $(this);

                    $.confirm({
                        title: 'Attention!',
                        content: 'Do you want to continue? ',
                        type: 'blue',
                        theme: 'modern',
                        typeAnimated: true,
                        buttons: {
                            confirm: function () {
                                deleteRowRoom(btn);
                            },
                            cancel: function () {

                            },
                        }
                    });

                });

            }

        });
    }


    $(".room_pax_clear_rooms").click(function () {
        $.confirm({
            title: 'Attention!',
            content: 'Do you want to continue? ',
            type: 'blue',
            theme: 'modern',
            typeAnimated: true,
            buttons: {
                confirm: function () {
                    deleteEmptyRooms();
                },
                cancel: function () {

                },
            }
        });

    });

    window.deleteEmptyRooms = function () {

        $.ajax({
            type: 'post',
            url: assetBaseUrl + "profile/delete/empty/rooms",
            dataType: 'Json',
            async: false,
            data: $.param({
                "quarto_id": $('#quarto_id').val(),
            }),
            beforeSend: function () {
                $(".loader").show();
            }, succes: function () {

            }, complete: function () {
                $(".loader").hide();
                $('#room-pax-names').modal('hide');
            },
            error: function () {
                $(".loader").hide();
            }
        });

    }

    window.deleteRowRoom = function (btn) {

        var modalMainDiv = $("#room_pax_names_div");
        var divPai = btn.parents().filter(".rowRooms");
        var quartoRommNameId = divPai.children().find('[type="hidden"]');
        var quartoId = divPai.children().find('select');

        modalMainDiv.append("<input type='hidden' value='" + quartoRommNameId.val() + "' name='delete_pedido_quarto_room_name_id[]' >");
        divPai.remove();
        return true;
    }

    //Edita os nomes das pessoas - Rooms
    window.newEditRooms = function (formData) {
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/editar/rooms",
            dataType: "json",
            data: formData,
            success: function (data) {
                console.log(data);
                $('#room-pax-names').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //$('#room-pax-names').modal('hide');
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }


    $("input[type='number']").filter("[name*=quantidade]").change(function (event) {
        event.preventDefault();

        var pai = $(this).parents().find('#room-original');

        var maxpeople = pai.find("input[type='number']");
        maxpeople = maxpeople.filter("[name*=pessoas]");

        var quarto_id = pai.find("input[type='hidden']");
        quarto_id = $(this).attr('data-quarto-id');

        var totalRooms = $(this).val();
        var totalPeople = maxpeople.val();
        //quarto_id = quarto_id.val();

        updateQtdRoomAndPeople(totalRooms, totalPeople, quarto_id, $(this), maxpeople);
    });

    $("input[type='number']").filter("[name*=pessoas]").change(function (event) {
        event.preventDefault();

        var pai = $(this).parents().find('#room-original');

        var maxrooms = pai.find("input[type='number']");
        maxrooms = maxrooms.filter("[name*=quantidade]");

        var quarto_id = pai.find("input[type='hidden']");
        quarto_id = $(this).attr('data-quarto-id');

        var totalRooms = maxrooms.val();
        var totalPeople = $(this).val();
        //quarto_id = quarto_id.val();

        updateQtdRoomAndPeople(totalRooms, totalPeople, quarto_id, maxrooms, $(this));
    });

    window.updateQtdRoomAndPeople = function (totalRooms, totalPeople, quarto_id, inputRooms, inputPeople) {
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/new/update/rooms",
            dataType: "json",
            data: {
                'quarto_id': quarto_id,
                'totalRooms': totalRooms,
                'totalPeople': totalPeople,
            },
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (data) {
                $(".loader").hide();
                inputRooms.attr('min', totalRooms);
                inputPeople.attr('min', totalPeople);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".loader").hide();
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
            },
        });
    }









    // $('.extra-tr').bind("DOMSubtreeModified",function(){
    //     $(this).find('[class^="buttonn"]').attr('data-changed', 'true');
    //     console.log($(this).find('[class^="buttonn"]'));
    // });

    //Menu apagar do remove product
    $(function () {

        var $contextMenu = $("#contextMenu");

        $("body").on("contextmenu", ".accordion-product", function (e) {
            $contextMenu.css({
                display: "block",
                left: e.pageX,
                top: e.pageY
            });
            return false;
        });

        $('html').click(function () {
            $contextMenu.hide();
        });

    });

    $('.accordion-product').mousedown(function (e) {
        if (e.button == 2) {
            $('#remove_product_id').val($(this).attr('data-product-id'));
            $('#remove_pedido_geral_id').val($(this).attr('data-pedido-geral-id'));
            $('#remove_key').val($(this).attr('data-key'));
            return false;
        }
        return true;
    });

    $('.remove_product_btn').click(function () {
        var product_id = $('#remove_product_id').val();
        var pedido_geral_id = $('#remove_pedido_geral_id').val();

        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/removeproduct",
            dataType: "json",
            data: $.param({
                "product_id": product_id,
                "pedido_geral_id": pedido_geral_id,
            }),
            success: function (data) {

                alert("Produto apagado com sucesso!");
                console.log(data);
                window.setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                location.reload();
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });

    });

    // //Edita os nomes das pessoas - Rooms
    // window.editRoomsNames = function(room_pax_name_id, room_pax_name, produto_id, people_number) {
    //     $.ajax({
    //         type: 'POST',
    //         url: assetBaseUrl + "profile/editar/rooms",
    //         dataType: "json",
    //         data: $.param({
    //             "room_pax_name_id": room_pax_name_id,
    //             "room_pax_name": room_pax_name,
    //             "people_number": people_number,
    //             "produto_id": produto_id,
    //         }),
    //         success: function(data) {
    //             $('#room-pax-names').modal('hide');
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             $('#room-pax-names').modal('hide');
    //             var errors = jqXHR.responseJSON;
    //             var errorsHtml = '';
    //             $.each(errors, function(key, value) {
    //                 errorsHtml += '<li>' + value[0] + '</li>';
    //             });
    //             $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
    //         },
    //     });
    // }


    //Seta as informações do select produtos, para adicionar novos produtos ao pedido.
    window.getProducts = function (type) {
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/getproducts",
            dataType: "json",
            data: $.param({
                "type": type,
            }),
            success: function (data) {
                var products = $("select[name='products']");
                products.empty();
                products.append("<option>Select...</option>");
                $.each(data, function (index, element) {
                    products.append("<option value='" + element.id + "'>" + element.nome + "</option>");
                });
                $('.loader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

    //Envia pedido de criação de novo Produto
    window.createProducts = function (type_product, pedido_geral_id, produto_id, qtd) {
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/createproducts",
            dataType: "json",
            data: $.param({
                "type": type_product,
                "pedido_geral_id": pedido_geral_id,
                "produto_id": produto_id,
                "qtd": qtd
            }),
            success: function (data) {
                alert(data.success);
                $('.loader').hide();
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

    //Edita a referência e o cliente(lead_name) do pedido.
    window.editPedidoGeral = function (pedido_geral_id, referency, lead_name) {
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/editpedidogeral",
            dataType: "json",
            data: $.param({
                "pedido_geral_id": pedido_geral_id,
                "referency": referency,
                "lead_name": lead_name
            }),
            success: function (data) {
                alert(data.success);
                $('.loader').hide();
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

    $('.create_add_products_btn').click(function () {
        var type = $(this).closest('div.modal').find('#add_product_type').val();
        var pedido_geral_id = $(this).closest('div').find('#add_product_pedido_geral_id').val();
        var produto_id = $(this).closest('div.modal').find('#add_product_product_id').val();
        var qtd = $(this).closest('div.modal').find('#add_product_qtd').val();
        createProducts(type, pedido_geral_id, produto_id, qtd);
    });

    $('.add-product i').click(function () {
        var agency = $(this).attr('data-agency');
        var lead_name = $(this).attr('data-leadname');
        var pedido_geral_id = $(this).attr('data-id');
        var referency = $(this).attr('data-referency');

        $('#add_product_agency').text(agency);
        $('#add_product_lead_name').text(lead_name);
        $('#add_product_pedido_geral_id').val(pedido_geral_id);
        $('#add_product_referency').text(referency);
    });

    $('.edit_products_btn').click(function () {
        var pedido_geral_id = $(this).closest('div').find('#edit_pedido_geral_id').val();
        var referency = $(this).closest('div.modal').find('#edit_referency').val();
        var lead_name = $(this).closest('div.modal').find('#edit_lead_name').val();
        editPedidoGeral(pedido_geral_id, referency, lead_name);
    });

    $('.edit-pedidogeral i').click(function () {
        var agency = $(this).attr('data-agency');
        var lead_name = $(this).attr('data-leadname');
        var pedido_geral_id = $(this).attr('data-id');
        var referency = $(this).attr('data-referency');

        $('#edit_agency').text(agency);
        $('#edit_lead_name').val(lead_name);
        $('#edit_pedido_geral_id').val(pedido_geral_id);
        $('#edit_referency').val(referency);
    });

    $('select[name="product_type"]').change(function () {
        getProducts($(this).val());
    });

    //Edita os remarks
    window.sendRemark = function (remark, pedido_quarto_id, type, operador) {
        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/sendremark",
            dataType: "json",
            data: $.param({
                "remark": remark,
                "pedido_quarto_id": pedido_quarto_id,
                "type": type,
                "operador": operador,
            }),
            success: function (data) {

            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

    //SendRemark
    $(".sendremark_button").click(function () {
        var remark = $(this).closest('div').find('.remark_geral').val();
        var remark_operador = $(this).closest('div').find('.remark_operador').val();
        var pedido_quarto_id = $(this).closest('div').find('.remark_geral_id').val();
        var type = $(this).closest('div').find('.type_remark').val();
        var remark_box_div = $(this).closest('section').find('.remark-box');
        if (remark_operador == "ats") {
            remark_box_div.append("<b class='ats_b'>ATS: </b>" + remark + " </b> <br>");
        } else {
            remark_box_div.append("<b class='agency_b'>Agency: </b>" + remark + " </b> <br>");
        }

        var height = 0;
        $('b').each(function (i, value) {
            height += parseInt($(this).height());
        });
        height += '';
        $(remark_box_div).animate({ scrollTop: height }, 500);

        sendRemark(remark, pedido_quarto_id, type, remark_operador);
    });

    // $('.room_pax_save').click(function(event) {

    //     event.preventDefault();

    //     var people_number = $(this).closest('span').find('#people_number_modal').val();
    //     $('.room_pax_name_edit').each(function(i, obj) {
    //         var room_pax_name = $(this).val();
    //         var room_pax_name_id = $(this).attr('data-id');
    //         var room_id = $(this).attr('data-room-id');
    //         //var room_id = $('.produto_room_id').val();
    //         //var room_pax_name_id = $('pedido_geral_id').val();
    //         editRoomsNames(room_pax_name_id, room_pax_name, room_id, people_number);
    //     });
    // });

    $('.room_pax_save').click(function (event) {

        event.preventDefault();
        var FormData = $("#room-pax-names #room_pax_names_div").find("select, input").serialize();
        newEditRooms(FormData)
        return false;
    });

    //Abre o modal dos nomes - Rooms
    $('.room-pax-icon').click(function () {

        var agencyleadname = $(this).closest('td').find('.agency-lead-name').val();
        var agencyname = $(this).closest('td').find('.agency-name').val();
        var pedido_geral_id = $(this).closest('td').find('.pedido_geral_id').val();
        var produto_room_id = $(this).closest('td').find('.produto_room_id').val();
        var quarto_id = $(this).attr('data-quarto-id');


        var people_number = $(this).closest('tr').find('.people_number').val();
        $('#room-pax-lead-names-modal').text(agencyleadname);
        $('#room-pax-agency-names-modal').text(agencyname);
        $('#room-pax-pedido-geral-modal').val(pedido_geral_id);
        $('#people_number_modal').val(people_number);
        $('#quarto_id').val(quarto_id);

        //Chama a função de listar os nomes

        //getRoomsNames(pedido_geral_id, produto_room_id, people_number);

        getNewRooms(produto_room_id);

    });

    //Limpa os campos dos nomes quando fecha o modal
    $('#room-pax-names').on('hidden.bs.modal', function () {
        $('#room_pax_names_div').empty();
    });

    //Faz a verificação do pagamento, para ver se já está pago ou ainda falta
    window.totalPaymentVerify = function (total_payment, total_pedido_paid, element) {
        // console.log(total_payment, total_pedido_paid, element);
        total_payment = parseFloat(total_payment);
        total_pedido_paid = parseFloat(total_pedido_paid);
        if (total_payment == total_pedido_paid) {
            $(element).css({
                "background-color": "#4CAF50",
                "color": "#fff"
            });
        } else if (total_payment < total_pedido_paid) {
            $(element).css({
                "background-color": "green",
                "color": "#fff"
            });
        } else {
            $(element).css({
                "background-color": "#eee",
                "color": "#555"
            });
        }
    }







    /* NAO ALTERAR DAQUI PARA BAIXO */


    //Envia os Payments
    window.sendPayment = function (payment, pedido_geral_id, date) {
        var total_payment = $("#total-pedido-modal").val();
        $(window).ready(function () {
            $('.loader').show();
        });

        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/sendpayment",
            dataType: "json",
            data: $.param({
                "payment": payment,
                "pedido_geral_id": pedido_geral_id,
                "date": date,
            }),
            success: function (data) {
                var div = $('#payments-table');
                var total = 0;
                div.empty();
                $.each(data, function (index, element) {
                    var payment = element.payment;
                    var valor = payment.toLocaleString('de-DE', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    var form = $("<tr></tr>");
                    var date = element.date;

                    console.log("ELEMENTO ", element);
                    form.append('<td width="96%">' + dateFormat(date) + ' - ' + valor + '€</td>');
                    form.append('<td><span class="remove-payment" data-id-payment="' + element.id + '"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span></td>');
                    $("#payments-table").append(form);
                    total += parseFloat(element.payment);
                });

                $('.payments-box').scrollTop($('.payments-box')[0].scrollHeight);
                $(window).ready(function () {
                    $('.loader').hide();
                });

                $(".remove-payment").click(function () {
                    var id = $(this).closest('span').attr('data-id-payment');
                    var element = $(this).closest('tr');
                    $.ajax({
                        type: 'POST',
                        url: assetBaseUrl + "profile/removepayment",
                        dataType: "json",
                        data: $.param({
                            "id": id
                        }),
                        success: function (data) {
                            element.remove();
                            var valor = data.valor;
                            $('.payments_total_modal').text(valor.toFixed(2));
                            //Atualiza o total pago no pedido geral
                            var total_paid = $('.total-paid-modal').val();
                            $(total_paid).val(valor.toFixed(2));
                            //Verifica o quanto já foi pago com base no total do pedido
                            totalPaymentVerify(total_payment, valor.toFixed(2), $(total_paid));
                            alert('Apagado com sucesso!');
                        },
                    });
                });

                $('.payments_total_modal').text(total.toFixed(2));
                //Atualiza o total pago no pedido geral
                var total_paid = $('.total-paid-modal').val();
                $(total_paid).val(total.toFixed(2));
                totalPaymentVerify(total_payment, total.toFixed(2), $(total_paid));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    }

    $('.sendpayment_button').click(function () {
        var payment = $(this).closest('section').find('#payment_value').val();
        var pedido_geral_id = $(this).closest('section').find('#payment_pedido_geral_id').val();
        var date = $(this).closest('section').find('#payment_date').val();
        // console.log("Valor: " + payment, "Pedido ID: " + pedido_geral_id, "Data: " + date);
        sendPayment(payment, pedido_geral_id, date);
    });

    window.dateFormat = function (date) {
        var d = new Date(date);
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        var date = day + "/" + month + "/" + year;

        return date;
    };

    $('.payments-modal-btn').click(function () {
        var agencyleadname = $(this).attr('data-leadname');
        var agencyname = $(this).attr('data-agency');
        var id = $(this).attr('data-id');
        var total_pedido_paid = $(this).closest('div').find('input').attr('id');
        var total_pedido = $(this).closest('div').find('#total_pedido').text();

        $('#payments-agency-modal').text(agencyleadname);
        $('#payments-leadname-modal').text(agencyname);
        $('#payment_pedido_geral_id').val(id);
        $('.total-paid-modal').val("#" + total_pedido_paid);
        $('.total-pedido-modal').val(total_pedido);
        var total_payment = $("#total-pedido-modal").val();
        var getPayments = 1;

        $(window).ready(function () {
            $('.loader').show();
        });

        $.ajax({
            type: 'POST',
            url: assetBaseUrl + "profile/sendpayment",
            dataType: "json",
            data: $.param({
                "pedido_geral_id": id,
                "getPayments": getPayments,
            }),
            success: function (data) {
                var div = $('#payments-table');
                var total = 0;
                div.empty();
                $.each(data, function (index, element) {
                    var payment = element.payment;
                    var valor = payment.toLocaleString('de-DE', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    var form = $("<tr></tr>");
                    var date = element.date;
                    form.append('<td width="96%">' + dateFormat(date) + ' - ' + valor + '€</td>');
                    form.append('<td><span class="remove-payment" data-id-payment="' + element.id + '"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span></td>');
                    $("#payments-table").append(form);
                    total += parseFloat(element.payment);
                });

                $(window).ready(function () {
                    $('.loader').hide();
                });

                $(".remove-payment").click(function () {
                    var id = $(this).closest('span').attr('data-id-payment');
                    var element = $(this).closest('tr');
                    $.ajax({
                        type: 'POST',
                        url: assetBaseUrl + "profile/removepayment",
                        dataType: "json",
                        data: $.param({
                            "id": id
                        }),
                        success: function (data) {
                            element.remove();
                            var valor = data.valor;
                            $('.payments_total_modal').text(valor.toFixed(2));
                            //Atualiza o total pago no pedido geral
                            var total_paid = $('.total-paid-modal').val();
                            $(total_paid).val(valor.toFixed(2));

                            totalPaymentVerify(total_payment, valor.toFixed(2), $(total_paid));
                            alert('Apagado com sucesso!');
                        },
                    });
                });

                $('.payments_total_modal').text(total.toFixed(2));
                var total_paid = $('.total-paid-modal').val();
                $(total_paid).val(total.toFixed(2));
                totalPaymentVerify(total_payment, total.toFixed(2), $(total_paid));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON;
                var errorsHtml = '';
                $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $('#contactediterror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
            },
        });
    });

    window.removeTR = function (row) {
        var key_max_value = row.closest('form').find('input[name^="key_max_"]').val();
        // console.log(key_max_value);
        parseInt(key_max_value);
        key_max_value--;
        row.closest('form').find('input[name^="key_max_"]').val(key_max_value);
        row.closest('tr').remove();
    }

    var room_incr = 101;
    //Adiciona linha de quartos
    $(".add-quarto").on('click', function () {
        var form = $("<tr id='new_room" + room_incr + "'></tr>");
        var key = $(this).attr("data-key");
        var key1 = $(this).attr("data-key1");
        //Remove
        console.log(key, key1, room_incr);
        form.append("<td><span class='remove-quarto'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Produto ID
        // form.append("<input type='hidden' id='produto_id_" + i + "' name='produto_id_" + i + "'>");
        //Quarto ID
        form.append("<input type='hidden' value='0' name='quarto_id" + key + "_" + key1 + "_" + room_incr + "'>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + room_incr + "'>");
        //Room Type
        form.append("<td><input type='text' class='form-control w3-block' name='tipologia" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Room Names Icon
        form.append("<td><i class='fa fa-users room-pax-icon' data-toggle='modal' data-target='#room-pax-names' aria-hidden='true'></i></td>");
        //Amount
        form.append("<td><input type='number' class='form-control w3-block' id='quantidade" + key + "_" + key1 + "_" + room_incr + "' name='quantidade" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")'></td>");


        //Checkin
        form.append("<td><div class='form-group'><div class='input-group date datetimepicker" + key + "_" + key1 + "_" + room_incr + "' id='datetimepicker12' style='position: relative;'><div style='position: absolute;' id='checkin" + key + "_" + key1 + "_" + room_incr + "'></div><input value='' type='text' name='init" + key + "_" + key1 + "_" + room_incr + "' id='init" + key + "_" + key1 + "_" + room_incr + "' class='roomCheckin th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");

        //Checkout
        form.append("<td><div class='form-group' style='width: 140px;' ><div class='input-group date datetimepickers" + key + "_" + key1 + "_" + room_incr + "' id='datetimepicker13' style='position: relative;'><div style='position: absolute;' id='checkout" + key + "_" + key1 + "_" + room_incr + "'></div><input value='' type='text' name='checkout" + key + "_" + key1 + "_" + room_incr + "' id='find" + key + "_" + key1 + "_" + room_incr + "' class='th-price form-control ats-border-color' onBlur='validaDatas(this)' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");



        //Nights
        form.append("<td id='dias" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //People Number
        form.append("<td><input type='number' id='pessoas" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block people_number' name='pessoas" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Boards
        form.append("<td><select type='text' id='board" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='board" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")'></select></td>");
        //Room rate per night
        form.append("<td><input type='number' id='real" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='real" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")'></td>");
        //Special Offer
        form.append("<td><input type='text' id='oferta" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='oferta" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Special Offer Value
        form.append("<td><input type='number' id='desconto" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='desconto" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")''></td>");
        //Price
        form.append("<td id='preco" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Total
        form.append("<td id='totaliza" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Remark
        /* form.append("<td><input type='text' id='remark" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='remark" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")'></td>"); */
        //ATSRate
        form.append("<td><input type='number' id='atsRate" + key + "_" + key1 + "_" + room_incr + "' class='form-control w3-block' name='atsRate" + key + "_" + key1 + "_" + room_incr + "' onchange='soma(" + key + "," + key1 + "," + room_incr + ")'></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalRate" + key + "_" + key1 + "_" + room_incr + "'></td>");
        //Total Profit
        form.append("<td id='atsProfit" + key + "_" + key1 + "_" + room_incr + "'></td>");

        //Apply append new ROW
        $(this).closest("form").find(".rooms-table").after().append(form);
        //Add board Options to the append select
        var boardOptions = $($("*[data-boardID='people-number-select']").get(0)).children('option').clone().appendTo('#board' + key + "_" + key1 + "_" + room_incr + '');

        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + room_incr + '').val(pedido_geral);

        var produto_id = $(this).closest('form').find('.produto_id').val();
        $('#produto_id_' + room_incr + '').val(produto_id);


        roomDateCalc(key, key1, room_incr); /*  EXECUTA AS OPERADOCOES COM AS DATAS AQUI */

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);
        room_incr++;
    });

    //Adiciona linha de Extras dos Quartos
    var room_extra_incr = 101;
    $(".add-quarto-extras").on('click', function (e) {
        var key = $(this).attr('data-key');
        var key1 = $(this).attr('data-key1');
        e.preventDefault();
        var form = $("<tr></tr>");
        //Remove
        form.append("<td><span class='remove-quarto'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Extra
        form.append("<td><select type='text' class='form-control w3-block' id='extra_name" + key + "_" + key1 + "_" + room_extra_incr + "' name='extra_name" + key + "_" + key1 + "_" + room_extra_incr + "'></select></td>");
        //Extra ID
        form.append("<input type='hidden' value='0' name='extra_id" + key + "_" + key1 + "_" + room_extra_incr + "'>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + key1 + "'>");
        //Amount
        form.append("<td><input type='number' class='form-control w3-block' id='room_extra_amount" + key + "_" + key1 + "_" + room_extra_incr + "' name='room_extra_amount" + key + "_" + key1 + "_" + room_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + room_extra_incr + ",'Acc')></td>");
        //Rate
        form.append("<td><input type='number' class='form-control w3-block' id='extraRate" + key + "_" + key1 + "_" + room_extra_incr + "' name='extraRate" + key + "_" + key1 + "_" + room_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + room_extra_incr + ",'Acc')></td>");
        //Tipo
        form.append("<input type='hidden' name='tipo" + key + "_" + key1 + "_" + room_extra_incr + "' value='alojamento'>");
        //--
        form.append("<td></td>");
        //Total
        form.append("<td><label id='extraTotal" + key + "_" + key1 + "_" + room_extra_incr + "'>0.00</label></td>");
        //--
        form.append("<td><input id='atsExtraRate" + key + "_" + key1 + "_" + room_extra_incr + "' type='number' class='form-control w3-block' name='atsExtraRate" + key + "_" + key1 + "_" + room_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + room_extra_incr + ",'Acc')></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalExtraRate" + key + "_" + key1 + "_" + room_extra_incr + "'></td>");
        //Total Profit
        form.append("<td><span id='atsExtraProfit" + key + "_" + key1 + "_" + room_extra_incr + "'></span><span  onclick='enviaExtra(0," + key + "," + key1 + "," + room_extra_incr + ", 1)' class='buttonn" + key + " hidden form-control'></span></td>");
        //Apply append new ROW
        $(this).closest('form').find(".rooms-extras-table").append(form);
        //Add board Options to the append select
        var cloneOptions = $(this).closest("form").find("[data-extraid='extra-select'] > option").clone().appendTo("#extra_name" + key + "_" + key1 + "_" + room_extra_incr + "");

        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + key1 + '').val(pedido_geral);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);

        room_extra_incr++;
    });

    $(".add-quarto-extras, .add-quarto").click(function () {
        $('.remove-quarto, .remove-extra').click(function () {
            $(this).closest('tr').remove();
        });
    });

    /********************************* GOLF ******************************************/
    var golf_incr = 201;
    //Adiciona linha de golfs
    $(".add-golf").on('click', function () {
        var form = $("<tr></tr>");
        var key = $(this).attr("data-key");
        var key1 = $(this).attr("data-key1");
        //Remove
        form.append("<td><span class='remove-golf'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Golfe ID
        form.append("<input type='hidden' value='0' name='golfe_id" + key + "_" + key1 + "_" + golf_incr + "'>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + golf_incr + "'>");


        //Data
        form.append("<td><div class='form-group'><div class='input-group datetimepicker' style='position: relative;'><div style='position: absolute;' id='golf_data" + key + "_" + key1 + "_" + golf_incr + "'></div><input value='' type='text' name='golfe_data" + key + "_" + key1 + "_" + golf_incr + "' id='golf_data" + key + "_" + key1 + "_" + golf_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");
        //Hora
        form.append("<td><div class='form-group' style='width: 140px;'><div class='input-group timepicker' style='position: relative;'><div style='position: absolute;' id='golf_hora" + key + "_" + key1 + "_" + golf_incr + "'></div><input value='' type='text' name='golfe_hora" + key + "_" + key1 + "_" + golf_incr + "' id='golf_hora" + key + "_" + key1 + "_" + golf_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");


        //Course
        form.append("<td><input type='text' class='form-control w3-block' id='golfe_course" + key + "_" + key1 + "_" + golf_incr + "' name='golfe_course" + key + "_" + key1 + "_" + golf_incr + "'></td>");
        //People
        form.append("<td><input type='number' class='form-control w3-block' id='golfe_people" + key + "_" + key1 + "_" + golf_incr + "' name='golfe_people" + key + "_" + key1 + "_" + golf_incr + "' onchange='somaGolf(" + key + "," + key1 + "," + golf_incr + ")'></td>");
        //Players Free
        form.append("<td><input type='number' onchange='somaGolf(" + key + "," + key1 + "," + golf_incr + ")' id='playersFree" + key + "_" + key1 + "_" + golf_incr + "' class='form-control w3-block' name='playersFree" + key + "_" + key1 + "_" + golf_incr + "'></td>");
        //Rate
        form.append("<td><input type='number' onchange='somaGolf(" + key + "," + key1 + "," + golf_incr + ")' id='realGolf" + key + "_" + key1 + "_" + golf_incr + "' class='form-control w3-block' name='realGolf" + key + "_" + key1 + "_" + golf_incr + "'></td>");
        //Total
        form.append("<td id='totalizaGolf" + key + "_" + key1 + "_" + golf_incr + "'>0.00</td>");
        //Remarks
        // form.append("<td><input type='text' class='form-control w3-block' name='golf_remark" + key + "_" + key1 + "_" + golf_incr + "'></td>");
        //ATSRate
        form.append("<td><input type='number' id='atsRateGolf" + key + "_" + key1 + "_" + golf_incr + "' class='form-control w3-block' name='atsRateGolf" + key + "_" + key1 + "_" + golf_incr + "' onchange='somaGolf(" + key + "," + key1 + "," + golf_incr + ")'></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalRateGolf" + key + "_" + key1 + "_" + golf_incr + "'>0.00</td>");
        //Total Profit
        form.append("<td id='atsProfitGolf" + key + "_" + key1 + "_" + golf_incr + "'>0.00</td>");

        //Apply append new ROW
        $(this).closest('form').find(".golf-table").after().append(form);
        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + golf_incr + '').val(pedido_geral);

        initDateTimePicker();
        somaGolf(key, key1, golf_incr);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);
        golf_incr++;
    });

    //Adiciona linha de Extras do Golf
    var golf_extra_incr = 201;
    $(".add-golf-extras").on('click', function (e) {
        e.preventDefault();
        var key = $(this).attr('data-key');
        var key1 = $(this).attr('data-key1');
        var form = $("<tr></tr>");
        //Remove
        form.append("<td><span class='remove-golf'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Extra ID
        form.append("<input type='hidden' value='0' name='extra_id" + key + "_" + key1 + "_" + golf_extra_incr + "'>");
        //Extra
        form.append("<td><select type='text' class='form-control w3-block' id='extra_name" + key + "_" + key1 + "_" + golf_extra_incr + "' name='extra_name" + key + "_" + key1 + "_" + golf_extra_incr + "'></select></td>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + key1 + "'>");
        //Amount
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='golf_extra_amount" + key + "_" + key1 + "_" + golf_extra_incr + "' name='golf_extra_amount" + key + "_" + key1 + "_" + golf_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + golf_extra_incr + ",'Golf')></td>");
        //Rate
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='extraRate" + key + "_" + key1 + "_" + golf_extra_incr + "' name='extraRate" + key + "_" + key1 + "_" + golf_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + golf_extra_incr + ",'Golf')></td>");
        //Tipo
        form.append("<input type='hidden' name='tipo" + key + "_" + key1 + "_" + golf_extra_incr + "' value='golf'>");
        //--
        form.append("<td></td>");
        //Total
        form.append("<td><label id='extraTotal" + key + "_" + key1 + "_" + golf_extra_incr + "'>0.00</label></td>");
        //--
        form.append("<td><input id='atsExtraRate" + key + "_" + key1 + "_" + golf_extra_incr + "' type='number' value='0' class='form-control w3-block' name='atsExtraRate" + key + "_" + key1 + "_" + golf_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + golf_extra_incr + ",'Golf')></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalExtraRate" + key + "_" + key1 + "_" + golf_extra_incr + "'>0</td>");
        //Total Profit
        form.append("<td><span id='atsExtraProfit" + key + "_" + key1 + "_" + golf_extra_incr + "'>0</span><span  onclick='enviaExtra(0," + key + "," + key1 + "," + golf_extra_incr + ", 2)' class='buttonn" + key + " hidden form-control'></span></td>");

        //Apply append new ROW
        $(this).closest('form').find(".golf-extras-table").append(form);
        //Add board Options to the append select
        var cloneOptions = $(this).closest("form").find("select[data-extraID='extra-select'] > option").clone().appendTo("#extra_name" + key + "_" + key1 + "_" + golf_extra_incr + "");

        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + key1 + '').val(pedido_geral);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);

        golf_extra_incr++;
    });

    $(".add-golf-extras, .add-golf").click(function () {
        $('.remove-golf, .remove-golf-extra').click(function () {
            $(this).closest('tr').remove();
        });
    });

    /********************************* Transfers ******************************************/
    var transfer_incr = 301;
    //Adiciona linha de transfers
    $(".add-transfer").on('click', function () {
        var form = $("<tr></tr>");
        var key = $(this).attr("data-key");
        var key1 = $(this).attr("data-key1");
        //Remove
        form.append("<td><span class='remove-transfer'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Golfe ID
        form.append("<input type='hidden' value='0' name='transfer_id" + key + "_" + key1 + "_" + transfer_incr + "'>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + transfer_incr + "' name='pedido_produto_id" + transfer_incr + "'>");


        //Data
        form.append("<td><div class='form-group' ><div class='input-group datetimepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' name='data" + key + "_" + key1 + "_" + transfer_incr + "' id='data" + key + "_" + key1 + "_" + transfer_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");

        //Hora
        form.append("<td><div class='form-group' style='width: 140px;'><div class='input-group timepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' name='hora" + key + "_" + key1 + "_" + transfer_incr + "' id='hora" + key + "_" + key1 + "_" + transfer_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");




        //Adults
        form.append("<td><input type='number' class='form-control w3-block' id='adult" + key + "_" + key1 + "_" + transfer_incr + "' name='adult" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Childres
        form.append("<td><input type='number' class='form-control w3-block' id='children" + key + "_" + key1 + "_" + transfer_incr + "' name='children" + key + "_" + key1 + "_" + transfer_incr + "' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")'></td>");
        //Babies
        form.append("<td><input type='number' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='babie" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='babie" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Flight Nº
        form.append("<td><input type='text' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='flight" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='flight" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Pick-up
        form.append("<td><input type='text' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='pickup" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='pickup" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Drop Off
        form.append("<td><input type='text' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='dropoff" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='dropoff" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Company
        form.append("<td><input type='text' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='company" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='company" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Total
        form.append("<td><input style='width: 90%;' type='number' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")' id='realTransfer" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='realTransfer" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //Remarks
        // form.append("<td><input type='text' class='form-control w3-block' name='remark" + key + "_" + key1 + "_" + transfer_incr + "'></td>");
        //ATSRate
        form.append("<td><input style='width: 90%;' type='number' id='atsRateTransfer" + key + "_" + key1 + "_" + transfer_incr + "' class='form-control w3-block' name='atsRateTransfer" + key + "_" + key1 + "_" + transfer_incr + "' onchange='somaTransfer(" + key + "," + key1 + "," + transfer_incr + ")'></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalRateTransfer" + key + "_" + key1 + "_" + transfer_incr + "'>0.00</td>");
        //Total Profit
        form.append("<td id='atsProfitTransfer" + key + "_" + key1 + "_" + transfer_incr + "'>0.00</td>");

        //Apply append new ROW
        $(this).closest('form').find(".transfer-table").after().append(form);
        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + transfer_incr + '').val(pedido_geral);

        initDateTimePicker();
        somaTransfer(key, key1, transfer_incr);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);
        transfer_incr++;
    });

    //Adiciona linha de Extras do Transfers
    var transfer_extra_incr = 301;
    $(".add-transfer-extras").on('click', function (e) {
        e.preventDefault();
        var key = $(this).attr('data-key');
        var key1 = $(this).attr('data-key1');
        var form = $("<tr></tr>");
        //Remove
        form.append("<td><span class='remove-transfer'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Extra ID
        form.append("<input type='hidden' value='0' name='extra_id" + key + "_" + key1 + "_" + transfer_extra_incr + "'>");
        //Extra
        form.append("<td><select type='text' class='form-control w3-block' id='extra_name" + key + "_" + key1 + "_" + transfer_extra_incr + "' name='extra_name" + key + "_" + key1 + "_" + transfer_extra_incr + "'></select></td>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + key1 + "'>");
        //Amount
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='transfer_extra_amount" + key + "_" + key1 + "_" + transfer_extra_incr + "' name='transfer_extra_amount" + key + "_" + key1 + "_" + transfer_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + transfer_extra_incr + ",'Transfer')></td>");
        //Rate
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='extraRate" + key + "_" + key1 + "_" + transfer_extra_incr + "' name='extraRate" + key + "_" + key1 + "_" + transfer_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + transfer_extra_incr + ",'Transfer')></td>");
        //Tipo
        form.append("<input type='hidden' name='tipo" + key + "_" + key1 + "_" + transfer_extra_incr + "' value='transfer'>");
        //--
        form.append("<td></td>");
        //Total
        form.append("<td><label id='extraTotal" + key + "_" + key1 + "_" + transfer_extra_incr + "'>0.00</label></td>");
        //--
        form.append("<td><input id='atsExtraRate" + key + "_" + key1 + "_" + transfer_extra_incr + "' type='number' value='0' class='form-control w3-block' name='atsExtraRate" + key + "_" + key1 + "_" + transfer_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + transfer_extra_incr + ",'Transfer')></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalExtraRate" + key + "_" + key1 + "_" + transfer_extra_incr + "'>0</td>");
        //Total Profit
        form.append("<td><span id='atsExtraProfit" + key + "_" + key1 + "_" + transfer_extra_incr + "'>0</span><span  onclick='enviaExtra(0," + key + "," + key1 + "," + transfer_extra_incr + ", 3)' class='buttonn" + key + " hidden form-control'></span></td>");

        //Apply append new ROW
        $(this).closest('form').find(".transfer-extras-table").append(form);
        //Add board Options to the append select
        var cloneOptions = $(this).closest("form").find("select[data-extraID='extra-select'] > option").clone().appendTo("#extra_name" + key + "_" + key1 + "_" + transfer_extra_incr + "");

        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + key1 + '').val(pedido_geral);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);

        transfer_extra_incr++;
    });

    $(".add-transfer-extras, .add-transfer").click(function () {
        $('.remove-transfer, .remove-transfer-extra').click(function () {
            $(this).closest('tr').remove();
        });
    });


    /********************************* Cars ******************************************/
    var cars_incr = 401;
    //Adiciona linha de transfers
    $(".add-car").on('click', function () {


        var form = $("<tr></tr>");
        var key = $(this).attr("data-key");
        var key1 = $(this).attr("data-key1");
        //Remove
        form.append("<td><span class='remove-car'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Golfe ID
        form.append("<input type='hidden' value='0' name='car_id" + key + "_" + key1 + "_" + cars_incr + "'>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + cars_incr + "' name='pedido_produto_id" + cars_incr + "'>");



        form.append("<td><input type='text' name='car_pickup_name_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control'></td>");

        //Data
        form.append("<td><div class='form-group' ><div class='input-group datetimepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' name='car_pickup_date_" + key + "_" + key1 + "_" + cars_incr + "' id='car_pickup_date_" + key + "_" + key1 + "_" + cars_incr + "' data-key=" + key + " data-key1=" + key1 + " data-car=" + cars_incr + " class='th-price form-control ats-border-color' placeholder='Check-In' ><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");
        //Hora
        form.append("<td><div class='form-group' style='width: 140px;'><div class='input-group timepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' readonly name='car_pickup_hora_" + key + "_" + key1 + "_" + cars_incr + "' id='car_pickup_hora_" + key + "_" + key1 + "_" + cars_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");


        //car_pickup_flight_
        form.append("<td><input type='text' class='form-control w3-block' id='car_pickup_flight_" + key + "_" + key1 + "_" + cars_incr + "' name='car_pickup_flight_" + key + "_" + key1 + "_" + cars_incr + "'></td>");
        //Childres
        form.append("<td><input type='text' class='form-control w3-block' id='car_pickup_country_" + key + "_" + key1 + "_" + cars_incr + "' name='car_pickup_country_" + key + "_" + key1 + "_" + cars_incr + "' onchange='somaCar(" + key + "," + key1 + "," + cars_incr + ")'></td>");
        //Babies
        form.append("<td><input type='text' id='car_pickup_airport_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control w3-block' name='car_pickup_airport_" + key + "_" + key1 + "_" + cars_incr + "'></td>");
        //Flight Nº
        form.append("<td><input type='text' id='car_dropoff_name_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control w3-block' name='car_dropoff_name_" + key + "_" + key1 + "_" + cars_incr + "'></td>");

        //Data
        form.append("<td><div class='form-group' ><div class='input-group datetimepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' readonly name='car_dropoff_date_" + key + "_" + key1 + "_" + cars_incr + "' id='car_dropoff_date_" + key + "_" + key1 + "_" + cars_incr + "' data-key=" + key + " data-key1=" + key1 + " data-car=" + cars_incr + "  class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");
        //Hora
        form.append("<td><div class='form-group' style='width: 140px;'><div class='input-group timepicker' style='position: relative;'><div style='position: absolute;'></div><input value='' type='text' readonly name='car_dropoff_hora_" + key + "_" + key1 + "_" + cars_incr + "' id='car_dropoff_hora_" + key + "_" + key1 + "_" + cars_incr + "' class='th-price form-control ats-border-color' placeholder='Check-In'><span class='input-group-addon ats-border-color'><span class='w3-large ats-text-color fa fa-calendar'></span></span></div></div></td>");


        //Company
        form.append("<td><input type='text' id='car_dropoff_flight_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control w3-block' name='car_dropoff_flight_" + key + "_" + key1 + "_" + cars_incr + "'></td>");

        form.append("<td><input type='text' name='car_group_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control'></td>");

        form.append("<td><input type='text' name='car_model_" + key + "_" + key1 + "_" + cars_incr + "' class='form-control'></td>");


        //Total
        form.append("<td><input type='number' onchange='somaCar(" + key + "," + key1 + "," + cars_incr + ")' id='realCar" + key + "_" + key1 + "_" + cars_incr + "' class='form-control w3-block' name='realCar" + key + "_" + key1 + "_" + cars_incr + "'></td>");


        form.append("<td id='dias" + key + "_" + key1 + "_" + cars_incr + "'></td>");

        form.append("<td><input type='number' onchange='somaCar(" + key + "," + key1 + "," + cars_incr + ")' class='form-control w3-block' id='tax" + key + "_" + key1 + "_" + cars_incr + "' name='tax" + key + "_" + key1 + "_" + cars_incr + "'></td>");


        // //Remarks
        // form.append("<td><input type='text' class='form-control w3-block' name='remark" + key + "_" + key1 + "_" + cars_incr + "'></td>");

        form.append('<td id="totalizaCar' + key + '_' + key1 + '_' + cars_incr + '"></td>');

        //ATSRate
        form.append("<td><input type='number' id='atsRateCar" + key + "_" + key1 + "_" + cars_incr + "' class='form-control w3-block' name='atsRateCar" + key + "_" + key1 + "_" + cars_incr + "' onchange='somaCar(" + key + "," + key1 + "," + cars_incr + ")'></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalRateCar" + key + "_" + key1 + "_" + cars_incr + "'>0.00</td>");
        //Total Profit
        form.append("<td id='atsProfitCar" + key + "_" + key1 + "_" + cars_incr + "'>0.00</td>");

        //Apply append new ROW
        $(this).closest('form').find(".car-table").after().append(form);
        //Adiciona o ID do pedido produto
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + cars_incr + '').val(pedido_geral);

        initDateTimePicker();
        somaCar(key, key1, cars_incr);


        var dateInicio = "#car_pickup_date_" + key + "_" + key1 + "_" + cars_incr;
        var dateFim = "#car_dropoff_date_" + key + "_" + key1 + "_" + cars_incr;

        $(dateInicio).datetimepicker({
            format: 'DD/MM/YYYY',
            ignoreReadonly: true,
            debug: false,
            widgetPositioning: {
                horizontal: "right",
                vertical: "bottom"
            },
        }).on('dp.hide', function () {

            var inicio = $(this).val();
            var key = $(this).attr("data-key");
            var key1 = $(this).attr("data-key1");
            var cars_incr = $(this).attr("data-car");

            var fim = $("#car_dropoff_date_" + key + "_" + key1 + "_" + cars_incr).val();
            var chave = key + '_' + key1 + '_' + cars_incr;

            console.log(inicio, fim, chave);
            if (typeof (fim) != undefined && typeof (fim) != 'undefined') {
                mudadia(inicio, fim, chave);
            }

        }).on('dp.show', function (e) {
            $(".bootstrap-datetimepicker-widget").css("position", "relative");
            $(".bootstrap-datetimepicker-widget").css("z-index", "9999");
        });



        $(dateFim).datetimepicker({
            format: 'DD/MM/YYYY',
            ignoreReadonly: true,
            debug: false,
            widgetPositioning: {
                horizontal: "right",
                vertical: "bottom"
            },
        }).on('dp.hide', function () {

            var fim = $(this).val();
            var key = $(this).attr("data-key");
            var key1 = $(this).attr("data-key1");
            var cars_incr = $(this).attr("data-car");

            var inicio = $("#car_pickup_date_" + key + "_" + key1 + "_" + cars_incr).val();

            var chave = key + '_' + key1 + '_' + cars_incr;


            console.log(inicio, fim, chave);
            if (typeof (inicio) != undefined && typeof (inicio) != 'undefined') {
                mudadia(inicio, fim, chave);
            }


        }).on('dp.show', function (e) {
            $(".bootstrap-datetimepicker-widget").css("position", "relative");
            $(".bootstrap-datetimepicker-widget").css("z-index", "9999");
        });









        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);

        cars_incr++;
    });

    //Adiciona linha de Extras do Cars
    var cars_extra_incr = 401;
    $(".add-car-extras").on('click', function (e) {
        e.preventDefault();
        var key = $(this).attr('data-key');
        var key1 = $(this).attr('data-key1');
        var form = $("<tr></tr>");
        //Remove
        form.append("<td><span class='remove-car-extras'><i class='fa fa-minus-circle fa-2x' aria-hidden='true'></i></span></td>");
        //Extra ID
        form.append("<input type='hidden' value='0' name='extra_id" + key + "_" + key1 + "_" + cars_extra_incr + "'>");
        //Extra
        form.append("<td><select type='text' class='form-control w3-block' id='extra_name" + key + "_" + key1 + "_" + cars_extra_incr + "' name='extra_name" + key + "_" + key1 + "_" + cars_extra_incr + "'></select></td>");
        //Pedido Produto ID
        form.append("<input type='hidden' id='pedido_produto_id" + key + "' name='pedido_produto_id" + key1 + "'>");
        //Amount
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='car_extra_amount" + key + "_" + key1 + "_" + cars_extra_incr + "' name='car_extra_amount" + key + "_" + key1 + "_" + cars_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + cars_extra_incr + ",'Car')></td>");
        //Rate
        form.append("<td><input type='number' value='0' class='form-control w3-block' id='extraRate" + key + "_" + key1 + "_" + cars_extra_incr + "' name='extraRate" + key + "_" + key1 + "_" + cars_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + cars_extra_incr + ",'Car')></td>");
        //Tipo
        form.append("<input type='hidden' name='tipo" + key + "_" + key1 + "_" + cars_extra_incr + "' value='car'>");
        //--
        form.append("<td></td>");
        //Total
        form.append("<td><label id='extraTotal" + key + "_" + key1 + "_" + cars_extra_incr + "'>0.00</label></td>");
        //--
        form.append("<td><input id='atsExtraRate" + key + "_" + key1 + "_" + cars_extra_incr + "' type='number' value='0' class='form-control w3-block' name='atsExtraRate" + key + "_" + key1 + "_" + cars_extra_incr + "' onchange=somaExtra(''," + key + "," + key1 + "," + cars_extra_incr + ",'Car')></td>");
        //ATS Total Rate
        form.append("<td id='atsTotalExtraRate" + key + "_" + key1 + "_" + cars_extra_incr + "'>0</td>");
        //Total Profit
        form.append("<td><span id='atsExtraProfit" + key + "_" + key1 + "_" + cars_extra_incr + "'>0</span><span  onclick='enviaExtra(0," + key + "," + key1 + "," + cars_extra_incr + ", 4)' class='buttonn" + key + " hidden form-control'></span></td>");

        //Apply append new ROW
        $(this).closest('form').find(".car-extras-table").append(form);
        //Add board Options to the append select
        var cloneOptions = $(this).closest("form").find("select[data-extraID='extra-select'] > option").clone().appendTo("#extra_name" + key + "_" + key1 + "_" + cars_extra_incr + "");
        //Incremento do pedido geral
        var pedido_geral = $(this).closest('form').find('input[name="pedido_produto_id' + key1 + '"]').val();
        $('#pedido_produto_id' + key1 + '').val(pedido_geral);

        //Incremento do Key_Max
        var rows_tr = $(this).closest('tbody').find('tr');
        var count_rows = 0;
        $(rows_tr).each(function () {
            count_rows++;
        });
        var rows = count_rows - 1;
        $(this).closest('form').find('.key_max').val(rows);

        cars_extra_incr++;
    });

    $(".add-car-extras, .add-car").click(function () {
        $('.remove-car, .remove-car-extra').click(function () {
            $(this).closest('tr').remove();
        });

    });

});
/************ Add by Netto FIM ************/


$(document).ready(function () {
    $('*').filter('[title]').tooltip();
});

$(document).ajaxComplete(function () {
    $('*').filter('[title]').tooltip();
    /* aciona o tolltip sempre que uma chamada ajax for concluida */
});

// $(document).ajaxSuccess(function() {
//     $('[title!=""]').tooltip();
//     console.log("2  acionando o tooltip");
// });
