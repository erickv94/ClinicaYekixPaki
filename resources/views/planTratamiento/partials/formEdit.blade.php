<div class="row">
	{{ Form::hidden('events_id', $id,['class' => 'form-control'])  }}
	<div class="col-md-2">
		<div class="form-group">
			{{ Form::label('no_de_piezas', 'No. de Piezas') }}
			{{ Form::number('no_de_piezas', null, ['class' => 'form-control '.($errors->has('no_de_piezas')?'is-invalid':''), 'step' => '1', 'min'=>'0','max'=>'32','required'])}}
		</div>
		@if($errors->has('no_de_piezas'))
		<div class="form-control-feedback text-danger">
			{{$errors->first('no_de_piezas')}}
		</div>		
		@endif
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			{{ Form::label('honorarios', 'Honorarios') }}
			{{ Form::number('honorarios', null, ['class' => 'form-control '.($errors->has('honorarios')?'is-invalid':''), 'step' => '0.01', 'min'=>'0','max'=>'1000000','required'])}}
		</div>
		@if($errors->has('honorarios'))
		<div class="form-control-feedback text-danger">
			{{$errors->first('honorarios')}}
		</div>		
		@endif
	</div>
</div>
         

<div class="row pt-3">
	<div class="col-md-4">
		*Campos obligatorios
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{{ Form::submit('Guardar', ['class' => 'btn btn-block btn-lg btn-success']) }}
		</div>
	</div>
</div>