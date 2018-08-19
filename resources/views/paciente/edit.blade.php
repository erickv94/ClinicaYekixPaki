@extends('layouts.base')

@section('content')
	<div>
		<div class="row">
			<div class="col-md-12 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
					Paciente
					</div>
					<div class="panel-body">
						{!! Form::model($paciente, ['route' => ['paciente.update', $paciente->id], 'method' => 'PUT']) !!}
							@include('paciente.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection