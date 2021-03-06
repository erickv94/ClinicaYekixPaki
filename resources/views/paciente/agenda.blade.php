@extends('layouts.base')

@section('javascript')
<script>
$(document).ready(function(){
    $("#cosa").click(function(){
        document.getElementById('procedimiento').style.visibility = 'visible';
        document.getElementById('procedimiento_id').style.visibility = 'visible';
        document.getElementById('no_de_piezas1').style.display = 'inline';
        document.getElementById('no_de_piezas2').style.display = 'inline';
        document.getElementById('honorarios1').style.display = 'inline';
        document.getElementById('honorarios2').style.display = 'inline';
        $("#cosa2").prop('checked',false);
        $("#cosa").prop('disabled',true);
        $("#cosa2").prop('disabled',false);
    });
    $("#cosa2").click(function(){
        document.getElementById('procedimiento').style.visibility = 'hidden';
        document.getElementById('procedimiento_id').style.visibility = 'hidden';
        document.getElementById('no_de_piezas1').style.display = 'none';
        document.getElementById('no_de_piezas2').style.display = 'none';
        document.getElementById('honorarios1').style.display = 'none';
        document.getElementById('honorarios2').style.display = 'none';
        $("#cosa").prop('checked',false);
        $("#cosa2").prop('disabled',true);
        $("#cosa").prop('disabled',false);
        $("#procedimiento_id").val("");
        $("#no_de_piezas2").val("");
        $("#honorarios2").val("");
    });

    $("#cosa2").prop('checked',true);
    $("#cosa2").prop('disabled',true);
});
</script>
@endsection

@section('bread')
<li class="breadcrumb-item">
  <a href="/paciente">Paciente</a>
</li>

<li class="breadcrumb-item">
  <a href="/paciente/{{$paciente->id}}">Detalle Paciente</a>
</li>

<li class="breadcrumb-item">
  <a class="breadcrumb-item active">Citas</a>
</li>
@endsection

@section('javascript')
<link href="{{ asset('css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection

@section('content')
<!-- en caso de error -->
<div>
  <div class="row">
    <div class="col-md-12 pt-3">
      @if($errors->has('txtFecha'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('txtFecha')}} 
      </div>
      @elseif($errors->has('start_date'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('start_date')}} 
      </div>		
      @elseif($errors->has('end_date'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('end_date')}} 
      </div>
      @elseif($errors->has('RangoStartHora'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('RangoStartHora')}} 
      </div>
      @elseif($errors->has('RangoEndHora'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('RangoEndHora')}} 
      </div>
      @elseif($errors->has('RangoLibre'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('RangoLibre')}} 
      </div>
      @elseif($errors->has('notEqualFree'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('notEqualFree')}} 
      </div>
      @elseif($errors->has('horasFijas'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('horasFijas')}} 
      </div>
      @elseif($errors->has('choques'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('choques')}} 
      </div>
      @elseif($errors->has('minCita'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('minCita')}} 
      </div>
      @elseif($errors->has('maxCita'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('maxCita')}} 
      </div>
      @elseif($errors->has('notRangoFree'))
      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button><strong>Un problema!</strong> {{$errors->first('notRangoFree')}} 
      </div>
      @endif
    </div>
  </div>
</div>


<!--fin en caso de error-->
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">
          <div class="row">
              <div class="col-md-2 col-sm-12">
                <a href="/paciente/{{$paciente->id}}" class="btn btn-block btn-secondary" style="width: 100%">
                <i class="fa fa-arrow-circle-left"></i>Atrás</a>
              </div>
              <div class="col-md-8">
                <h3 align="center">Citas:</h3>
              </div>
          </div>
                  <div class="row">
            <div class="col-md-3">
              <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ModalX">
                  <i class="fa fa-list"></i>  Leyenda de Procedimientos
                </button>
              {!! Form::open() !!}
              <!-- Modal -->
              <div class="modal fade" data-backdrop="static" id="ModalX" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Identificador de Procedimientos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 150px);">
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Procedimiento</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td style="text-align: center; background: black;color: white; border-radius: 10px">
                                      Revision General
                                    </td>
                                  </tr>
                                  @foreach($procesos as $p)
                                  <tr>
                                      <td>{{$loop->iteration+1}}</td>
                                      <td style="text-align: center; background: {{$p->color}};color: white; border-radius: 10px">
                                        {{$p->nombre}}
                                      </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>
          <br />
			<div class="panel-body">
				{!! $calendar_details->calendar() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Modal -->
{!! Form::open(array('route' => 'paciente.add','id'=> 'form', 'method' => 'POST', 'autocomplete' => 'off') ) !!}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloEvento">Descripcion de la Cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"  style="overflow-y: auto; max-height: calc(100vh - 150px);">
      	<input type="hidden" name="txtID" id="txtID"/>
      	<input type="hidden" name="txtFecha" id="txtFecha"/>
        <input type="hidden" name="pacienteID" id="pacienteID" value="{{$paciente->id}}">
        <input type="hidden" name="encendido" id="encendido" value="{{$encendido}}">
        @can('pacientes.create')
        <a class="btn btn-primary" href="{{route('paciente.agenda2', $paciente->id)}}" target="_blank" id="cupos" name="cupos"><i class="fa fa-outdent"></i> Ver Cupos</a>
        @endcan
      	<div class="form-row">	
      		<div class="form-group col-md-12">
      			{!! Form::label('paciente_id', 'Paciente:',['id' => 'tit']) !!}
      			{!! Form::text('paciente_id', null, ['class' => 'form-control', 'placeholder' => 'Titulo del Evento', 'id' => 'txtTitulo']) !!}
      		</div>
      	</div>
        <div class="form-group">
          {!! Form::label('start_date','Hora Inicio de la Cita:')!!}
          <div class="input-group clockpicker " data-autoclose="true">
            {!! Form::text('start_date', null, ['class' => 'form-control','required']) !!}
            <span class="input-group-addon">
              <i class="btn btn-primary">
              <span class="fas fa-clock"></span>
              </i>
            </span>
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('end_date','Hora Fin de la Cita:')!!}
          <div class="input-group clockpicker " data-autoclose="true">
            {!! Form::text('end_date', null, ['class' => 'form-control','required']) !!}
            <span class="input-group-addon">
              <i class="btn btn-primary">
              <span class="fas fa-clock"></span>
              </i>
            </span>
          </div>
        </div>
      	<div class="form-group">
      		{!! Form::label('txtDescripcion', 'Descripcion:')!!}
      		{!! Form::textarea('txtDescripcion', null, ['class' => 'form-control', 'rows' => '2', 'id' => 'txtDescripcion'])!!}
      	</div>
        @can('pacientes.create')
          {{ Form::label('reprogramacion','¿Cita por reprogramacion?',['id'=>'labelReprogramacion']) }}
          <br />
          <div class="row">
            <div class="col-md-2">
              {{ Form::radio('reprogramacion', '1', false, ['id'=> 'siReprogramacion'] )}}
              <label for="siReprogramacion">Si</label>
            </div>
            <div class="col-md-2">
              {{ Form::radio('reprogramacion', '2', true, ['id'=> 'noReprogramacion'] )}}
              <label for="noReprogramacion">No</label>
            </div>
          </div>
          
        @endcan
        {!! Form::label('pregunta', 'Paciente con Procedimiento?',['style' => 'visibility:visible', 'id'=>'pregunta'])!!}
          <br/>
          <div class="row">
            <div class="col-md-2">
              <input type="checkbox" name="cosa" value="1" id="cosa" >
              {{ Form::label('radio', 'Si ', ['style' => 'visibility:visible','id' => 'radio']) }}
            </div>
            <div class="col-md-2">
              <input type="checkbox" name="cosa2" value="2" id="cosa2" >
              {{ Form::label('radio2', 'No ', ['style' => 'visibility:visible','id' => 'radio2']) }}
            </div>
          </div>
        <!-- EN EL SELECT VA LA VARIABLE PROCEDIMIENTO -->
          <div class="form-group">
            {!! Form::label('procedimiento_id', 'Procedimiento:',['style' => 'visibility:hidden', 'id' => 'procedimiento'])!!}
            {!! Form::select('procedimiento_id', $procedimiento, null, ['placeholder' => 'Elija un procedimiento', 'style' => 'visibility:hidden','id' =>'procedimiento_id','class' => 'form-control'])!!}
          </div>
          <div class="form-group">
              {{ Form::label('no_de_piezas', 'No. de Piezas',['id'=>'no_de_piezas1','style' => 'display:none']) }}
              {{ Form::number('no_de_piezas', null, ['class' => 'form-control', 'step' => '0.01', 'min'=>'0','max'=>'600','id'=>'no_de_piezas2', 'style' => 'display:none'])}}
          </div>
        @can('pacientes.create')
          <div class="form-group">
              {{ Form::label('honorarios', 'Honorarios:',['id'=>'honorarios1','style' => 'display:none']) }}
              {{ Form::number('honorarios', null, ['class' => 'form-control', 'step' => '0.10', 'min'=>'0','max'=>'600','id'=>'honorarios2','style' => 'display:none'])}}
          </div>
        @endcan
        <div class="row justify-content-between">
            <div class="col-md-3 col-sm-12 pr-md-1 mb-1 mb-md-0 text-center">
  		        {!! Form::submit('Añadir Cita', ['class' => 'btn btn-success btn-block','id' => 'btnAgregar', 'name' => 'btnAgregar','style'=>'width=100%']) !!}
            </div>
            <div class="col-md-3 col-sm-12 px-md-1 mb-1 mb-md-0 text-center">
  		        {!! Form::submit('Modificar Cita', ['class' => 'btn btn-success btn-block px-0','id' => 'btnModificar','name' => 'btnModificar','style'=>'width=100%']) !!}
            </div>
  		      <div class="col-md-3 col-sm-12 px-md-1 mb-1 mb-md-0 text-center">
              {!! Form::submit('Borrar', ['class' => 'btn btn-danger btn-block','id' => 'btnEliminar','name' => 'btnEliminar','style'=>'width=100%']) !!}
            </div>
            <div class="col-md-3 col-sm-12 pl-md-1 text-center">
              <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection


@section('calendar')
    {!! $calendar_details->script() !!}

    <script type="text/javascript">

	$('.clockpicker').clockpicker();

	function limpiarFormulario(){
		$('#txtID').val("");
		$('#txtDescripcion').val("");
		$('#txtTitulo').val("");
		$('#txtColor').val("");
		$('#start_date').val("");
		$('#end_date').val("");
		$('#procedimiento_id').val("");
    $('#no_de_piezas2').val("");
    $('#honorarios2').val("");
	}

</script>


    <!-- Page level plugin JavaScript-->
    <script src="{{ asset('js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js')}}"></script>
    <!-- Demo scripts for this page-->
    <script src="{{ asset('js/datatables-demo.js')}}"></script>
@endsection