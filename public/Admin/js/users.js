//---------------------------------------------------------------------------------------------//
//--------------------------------Tratamento das Postagens-------------------------------------//
//---------------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------------//
//------------------------------------Variaveis Globais----------------------------------------//
var IDdest;
var ids;
var modal;
var IDdestupdt;
var idsupdt;
var contactType;
var locIds;





//---------------------------------------------------------------------------------------------//
//------------------------------------------Funções--------------------------------------------//

function PrintElem(id) {


     $('#divsup'+id).printThis({
      header: "<h1>Supplier</h1>",
      importCSS: true,            // import page CSS
      importStyle: true

     });

}

//---------------------------------------------------------------------------------------------//


function search(){
  var search = $('#procura').val();


  var val = {'search':search};

      $.ajax({
        type:'GET',
        url:"users/search",
        success: function(data){
          // var armaz = new Array();
          data.result.forEach(function(dest,key) {
            str=dest['name'];
            verify=str.toLowerCase();
            character=search.toLowerCase();
            if((verify.indexOf(character) > -1)){
              if($('#divsup'+dest.id).hasClass('hidden')){
                $('#divsup'+dest.id).removeClass('hidden');
              }
            }
            if(!(verify.indexOf(character) > -1)){

              // classe=document.getElementById('divsup'+dest.id);
              $('#divsup'+dest.id).addClass('hidden');

              //armaz.push(dest);
              //$('#supp').append(dest['name']+'<br>')
              //{'id': dest['id'],'name': dest['name'],'social_denomination': dest['social_denomination'],'path_image': dest['path_image'],'fiscal_number': dest['fiscal_number'],'remarks': dest['remarks'],'web': dest['web']}

            }


      });
          // $('#supp').load('users/lista', {'list': armaz},function(variavel){
          //       $(this).append(variavel);
          //     })
        },
        error: function (jqXHR, textStatus, errorThrown) {

          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);

        },
      });
}



//---------------------------------------------------------------------------------------------//
function supplieredit(id) {

  ids=id;
  modal  = 'editSupplier';
  document.getElementById('editSupplier'+id).style.display='block';

}

//---------------------------------------------------------------------------------------------//

function closeModal(divModal,formcln,IMG,ERR,PATH){

    document.getElementById(divModal).style.display='none'
    document.getElementById(formcln).reset();
    if(PATH=='padrao'){
      $('#'+IMG).attr('src', "storage/padrao.png");
      $('#'+IMG).attr('style','');
      $('#'+IMG).attr('class','w3-margin-bottom');
    }
    else{
      $('#'+IMG).attr('src', "storage/"+PATH);
      $('#'+IMG).attr('style','width:272px;height:153px;');
      $('#'+IMG).attr('class','w3-card-4 w3-margin-bottom');
    }
    $('#'+ERR).empty();
}

//------------------------------------------Funções--------------------------------------------//

function readURL(input, img) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(img).attr('src', e.target.result);
            $(img).attr('style','width:272px;height:153px;');
            $(img).attr('class','w3-card-4 w3-margin-bottom');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

//------------------------------------------Funções--------------------------------------------//


function suppliercreate() {


  modal  = document.getElementById('supplier');

  document.getElementById('supplier').style.display='block';

}

//------------------------------------------Funções--------------------------------------------//

function bankcreate(id) {

  ids=id;
  modal  = document.getElementById('bankcreatedestiny');

  document.getElementById('bankcreatedestiny').style.display='block';

}

//------------------------------------------Funções--------------------------------------------//
function bankdel(valID,valIDdest) {
  ids=valID;
  IDdest=valIDdest;

  var val = {'id':ids,'IDdest':IDdest};

      $.ajax({
        type:'GET',
        url:"users/bankdestroy",
        data:val,
        success: function(data){

          $('#myDIVbank'+ids).empty();
          $('#myDIVbank'+ids).append('<br/>');
          $('#myDIVbank'+ids).append('<a title="Create" onclick="bankcreate('+ids+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.destination.forEach(function(dest) {
            $('#myDIVbank'+ids).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="Create Account" onclick="bankcreateaccount('+ids+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+ids+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'" title="Edit" onclick="bankeditdestination('+ids+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
          });

          for (i = 0; i < data.result.account.length; ++i) {
            data.result.account[i].forEach(function(acc) {
              $('#acc'+acc.id).empty();
              $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+ids+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
            });
          }

          $('#myDIVbank'+ids).append('</tbody></table>');
        },
        error: function (jqXHR, textStatus, errorThrown) {

          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);

        },
      });


}

//------------------------------------------Funções--------------------------------------------//
function bankaccountdel(valID,valIDdest) {
  ids=valID;

  var val = {'id':ids,'IdAccount':valIDdest};

      $.ajax({
        type:'GET',
        url:"users/bankaccountdestroy",
        data:val,
        success: function(data){

          $('#myDIVbank'+ids).empty();
          $('#myDIVbank'+ids).append('<br/>');
          $('#myDIVbank'+ids).append('<a title="Create" onclick="bankcreate('+ids+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.destination.forEach(function(dest) {
            $('#myDIVbank'+ids).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="Create Account" onclick="bankcreateaccount('+ids+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+ids+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'"title="Edit" onclick="bankeditdestination('+ids+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
          });

          for (i = 0; i < data.result.account.length; ++i) {
            data.result.account[i].forEach(function(acc) {
              $('#acc'+acc.id).empty();
              $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+ids+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
            });
          }

          $('#myDIVbank'+ids).append('</tbody></table>');
        },
        error: function (jqXHR, textStatus, errorThrown) {

          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);

        },
      });


}


//---------------------------------------------------------------------------------------------//
function bankeditdestination(valID,valIDdest) {

  IDdestupdt=valIDdest;
  idsupdt=valID;

  modal  = document.getElementById('bankeditdestination');

  document.getElementById('bankeditdestination').style.display='block';

  var val = {'IDdest':IDdestupdt};

  $.ajax({
    type:'GET',
    url:"users/bankedit",
    data:val,
    success: function(data){

      $("#bankeditdestinationform").empty();
      //document.getElementById("bankeditdestinationform").innerHTML = '<form class="w3-container"><label for="Destination:">Destination:</label><input type="text" value="'+data.result.destination.destination+'" id="destination" name="Destination" class="w3-input w3-border w3-margin-bottom"><select name="Destination" class="w3-input w3-border w3-margin-bottom"><option value="IBAN">IBAN</option><option value="SWIFT">SWIFT</option><option value="NIB">NIB</option></select><input type="submit" value="Create" class="w3-button w3-block w3-green w3-section w3-padding" ></form>';
      $("#bankeditdestinationform").append('<label for="Destination:">Destination:</label><input type="text" value="'+data.result.destination.destination+'" id="destination'+data.result.destination.id+'" name="Destination" class="w3-input w3-border w3-margin-bottom"><div id="test-list"><ul class="list" style="list-style-type: none; margin-left: -40px;" id="bankeditdestinationforminput"></ul><ul class="pagination"></ul></div><input type="submit" value="Save" class="w3-button w3-block w3-blue w3-section w3-padding" >');
      data.result.account.forEach(function(acc) {
        $("#bankeditdestinationforminput").append('<li><input type="hidden" id="idacc"  value="'+acc.id+'"><label for="Type:">Type:</label><select id="select'+acc.id+'" name="Type" class="w3-input w3-border w3-margin-bottom"><option value="IBAN">IBAN</option><option value="SWIFT">SWIFT</option><option value="NIB">NIB</option></select><label for="Number:">Number:</label><input type="text" value="'+acc.account_number+'" id="account_numbe" name="Number" class="w3-input w3-border w3-margin-bottom"></li>');
        $("#select"+acc.id).val(acc.type);
      });

      var monkeyList = new List('test-list', {
        valueNames: ['name'],
        page: 1,
        pagination: true
      });


    },
    error: function (jqXHR, textStatus, errorThrown){

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}

//---------------------------------------------------------------------------------------------//
function bankcreateaccount(val1,val2) {

  IDdest=val2;
  ids=val1;
  modal= document.getElementById('bankcreateaccount');

  document.getElementById('bankcreateaccount').style.display='block';

}

//---------------------------------------------------------------------------------------------//
function bank(id) {

  var b = document.getElementById("bank"+id);

  if(b.className== "fa fa-close w3-right w3-button"){
    b.className = "fa fa-plus w3-right w3-button";
  }
  else{
    b.className = "fa fa-close w3-right w3-button";
  }

  var x = document.getElementById('myDIVbank'+id);

  if (x.style.display === 'none') {
    x.style.display = 'block';
  }
  else{
    x.style.display = 'none';
  }



  var val = {id:id};

  $.ajax({
    type:'GET',
    url:"users/bank",
    data:val,
    success: function(data){

      $('#myDIVbank'+id).empty();
      $('#myDIVbank'+id).append('<br/>');
      $('#myDIVbank'+id).append('<a title="Create" onclick="bankcreate('+id+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

      data.result.destination.forEach(function(dest) {
        $('#myDIVbank'+id).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="Create Account" onclick="bankcreateaccount('+id+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+id+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'" title="Edit" onclick="bankeditdestination('+id+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
      });

      for (i = 0; i < data.result.account.length; ++i) {
        data.result.account[i].forEach(function(acc) {
          $('#acc'+acc.id).empty();
          $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+id+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
        });
      }

      $('#myDIVbank'+id).append('</tbody></table>');

    },
    error: function (jqXHR, textStatus, errorThrown){

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });
}
//---------------------------------------------------------------------------------------------//
function contactedit(supID,type){
  idsupdt=supID;
  contactType=type;

  $('#contactEditModalTitle').html('<h5 class="w3-center"><b>Edit '+type+'</b></h5>');
  modal  = document.getElementById('contactedit');

  document.getElementById('contactedit').style.display='block';
  var val = {'supID':supID, 'type':type};

  $.ajax({
    type:'GET',
    url:"users/contactedit",
    data:val,
    success: function(data){
      // PAREI AQUI 07/08/2017 FAZER  COM  QUE O FORM SEJA CONTRUIDO, SEGUE AS LINHAS ABAIXO
      $("#contacteditform").empty();
      $("#contacteditform").append('<div id="contactlist"><ul class="list" style="list-style-type: none; margin-left: -40px;" id="contacteditforminput"></ul><ul class="pagination"></ul></div><input type="submit" value="Save" class="w3-button w3-block w3-blue w3-section w3-padding" >');
      data.result.contact.forEach(function(contact) {
        $("#contacteditforminput").append('<li><input type="hidden" id="idcontact"  value="'+contact.id+'"><label for="Name:">Name:</label><input type="text" value="'+contact.name+'" id="editname" name="Name" class="w3-input w3-border w3-margin-bottom"><label for="Phone:">Phone:</label><input type="text" value="'+contact.telephone+'" id="editphone" name="Phone" class="w3-input w3-border w3-margin-bottom"><label for="E-mail:">E-mail:</label><input type="text" value="'+contact.email+'" id="editemail" name="E-mail" class="w3-input w3-border w3-margin-bottom"><label for="Mobile:">Mobile:</label><input type="text" value="'+contact.mobile+'" id="editmobile" name="Mobile" class="w3-input w3-border w3-margin-bottom"></li>');
      });

      var monkeyList = new List('contactlist', {
        valueNames: ['name'],
        page: 1,
        pagination: true
      });


    },
    error: function (jqXHR, textStatus, errorThrown){

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}
//---------------------------------------------------------------------------------------------//

function createcontact(id,type) {
  ids=id;
  contactType=type;
  $('#contactCreateModalTitle').html('<h5 class="w3-center"><b>Add New '+type+'</b></h5>');
  modal  = document.getElementById('createcontact');

  document.getElementById('createcontact').style.display='block';

}
//---------------------------------------------------------------------------------------------//

function destroycontact(id,supID, type){

  if(type==0){
    type='Contracts';
  }
  else if(type==1){
    type='Reservations'
  }
  else if(type==2){
    type='Accounts'
  }

  var val = {'id':id,'supID':supID, 'type':type};
  $.ajax({
    type:'GET',
    url:"users/destroycontact",
    data:val,
    success: function(data){
       $('#tablecontracts'+supID).empty();
          $('#tablereservations'+supID).empty();
          $('#tableaccounts'+supID).empty();
      //$('#myDIVcontact'+id).empty();

      //$('#myDIVcontact'+id).append('<button  onclick="bankcreate('+id+')" class="fa fa-plus w3-right" style="color:teal" aria-hidden="true"></button>');
          $('#tablecontracts'+supID).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tablereservations'+supID).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tableaccounts'+supID).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          data.result.contact.forEach(function(dest) {
            if(dest.type=='Contracts'){
              $('#tablecontracts'+supID).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<a title="Delete Contract" onclick="destroycontact('+dest.id+','+supID+','+0+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a>'+'</td>'+'</tr>');
            }
            else if(dest.type=='Reservations'){
              $('#tablereservations'+supID).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Reservations" onclick="destroycontact('+dest.id+','+supID+','+1+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
            else if(dest.type=='Accounts'){
              $('#tableaccounts'+supID).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Accounts" onclick="destroycontact('+dest.id+','+supID+','+2+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
          });
    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}
//---------------------------------------------------------------------------------------------//
function contato(id){

  var c = document.getElementById("contact"+id);

  if(c.className== "fa fa-close w3-right w3-button"){
    c.className = "fa fa-plus w3-right w3-button";
  }
  else{
    c.className = "fa fa-close w3-right w3-button";
  }

  var x = document.getElementById('myDIVcontact'+id);

  if (x.style.display === 'none') {
    x.style.display = 'block';
  }
  else{
    x.style.display = 'none';
  }

  var val = {id:id};

  $.ajax({
    type:'GET',
    url:"users/contact",
    data:val,
    success: function(data){
      $('#tablecontracts'+id).empty();
      $('#tablereservations'+id).empty();
      $('#tableaccounts'+id).empty();
      //$('#myDIVcontact'+id).empty();

      //$('#myDIVcontact'+id).append('<button  onclick="bankcreate('+id+')" class="fa fa-plus w3-right" style="color:teal" aria-hidden="true"></button>');


      $('#tablecontracts'+id).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
      $('#tablereservations'+id).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
      $('#tableaccounts'+id).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
      data.result.contact.forEach(function(dest) {
        if(dest.type=='Contracts'){
          $('#tablecontracts'+id).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<a title="Delete Contract" onclick="destroycontact('+dest.id+','+id+','+0+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a>'+'</td>'+'</tr>');
        }
        else if(dest.type=='Reservations'){
          $('#tablereservations'+id).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Reservations" onclick="destroycontact('+dest.id+','+id+','+1+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

        }
        else if(dest.type=='Accounts'){
          $('#tableaccounts'+id).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Accounts" onclick="destroycontact('+dest.id+','+id+','+2+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

        }
      });

    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });
}

//---------------------------------------------------------------------------------------------//
function loca(id){

  var l = document.getElementById("location"+id);

  if(l.className== "fa fa-close w3-right w3-button"){
    l.className = "fa fa-plus w3-right w3-button";
  }
  else{
    l.className = "fa fa-close w3-right w3-button";
  }

  var x = document.getElementById('myDIVlocation'+id);

  if (x.style.display === 'none') {
    x.style.display = 'block';
  }
  else {
    x.style.display = 'none';
  }

  var val = {id:id};

  $.ajax({
    type:'GET',
    url:"users/location",
    data:val,
    success: function(data){

      $('#myDIVlocation'+id).empty();
      $('#myDIVlocation'+id).append('<br/>');
      $('#myDIVlocation'+id).append('<a title="Create" onclick="localcreate('+id+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

      data.result.location.forEach(function(dest) {
        $('#loca'+dest.id).empty();
        $('#myDIVlocation'+id).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.location+'<a title="Delete" onclick="destroylocal('+dest.id+','+id+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a><a title="Edit" onclick="editlocal('+dest.id+','+id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><thead><tr><th class="w3-text-teal">Adress</th><th class="w3-text-teal">Zip Code</th><th class="w3-text-teal">City</th><th class="w3-text-teal">Country</th></tr></thead><tbody  id="loca'+dest.id+'">'+'<tr ><td>'+dest.address+'</td><td>'+dest.zip_code+'</td>'+'<td>'+dest.city+'</td>'+'<td>'+dest.country+'</td>'+'</tr>'+'</tbody></table>');
      });

    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });
}


function role(id){

  var l = document.getElementById("role"+id);

  if(l.className== "fa fa-close w3-right w3-button"){
    l.className = "fa fa-plus w3-right w3-button";
  }
  else{
    l.className = "fa fa-close w3-right w3-button";
  }

  var x = document.getElementById('myDIVrole'+id);

  if (x.style.display === 'none') {
    x.style.display = 'block';
  }
  else {
    x.style.display = 'none';
  }



}

//---------------------------------------------------------------------------------------------//

function destroylocal(locId,id){


  var val = {'locId':locId,'id':id};
  $.ajax({
    type:'GET',
    url:"users/destroylocal",
    data:val,
    success: function(data){
      $('#myDIVlocation'+id).empty();
      $('#myDIVlocation'+id).append('<br/>');
      $('#myDIVlocation'+id).append('<a title="Create" onclick="localcreate('+id+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

      data.result.location.forEach(function(dest) {
        $('#loca'+dest.id).empty();
        $('#myDIVlocation'+id).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.location+'<a title="Delete" onclick="destroylocal('+dest.id+','+id+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a><a title="Edit" onclick="editlocal('+dest.id+','+id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><thead><tr><th class="w3-text-teal">Adress</th><th class="w3-text-teal">Zip Code</th><th class="w3-text-teal">City</th><th class="w3-text-teal">Country</th></tr></thead><tbody id="loca'+dest.id+'">'+'<tr><td>'+dest.address+'</td><td>'+dest.zip_code+'</td>'+'<td>'+dest.city+'</td>'+'<td>'+dest.country+'</td>'+'</tr>'+'</tbody></table>');
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}

//---------------------------------------------------------------------------------------------//

function localcreate(id) {
  ids=id;
  modal  = document.getElementById('createlocal');

  document.getElementById('createlocal').style.display='block';

}

function editlocal(locId,id){
  locIds=locId;
  idsupdt=id;


  modal  = document.getElementById('localtedit');

  document.getElementById('localtedit').style.display='block';
  var val = {'id':locId};

  $.ajax({
    type:'GET',
    url:"users/localtedit",
    data:val,
    success: function(data){

      $("#editlocation").val(data.result.location.location)
      $("#editaddress").val(data.result.location.address)
      $("#editzip").val(data.result.location.zip_code)
      $("#editcity").val(data.result.location.city)
      $("#editcountry").val(data.result.location.country)

    },
    error: function (jqXHR, textStatus, errorThrown){

      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

    },
  });

}

//---------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------//

$(document).ready(function(){



  $.ajaxSetup({
      headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
$('.formula').on('submit',function(e){

     //later you decide you want to submit
     $(this).unbind('submit').submit()
     return true;
});
  $('form').on('submit',function(e){

    e.preventDefault();


//---------------------------------------------------------------------------------------------//
    if(modal.id=='bankcreatedestiny'){



      var destination = $('#destination').val();
      var val = {'id':ids, 'destination':destination};

      $('#destination').val('')

      $.ajax({
        type:'POST',
        url:"users/bankcreate",
        data:val,
        success: function(data){
          $('#bankcreatedestinyerror').empty();
        document.getElementById('bankcreatedestiny').style.display='none';
          $('#myDIVbank'+ids).empty();
          $('#myDIVbank'+ids).append('<br/>');
          $('#myDIVbank'+ids).append('<a title="Create" onclick="bankcreate('+ids+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.destination.forEach(function(dest) {
            $('#myDIVbank'+ids).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="New Account" onclick="bankcreateaccount('+ids+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+ids+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'" title="Edit" onclick="bankeditdestination('+ids+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
          });

          for (i = 0; i < data.result.account.length; ++i) {
            data.result.account[i].forEach(function(acc) {
              $('#acc'+acc.id).empty();
              $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+ids+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
            });
          }

          $('#myDIVbank'+ids).append('</tbody></table>');

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
            $('#bankcreatedestinyerror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);


        },
      });
    }
//---------------------------------------------------------------------------------------------//
    if(modal.id=='bankcreateaccount'){



      var type = $('#type').val();
      var account_number = $('#account_number').val();

      var val = {'IDsup':ids,'id':IDdest, 'type':type, 'account_number':account_number};

      $('#account_number').val('')

      $.ajax({
        type:'POST',
        url:"users/bankcreateaccount",
        data:val,
        success: function(data){

          document.getElementById('bankcreateaccount').style.display='none';
          $('#bankcreateerror').empty();
          $('#myDIVbank'+ids).empty();
          $('#myDIVbank'+ids).append('<br/>');
          $('#myDIVbank'+ids).append('<a title="Create" onclick="bankcreate('+ids+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.destination.forEach(function(dest) {
            $('#myDIVbank'+ids).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="Create Account" onclick="bankcreateaccount('+ids+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+ids+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'" title="Edit" onclick="bankeditdestination('+ids+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
          });

          for (i = 0; i < data.result.account.length; ++i) {
            data.result.account[i].forEach(function(acc) {
              $('#acc'+acc.id).empty();
              $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+ids+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
            });
          }

          $('#myDIVbank'+ids).append('</tbody></table>');
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
            $('#bankcreateerror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);

        },
      });
    }
//---------------------------------------------------------------------------------------------//
    if(modal.id=='bankeditdestination'){



      var type = $('select').val();
      var account_number = $('#account_numbe').val();
      var idacc= $('#idacc').val();
      var destination = $('#destination'+IDdestupdt).val();

      var val = {'id':idsupdt,'IDdest':IDdestupdt,'destination':destination,'idacc':idacc, 'type':type, 'account_number':account_number};

      $.ajax({
        type:'POST',
        url:"users/bankupdate",
        data:val,
        success: function(data){
          document.getElementById('bankeditdestination').style.display='none';
          $('#bankediterror').empty();
          $('#myDIVbank'+idsupdt).empty();
          $('#myDIVbank'+idsupdt).append('<br/>');
          $('#myDIVbank'+idsupdt).append('<a title="Create" onclick="bankcreate('+idsupdt+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.destination.forEach(function(dest) {
            $('#myDIVbank'+idsupdt).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.destination+'<a class="w3-right" title="Create Account" onclick="bankcreateaccount('+idsupdt+','+dest.id+')"><i style="color:teal" class="fa fa-plus-circle w3-right w3-button" aria-hidden="true"></i></a>'+'<a title="Delete Destination" onclick="bankdel('+idsupdt+','+dest.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'<a id="sendedit" data-field-id="'+dest.id+'" title="Edit" onclick="bankeditdestination('+idsupdt+','+dest.id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><tbody id="acc'+dest.id+'">');
          });

          for (i = 0; i < data.result.account.length; ++i) {
            data.result.account[i].forEach(function(acc) {
              $('#acc'+acc.id).empty();
              $('#acc'+acc.usr_bank_acc_dest_id).append('<tr><th class="w3-text-teal">'+acc.type+'</th><td>'+acc.account_number+'<a title="Delete Account" onclick="bankaccountdel('+idsupdt+','+acc.id+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right w3-button" aria-hidden="true"></i></a>'+'</td></tr>');
            });
          }

          $('#myDIVbank'+idsupdt).append('</tbody></table>');
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
            $('#bankediterror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);


        },
      });

    }

//---------------------------------------------------------------------------------------------//
    if(modal.id=='contactedit'){



      var idcontact = $('#idcontact').val();
      var type=contactType;
      var name = $('#editname').val();
      var phone = $('#editphone').val();
      var email = $('#editemail').val();
      var mobile = $('#editmobile').val();

      var val = {'id':idsupdt,'idcontact':idcontact,'type':type,'name':name, 'phone':phone, 'email':email, 'mobile':mobile};

      $.ajax({
        type:'POST',
        url:"users/contactupdate",
        data:val,
        success: function(data){
          document.getElementById('contactedit').style.display='none';
          $('#contactediterror').empty();
          $('#tablecontracts'+idsupdt).empty();
          $('#tablereservations'+idsupdt).empty();
          $('#tableaccounts'+idsupdt).empty();
      //$('#myDIVcontact'+id).empty();

      //$('#myDIVcontact'+id).append('<button  onclick="bankcreate('+id+')" class="fa fa-plus w3-right" style="color:teal" aria-hidden="true"></button>');
          $('#tablecontracts'+idsupdt).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tablereservations'+idsupdt).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tableaccounts'+idsupdt).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          data.result.contact.forEach(function(dest) {
            if(dest.type=='Contracts'){
              $('#tablecontracts'+idsupdt).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<a title="Delete Contract" onclick="destroycontact('+dest.id+','+idsupdt+','+0+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a>'+'</td>'+'</tr>');
            }
            else if(dest.type=='Reservations'){
              $('#tablereservations'+idsupdt).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Reservations" onclick="destroycontact('+dest.id+','+idsupdt+','+1+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
            else if(dest.type=='Accounts'){
              $('#tableaccounts'+idsupdt).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Accounts" onclick="destroycontact('+dest.id+','+idsupdt+','+2+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
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

//---------------------------------------------------------------------------------------------//
    if(modal.id=='createcontact'){


      var type=contactType;
      var name = $('#name').val();
      var phone = $('#phone').val();
      var email = $('#email').val();
      var mobile = $('#mobile').val();

      var val = {'id':ids,'type':type, 'name':name, 'phone':phone, 'email':email, 'mobile':mobile};



      $.ajax({
        type:'POST',
        url:"users/createcontact",
        data:val,
        success: function(data){

          document.getElementById('createcontact').style.display='none';
          $('#name').val('')
          $('#phone').val('')
          $('#email').val('')
          $('#mobile').val('')
          $('#contactcreateerror').empty();
          $('#tablecontracts'+ids).empty();
          $('#tablereservations'+ids).empty();
          $('#tableaccounts'+ids).empty();
      //$('#myDIVcontact'+id).empty();

      //$('#myDIVcontact'+id).append('<button  onclick="bankcreate('+id+')" class="fa fa-plus w3-right" style="color:teal" aria-hidden="true"></button>');
          $('#tablecontracts'+ids).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tablereservations'+ids).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          $('#tableaccounts'+ids).append('<tr><th class="w3-text-teal">Name</th><th class="w3-text-teal">Phone</th><th class="w3-text-teal">E-mail</th><th class="w3-text-teal">Mobile</th></tr>');
          data.result.contact.forEach(function(dest) {
            if(dest.type=='Contracts'){
              $('#tablecontracts'+ids).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<a title="Delete Contract" onclick="destroycontact('+dest.id+','+ids+','+0+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a>'+'</td>'+'</tr>');
            }
            else if(dest.type=='Reservations'){
              $('#tablereservations'+ids).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Reservations" onclick="destroycontact('+dest.id+','+ids+','+1+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
            else if(dest.type=='Accounts'){
              $('#tableaccounts'+ids).append('<tr>'+'<td>'+dest.name+'</td><td>'+dest.telephone+'</td>'+'<td>'+dest.email+'</td>'+'<td>'+dest.mobile+'<button title="Delete Accounts" onclick="destroycontact('+dest.id+','+ids+','+2+')" class="w3-right"><i style="color:teal" class="fa fa-eraser w3-right" aria-hidden="true"></i></button>'+'</td>'+'</tr>');

            }
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
          $('#contactcreateerror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);


        },
      });
    }


//---------------------------------------------------------------------------------------------//
    if(modal.id=='createlocal'){


      var id=ids;
      var location = $('#location').val();
      var address = $('#address').val();
      var zip = $('#zip').val();
      var city = $('#city').val();
      var country = $('#country').val();

      var val = {'id':id,'location':location, 'address':address, 'zip':zip, 'city':city, 'country':country};



      $.ajax({
        type:'POST',
        url:"users/createlocal",
        data:val,
        success: function(data){
          document.getElementById('createlocal').style.display='none';
          $('#localcreateerror').empty();
          $('#location').val('')
          $('#address').val('')
          $('#zip').val('')
          $('#city').val('')
          $('#country').val('')
          $('#myDIVlocation'+id).empty();
          $('#myDIVlocation'+id).append('<br/>');
          $('#myDIVlocation'+id).append('<a title="Create" onclick="localcreate('+id+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.location.forEach(function(dest) {
            $('#loca'+dest.id).empty();
            $('#myDIVlocation'+id).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.location+'<a title="Delete" onclick="destroylocal('+dest.id+','+id+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a><a title="Edit" onclick="editlocal('+dest.id+','+id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><thead><tr><th class="w3-text-teal">Adress</th><th class="w3-text-teal">Zip Code</th><th class="w3-text-teal">City</th><th class="w3-text-teal">Country</th></tr></thead><tbody id="loca'+dest.id+'">'+'<tr><td>'+dest.address+'</td><td>'+dest.zip_code+'</td>'+'<td>'+dest.city+'</td>'+'<td>'+dest.country+'</td>'+'</tr>'+'</tbody></table>');
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
          $('#localcreateerror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);


        },
      });
    }

//---------------------------------------------------------------------------------------------//

    if(modal.id=='localtedit'){


      var id=idsupdt;
      var location = $('#editlocation').val();
      var address = $('#editaddress').val();
      var zip = $('#editzip').val();
      var city = $('#editcity').val();
      var country = $('#editcountry').val();

      var val = {'locIds':locIds,'id':id,'location':location, 'address':address, 'zip':zip, 'city':city, 'country':country};

      $.ajax({
        type:'POST',
        url:"users/localupdt",
        data:val,
        success: function(data){
          document.getElementById('localtedit').style.display='none';
          $('#localediterror').empty();
          $('#myDIVlocation'+id).empty();
          $('#myDIVlocation'+id).append('<br/>');
          $('#myDIVlocation'+id).append('<a title="Create"  onclick="localcreate('+id+')" class="fa fa-plus w3-right w3-button" style="color:teal" aria-hidden="true"></a>');

          data.result.location.forEach(function(dest) {
            $('#loca'+dest.id).empty();
            $('#myDIVlocation'+id).append('<table width="100%"><tbody><th class="w3-border w3-border-teal ">'+dest.location+'<a title="Delete" onclick="destroylocal('+dest.id+','+id+')" class="w3-button fa fa-eraser w3-right" style="color:teal" aria-hidden="true"></a><a title="Edit" onclick="editlocal('+dest.id+','+id+')" class="fa fa-pencil-square-o w3-right w3-button" style="color:teal" aria-hidden="true"></a>'+'</th></tbody></table >'+'<table  class="table table-striped table-bordered table-hover w3-animate-opacity w3-container"><thead><tr><th class="w3-text-teal">Adress</th><th class="w3-text-teal">Zip Code</th><th class="w3-text-teal">City</th><th class="w3-text-teal">Country</th></tr></thead><tbody id="loca'+dest.id+'">'+'<tr><td>'+dest.address+'</td><td>'+dest.zip_code+'</td>'+'<td>'+dest.city+'</td>'+'<td>'+dest.country+'</td>'+'</tr>'+'</tbody></table>');
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
          $('#localediterror').html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);

        },
      });
    }

//---------------------------------------------------------------------------------------------//
    if (modal.id == 'supplier') {
      $('#upload_form').submit(function(e) {
          $.ajax({
              type: 'POST',
              url: "users/create",
              data: new FormData(this), // Create FormData inside AJAX call
              processData: false,
              contentType: false,
              success: function(data) {
                  document.getElementById('supplier').style.display = 'none';
                  document.getElementById("upload_form").reset();
                  window.location.reload();
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  var errors = jqXHR.responseJSON.errors;
                  var errorsHtml = '';
                  $.each(errors, function(key, value) {
                      errorsHtml += '<li>' + value[0] + '</li>';
                  });
                  $('#error').html('<ul class="alert alert-warning">' + errorsHtml + '</ul>', "Error " + jqXHR.status + ': ' + errorThrown);
              }
          });
      });
    }

    //---------------------------------------------------------------------------------------------//
    if(modal=='editSupplier'){

      var form = new FormData();
      var image = $('#path_image'+ids)[0].files[0];
      var name = $('#name'+ids).val();
      var email = $('#emailuser'+ids).val();
      var password = $('#passworduser'+ids).val();
      var social_denomination = $('#social_denomination'+ids).val();
      var fiscal_number = $('#fiscal_number'+ids).val();
      var web = $('#web'+ids).val();
      var remarks = $('#remarks'+ids).val();
      var active = $('#active'+ids).val();

      form.append('id', ids);

      if(image==null){
        form.append('path_image', 'nulo');
      }
      else{
        form.append('path_image', image);
      }

      form.append('name', name);
      form.append('email', email);
      form.append('password', password);
      form.append('social_denomination', social_denomination);
      form.append('fiscal_number', fiscal_number);
      form.append('web', web);
      form.append('remarks', remarks);
      form.append('active', active);
      //$('#account_number').val('')

      $.ajax({
        type:'POST',
        url:"users/edit",
        data:form,
        dataType: "Json",
        processData: false,
        contentType: false,
        success: function(data){
          document.getElementById('editSupplier'+ids).style.display='none';
          document.getElementById("editSupplier_form"+ids).reset();
          $(".loader").hide();

          $.confirm({
              icon: 'fa fa-spinner fa-spin',
              theme: 'material',
              title: "Awesome!",
              content: 'User edited with successfull',
              type: 'blue',
              typeAnimated: true,
              onClose: function () {
                  window.location.reload();
              },
              onDestroy: function () {
                  window.location.reload();
              },
              buttons: {
                  ok: function(){
                  }
              }
          });

        },
        beforeSend: function(){
          $('.loader').show();
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
          $('#error'+ids).html('<ul class="alert alert-warning">'+errorsHtml+'</ul>' , "Error " + jqXHR.status +': '+ errorThrown);

          $('.loader').hide();
        },
      });
    }

  });
});
//---------------------------------------------------------------------------------------------//
