@extends('layouts.base')

@section('bread')
<li class="breadcrumb-item">
  <a href="/paciente">Paciente</a>
</li>

<li class="breadcrumb-item">
  <a class="breadcrumb-item active">Detalle Paciente</a>
</li>

@endsection

@section('content')
	<div>
		<div class="row">
			<div class="col-md-12 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading" align="center">
					<h3>Datos del Paciente</h3>
					</div>
					<div class="panel-body">
						{!! Form::model($paciente, ['route' => ['paciente.update', $paciente->id]]) !!}
						<div class="row">
							<div class="col-md-2">
								{{ Form::label('expediente', 'No. Expediente') }}
								{{ Form::text('expediente', null, ['class' => 'form-control','disabled' ])}}
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									{{ Form::label('nombre1', 'Primer nombre') }}
									{{ Form::text('nombre1', null, ['class' => 'form-control', 'disabled'])}}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{{ Form::label('nombre2', 'Segundo nombre') }}
									{{ Form::text('nombre2', null, ['class' => 'form-control', 'disabled'])}}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{{ Form::label('nombre3', 'Tercer nombre') }}
									{{ Form::text('nombre3', null, ['class' => 'form-control', 'disabled'])}}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{{ Form::label('apellido1', 'Primer apellido') }}
									{{ Form::text('apellido1', null, ['class' => 'form-control', 'disabled'])}}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{{ Form::label('apellido2', 'Segundo apellido') }}
									{{ Form::text('apellido2', null, ['class' => 'form-control', 'disabled'])}}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-2">
								{{ Form::label('fechaNacimiento', 'Fecha de nacimiento') }}
								{{ Form::date('fechaNacimiento', \Carbon\Carbon::now(), ['class' => 'form-control', 'type'=>'date', 'style'=>'height: 38px', 'disabled']) }}
							</div>
							<div class="col-md-2">
								{{ Form::label('telefono', 'Telefono') }}
								{{ Form::text('telefono', null, ['class'=>'form-control', 'disabled']) }}
							</div>
							<div class="col-md-1">
								{{ Form::label('sexo', 'Sexo') }}
								{{ Form::text('sexo', null, ['class'=>'form-control', 'disabled']) }}
							</div>
							<div class="col-md-1">
							</div>
							<div class="col-md-3">
								{{ Form::label('email', 'Correo Electronico') }}
								{{ Form::email('email', null, ['class'=>'form-control', 'disabled']) }}
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								{{ Form::label('domicilio', 'Domicilio') }}
								{{ Form::text('domicilio', null, ['class'=>'form-control', 'disabled']) }}
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								{{ Form::label('direccion_de_trabajo', 'Direccion de trabajo') }}
								{{ Form::text('direccion_de_trabajo', null, ['class'=>'form-control', 'disabled']) }}
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								{{ Form::label('ocupacion', 'Ocupacion') }}
								{{ Form::text('ocupacion', null, ['class' => 'form-control', 'disabled']) }}
							</div>
							<div class="col-md-8">
								{{ Form::label('responsable', 'Responsable') }}
								{{ Form::text('responsable', null, ['class'=>'form-control', 'disabled']) }}
							</div>
						</div>
						<br/>
						<a href="{{ route('paciente.agenda', $paciente->id) }}" class="btn btn-sm btn-default bg-info" style="color: white">Agendar Cita
					</a>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection