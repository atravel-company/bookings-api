<div class="w3-col l12">
<div style="display: flex;">
        <span onclick="var x = document.getElementById('detailModal'); x.className = x.className.replace( 'w3-show', '');" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
</div>
<br><br>
</div>
<script type="text/javascript">
var userID = {{ Auth::user()->id }};

$('.datetimepickercarup1').datetimepicker({
                widgetParent: '#datacarup1',
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,


            });

            $('.datetimepickerscarup1').datetimepicker({
               widgetParent: '#horacarup1',
               format: 'HH:mm',
               ignoreReadonly: true,

            });

            $('.datetimepickercaroff1').datetimepicker({
                widgetParent: '#datacaroff1',
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,


            });

            $('.datetimepickerscaroff1').datetimepicker({
               widgetParent: '#horacaroff1',
               format: 'HH:mm',
               ignoreReadonly: true,

            });

function editaQuarto(produto,key){
	var armaz = new Array();
	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));

	console.log(retrievedObject[produto]['array'][key])

	retrievedObject[produto]['array'][key]['in']=document.getElementById('in'+key).value;
	retrievedObject[produto]['array'][key]['out']=document.getElementById('out'+key).value;
	retrievedObject[produto]['array'][key]['type']=document.getElementById('type'+key).value;
	retrievedObject[produto]['array'][key]['room']=document.getElementById('room'+key).value;
	retrievedObject[produto]['array'][key]['plan']=document.getElementById('plan'+key).value;
	retrievedObject[produto]['array'][key]['people']=document.getElementById('people'+key).value;
	delete retrievedObject[produto]['array'][key]['quartos'];
	console.log(retrievedObject[produto]['array'][key])
	console.log(retrievedObject)
	armaz=retrievedObject;
	localStorage.setItem('array'+userID, JSON.stringify(armaz));
	var carrinho = document.getElementById("Demo");
    carrinho.className = carrinho.className.replace(" w3-show", "");
}

function editaGolf(produto,key){
	console.log(produto, key);
	var armaz = new Array();
	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));

	//console.log(retrievedObject[produto]['array'][key])

	retrievedObject[produto]['array'][key]['coursegolf']=document.getElementById('coursegolf'+key).value;
	retrievedObject[produto]['array'][key]['datagolf']=document.getElementById('datagolf'+key).value;
	retrievedObject[produto]['array'][key]['horagolf']=document.getElementById('horagolf'+key).value;
	retrievedObject[produto]['array'][key]['peoplegolf']=document.getElementById('peoplegolf'+key).value;
	delete retrievedObject[produto]['array'][key]['golf'];
	//console.log(retrievedObject[produto]['array'][key])
	//console.log(retrievedObject)
	armaz=retrievedObject;
	localStorage.setItem('array'+userID, JSON.stringify(armaz));
	var carrinho = document.getElementById("Demo");
    carrinho.className = carrinho.className.replace(" w3-show", "");
}

function editaCar(produto,key){
	var armaz = new Array();
	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));

	console.log(retrievedObject[produto]['array'][key])

	retrievedObject[produto]['array'][key]['dropoffairportcar']=document.getElementById('dropoffairportcar'+key).value;
	retrievedObject[produto]['array'][key]['dropoffcar']=document.getElementById('dropoffcar'+key).value;
	retrievedObject[produto]['array'][key]['dropoffcountrycar']=document.getElementById('dropoffcountrycar'+key).value;
	retrievedObject[produto]['array'][key]['dropoffdatacar']=document.getElementById('dropoffdatacar'+key).value;
	retrievedObject[produto]['array'][key]['dropoffflightcar']=document.getElementById('dropoffflightcar'+key).value;
	retrievedObject[produto]['array'][key]['dropoffhoracar']=document.getElementById('dropoffhoracar'+key).value;
	retrievedObject[produto]['array'][key]['group']=document.getElementById('group'+key).value;
	retrievedObject[produto]['array'][key]['model']=document.getElementById('model'+key).value;
	retrievedObject[produto]['array'][key]['pickupairportcar']=document.getElementById('pickupairportcar'+key).value;
	retrievedObject[produto]['array'][key]['pickupcar']=document.getElementById('pickupcar'+key).value;
	retrievedObject[produto]['array'][key]['pickupcountrycar']=document.getElementById('pickupcountrycar'+key).value;
	retrievedObject[produto]['array'][key]['pickupdatacar']=document.getElementById('pickupdatacar'+key).value;
	retrievedObject[produto]['array'][key]['pickupflightcar']=document.getElementById('pickupflightcar'+key).value;
	retrievedObject[produto]['array'][key]['pickuphoracar']=document.getElementById('pickuphoracar'+key).value;

	delete retrievedObject[produto]['array'][key]['car'];
	console.log(retrievedObject[produto]['array'][key])
	console.log(retrievedObject)
	armaz=retrievedObject;
	localStorage.setItem('array'+userID, JSON.stringify(armaz));
	var carrinho = document.getElementById("Demo");
    carrinho.className = carrinho.className.replace(" w3-show", "");
}

function editaTransfer(produto,key){
	var armaz = new Array();
	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));

	console.log(retrievedObject[produto]['array'][key])

	retrievedObject[produto]['array'][key]['adultstransfer']=document.getElementById('adultstransfer'+key).value;
	retrievedObject[produto]['array'][key]['babiestransfer']=document.getElementById('babiestransfer'+key).value;
	retrievedObject[produto]['array'][key]['childrenstransfer']=document.getElementById('childrenstransfer'+key).value;
	retrievedObject[produto]['array'][key]['datatransfer']=document.getElementById('datatransfer'+key).value;
	retrievedObject[produto]['array'][key]['dropofftransfer']=document.getElementById('dropofftransfer'+key).value;
	retrievedObject[produto]['array'][key]['flighttransfer']=document.getElementById('flighttransfer'+key).value;
	retrievedObject[produto]['array'][key]['pickuptransfer']=document.getElementById('pickuptransfer'+key).value;

	delete retrievedObject[produto]['array'][key]['transfer'];
	console.log(retrievedObject[produto]['array'][key])
	console.log(retrievedObject)
	armaz=retrievedObject;
	localStorage.setItem('array'+userID, JSON.stringify(armaz));
	var carrinho = document.getElementById("Demo");
    carrinho.className = carrinho.className.replace(" w3-show", "");
}

function editaTicket(produto,key){
	var armaz = new Array();
	retrievedObject = JSON.parse(localStorage.getItem('array'+userID));

	console.log(retrievedObject[produto]['array'][key]);

	retrievedObject[produto]['array'][key]['adultsticket']=document.getElementById('adultsticket'+key).value;
	retrievedObject[produto]['array'][key]['babiesticket']=document.getElementById('babiesticket'+key).value;
	retrievedObject[produto]['array'][key]['childrensticket']=document.getElementById('childrensticket'+key).value;
	retrievedObject[produto]['array'][key]['dataticket']=document.getElementById('dataticket'+key).value;
	retrievedObject[produto]['array'][key]['horaticket']=document.getElementById('horaticket'+key).value;

	delete retrievedObject[produto]['array'][key]['transfer'];
	console.log(retrievedObject[produto]['array'][key])
	console.log(retrievedObject)
	armaz=retrievedObject;
	localStorage.setItem('array'+userID, JSON.stringify(armaz));
	var carrinho = document.getElementById("Demo");
    carrinho.className = carrinho.className.replace(" w3-show", "");
}



</script>

<div class="w3-container  w3-padding w3-col l12">

	<ul class="w3-ul" id='array'>
		<li>
			<h1 class="w3-center">{{$produto->arg['nome']}}</h1>

		<ul class="w3-ul w3-striped">
			@foreach($produto->arg['array'] as $key=>$array)

				@if($key%2==0)
					@if($array['form']=='alojamento')
						<div class="w3-card-4 w3-border w3-padding w3-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-bed" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Alojamento</b>
					</div>
					@endif
					@if($array['form']=='golf')
						<div class="w3-card-4 w3-border w3-padding w3-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Golf</b>
					</div>
					@endif
					@if($array['form']=='transfer')
						<div class="w3-card-4 w3-border w3-padding w3-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-taxi" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Transfer</b>
					</div>
					@endif
					@if($array['form']=='car')
						<div class="w3-card-4 w3-border w3-padding w3-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-car" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Rent a Car</b>
					</div>
					@endif
					@if($array['form']=='ticket')
						<div class="w3-card-4 w3-border w3-padding w3-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Ticket</b>
					</div>
					@endif
				@else
					@if($array['form']=='alojamento')
						<div class="w3-card-4 w3-border w3-padding w3-text-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-bed" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Alojamento</b>
					</div>
					@endif
					@if($array['form']=='golf')
						<div class="w3-card-4 w3-border w3-padding w3-text-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Golf</b>
					</div>
					@endif
					@if($array['form']=='transfer')
						<div class="w3-card-4 w3-border w3-padding w3-text-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-taxi" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Transfer</b>
					</div>
					@endif
					@if($array['form']=='car')
						<div class="w3-card-4 w3-border w3-padding w3-text-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-car" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Rent a Car</b>
					</div>
					@endif
					@if($array['form']=='ticket')
						<div class="w3-card-4 w3-border w3-padding w3-text-blue" onclick="accord('demo{{$key}}')" style="cursor:pointer;">
						<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<b>Ticket</b>
					</div>
					@endif

				@endif

				<div class="w3-hover-pale-yellow w3-container w3-hide w3-padding" id="demo{{$key}}">

				<ul>

				<script type="text/javascript">
					$(function () {
						checkin='#checkin{{$key}}';
						checkout='#checkout{{$key}}';

			            $('.datetimepicker{{$key}}').datetimepicker({
			                widgetParent: checkin,
			                format: 'DD/MM/YYYY',
			                ignoreReadonly: true,


			            }).on("dp.change", function (e) {
			              $('.datetimepickers{{$key}}').data("DateTimePicker").minDate(e.date);
			            });

			            $('.datetimepickers{{$key}}').datetimepicker({
			               widgetParent: checkout,
			               format: 'DD/MM/YYYY',
			               ignoreReadonly: true,

			            });
			        });
				</script>
						<div class="w3-row-padding">
							@if(isset($array['in']))
								  <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Check-In :</label>
										            <div class="form-group">
										                <div class="input-group date datetimepicker{{$key}}" id='datetimepicker{{$key}}' style="position: relative;">
										                <div style="width: 330px; position: absolute;" id="checkin{{$key}}"></div>
										                    <input value="{{$array['in']}}"  type="text" readonly class="form-control ats-border-color" id="in{{$key}}" name="out" placeholder="Checkin">
										                    <span class="input-group-addon ats-border-color">
										                        <span class="w3-large ats-text-color fa fa-calendar"></span>
										                    </span>
										                </div>

										            </div>
										      </div>
							@endif

							@if(isset($array['out']))

								<div class="w3-col l2 m4 s12">
										        <label class="ats-label">Check-Out :</label>
										            <div class="form-group">
										                <div class="input-group date datetimepickers{{$key}}" id='datetimepicker13' style="position: relative;">
										                <div style="width: 330px; position: absolute;" id="checkout{{$key}}"></div>
										                    <input value="{{$array['out']}}" type="text" readonly class="form-control ats-border-color" id="out{{$key}}" name="out" placeholder="Check-Out">
										                    <span class="input-group-addon ats-border-color">
										                        <span class="w3-large ats-text-color fa fa-calendar"></span>
										                    </span>
										                </div>

										            </div>
										      </div>
							@endif

							@if(isset($array['type']))

								<div class="w3-col l2 m4 s12">
										        <label class="ats-label">Type :</label>
										            <div class="form-group">


										                    <input value="{{$array['type']}}" type="text" class="w3-block form-control ats-border-color" id="type{{$key}}" placeholder="Type">



										            </div>
										      </div>
							@endif

							@if(isset($array['room']))


								<div class="w3-col l2 m4 s12">
										        <label class="ats-label">Rooms :</label>
										            <div class="form-group">


										                    <input value="{{$array['room']}}" type="number" class="w3-block form-control ats-border-color" id="room{{$key}}" placeholder="Type">



										            </div>
										      </div>
							@endif

							@if(isset($array['plan']))


								<div class="w3-col l1 m4 s12">
										        <label class="ats-label">Plan :</label>
										            <div class="form-group">


										                    <select id="plan{{$key}}" class="form-control ats-border-color w3-block">
										                    @if($array['plan']=='AI')
										                    	<option selected="selected">AI</option>
										                    @else
										                    	<option>AI</option>
										                    @endif
										                    @if($array['plan']=='BB')
										                    	<option selected="selected">BB</option>
										                    @else
										                    	<option>BB</option>
										                    @endif
										                    @if($array['plan']=='FB')
										                    	<option selected="selected">FB</option>
										                    @else
										                    	<option>FB</option>
										                    @endif
										                    @if($array['plan']=='HB')
										                    	<option selected="selected">HB</option>
										                    @else
										                    	<option>HB</option>
										                    @endif
										                    @if($array['plan']=='RO')
										                    	<option selected="selected">RO</option>
										                    @else
										                    	<option>RO</option>
										                    @endif
										                    @if($array['plan']=='SC')
										                    	<option selected="selected">SC</option>
										                    @else
										                    	<option>SC</option>
										                    @endif
										                    @if($array['plan']=='SEMI AI')
										                    	<option selected="selected">SEMI AI</option>
										                    @else
										                    	<option>SEMI AI</option>
										                    @endif
										                    </select>



										            </div>
										      </div>

							@endif

							@if(isset($array['people']))

								<div class="w3-col l1 m4 s12">
										        <label class="ats-label">People :</label>
										            <div class="form-group">


										                    <input id="people{{$key}}" value="{{$array['people']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
							   </div>


								<div class="w3-col l2 m4 s12">
									<label class="ats-label">&nbsp;</label>
									<div class="form-group">
										<span class="w3-button w3-blue w3-block" onclick="editaQuarto({{$produto->key}},{{$key}})">Edit</span>
									</div>
								</div>
								@endif
						</div>


						<!-- @if(isset($array['quartos']))
							<ul>
								@foreach($array['quartos'] as $key1=>$quarto)

										@if(isset($quarto['roomremark']))
											Remark : {{$quarto['roomremark']}}
										@endif

									@if(isset($quarto['nomes']))
										<ul>
											@foreach($quarto['nomes'] as $key2=>$nome)
													<li>
														@if(isset($nome['roomname']))
														Name : {{$nome['roomname']}}
													@endif
													</li>


											@endforeach
										</ul>
									@endif
								@endforeach
							</ul>
						@endif -->

				</ul>
				<ul>
				<script type="text/javascript">
					data='#data{{$key}}';
					hora='#hora{{$key}}';

					$('.datetimepickergolf{{$key}}').datetimepicker({
					                widgetParent: data,
					                format: 'DD/MM/YYYY',
					                ignoreReadonly: true,


					            });

					            $('.datetimepickersgolf{{$key}}').datetimepicker({
					               widgetParent: hora,
					               format: 'HH:mm',
					               ignoreReadonly: true,

					            });
				</script>
					<div class="w3-row-padding">
							@if(isset($array['datagolf']))

								 <div class="w3-col l2 m5 s12">
							         <label class="ats-label">Date</label>
							            <div class="form-group">

										<div class='input-group date datetimepickergolf{{$key}}'  style="position: relative;">
										                 <div style="width: 330px; position: absolute;" id="data{{$key}}"></div>
							                    <input value="{{$array['datagolf']}}" type='text' readonly name="datagolf" id="datagolf{{$key}}" class="form-control ats-border-color" placeholder="Check-In" />
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-calendar"></span>
							                    </span>
							                </div>





             </div>
        </div>
							@endif

							@if(isset($array['horagolf']))

								 <div class="w3-col l2 m5 s12">
        <label class="ats-label">Tee Time Request</label>
            <div class="form-group">


                <div class='input-group date datetimepickersgolf{{$key}}'  style="position: relative;">
                 <div style="width: 330px; position: absolute;" id="hora{{$key}}"></div>
                    <input value="{{$array['horagolf']}}" type='text' readonly name="horagolf" id="horagolf{{$key}}" class="form-control ats-border-color" placeholder="Check-In" />
                    <span class="input-group-addon ats-border-color">
                        <span class="w3-large ats-text-color fa fa-clock-o"></span>
                    </span>
                </div>
            </div>
        </div>

							@endif

							@if(isset($array['coursegolf']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Golf Course :</label>
										            <div class="form-group">


										                    <input value="{{$array['coursegolf']}}" type="text" class="w3-block form-control ats-border-color" id="coursegolf{{$key}}" placeholder="Course Golf">



										            </div>
										      </div>
							@endif

							@if(isset($array['peoplegolf']))


								 <div class="w3-col l1 m4 s12">
										        <label class="ats-label">Players :</label>
										            <div class="form-group">


										                    <input id="peoplegolf{{$key}}" value="{{$array['peoplegolf']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
							   </div>


								<div class="w3-col l2 m4 s12">
									<label class="ats-label">&nbsp;</label>
									<div class="form-group">
										<span class="w3-button w3-blue w3-block" onclick="editaGolf({{$produto->key}},{{$key}})">Edit</span>
									</div>
								</div>
							@endif
					</div>
				</ul>
				<ul>
				<script type="text/javascript">
					data='#datatransfer{{$key}}';
					hora='#horatransfer{{$key}}';

					$('.datetimepickertransfer{{$key}}').datetimepicker({
					                widgetParent: data,
					                format: 'DD/MM/YYYY',
					                ignoreReadonly: true,


					            });

					            $('.datetimepickerstransfer{{$key}}').datetimepicker({
					               widgetParent: hora,
					               format: 'HH:mm',
					               ignoreReadonly: true,

					            });
				</script>
					<div class="w3-row-padding">
							@if(isset($array['pickuptransfer']))

								  <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Pick-up :</label>
										            <div class="form-group">


										                    <input value="{{$array['pickuptransfer']}}" type="text" class="w3-block form-control ats-border-color" id="pickuptransfer{{$key}}" placeholder="Pick-up">



										            </div>
										      </div>

							@endif

							@if(isset($array['datatransfer']))


								 <div class="w3-col l2 m5 s12">
									        <label class="ats-label">Date</label>
									            <div class="form-group">
									                <div class="input-group date datetimepickertransfer{{$key}}">
									                <div style="width: 330px; position: absolute;" ></div>
									                    <input value="{{$array['datatransfer']}}" id="datatransfer{{$key}}" type="text"  class="form-control ats-border-color" name="datatransfer" placeholder="Date">
									                    <span class="input-group-addon ats-border-color">
									                        <span class="w3-large ats-text-color fa fa-calendar"></span>
									                    </span>
									                </div>
									            </div>
									      </div>
							@endif

							@if(isset($array['horatransfer']))

								  <div class="w3-col l1 m5 s12">
							        <label class="ats-label">Hour</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickerstransfer{{$key}}">
							                <div style="width: 330px; position: absolute;" id="horatransfer{{$key}}"></div>
							                    <input value="{{$array['horatransfer']}}" type="text"  class="form-control ats-border-color" name="horatransfer" placeholder="Hour">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-clock-o"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['flighttransfer']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Flight Number :</label>
										            <div class="form-group">


										                    <input value="{{$array['flighttransfer']}}" type="text" class="w3-block form-control ats-border-color" id="flighttransfer{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['adultstransfer']))

								 <div class="w3-col l1 m4 s12">
										        <label class="ats-label">Adults :</label>
										            <div class="form-group">


										                    <input id="adultstransfer{{$key}}" value="{{$array['adultstransfer']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
							   </div>
							@endif

							@if(isset($array['babiestransfer']))

								 <div class="w3-col l1 m4 s12">
										        <label class="ats-label">Babies :</label>
										            <div class="form-group">


										                    <input id="babiestransfer{{$key}}" value="{{$array['babiestransfer']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
							   </div>
							@endif

							@if(isset($array['childrenstransfer']))

								 <div class="w3-col l1 m4 s12">
										        <label class="ats-label">Childres :</label>
										            <div class="form-group">


										                    <input id="childrenstransfer{{$key}}" value="{{$array['childrenstransfer']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
							   </div>

							   <div class="w3-col l1 m4 s12">
									<label class="ats-label">&nbsp;</label>
									<div class="form-group">
										<span class="w3-button w3-blue w3-block" onclick="editaTransfer({{$produto->key}},{{$key}})">Edit</span>
									</div>
								</div>
							@endif

							@if(isset($array['dropofftransfer']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Drop-off :</label>
										            <div class="form-group">


										                    <input value="{{$array['dropofftransfer']}}" type="text" class="w3-block form-control ats-border-color" id="dropofftransfer{{$key}}" placeholder="Pick-up">



										            </div>
										      </div>


							@endif

					</div>
				</ul>
				<ul>
					<div class="w3-row-padding">
							@if(isset($array['pickupcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Pick-up Location :</label>
										            <div class="form-group">


										                    <input value="{{$array['pickupcar']}}" type="text" class="w3-block form-control ats-border-color" id="pickupcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>

							@endif

							@if(isset($array['pickupdatacar']))


								 <div class="w3-col l2 m4 s12">
							        <label class="ats-label">Pick-up Date :</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickercarup1">
							                <div style="width: 330px; position: absolute;" id="datacarup1"></div>
							                    <input value="{{$array['pickupdatacar']}}" type="text" readonly class="form-control ats-border-color" name="pickupdatacar" id="pickupdatacar{{$key}}" placeholder="Pick-up Date">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['pickuphoracar']))


								 <div class="w3-col l2 m4 s6">
							        <label class="ats-label">Pick-up Hour :</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickerscarup1">
							                <div style="width: 330px; position: absolute;" id="horacarup1"></div>
							                    <input value="{{$array['pickuphoracar']}}" type="text" readonly class="form-control ats-border-color" name="pickuphoracar" id="pickuphoracar{{$key}}" placeholder="Pick-up Hour">
							                   <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-clock-o"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['pickupflightcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Flight Nº:</label>
										            <div class="form-group">


										                    <input value="{{$array['pickupflightcar']}}" type="text" class="w3-block form-control ats-border-color" id="pickupflightcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['pickupcountrycar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Country Origin :</label>
										            <div class="form-group">


										                    <input value="{{$array['pickupcountrycar']}}" type="text" class="w3-block form-control ats-border-color" id="pickupcountrycar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['pickupairportcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Airport :</label>
										            <div class="form-group">


										                    <input value="{{$array['pickupairportcar']}}" type="text" class="w3-block form-control ats-border-color" id="pickupairportcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['dropoffcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Drop Off Location :</label>
										            <div class="form-group">


										                    <input value="{{$array['dropoffcar']}}" type="text" class="w3-block form-control ats-border-color" id="dropoffcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['dropoffdatacar']))


								 <div class="w3-col l2 m4 s12">
							        <label class="ats-label">Drop Off Date</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickercaroff1">
							                <div style="width: 330px; position: absolute;" id="datacaroff1"></div>
							                    <input value="{{$array['dropoffdatacar']}}" type="text" readonly class="form-control ats-border-color" name="dropoffdatacar" id="dropoffdatacar{{$key}}" placeholder="Drop Off Date">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
							        </div>

							@endif

							@if(isset($array['dropoffhoracar']))


								 <div class="w3-col l2 m4 s6">
							        <label class="ats-label">Drop Off Hour</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickerscaroff1">
							                <div style="width: 330px; position: absolute;" id="horacaroff1"></div>
							                    <input value="{{$array['dropoffhoracar']}}" id="dropoffhoracar{{$key}}" type="text" readonly class="form-control ats-border-color" name="dropoffhoracar" placeholder="Drop Off Hour">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-clock-o"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['dropoffflightcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Flight Nº :</label>
										            <div class="form-group">


										                    <input value="{{$array['dropoffflightcar']}}" type="text" class="w3-block form-control ats-border-color" id="dropoffflightcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['dropoffcountrycar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Country Origin :</label>
										            <div class="form-group">


										                    <input value="{{$array['dropoffcountrycar']}}" type="text" class="w3-block form-control ats-border-color" id="dropoffcountrycar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

							@if(isset($array['dropoffairportcar']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Airport :</label>
										            <div class="form-group">


										                    <input value="{{$array['dropoffairportcar']}}" type="text" class="w3-block form-control ats-border-color" id="dropoffairportcar{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>

										      <div class="w3-col l2 m4 s12">
									<label class="ats-label">&nbsp;</label>
									<div class="form-group">
										<span class="w3-button w3-blue w3-block" onclick="editaCar({{$produto->key}},{{$key}})">Edit</span>
									</div>
								</div>
							@endif
							@if(isset($array['group']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Group :</label>
										            <div class="form-group">


										                    <input value="{{$array['group']}}" type="text" class="w3-block form-control ats-border-color" id="group{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif
							@if(isset($array['model']))

								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Model :</label>
										            <div class="form-group">


										                    <input value="{{$array['model']}}" type="text" class="w3-block form-control ats-border-color" id="model{{$key}}" placeholder="Flight Number">



										            </div>
										      </div>
							@endif

					</div>
				</ul>
				<ul>
				<script type="text/javascript">
					data='#dataticket{{$key}}';
					hora='#horaticket{{$key}}';

					$('.datetimepickerticket{{$key}}').datetimepicker({
					                widgetParent: data,
					                format: 'DD/MM/YYYY',
					                ignoreReadonly: true,


					            });

					            $('.datetimepickersticket{{$key}}').datetimepicker({
					               widgetParent: hora,
					               format: 'HH:mm',
					               ignoreReadonly: true,

					            });
				</script>
					<div class="w3-row-padding">
							@if(isset($array['dataticket']))

								 <div class="w3-col l2 m5 s12">
							        <label class="ats-label">Date</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickerticket{{$key}}">
							                <div style="width: 330px; position: absolute;"></div>
							                    <input value="{{$array['dataticket']}}" id="dataticket{{$key}}" type="text" readonly class="form-control ats-border-color" name="dataticket" placeholder="Date">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['horaticket']))


								  <div class="w3-col l2 m5 s12">
							        <label class="ats-label">Hour</label>
							            <div class="form-group">
							                <div class="input-group date datetimepickersticket{{$key}}">
							                <div style="width: 330px; position: absolute;"></div>
							                    <input value="{{$array['horaticket']}}" id="horaticket{{$key}}" type="text" readonly class="form-control ats-border-color" name="horaticket" placeholder="Hour">
							                    <span class="input-group-addon ats-border-color">
							                        <span class="w3-large ats-text-color fa fa-clock-o"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							@endif

							@if(isset($array['adultsticket']))


								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Adults :</label>
										            <div class="form-group">


										                    <input id="adultsticket{{$key}}" value="{{$array['adultsticket']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
										            </div>
							@endif

							@if(isset($array['childrensticket']))


								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Childres :</label>
										            <div class="form-group">


										                    <input id="childrensticket{{$key}}" value="{{$array['childrensticket']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
										            </div>
							@endif

							@if(isset($array['babiesticket']))


								 <div class="w3-col l2 m4 s12">
										        <label class="ats-label">Babies :</label>
										            <div class="form-group">


										                    <input id="babiesticket{{$key}}" value="{{$array['babiesticket']}}" type="number" class=" w3-block form-control ats-border-color"  placeholder="Type">



										            </div>
										            </div>

										            <div class="w3-col l2 m4 s12">
									<label class="ats-label">&nbsp;</label>
									<div class="form-group">
										<span class="w3-button w3-blue w3-block" onclick="editaTicket({{$produto->key}},{{$key}})">Edit</span>
									</div>
								</div>
							@endif

					</div>
				</ul>
				</div>
			@endforeach
		</ul>
		</li>
	</ul>
</div>




<script>
function accord(id) {
    var x = document.getElementById(id);

    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>
