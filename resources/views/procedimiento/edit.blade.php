@extends('layouts.base')

@section('bread')
<li class="breadcrumb-item">
  <a href="/procedimiento/">Procedimientos</a>
</li>
<li class="breadcrumb-item">
  <a class="breadcrumb-item active">Editar Procedimiento</a>
</li>
@endsection

@section('content')
	<div>
		<div class="row">
			<div class="col-md-12 col-md-offset-2">
				<div class="card card-default">
					<div class="card-header text-center">
					<div class="row">
							<div class="col-md-2 col-sm-12">
								<a href="{{ route('procedimiento.index') }}" class="btn btn-block btn-secondary" style="width: 100%">
								<i class="fa fa-arrow-circle-left"></i> Atrás</a>
							</div>
							<div class="col-md-10">
								<h4>Editar procedimiento</h4>
							</div>
						</div>
					</div>
					<div class="card-body">
						{!! Form::model($procedimiento, ['route' => ['procedimiento.update', $procedimiento->id], 'method' => 'PUT' , 'autocomplete'=> 'off']) !!}

							@include('procedimiento.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection