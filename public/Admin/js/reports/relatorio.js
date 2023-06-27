$(document).ready(function () {

  $.fn.dataTable.moment('DD/MM/YYYY');

  geraTabela();

  $("#datepicker").datetimepicker({
    format: 'DD/MM/YYYY',
    ignoreReadonly: true,
  }).on("dp.change", function (e) {
    console.log(e.date);
    $('#datepicker2').data("DateTimePicker").minDate(e.date);
  });

  $("#datepicker2").datetimepicker({
    format: 'DD/MM/YYYY',
    ignoreReadonly: true,
  });

  $("#datepicker").val(null);
  $("#datepicker2").val(null);
  $("#datepicker").val("");
  $("#datepicker2").val("");

  $("select").select2();


  $(".reset").click(function () {
    $(this).closest('form').find("input[type=text], textarea").val("");
  });

  $('#print-prof').on('click', function () {
    const start = $("#start").val();
    const end = $("#end").val();
    const hotel = $("select[name=hotel]").val();
    const operator = $("select[name=operator]").val();
    const client = $("input[name=client]").val();

    const urlOpen = route('pedidos.v2.reports.export.pdf', {
      start: start,
      end: end,
      hotel: hotel,
      operator: operator,
      client: client,
    });

    window.open(urlOpen, "Voucher", "width=1000,height=800", "_blank");
  });
});

/**
 * gera um relatorio em excel com todos os pedidos individualmente ( nao agrupados )
 */
const gerarRelatorioExcel = (ats) => {
  const start = $("#start").val();
  const end = $("#end").val();
  const hotel = $("select[name=hotel]").val();
  const operator = $("select[name=operator]").val();
  const client = $("input[name=client]").val();

  const urlOpen = route('pedidos.v2.reports.export.excel', {
    start: start,
    end: end,
    hotel: hotel,
    operator: operator,
    client: client,
    ats: ats
  });

  window.open(urlOpen, "_self", "Relatorio excel", "width=1000,height=800");
}

const formatExcelDecimal = (num) => {
  try {
    if (isNaN(rnts) || num == null || num == "") {
      return "0.00 €";
    }
    num = parseFloat(num);
    return new Intl.NumberFormat('de-DE', {
      style: 'currency',
      currency: 'EUR',
      aximumSignificantDigits: 2
    }).format(num);
  } catch (error) {
    console.log(error);
  }
}

const toFloat = (num) => {
  num = num.toString();
  dotPos = num.indexOf('.');
  commaPos = num.indexOf(',');
  if (dotPos < 0)
    dotPos = 0;

  if (commaPos < 0)
    commaPos = 0;

  if ((dotPos > commaPos) && dotPos)
    sep = dotPos;
  else {
    if ((commaPos > dotPos) && commaPos)
      sep = commaPos;
    else
      sep = false;
  }

  if (sep == false)
    return parseFloat(num.replace(/[^\d]/g, ""));

  return parseFloat(
    num.substr(0, sep).replace(/[^\d]/g, "") + '.' +
    num.substr(sep + 1, num.length).replace(/[^0-9]/, "")
  );

}

const geraTabela = () => {
  var table = $('#reports-table');


  /* Formatting function for row details */
  function fnFormatDetails(oTable, nTr) {
    var rowsTable = oTable.fnGetData(nTr);
    let MyPedido = new Reports(rowsTable[1]);
    var json = MyPedido.ajaxToHiddenData().responseJSON[0];
    var table = MyPedido.formatTable(json, rowsTable);
    return table;
  }

  var nCloneTd = document.createElement('td');
  nCloneTd.innerHTML = '<span class="row-details row-details-close fa fa-plus-circle"></span>';

  table.find('tbody tr').each(function () {
    this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
  });

  var oTable = table.dataTable({
    dom: 'lfrti<"bottom"p>',
    buttons: [],
    format: 'dd/mm/yyyy',
    "order": [
      [2, 'asc'],
      [16, 'asc']
    ],
    pageLength: 25,
    autoWidth: true,
    resposive: false,
    language: {
      "aria": {
        "sortAscending": ": activate to sort column ascending",
        "sortDescending": ": activate to sort column descending"
      },
      "emptyTable": "Nenhum dado encontrado",
      "info": "Exibindo de _START_ à _END_ de _TOTAL_ linhas",
      "infoEmpty": "Nenhuma linha encontrada",
      "infoFiltered": "(Filtrado 1 de _MAX_ registros)",
      "lengthMenu": "Exibir _MENU_ linhas",
      "search": "Pesquisa:",
      "zeroRecords": "Nenhum dado encontrado"
    },
    footerCallback: function (row, data, start, end, display) {
      var api = this.api(), data;
      var intVal = function (i) {

        if (typeof i === 'string') {
          var number = i.replace(/[\$.]/g, '');
          number = number.replace(",", "");
          number = number.replace(" ", "");
          number *= 1;
        } else if (typeof i === 'number') {
          return i
        } else {
          return 0;
        }
        return number;
      };

      rnts = api.column(4).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      bedn = api.column(5).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);

      room = api.column(8).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      golf = api.column(9).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      trans = api.column(10).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      car = api.column(11).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      extras = api.column(12).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);
      kback = api.column(13).data().reduce(function (a, b) {
        return toFloat(a) + toFloat(b);
      }, 0);


      adr = parseFloat(room) / parseFloat(rnts);
      adr = parseFloat(adr);
      rnts = parseFloat(rnts);
      bedn = parseFloat(bedn);

      total = $("#TableValorTotalTotal").attr("data-value");
      vpaid = $("#ValorTotalPago").attr("data-value");
      unpaid = $("#TableTotalValorNaoPago").attr("data-value");

      $(api.column(3).footer()).html(isNaN(rnts) ? "0.00" : rnts.toLocaleString('de-DE'));
      $(api.column(4).footer()).html(isNaN(bedn) ? "0.00" : bedn.toLocaleString('de-DE'));
      $(api.column(5).footer()).html(isNaN(adr) ? "0.00" : adr.toLocaleString('pt-PT') + '€');
      $(api.column(6).footer()).html('-');
      $(api.column(7).footer()).html(formatExcelDecimal(room));
      $(api.column(8).footer()).html(formatExcelDecimal(golf));
      $(api.column(9).footer()).html(formatExcelDecimal(trans));
      $(api.column(10).footer()).html(formatExcelDecimal(car));
      $(api.column(11).footer()).html(formatExcelDecimal(extras));
      $(api.column(12).footer()).html(formatExcelDecimal(kback));
      $(api.column(13).footer()).html(total.toLocaleString('pt-PT') + ' €');
      $(api.column(14).footer()).html(vpaid.toLocaleString('pt-PT') + ' €');

      if (parseFloat(unpaid) > 0) {
        $(api.column(15).footer()).html('<text style="color: green">' + unpaid.toLocaleString(
          'pt-PT') + ' €</text>');
      } else if (parseFloat(unpaid) < 0) {
        $(api.column(15).footer()).html('<text style="color: red">' + unpaid.toLocaleString(
          'pt-PT') + ' €</text>');
      } else {
        $(api.column(15).footer()).html('<text style="color: black">' + unpaid.toLocaleString(
          'pt-PT') + ' €</text>');
      }

      if ($('#ats_profit').data('condition') == true) {
        room_ats = api.column(17).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        golf_ats = api.column(18).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        transfer_ats = api.column(19).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        car_ats = api.column(20).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        extras_ats = api.column(21).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        total_ats = api.column(22).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);
        // unpaid nights total
        profit_ats = api.column(23).data().reduce(function (a, b) {
          return toFloat(a) + toFloat(b);
        }, 0);

        $(api.column(16).footer()).html(formatExcelDecimal(room_ats));
        $(api.column(17).footer()).html(formatExcelDecimal(golf_ats));
        $(api.column(18).footer()).html(formatExcelDecimal(transfer_ats));
        $(api.column(19).footer()).html(formatExcelDecimal(car_ats));
        $(api.column(20).footer()).html(formatExcelDecimal(extras_ats));
        $(api.column(21).footer()).html(formatExcelDecimal(total_ats));
        $(api.column(22).footer()).html(formatExcelDecimal(profit_ats));
      }
    } /* end footer callback */
  });


  $("div.dataTables_filter label input").addClass("form-control").attr("style", "display:inline; margin-left: 10px !important; height: 35px; width: auto");

  var tableWrapper = $('#tableReports_wrapper');
  tableWrapper.find('.dataTables_length select').select2();

  table.on('click', ' tbody td .row-details', function () {
    var nTr = $(this).parents('tr')[0];
    if (oTable.fnIsOpen(nTr)) {
      $(this).addClass("row-details-close").removeClass("row-details-open");
      $(this).addClass("fa-plus-circle").removeClass("fa-minus-circle");
      oTable.fnClose(nTr);
    } else {
      $(this).addClass("row-details-open").removeClass("row-details-close");
      $(this).addClass("fa-minus-circle").removeClass("fa-plus-circle");

      oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
      $(".hiddenTable thead th").removeAttr('orderable');
      $(".hiddenTable thead th").removeAttr('searchable');
      $(".hiddenTable thead tr").removeAttr('aria-controls');
      $(".hiddenTable thead tr").removeAttr('style');

      $(".hiddenTable th").filter(function () {
        var t = $(this);
        t.text() == "Money Paid" ? t.attr("colspan", 3) : null;
        t.text() == "Money Received" ? t.attr("colspan", 10) : null;
      }).closest("th");

      if ($('#ats_profit').data('condition') == true) {
        var h = $(".hiddenTable .ats_hidden");
        jQuery(h.get(5)).html("EXTRA");
        h.get(0).remove();
        h.get(1).remove();
        h.get(2).remove();
        h.get(3).remove();
        h.get(4).remove();
      }

      $(".hiddenTable thead tr > th").removeAttr('style');
      $(".hiddenTable thead tr > th").removeAttr('tabindex');
      $(".hiddenTable thead tr > th").removeAttr('aria-controls');
      $(".hiddenTable thead tr > th").removeAttr('aria-label');
      $(".hiddenTable thead tr > th").attr('style', "width: 1px !important; white-space: nowrap !important; text-align: center; vertical-align: inherit;");
      $(".hiddenTable tbody td").attr('style', "width: 1px; white-space: nowrap; vertical-align: inherit;");
    }
  });
  $.fn.dataTable.moment('d M Y');
}

/* CLASS */
function Reports(pedidoid) {
  this.pedidogeral_id = pedidoid;
}

/**
 * gera a tabela de informacoes  ,contendo os produtos do pedido geral
 * @param {object} json
 * @param {object} rowsTable
 * @returns
 */
Reports.prototype.formatTable = function (json, rowsTable) {

  var thead = $("table#reports-table").find("thead").get(0);
  $(thead).children('tr:first-child').find("th:nth-child(7)").after(`<th rowspan="2" style="text-align: center; vertical-align: inherit; width:1px"> Suplier </th`);
  $(thead).children('tr:last-child').append('<th style="background-color:yellow; width:50px">MKP</th>');
  $(thead).children('tr:first-child').find("th:last-child").removeAttr("colspan").attr("colspan", "10");

  var html = "<table class='table hiddenTable display compact table-striped table-bordered nowrap'>";
  html += "<thead>";
  html += thead.innerHTML;
  html += "</thead>";
  html += "<tbody>";

  $(thead).children('tr:first-child').find("th:nth-child(8)").remove();
  $(thead).children('tr:last-child').children('th:last-child').remove();
  $(thead).children('tr:first-child').find("th:last-child").removeAttr("colspan").attr("colspan", "9");

  if (typeof json.pedidoprodutos == undefined || json.pedidoprodutos.length == 0) {
    /**  */
    html += "<tr>";
    html += "</tr>";
  } else {
    $.each(json.pedidoprodutos, function (indice, pedidos) {

      var rnts = 0;
      var bednight = 0;

      var relacaoP = "pedido" + pedidos.tipoproduto;
      var relacao = "valor" + pedidos.tipoproduto;
      var field = "valor_" + relacao;

      if (pedidos.pedidoquarto.length > 0) {
        $.each(pedidos.pedidoquarto, function (index, value) {
          rnts += parseInt(value.rnts);
          bednight += parseInt(value.bednight);
        });
      } else {
        $.each(pedidos[relacaoP], function (index, value) {
          rnts += parseInt(value.TotalPax);
        });
        rnts += " pax";
      }

      var extraAtsRate = 0;
      $.each(pedidos.extras, function (index, value) {
        extraAtsRate += value.ats_total_rate;
      });

      var adr = (pedidos.valorquarto != null ? pedidos.valorquarto.valor_quarto / rnts : 0);
      var valor_quarto = pedidos.valorquarto != null ? pedidos.valorquarto.valor_quarto : 0;
      var valor_golf = pedidos.valorgame != null ? pedidos.valorgame.valor_golf : 0;
      var valor_car = pedidos.valorcar != null ? pedidos.valorcar.valor_car : 0;
      var valor_transfer = pedidos.valortransfer != null ? pedidos.valortransfer.valor_transfer : 0;
      var valor_extras = pedidos[relacao] != null ? pedidos[relacao].valor_extra : 0;
      var valor_kickback = pedidos[relacao] != null ? pedidos[relacao].ValorKick : 0;
      var valorMarkup = pedidos[relacao] != null ? pedidos[relacao].ValorMarkup : 0;

      html += "<tr>";
      html += "<td> # </td>";
      html += "<td>" + moment(pedidos.FirstCheckin).format("DD/MM/YYYY") + "</td>";
      html += "<td>" + json.lead_name + "</td>";
      html += "<td>" + rnts + "</td>";
      html += "<td>" + parseFloat(bednight).toFixed(2) + "</td>";
      html += "<td>" + parseFloat(adr).toFixed(2) + "</td>";
      if (pedidos.produto != null && pedidos.produto != "" && typeof pedidos.produto != undefined) {
        html += "<td>" + pedidos.produto.nome + "</td>";
      } else {
        html += "<td>Produto Nao encontrado</td>";
      }
      html += "<td>" + json.user.name + "</td>";
      html += "<td align='right'>" + parseFloat(valor_quarto).toFixed(2) + "</td>";
      html += "<td align='right'>" + parseFloat(valor_golf).toFixed(2) + "</td>";
      html += "<td align='right'>" + parseFloat(valor_transfer).toFixed(2) + "</td>";
      html += "<td align='right'>" + parseFloat(valor_car).toFixed(2) + "</td>";
      html += "<td align='right'>" + valor_extras + "</td>";
      html += "<td align='right'>" + valor_kickback + " € </td>";
      html += "<td align='right'>" + parseFloat(pedidos.valor).toFixed(2) + "</td>";
      html += "<td align='right'>" + rowsTable[15] + "</td>";
      html += "<td align='right'>" + rowsTable[16] + "</td>";
      html += "<td style='width:50px' align='right' data-teste=true>" + valorMarkup + "</td>";

      if ($('#ats_profit').data('condition') == true) {
        html += "<td align='right'>" + parseFloat(extraAtsRate).toFixed(2) + "</td>";
        html += "<td align='right'>" + parseFloat(pedidos.profit).toFixed(2) + "</td>";
      }
      html += "</tr>";
    });
  }
  html += "</tbody>";
  html += "</table>";
  return html;
}


Reports.prototype.ajaxToHiddenData = function () {
  return $.ajax({
    type: 'post',
    dataType: 'JSON',
    async: false,
    url: route('pedidos.v2.reports.buscar'),
    data: {
      'pedidoid': this.pedidogeral_id,
      'suplier_id': $('select[name=hotel]').val()
    }
  });
}