<?php

namespace App\Http\Controllers;

use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
        $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
        $datos= json_encode($result);
        $sub = substr($datos, 10,-3);
        return view('user.index', compact('users','sub'));
    }

    public function usuarios()
    {
      $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $head = "Lista de Usuarios";
          $sub = "usuarioNuevo";
          $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
          $roles = DB::table('roles')->where('id','<>',1)->get();
          return view('user.usuarios', compact('users','sub','head','resultados','roles'));
    }

     public function asistentes()
    {   
        Auth::user()->id;
        $loggedUser=Auth::id();
        $result2 =  DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('users.id', '=', $loggedUser)->value('role_id');
        if($result2 != 3){
          $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $head = "Lista de Asistentes";
          $sub = substr($datos, 10,-3);
          return view('user.asistente', compact('users','sub','head'));
        }else{
          $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->where('users.id',Auth::user()->id)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $sub = substr($datos, 10,-3);
          $head = "Asistente";
          return view('user.asistente', compact('users','sub','head'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($idRole)
    {
        $roles = Role::get();
        return view('user.create',compact('roles','idRole'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if($request['idRole'] == 'doctor'){
            if(is_null($request['nombre1']) or is_null($request['apellido1']) or $request['numeroJunta'] == 'JVPO-' or is_null($request['email'])){
                return redirect()->route('user.create',$request->idRole)
                ->with('error', 'Complete los Campos Obligatorios o digite correctamente el Numero de Junta')
                ->with('tipo', 'danger');
            }
        }else{
            if(is_null($request['nombre1']) or is_null($request['apellido1']) or is_null($request['email'])){ 
                return redirect()->route('user.create',$request->idRole)
                ->with('error', 'Complete los Campos Obligatorios')
                ->with('tipo', 'danger');
            }
        }
        
        $numero = DB::table('users')->select('correlativo')->max('correlativo')+1;
        $user = new User();
        $user->correlativo = $numero;
        $user->nombre1 = $request->nombre1;
        $user->apellido1 = $request->apellido1;
        $user->name = $request->nombre1.".".$request->apellido1.$numero;
        $user->email = $request->email;
        $user->numeroJunta = $request->numeroJunta;
        $user->especialidad = $request->especialidad;
          /**generando password */
        $password=substr(md5(microtime()),1,6);
        if(!is_null($request['nombre2']))
            $user->nombre2 = $request->nombre2;
        if(!is_null($request['nombre3']))
            $user->nombre3 = $request->nombre3;
        if(!is_null($request['apellido2']))
            $user->apellido2 = $request->apellido2;
        $user->password = $password;
             //** enviando email, contraseña */
        Mail::send('email.paciente', ['user'=>$user], function ($m) use ($user) {
                $m->to($user->email,$user->nombre1);
                $m->subject('Contraseña y nombre de usuario');
                $m->from('clinicayekixpaki@gmail.com','YekixPaki');
        });

        $user->password =bcrypt($password);
        if($user->save()){
            if($request['role']=='doctor'){
                $user->roles()->sync(2);
                return redirect()->route('user.index')->with('info','Odontologo guardado con exito');
            }elseif ($request['role']=='asistente') {
                $user->roles()->sync(3);
                return redirect()->route('user.asistente')
                ->with('head',"Lista de Asistentes")
                ->with('info','Asistente guardado con exito');
            }
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user,$idRole)
    {      
        return view('user.show', compact('user','idRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$idRole)
    {   
        $user = User::find($id);
        $roles = Role::get();
        return view('user.edit', compact('user','roles','idRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {

        if($request['idRole'] == 'doctor'){
            if(is_null($request['nombre1']) or is_null($request['apellido1']) or $request['numeroJunta'] == 'JVPO-' or is_null($request['email']) ){
                return redirect()->route('user.edit',[$user->id, $request->role])
                ->with('error', 'Complete los Campos Obligatorios o digite correctamente el Numero de Junta')
                ->withInput($request->all())
                ->with('tipo', 'danger');
            }
        }else{
            if(is_null($request['nombre1']) or is_null($request['apellido1']) or is_null($request['email']) ){
                return redirect()->route('user.edit',[$user->id, $request->role])
                ->with('error', 'Complete los Campos Obligatorios')
                ->withInput($request->all())
                ->with('tipo', 'danger');
            }
        }

        $userAux = User::find($user->id);
        $userAux->nombre1     = $request->nombre1;
        $userAux->nombre2     = $request->nombre2;
        $userAux->nombre3     = $request->nombre3;
        $userAux->apellido1   = $request->apellido1;
        $userAux->apellido2   = $request->apellido2;
        $userAux->email       = $request->email;
        $userAux->numeroJunta = $request->numeroJunta;
        $userAux->especialidad = $request->especialidad;
        if($userAux->save()){
            if($request['role']=='doctor'){
                    return redirect()->route('user.index')->with('info','Odonotologo Actualizado con exito');
            }elseif ($request['role']=='asistente') {
                    $head = "Lista de Asistentes";
                    return redirect()->route('user.asistente')
                    ->with('head',$head)
                    ->with('info','Asistente Actualizado con exito');
                }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user,$idRole)
    {
        $user->delete();
        if($idRole == 'asistente'){
          $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $sub = substr($datos, 10,-3);
          $head = "Lista de Asistentes";
          return redirect()->route('user.asistente')
            ->with('users', $users)
            ->with('sub', $sub)
            ->with('head',$head)
            ->with('info','Asistente Eliminado con Exito')
            ->with('tipo', 'success');
        }elseif($idRole =='doctor'){
          $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $sub = substr($datos, 10,-3);
          return redirect()->route('user.index')
            ->with('users', $users)
            ->with('sub', $sub)
            ->with('info','Odontologo Eliminado con Exito')
            ->with('tipo', 'success');
        }else{
          $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
          $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
          $datos= json_encode($result);
          $head = "Lista de Usuarios";
          $sub = "usuarioNuevo";
          $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
          $roles = DB::table('roles')->where('id','<>',1)->get();
          return redirect()->route('user.usuario')
            ->with('users',$users)
            ->with('head',$head)
            ->with('sub', $sub)
            ->with('resultados',$resultados)
            ->with('roles', $roles)
            ->with('info','Usuario Eliminado con exito')
            ->with('tipo', 'success');
        }
       /* return redirect()->route('paciente.index')
            ->with('pacientes',$pacientes)
            ->with('head',$head)
            ->with('user', $user)
            ->with('info','Paciente Inhabilitado con exito')
            ->with('tipo', 'success');*/
    }

    public function revocarRol( User $user, $idRole){
         $user->roles()->sync(4);

         if($idRole=='doctor'){
                return redirect()->route('user.index')->with('info','Suspencion permisos con exito');
        }elseif ($idRole=='asistente') {
                return redirect()->route('user.asistente')
                ->with('head',"Lista de Asistentes")
                ->with('info','Suspencion de permisos con exito');
            }

    }

        public function search1(Request $request){

        if($request['buscador']!='Buscar Por...'){
            if($request['buscador'] == 'Nombre'){

                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->
                    where(  function ($query) use ($request) {
                        $query->where('nombre1','ILIKE',$request->buscar."%")
                              ->orWhere('nombre2','ILIKE',$request->buscar."%")
                              ->orWhere('nombre3','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
                else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','roles','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
            }elseif ($request['buscador'] == 'Apellido') {
               if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->
                    where(  function ($query) use ($request) {
                        $query->where('apellido1','ILIKE',$request->buscar."%")
                              ->orWhere('apellido2','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
                else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','roles','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
            }elseif ($request['buscador'] == 'No. de Junta') {
               if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->where('numeroJunta','ILIKE',$request->buscar."%")->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
                else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','roles','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
            }elseif ($request['buscador'] == 'Nombre de Usuario') {
                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->where('name','ILIKE',$request->buscar."%")->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    return view('user.index', compact('users','roles','sub'))
                           ->with('info', 'Busqueda Exitosa');
                }
            }else{
              $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
              $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
              $datos= json_encode($result);
              $sub = substr($datos, 10,-3);
              return view('user.index', compact('users','roles','sub'))
                     ->with('info', 'Busqueda Exitosa');
            }
          }else{
              $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 2)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
              $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
              $datos= json_encode($result);
              $sub = substr($datos, 10,-3);
              return view('user.index', compact('users','roles','sub'))
                     ->with('info', 'Busqueda Exitosa');
          }
}

    public function search2(Request $request) {
            if($request['buscador']!='Buscar Por...'){
              if($request['buscador'] == 'Nombre'){
                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->
                    where(  function ($query) use ($request) {
                        $query->where('nombre1','ILIKE',$request->buscar."%")
                              ->orWhere('nombre2','ILIKE',$request->buscar."%")
                              ->orWhere('nombre3','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }elseif ($request['buscador'] == 'Apellido') {
                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->
                    where(  function ($query) use ($request) {
                        $query->where('apellido1','ILIKE',$request->buscar."%")
                              ->orWhere('apellido2','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }elseif ($request['buscador'] == 'Nombre de Usuario') {
                  if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->where('name','ILIKE',$request->buscar."%")->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                  }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = substr($datos, 10,-3);
                    $head = "Lista de Asistentes";
                    return view('user.asistente', compact('users','sub','head'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }else{
              $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
              $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
              $datos= json_encode($result);
              $sub = substr($datos, 10,-3);
              $head = "Lista de Asistentes";
              return view('user.asistente', compact('users','sub','head'));
            }
          }else{
            $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
              $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
              $datos= json_encode($result);
              $sub = substr($datos, 10,-3);
              $head = "Lista de Asistentes";
              return view('user.asistente', compact('users','sub','head'));
          }
    }

    public function search3(Request $request) {
            if($request['buscador']!='Buscar Por...'){
              if($request['buscador'] == 'Nombre'){
                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->
                    where(  function ($query) use ($request) {
                        $query->where('nombre1','ILIKE',$request->buscar."%")
                              ->orWhere('nombre2','ILIKE',$request->buscar."%")
                              ->orWhere('nombre3','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }elseif ($request['buscador'] == 'Apellido') {
                if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->
                    where(  function ($query) use ($request) {
                        $query->where('apellido1','ILIKE',$request->buscar."%")
                              ->orWhere('apellido2','ILIKE',$request->buscar."%");
                    })->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }elseif ($request['buscador'] == 'Nombre de Usuario') {
                  if(!is_null($request['buscar'])){
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->where('name','ILIKE',$request->buscar."%")->select('users.id', 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                  }else{
                    $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.numeroJunta','users.name')->paginate();
                    $result = DB::table('roles')->where('slug','doctor')->select('slug')->get();
                    $datos= json_encode($result);
                    $sub = "usuarioNuevo";
                    $head = "Lista de Usuarios";
                    $roles = DB::table('roles')->where('id','<>',1)->get();
                    $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
                    return view('user.usuarios', compact('users','sub','head','resultados','roles'))
                           ->with('info', 'Busqueda Exitosa');
                }
              }else{
              $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
              $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
              $datos= json_encode($result);
              $sub = "usuarioNuevo";
              $head = "Lista de Usuarios";
              $roles = DB::table('roles')->where('id','<>',1)->get();
              $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
              return view('user.usuarios', compact('users','sub','head','resultados','roles'));
            }
          }else{
            $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
              $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
              $datos= json_encode($result);
              $sub = "usuarioNuevo";
              $head = "Lista de Usuarios";
              $roles = DB::table('roles')->where('id','<>',1)->get();
              $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
              return view('user.usuarios', compact('users','sub','head','resultados','roles'));
          }
    }


    public function grant(User $user,Request $request){
      if(!is_null($request->rol)){
        $user->roles()->sync($request->rol);
        $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
                $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
                $datos= json_encode($result);
                $sub = "usuarioNuevo";
                $head = "Lista de Usuarios";
                $roles = DB::table('roles')->where('id','<>',1)->get();
                $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
        return redirect()->route('user.usuario')
              ->with('users')
              ->with('sub')
              ->with('head')
              ->with('resultados')
              ->with('roles')
              ->with('info', 'Habilitado Correctamente');
      }else{
        $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', 1)->where('role_user.role_id', '<>', 2)->where('role_user.role_id', '<>', 3)->select('users.id' , 'users.nombre1','users.nombre2','users.nombre3','users.apellido1','users.apellido2','users.name')->paginate();
                $result = DB::table('roles')->where('slug','asistente')->select('slug')->get();
                $datos= json_encode($result);
                $sub = "usuarioNuevo";
                $head = "Lista de Usuarios";
                $roles = DB::table('roles')->where('id','<>',1)->get();
                $resultados = DB::table('role_user')->where('role_id','<>',1)->get();
        return redirect()->route('user.usuario')
              ->with('users')
              ->with('sub')
              ->with('head')
              ->with('resultados')
              ->with('roles')
              ->with('error', 'No se pudo habilitar el usuario elija una opcion');
      }
    }
}
