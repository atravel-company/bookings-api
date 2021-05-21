
@extends('Admin.layouts.app')

@section('content')

<style type="text/css">

.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}

.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 50px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}


th,td:first-child { width: 33% ;}

</style>


<div class="w3-container">



	<h1>Extras</h1>





@if (Auth::check())





@foreach($extras as $extra)
	<table class="table table-striped table-bordered table-hover w3-container">

		<thead>
			<tr>

				<th>Name</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
		</thead>

		<tbody>



				<tr>

					<td>{{ $extra->name }}</td>
					<td>{{ $extra->description }}</td>
					<td>
					@hasrole('edita')
					<a href="{{ route('extras.edit',['id'=>$extra->id]) }}" class="btn btn-info btn-xs">Edit</a>&nbsp&nbsp&nbsp
					@else
						Not have permission!
					@endhasallroles



			@hasrole('apaga')
					<a href="{{ route('extras.destroy',['id'=>$extra->id]) }}" class="btn btn-danger btn-xs">Delete</a>
					@else
						Not have permission!
					@endhasallroles
					</td>
				</tr>


		</tbody>

	</table>


<!--................................................MODAL.................................................-->


<div id="pdfcreatemodalBrochures{{$extra->id}}"  class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('pdfcreatemodalBrochures{{ $extra->id }}').style.display='none';" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Create Brochures PDFs</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="bankcreateerror"></div>

      {!! Form::open([ 'class'=>'w3-container']) !!}
       <div class="w3-section">
       {!! Form::label('upload', 'Upload:') !!}
       {{Form::file('path_pdfBrochures',['id'=>'path_pdfBrochures'.$extra->id,'onchange'=>"sendpdf($extra->id ,'Brochures')"])}}


       {!! Form::label('title', 'Title:') !!}
       {!! Form::text('Titlte', null, ['id'=>'titleBrochures'.$extra->id, 'class'=>'w3-input w3-border w3-margin-bottom']) !!}




    {!! Form::submit('Create', ['class'=>'w3-button w3-block w3-green w3-section w3-padding']) !!}
  </div>
      {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>


  <!--................................................MODAL.................................................-->

<!--................................................MODAL.................................................-->


<div id="pdfcreatemodalCampaigns{{$extra->id}}"  class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('pdfcreatemodalCampaigns{{ $extra->id }}').style.display='none';" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Create Campaigns PDFs</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="bankcreateerror"></div>

      {!! Form::open([ 'class'=>'w3-container']) !!}
       <div class="w3-section">
       {!! Form::label('upload', 'Upload:') !!}
       {{Form::file('path_pdfCampaigns',['id'=>'path_pdfCampaigns'.$extra->id,'onchange'=>"sendpdf($extra->id ,'Campaigns')"])}}


       {!! Form::label('title', 'Title:') !!}
       {!! Form::text('Titlte', null, ['id'=>'titleCampaigns'.$extra->id, 'class'=>'w3-input w3-border w3-margin-bottom']) !!}




    {!! Form::submit('Create', ['class'=>'w3-button w3-block w3-green w3-section w3-padding']) !!}
  </div>
      {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>


  <!--................................................MODAL.................................................-->





	@endforeach
    @hasrole('cria')
	<a href="{{ route('extras.create') }}" class="btn btn-success  btn-xs">Create</a>
	@else
		Not have permission!
	@endhasallroles




@else






<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue</h1>
            <div class="account-wall">
                <img class="profile-img center-block" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                    <br/>
                  {!! Form::open(['url'=>'enter', 'class'=>'form-signin']) !!}
                  <div class="form-group">

					{!! Form::text('login', null, ['class'=>'form-control', 'placeholder'=>'Email']) !!}

				  </div>
				  <div class="form-group">
				  {!! Form::password('Password', ['class'=>'form-control', 'placeholder'=>'Password']) !!}
				  </div>
				  <div class="form-group">
					{!! Form::submit('Sign in', ['class'=>'btn btn-lg btn-primary btn-block']) !!}
				  </div>

               		<div class="form-group">
              		{!! Form::checkbox('remember', 'remember-me', ['class'=>'checkbox pull-left']); !!}
              		{!! Form::label('remember', 'Remember me') !!}
              		</div>

                {!! Form::close() !!}


            </div>

        </div>
    </div>
</div>







@endif





</div>

@endsection
