<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Procedimiento;
use App\Events;
use App\Paciente;
use Calendar;
use Validator;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pacientes = Paciente::paginate();  
        return view('paciente.index', compact("pacientes"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('paciente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $valores = $request->all();
        //Verificando si estan todos los campos obligatorios
        if(is_null($valores['nombre1']) or is_null($valores['apellido1']) or is_null($valores['fechaNacimiento']) or is_null($valores['telefono']) or is_null($valores['sexo'])
            or is_null($valores['domicilio']) or is_null($valores['ocupacion'])){

            return redirect()->route('paciente.create')
                ->with('info', 'Complete los campos obligatorios')
                ->with('tipo', 'danger');
        }

        $paciente = new Paciente();
        $paciente->nombre1               = $request->nombre1;
        $apellido                        = $request->apellido1;
        $inicio                          = strtoupper($request->apellido1[0]);
        $apellido[0]                     =$inicio;
        $inicio                          =$inicio."%";
        $string = "SELECT expediente FROM pacientes WHERE expediente LIKE '".$inicio."' AND id IN (SELECT MAX(id) FROM pacientes WHERE expediente LIKE '".$inicio."')";
        $query                           = DB::select( DB::raw($string));
        if($query != NULL){
            foreach ($query as $key => $value) {
            $paciente->expediente        =$apellido[0]. strval((int) substr($value->expediente,1)+1);
            }
        }else{
            $paciente->expediente        = $apellido[0]."1";
        }

        $paciente->apellido1             = $apellido;
        $paciente->fechaNacimiento       = $request->fechaNacimiento;
        $paciente->telefono              = $request->telefono;
        $paciente->sexo                  = $request->sexo;
        $paciente->domicilio             = $request->domicilio;
        $paciente->ocupacion             = $request->ocupacion;

        //campos opcionales
        if(!is_null($valores['direccion_de_trabajo']))
            $paciente->direccion_de_trabajo = $request->direccion_de_trabajo;
        if(!is_null($valores['responsable']))
            $paciente->responsable = $request->responsable;
        if(!is_null($valores['email']))
            $paciente->email = $request->email;
        if(!is_null($valores['nombre2']))
            $paciente->nombre2 = $request->nombre2;
        if(!is_null($valores['nombre3']))
            $paciente->nombre3 = $request->nombre3;
         if(!is_null($valores['apellido2']))
            $paciente->apellido2 = $request->apellido2;
        if($paciente->save()){
            return redirect()->route('paciente.index',$paciente->id)
                ->with('info','Paciente guardado con exito')
                ->with('tipo', 'success');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {

        return view('paciente.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function edit(Paciente $paciente)
    {
        //dd(compact('paciente'));
        return view('paciente.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        $valores = $request->all();
        //Verificando si estan todos los campos obligatorios
        if(is_null($valores['nombre1']) or is_null($valores['nombre2']) or is_null($valores['apellido1']) or is_null($valores['apellido2'])
            or is_null($valores['fechaNacimiento']) or is_null($valores['telefono']) or is_null($valores['sexo'])
            or is_null($valores['domicilio']) or is_null($valores['ocupacion'])){

            return redirect()
                    ->route('paciente.edit')
                    ->with('info', 'Complete los campos obligatorios')
                    ->with('tipo', 'danger');
        }

        if(is_null($request['direccion_de_trabajo']))
            $request['direccion_de_trabajo'] = "Sin direccion de trabajo";
        if(is_null($request['responsable']))
            $request['responsable'] = "Sin responsable";
        if(is_null($request['email']))
            $request['email'] = "Sin correo electronico";
        
        $paciente->update($request->all());
        return redirect()->route('paciente.edit',$paciente->id)
            ->with('info','Paciente actualizado con exito')
            ->with('tipo', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return back()
            ->with('info','Eliminado Correctamente')
            ->with('tipo', 'success');
    }

    public function agendar(Paciente $paciente)
    {
        $procedimiento = Procedimiento::pluck('nombre', 'id')->toArray();

        $events = Events::select('id','paciente_id','start_date','end_date','procedimiento_id','descripcion')->where('paciente_id',$paciente->id)->get();
        $event_list= [];
        foreach ($events as $key => $event) {
            $proceso = Procedimiento::find($event->procedimiento_id);
            $paciente = Paciente::find($event->paciente_id);
            $event_list[] =Calendar::event(
                $paciente->nombre1." ".$paciente->nombre2." ".$paciente->apellido1." ".$paciente->apellido2,
                false,
                new \DateTime($event->start_date),
                new \DateTime($event->end_date),
                $event->id,
                [
                'color'             => $proceso->color,
                'descripcion'       => $event->descripcion,
                'textColor'         => $event->textcolor,
                'procedimiento'     => $proceso->id,
                'durationEditable'  => false,
                ]
            );
        }
        
        

        $calendar_details = Calendar::addEvents($event_list)->setOptions([
            'firstDay'      => 1,
            'editable'      => true,
            'themeSystem'   => 'bootstrap4',
            'locale'        => 'es',
            'header'        => array(
                        'left' => 'prev,next today', 
                      'center' => 'title', 
                       'right' => 'month,agendaWeek,agendaDay'
            ),

            ])->setCallbacks([
            'dayClick' => 'function(date,jsEvent,view){
                    $("#btnAgregar").prop("disabled",false);
                    $("#btnEliminar").prop("disabled",true);
                    $("#btnModificar").prop("disabled",true);
                    $("#tit").hide();
                    $("#txtTitulo").hide();
                    limpiarFormulario();
                    $("#txtFecha").val(date.format());
                    var horaInicio=String(date).substring(16,24);
                    if(horaInicio == "00:00:00"){
                    }else{
                           $("#start_date").val(horaInicio);
                    }
                    $("#exampleModal").modal();
                }',


            'eventClick' => 'function(calEvent,jsEvent,view){
                    $("#btnAgregar").prop("disabled",true);
                    $("#btnEliminar").prop("disabled",false);
                    $("#btnModificar").prop("disabled",false);
                    $("#txtDescripcion").val(calEvent.descripcion);
                    $("#txtTitulo").val(calEvent.title);
                    $("#txtID").val(calEvent.id);
                    $("#txtColor").val(calEvent.color);
                    FechaHora= calEvent.start._i.split("T");
                    horaInicio=FechaHora[1].split("+");
                    FechaHora2= calEvent.end._i.split("T");
                    horaFin=FechaHora2[1].split("+");
                    $("#txtFecha").val(FechaHora[0]);
                    $("#start_date").val(horaInicio[0]);
                    $("#end_date").val(horaFin[0]);
                    $("#procedimiento_id").val(calEvent.procedimiento);
                    $("#exampleModal").modal();     
                 }',

            'eventDrop' => 'function(calEvent,jsEvent,view){
                    $("#txtID").val(calEvent.id);
                    $("#txtTitulo").val(calEvent.title);
                    $("#txtColor").val(calEvent.color);
                    $("#txtDescripcion").val(calEvent.descripcion);
                    var fechaHora   = calEvent.start.format().split("T");
                    var fechaHora2  = calEvent.end.format().split("T");
                    $("#txtFecha").val(fechaHora[0]);
                    $("#start_date").val(fechaHora[1]);
                    $("#end_date").val(fechaHora2[1]);
                    $("#procedimiento_id").val(calEvent.procedimiento);
                    document.getElementById("btnModificar").click();
                 }',

            ]);

        return view('paciente.agenda',compact('procedimiento','calendar_details','paciente'));
    }

    public function addEvent(Request $request){
        $validator = Validator::make($request->all(), [
            'pacienteID'        => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required',
            'procedimiento_id'  => 'required' 
        ]);
        if($validator->fails()){
            \Session::flash('warnning', 'Porfavor ingrese datos validos');
            return redirect()->route('paciente.agenda',$request->pacienteID)->withInput()->withErrors($validator);
        }
        if(isset($_POST["btnAgregar"])){
        $event = new Events();
        $event->paciente_id         = $request['pacienteID'];
        if(strpos($request["txtFecha"], "T")){
            $event->start_date          = str_replace("T", " ", $request['txtFecha']);
            $str = substr($request['txtFecha'],0,-8);
            $event->end_date            = $str." ".$request['end_date'];
        }else{
            $event->start_date          = $request['txtFecha']." ".$request['start_date'];
            $event->end_date            = $request['txtFecha']." ".$request['end_date'];
        }
        $event->procedimiento_id    = $request['procedimiento_id'];
        $event->descripcion         = $request['txtDescripcion'];
        $event->save();
        \Session::flash('success','Cita añadida exitosamente');
        return redirect()->route('paciente.agenda',$request->pacienteID)->with('info','Cita guardada con exito');

        }elseif (isset($_POST["btnModificar"])) {
            $event = Events::find($request["txtID"]);
            $event->paciente_id         = $request['pacienteID'];
            $event->start_date          = $request['txtFecha']." ".$request['start_date'];
            $event->end_date            = $request['txtFecha']." ".$request['end_date'];
            $event->procedimiento_id    = $request['procedimiento_id'];
            $event->save();
            return redirect()->route('paciente.agenda',$request->pacienteID)->with('info','Cita actualizada con exito');
        }elseif (isset($_POST['btnEliminar'])) {
            $event = Events::find($request["txtID"]);
            $event->delete();
            return redirect()->route('paciente.agenda',$request->pacienteID)->with('info','Cita eliminada con exito');

        }

        
    }

}
