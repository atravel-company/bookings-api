var i = 0;
var id;
var pdfId;
var ExtraID;
var type;
var name;
var modal = 0;
//---------------------------------------------------------------------------------------------//

function fotoprincipal(id, prodID) {

  $('.loader').show()
  var val = { 'id': id, 'title': 'principal', 'prod': prodID };

  $.ajax({
    type: 'GET',
    url: "produtos/fotoprincipal",
    data: val,
    success: function (data) {
      caminho = window.location.href + " .reload" + prodID;

      $("#reload" + prodID).load(caminho, function () {
        $('.loader').hide()
        return false;
      });

    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}

function DeleteFoto(id, prodID) {

  $('.loader').show()
  var val = { 'id': id };

  $.ajax({
    type: 'GET',
    url: "produtos/fotodel",
    data: val,
    success: function (data) {
      caminho = window.location.href + " .reload" + prodID;

      $("#reload" + prodID).load(caminho, function () {
        $('.loader').hide()
        return false;
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });


}




function UpdateProdutoFormCheckBox(x, tipo) {
  if ($(x).is(":checked")) {


    $('input[name="' + tipo + '"]').val(1)

  }
  else {

    $('input[name="' + tipo + '"]').val(null)

  }
}


function fotomodaledit(id, fotoID) {
  document.getElementById('fotomodaledit' + id + '_' + fotoID).style.display = 'block';
}

function closefotomodaledit(id, fotoID) {
  document.getElementById('fotomodaledit' + id + '_' + fotoID).style.display = 'none';
  modal = 0;
  document.getElementById("upload_form" + id + '_' + fotoID).reset();
  document.getElementById('button' + id + '_' + fotoID).style.display = 'none';
  document.getElementById('redimbtn' + id + '_' + fotoID).style.display = 'block';
  // document.getElementById("upload_form"+id).reset();
  // document.getElementById('button'+id).style.display='none';
  // document.getElementById('redimbtn'+id).style.display='block';

  if (count == 1) {
    cropper.destroy()
  }
}

function readURLedit(input, img, id, fotoID) {
  document.getElementById('button' + id + '_' + fotoID).style.display = 'none';
  document.getElementById('redimbtn' + id + '_' + fotoID).style.display = 'block';
  if (count == 1) {
    cropper.destroy()
  }
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {

      $(img).attr('src', e.target.result);
      $(img).attr('class', 'w3-margin-top w3-margin-bottom w3-padding');
    }

    reader.readAsDataURL(input.files[0]);
  }

}

function redimedit(id, fotoID) {

  var image = document.getElementById('image' + id + '_' + fotoID);
  var button = document.getElementById('button' + id + '_' + fotoID);
  var result = document.getElementById('result' + id + '_' + fotoID);
  document.getElementById('button' + id + '_' + fotoID).style.display = 'block';
  document.getElementById('redimbtn' + id + '_' + fotoID).style.display = 'none';
  var croppable = false;
  var div = ".alert" + id + '_' + fotoID;
  cropper = new Cropper(image, {
    aspectRatio: 4 / 3,
    autoCropArea: 0.65,
    viewMode: 1,
    cropBoxResizable: false,
    cropBoxMovable: false,
    preview: div,
    dragMode: 'move',
    //guides: false,
    ready: function () {
      croppable = true;
    }
  });
  count = 1;
  button.onclick = function () {
    var croppedCanvas;
    var roundedCanvas;
    var roundedImage;
    if (!croppable) {
      return;
    }

    // Crop
    croppedCanvas = cropper.getCroppedCanvas({
      height: 450,
      width: 600,
      fillColor: '#fff',
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    // Round
    roundedCanvas = getRoundedCanvas(croppedCanvas);

    // Show
    roundedImage = document.createElement('img');
    //console.log(roundedCanvas.toDataURL())
    var im = roundedCanvas.toDataURL();
    $('.loader').show()


    var ImageURL = im;
    // Split the base64 string in data and contentType
    var block = ImageURL.split(";");
    // Get the content type
    var contentType = block[0].split(":")[1];// In this case "image/gif"
    // get the real base64 content of the file
    var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...."

    // Convert to blob
    var blob = b64toBlob(realData, contentType);

    // Create a FormData and append the file
    var fd = new FormData();
    fd.append("image", blob);
    fd.append("id", fotoID);


    var val = { 'path_image': im, 'id': fotoID };
    $.ajax({
      type: 'POST',
      url: "produtos/fotoedit",
      data: fd,
      contentType: false,
      processData: false,
      cache: false,
      dataType: "json",
      success: function (data) {
        modal = 0;
        document.getElementById("upload_form" + id + '_' + fotoID).reset();
        document.getElementById('button' + id + '_' + fotoID).style.display = 'none';
        document.getElementById('redimbtn' + id + '_' + fotoID).style.display = 'block';

        // $('#image'+id+'_'+fotoID).attr('src',assetBaseUrl+'storage/padrao.png');   
        var valor = id + i;

        caminho = window.location.href + " .reload" + id;

        $("#reload" + id).load(caminho, function () {
          $('.loader').hide()
          return false;
        });


        i++;

      },
      error: function (jqXHR, textStatus, errorThrown) {
        modal = 0;
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      },
    });


    // roundedImage.src = roundedCanvas.toDataURL()
    // result.innerHTML = '';
    // result.appendChild(roundedImage);
    cropper.destroy();
    document.getElementById('fotomodaledit' + id + '_' + fotoID).style.display = 'none';
  };

}


var count = 0;
function fotomodal(id) {

  document.getElementById("upload_form" + id).reset();
  document.getElementById('button' + id).style.display = 'none';
  document.getElementById('redimbtn' + id).style.display = 'block';

  if (count == 1) {
    cropper.destroy()
  }

  document.getElementById('fotomodal' + id).style.display = 'block';

}


function closefotomodal(id) {
  document.getElementById('fotomodal' + id).style.display = 'none';
  modal = 0;
  document.getElementById("upload_form" + id).reset();
  document.getElementById('button' + id).style.display = 'none';
  document.getElementById('redimbtn' + id).style.display = 'block';

  if (count == 1) {
    cropper.destroy()
  }
}




function getRoundedCanvas(sourceCanvas) {
  var canvas = document.createElement('canvas');
  var width = sourceCanvas.width;
  var height = sourceCanvas.height;

  canvas.width = width;
  canvas.height = height;
  var context = canvas.getContext('2d');



  context.imageSmoothingEnabled = true;
  context.drawImage(sourceCanvas, 0, 0, width, height);
  context.globalCompositeOperation = 'destination-in';
  context.beginPath();
  //context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
  context.fill();

  return canvas;
}

function readURL(input, img, id) {
  document.getElementById('button' + id).style.display = 'none';
  document.getElementById('redimbtn' + id).style.display = 'block';
  if (count == 1) {
    cropper.destroy()
  }
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {

      $(img).attr('src', e.target.result);
      $(img).attr('class', 'w3-margin-top w3-margin-bottom w3-padding');
    }

    reader.readAsDataURL(input.files[0]);
  }

}

function b64toBlob(b64Data, contentType, sliceSize) {
  contentType = contentType || '';
  sliceSize = sliceSize || 512;

  var byteCharacters = atob(b64Data);
  var byteArrays = [];

  for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    var slice = byteCharacters.slice(offset, offset + sliceSize);

    var byteNumbers = new Array(slice.length);
    for (var i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    var byteArray = new Uint8Array(byteNumbers);

    byteArrays.push(byteArray);
  }

  var blob = new Blob(byteArrays, { type: contentType });
  return blob;
}

function redim(id) {

  var image = document.getElementById('image' + id);
  var button = document.getElementById('button' + id);
  var result = document.getElementById('result' + id);
  document.getElementById('button' + id).style.display = 'block';
  document.getElementById('redimbtn' + id).style.display = 'none';
  var croppable = false;
  var div = ".alert" + id;
  cropper = new Cropper(image, {
    aspectRatio: 4 / 3,
    autoCropArea: 0.65,
    viewMode: 1,
    cropBoxResizable: false,
    cropBoxMovable: false,
    preview: div,
    dragMode: 'move',
    //guides: false,
    ready: function () {
      croppable = true;
    }
  });
  count = 1;
  button.onclick = function () {
    var croppedCanvas;
    var roundedCanvas;
    var roundedImage;
    if (!croppable) {
      return;
    }

    // Crop
    croppedCanvas = cropper.getCroppedCanvas({
      height: 450,
      width: 600,
      fillColor: '#fff',
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    // Round
    roundedCanvas = getRoundedCanvas(croppedCanvas);

    // Show
    roundedImage = document.createElement('img');
    //console.log(roundedCanvas.toDataURL())
    var im = roundedCanvas.toDataURL();
    $('.loader').show()



    var ImageURL = im;
    // Split the base64 string in data and contentType
    var block = ImageURL.split(";");
    // Get the content type
    var contentType = block[0].split(":")[1];// In this case "image/gif"
    // get the real base64 content of the file
    var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...."

    // Convert to blob
    var blob = b64toBlob(realData, contentType);

    // Create a FormData and append the file
    var fd = new FormData();
    fd.append("image", blob);
    fd.append("id", id);


    var val = { 'path_image': im, 'id': id };
    $.ajax({
      type: 'POST',
      url: "produtos/foto",
      data: fd,
      contentType: false,
      processData: false,
      cache: false,
      dataType: "json",
      success: function (data) {

        document.getElementById("upload_form" + id).reset();
        document.getElementById('button' + id).style.display = 'none';
        document.getElementById('redimbtn' + id).style.display = 'block';

        $('#image' + id).attr('src', assetBaseUrl + 'storage/padrao.png');
        var valor = id + i;
        caminho = window.location.href + " .reload" + id;

        $("#reload" + id).load(caminho, function () {
          $('.loader').hide()
          return false;
        });
        i++;
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      },
    });


    // roundedImage.src = roundedCanvas.toDataURL()
    // result.innerHTML = '';
    // result.appendChild(roundedImage);
    cropper.destroy();
    document.getElementById('fotomodal' + id).style.display = 'none';
  };

}




function create(id2, extraId2) {
  ExtraID = extraId2;
  id = id2;
  modal = 'createmodal';
  document.getElementById('createmodal').style.display = 'block';

}

//---------------------------------------------------------------------------------------------//
function createextra(id2) {
  id = id2;
  // alert(window.location.href)
  modal = 'extrascreatemodal';
  document.getElementById('extrascreatemodal' + id).style.display = 'block';

}

function DeleteExtra(id2, extraId2) {
  // alert('prodID: '+id2+' , Extraid:'+extraId2)

  var val = { 'produto_id': id2, 'extra_id': extraId2 };

  $.ajax({
    type: 'GET',
    url: "produtos/extradel",
    data: val,
    success: function (data) {
      modal = 0;
      $("#reloadExtra" + id2).load(window.location.href + " #reloadExtra" + id2, function () {

      });

    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });


}


function ExtraModalEdit(id2, extraId2) {

  // alert('Produto_id:'+id2+' ,Extra_id:'+extraId2)
  ExtraID = extraId2;
  id = id2;
  modal = 'extraseditmodal';
  document.getElementById('extraseditmodal' + id2 + '_' + extraId2).style.display = 'block';
}

function SendExtraModalEdit(id, ExtraID) {
  var extra_id = $('#extrainput' + id + '_' + ExtraID).val();
  var formulario = $('#typeextra' + id + '_' + ExtraID).val();
  var e = document.getElementById('extrainput' + id + '_' + ExtraID);
  var valor = e.options[e.selectedIndex].text;
  // alert(extra_id)
  var val = { 'produto_id': id, 'new_extra_id': extra_id, 'extra_id': ExtraID, 'formulario': formulario };

  $.ajax({
    type: 'POST',
    url: "produtos/editextra",
    data: val,
    success: function (data) {
      modal = 0;
      console.log(data);
      console.log(data.result);
      console.log(data.result.valor);
      console.log(data.result.resultado);
      if (data.result.resultado == 'Existe') {
        $('#extracreateerror' + id + '_' + ExtraID).html('<ul class="alert alert-warning"><li>O Extra: ' + valor + ' já foi adicionado, escolha outro diferente!</li></ul>');

      }
      else {
        $('#extracreateerror' + id + '_' + ExtraID).empty();
        document.getElementById('extraseditmodal' + id + '_' + ExtraID).style.display = 'none';
        $("#reloadExtra" + id).load(window.location.href + " #reloadExtra" + id, function () {

        });
        //$('#extrasDiv'+id).append('<div class="w3-col l2 w3-border"><div class="w3-cell-row w3-center"><div class="w3-container w3-section w3-cell w3-margin-bottom">'+valor+'</div></div></div>');
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      modal = 0;
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    },
  });

}

//---------------------------------------------------------------------------------------------//

function PdfModalEdit(id2, pdfId2, type1) {

  id = id2;
  pdfId = pdfId2;
  type = type1;
  if (type1 == 'Brochures') {
    document.getElementById('pdfeditmodalBrochures' + id2 + '_' + pdfId2).style.display = 'block';
  }
  else {
    document.getElementById('pdfeditmodalCampaigns' + id2 + '_' + pdfId2).style.display = 'block';
  }


  var button = document.getElementById('button' + id2 + '_' + pdfId2);
  button.onclick = function () {
    $('.loader').show()
    var form = new FormData();

    var checked = []

    $("input[name='ch" + id + "_" + pdfId + "[]']:checked").each(function () {
      checked.push(parseInt($(this).val()));

    });

    var title = $('#title' + type + id + '_' + pdfId).val();
    //alert(image)



    form.append('id', id);
    form.append('title', title)
    form.append('type', type)
    form.append('pdfid', pdfId)
    form.append('check', checked);

    $.ajax({
      type: 'POST',
      url: "produtos/pdfedit",
      data: form,
      processData: false,
      contentType: false,
      success: function (data) {
        modal = 0;
        console.log(data.result.data)
        document.getElementById('pdfeditmodal' + type + id + '_' + pdfId).style.display = 'none';

        $('#title' + type + id + '_' + pdfId).val('');


        // var valor = id+i;
        // $('#pdfs'+type+id).append('<div class="w3-col l2 w3-border"><div class="w3-cell-row w3-center"><div class="w3-container w3-section w3-cell w3-margin-bottom"><i style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></i></div></div><div class="w3-cell-row  w3-center"><i class="btn">'+title+'</i></div></div>');
        var valor = id + i;

        if (type == 'Brochures') {
          caminho = window.location.href + " .reloadpdf" + id;

          $("#reloadpdf" + id).load(caminho, function () {
            $('.loader').hide()
            return false;
          });
        }
        else {
          caminho = window.location.href + " .reloadpdfcamp" + id;

          $("#reloadpdfcamp" + id).load(caminho, function () {
            $('.loader').hide()
            return false;
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        modal = 0;
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      },
    });
  }


}


function DeletePdf(id, pdfId2, type) {
  type = type;

  $('.loader').show()
  var val = { 'id': pdfId2 };

  $.ajax({
    type: 'GET',
    url: "produtos/pdfdel",
    data: val,
    success: function (data) {

      if (type == 'Brochures') {

        caminho = window.location.href + " .reloadpdf" + id;

        $("#reloadpdf" + id).load(caminho, function () {

          $('.loader').hide()

          return false;
        });
      }
      else {
        caminho = window.location.href + " .reloadpdfcamp" + id;

        $("#reloadpdfcamp" + id).load(caminho, function () {
          $('.loader').hide()
          return false;
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $('.loader').hide()
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });


}

//---------------------------------------------------------------------------------------------//
function createpdf(id2, type2) {
  modal = 'pdfcreatemodal';
  document.getElementById('pdfcreatemodal' + type2 + id2).style.display = 'block';
}


//------------------------------------Tratamento de PDFs-------------------------------------//

function sendpdf(id1, type1) {
  id = id1;
  type = type1;
  // alert(id)
  // alert(type)
  var button = document.getElementById('button' + type1 + id1);
  button.onclick = function () {
    $('.loader').show()
    var form = new FormData();
    var image = $('#path_pdf' + type + id)[0].files[0];
    var checked = []
    $("input[name='ch" + id + "[]']:checked").each(function () {
      checked.push(parseInt($(this).val()));
    });

    var title = $('#title' + type + id).val();
    // alert(image)
    // alert(title)
    form.append('path_image', image);
    form.append('id', id);
    form.append('title', title)
    form.append('type', type)
    form.append('check', checked);
    console.log(image);
    $.ajax({
      type: 'POST',
      url: "produtos/pdf",
      data: form,
      processData: false,
      contentType: false,
      success: function (data) {
        modal = 0;
        console.log(data)
        document.getElementById('pdfcreatemodal' + type + id).style.display = 'none';

        $('#title' + type + id).val('');
        $('#path_pdf' + type + id).val('');

        // var valor = id+i;
        // $('#pdfs'+type+id).append('<div class="w3-col l2 w3-border"><div class="w3-cell-row w3-center"><div class="w3-container w3-section w3-cell w3-margin-bottom"><i style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></i></div></div><div class="w3-cell-row  w3-center"><i class="btn">'+title+'</i></div></div>');
        var valor = id + i;

        if (type == 'Brochures') {
          caminho = window.location.href + " .reloadpdf" + id;

          $("#reloadpdf" + id).load(caminho, function () {
            $('.loader').hide()
            return false;
          });
        }
        else {
          caminho = window.location.href + " .reloadpdfcamp" + id;

          $("#reloadpdfcamp" + id).load(caminho, function () {
            $('.loader').hide()
            return false;
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $('.loader').hide()
        modal = 0;
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);

        var errors = jqXHR.responseJSON;
        var errorsHtml = '';
        $.each(errors, function (key, value) {
          errorsHtml += '<li>' + value[0] + '</li>';
        });
        if (type == 'Brochures') {
          $('#createbrochureerror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        }
        else {
          $('#createcampaignerror').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
        }

      },
    });
  }

}

//---------------------------------------------------------------------------------------------//

//------------------------------------Tratamento de Imagens-------------------------------------//

function changeImage(input, id) {
  var form = new FormData();
  var pdf = $('#path_image' + id)[0].files[0];
  form.append('path_image', pdf);
  form.append('id', id)

  $.ajax({
    type: 'POST',
    url: "produtos/foto",
    data: form,
    processData: false,
    contentType: false,
    success: function (data) {
      // alert(data)
      var valor = id + i;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        $('#imagens' + id).append(' <div class="w3-col l2"><div class="w3-cell-row w3-margin-bottom w3-center"><div class="w3-container w3-section w3-cell w3-margin-bottom"><img  id="editSupplier_img' + valor + '"></div></div></div>')
        reader.onload = function (e) {


          $('#editSupplier_img' + valor).attr('src', e.target.result);
          $('#editSupplier_img' + valor).attr('style', 'width:136px;height:76px;');
          $('#editSupplier_img' + valor).attr('class', 'w3-card-4 ');

        }
        i++;
        reader.readAsDataURL(input.files[0]);
      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    },
  });


}

//---------------------------------------------------------------------------------------------//
//--------------------------------Tratamento das Postagens-------------------------------------//

function myFunction(id, name) {

  var x = document.getElementById(name + id);
  if (x.style.display === 'none') {
    x.style.display = 'block';
  } else {
    x.style.display = 'none';
  }
}
//---------------------------------------------------------------------------------------------//
//--------------------------------Tratamento dos comentarios-----------------------------------//
$(document).ready(function () {
  $.ajaxSetup({

    headers: {

      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }
  });
  $('form').on('submit', function (e) {

    e.preventDefault();

    if (modal == 0) {

      var nme = $('#searchname').val();
      var base_url = window.location.origin;

      var adress = base_url + '/public/produtos' + '?nome=' + nme;
      var url = new URL(adress);
      var produto = url.searchParams.get("nome");
      window.location.href = adress;
    }

    if (modal == 'extrascreatemodal') {


      var extra_id = $('#extrainput' + id).val();
      var formulario = $('#typeextra' + id).val();
      var e = document.getElementById('extrainput' + id);
      var valor = e.options[e.selectedIndex].text;
      var val = { 'produto_id': id, 'extra_id': extra_id, 'formulario': formulario };

      $.ajax({
        type: 'POST',
        url: "produtos/addextra",
        data: val,
        success: function (data) {
          modal = 0;
          console.log(data);
          console.log(data.result);
          console.log(data.result.valor);
          console.log(data.result.resultado);
          if (data.result.resultado == 'Existe') {
            $('#extracreateerror' + id).html('<ul class="alert alert-warning"><li>O Extra: ' + valor + ' já foi adicionado, escolha outro diferente!</li></ul>');

          }
          else {
            $('#extracreateerror' + id).empty();
            document.getElementById('extrascreatemodal' + id).style.display = 'none';
            $("#reloadExtra" + id).load(window.location.href + " #reloadExtra" + id);
            //$('#extrasDiv'+id).append('<div class="w3-col l2 w3-border"><div class="w3-cell-row w3-center"><div class="w3-container w3-section w3-cell w3-margin-bottom">'+valor+'</div></div></div>');
          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          modal = 0;
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
        },
      });

    }



    if (modal == 'createmodal') {


      var name = $('#nameextra').val();
      var description = $('#textextra').val();

      var val = { 'name': name, 'description': description };

      $.ajax({
        type: 'POST',
        url: "extras/store",
        data: val,
        success: function (data) {

          // alert('aqui'+ExtraID)
          modal = 'extrascreatemodal';
          document.getElementById("extracreateform").reset();
          // alert(id) 
          $('#selec' + id + '_' + ExtraID).empty();
          var divload = window.location.href + ' #selec' + id + '_' + ExtraID;

          $('#selec' + id + '_' + ExtraID).load(String(divload))
          document.getElementById('createmodal').style.display = 'none';

        },
        error: function (jqXHR, textStatus, errorThrown) {
          modal = 0;
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
        },
      });

    }



  });
});
//---------------------------------------------------------------------------------------------------------


function searchProduto() {
  var nome = $('#search').val();
  // alert(nome)
  var val = { 'nome': nome };

  $.ajax({
    type: 'get',
    url: "produtos/search",
    data: val,
    success: function (data) {


    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    },
  });
}

function changeState(produto_id, estado) {
  $.ajax({
    type: 'POST',
    url: "produtos/changestate",
    dataType: "json",
    data: $.param({
      "produto_id": produto_id,
      "estado": estado
    }),
    success: function (data) {
      alert(data.success);
      location.reload();
    },
    error: function () {
      alert("Error");
    },
  });
}