
<div class="w3-container  w3-padding" style=" overflow-y: auto; height: 260px;">
  <ul class="w3-ul w3-card-4" id='bolsa'></ul>
</div>

<div class="w3-padding">
  <div class="w3-col l6 w3-padding">
    <p class="w3-button w3-block" onclick="clearShoppingCart()" style="background-color: red; color: #fff;">Clear All</p>
  </div>
  <div class="w3-col l6 w3-padding">
	 <p onclick="send()" class="w3-button w3-block" style="background-color: green; color: #fff;">Send</p>
  </div>
</div>

  <script type="text/javascript">
  var userID = {{ Auth::user()->id }};

  	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));
    //retrievedImage = JSON.parse(localStorage.getItem());
    console.log(retrievedObject);
    if(retrievedObject!=null){
    	retrievedObject.forEach(function(entry,key) {

			object = JSON.stringify(entry);
     // console.log(entry);

      $('#bolsa').append('<li class="w3-bar"><div class="w3-col l2"><img src="<?php echo asset("storage/padrao.png")?>" class="w3-border w3-bar-item w3-circle w3-hide-small" style="width:85px"></div><div class="w3-col l9"><span class="w3-large">'+entry.nome+'</span><br><span>'+entry.array[0].form+'<a class="w3-right" onclick=\'detal('+object+','+key+')\' style="cursor:pointer;margin-right: 70px;">Details</a>'+'</span></div><div class="w3-col l1"><span onclick="renova(this,'+key+')" class=" w3-button w3-white w3-xlarge ">×</span></div></li>')
      var total= key+1;
      $('#total').html('<b>'+total+'</b>')
     });
    }

  	function renova(elemen,index){

  		elemen.parentElement.parentElement.remove()
  		retrievedObject.splice(index, 1);
  		console.log(retrievedObject)
  		$('#bolsa').empty()
  		if(retrievedObject.length==0){
        $('#total').css("display", "none");
      }

  		retrievedObject.forEach(function(entry,key) {
        $('#bolsa').append('<li class="w3-bar"><div class="w3-col l2"><img src="<?php echo asset("storage/padrao.png")?>" class="w3-border w3-bar-item w3-circle w3-hide-small" style="width:85px"></div><div class="w3-col l9"><span class="w3-large">'+entry.nome+'</span><br><span>'+entry.array[0].form+'<a class="w3-right" onclick=\'detal('+object+','+key+')\' style="cursor:pointer;margin-right: 70px;">Details</a>'+'</span></div><div class="w3-col l1"><span onclick="renova(this,'+key+')" class=" w3-button w3-white w3-xlarge ">×</span></div></li>')

        	var total= key+1;
    			$('#total').html('<b>'+total+'</b>')
       });
		  localStorage.removeItem('array'+userID)
		  localStorage.setItem('array'+userID, JSON.stringify(retrievedObject));
  	}


  	function detal(arg,key){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
  		console.log(arg)

  		var x = document.getElementById("detailModal");
  		x.className += " w3-show";
  		$('#details').load(assetBaseUrl+'mostra',{'arg':arg,'key':key},function(){

  		});
   	}

   	function send(){
      var armaz = new Array();

      retrievedObject = JSON.parse(localStorage.getItem('array'+userID));
      if(retrievedObject!=null){
        //alert('tem algo no localstorage!')
        armaz=retrievedObject;
      }
      // localStorage.removeItem('array'+userID)//LIMPEZA DO LOCAL STORAGE
      console.log('Tamanho:',armaz)
      tipoPedido = localStorage.getItem('tipoPedido'+userID);
      leadPedido = localStorage.getItem('leadPedido'+userID);
      responsavelPedido = localStorage.getItem('responsavelPedido'+userID);
      referenciapedido = localStorage.getItem('referenciaPedido'+userID);

      val = {'produtos': armaz, 'type' : tipoPedido, 'lead_name' : leadPedido, 'responsavel' : responsavelPedido, 'referencia' : referenciapedido, 'user_id': userID };
      console.log(val)

      $.ajax({
        type:'POST',
        url:assetBaseUrl+"alojamento",
        data:val,
        success: function(data){
          localStorage.removeItem('array'+userID)//LIMPEZA DO LOCAL STORAGE
          localStorage.removeItem('tipoPedido'+userID);
          localStorage.removeItem('leadPedido'+userID);
          localStorage.removeItem('responsavelPedido'+userID);
          localStorage.removeItem('referenciaPedido'+userID);

          $('#bolsa').empty()

          var carrinho = document.getElementById("Demo");
          carrinho.className = carrinho.className.replace(" w3-show", "");

          $('#total').css("display", "none");

          // alert('kk')
          // console.log(data.result.in)
          console.log(data.result.valor)
          console.log(data.result.obj)
          //console.log(data.result.obj[0].quartos[0].nomes[0])
          console.log(data.result.obj1)
          console.log(data.result.obj2)
          console.log(data.result.dataa)
          // $("#elementos *").attr("disabled", "disabled").off('click');
          // $("#button1").prop("onclick",false);
          // $("#button2").prop("onclick",false);
          //             $('#changes').append('<div class="w3-col l3" style="color: red;"><b id="Foo">Do you want to change someday?</b>&nbsp;</div> <div class="w3-col l9"><i  onclick="acordion()" class="fa fa-plus w3-button" style="font-size:18px"></i></div><div id="acordion"  style="display: none;"><div class="w3-col l12 w3-white">&nbsp;</div><div class="w3-col l12 w3-white"><div id="resultado"></div></div></div></div>');

          //             $('#resul               tado').append('<div id="test-list" class="w3-col l12"><div class="w3-col l12"><ul class="list" style="list-style-type: none; margin-left: -40px;" id="bankeditdestinationforminput"></ul></div><div class="w3-col l12 w3-center"><ul class="pagination"></ul></div></div>');


          //             for(z=1;z<=data.result.in;z++){
          //             $('#bankeditdestinationforminput').append('<li><div class="w3-col l12" style=" border-color: #eeeeee; border-style: solid; border-width: 1px;"><div class="w3-col l11" style="background-color: #F6F6F6;"><div style=" padding-top: 7px; padding-bottom: 6px;"><span style=" color: #24AEC9;">&nbsp;'+'Modify day '+z+'</span></div></div><div class="w3-col l1 " style="background-color: #F6F6F6;"><i href="'+'assetBaseUrl'+'main/product/'+'data.result.produtos[key].id'+'/'+'categoria_id'+'/'+'destino_id'+'" style="height: 35px;" class="fa fa-plus w3-button w3-right"></i></div></li>');
          //             }

          //             var monkeyList = new List('test-list', {
          //         valueNames: ['name'],
          //         page: 10,
          //         pagination: true
          //       });


          //               // var f = document.getElementById('Foo');
          //               // setInterval(function() {
          //               // f.style.display = (f.style.display == 'none' ? '' : 'none');
          //               // }, 1000);

        },
        error: function (data, jqXHR, textStatus, errorThrown) {
          console.log(data);
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
          alert('error')
        },
      });

   	}

   	function clearShoppingCart(){

   		localStorage.removeItem('array'+userID)//LIMPEZA DO LOCAL STORAGE
   		localStorage.removeItem('tipoPedido'+userID);
	    localStorage.removeItem('leadPedido'+userID);
	   	localStorage.removeItem('responsavelPedido'+userID);
	    localStorage.removeItem('referenciaPedido'+userID);

	    $('#bolsa').empty()
      $('#total').css("display", "none");
   	}

  </script>


<div id="detailModal"  class="w3-modal w3-animate-opacity">
  <div class="w3-modal-content w3-card-4 w3-animate-opacity w3-display-topmiddle" style="width:90%; margin-top: 20px; margin-bottom: 40px;">
    <div id="details"></div>
  </div>
</div>
