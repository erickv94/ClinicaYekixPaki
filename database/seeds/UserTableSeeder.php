<?php

use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Users
    	User::create([
    		'name'           => 'Kimberly',
    		'email'          => 'clinicayekixpaki@gmail.com',
    		'password'       => bcrypt('admin'),
    		'remember_token' => 'qwertyuiop',
            'nombre1'        => 'Kimberly',
            'nombre2'        => 'Johanna',
            'apellido1'      => 'Amaya',
            'apellido2'      => 'Jimenez',
            'numeroJunta'    => 'JVPO-5028',
            'especialidad'   => 'Cirujano Dental',
    	]);

        

    	Role::create([
    		'name' 		     => 'Administrador',
    		'slug' 		     => 'admin',
            'description'    => 'Rol de Administrador',
    		'special' 	     => 'all-access',
    	]);
        Role::create([
            'name'           => 'Odontologo',
            'slug'           => 'doctor',
            'description'    => 'Rol de Odontologo',
        ]);
        Role::create([
            'name'           => 'Asistente',
            'slug'           => 'asistente',
            'description'    => 'Rol de Asistente',
        ]);

        Role::create([
            'name'           => 'Suspendido',
            'slug'           => 'suspendido',
            'description'    => 'Rol de Suspendido',
        ]);

        Role::create([
            'name'           => 'Paciente',
            'slug'           => 'paciente',
            'description'    => 'Rol de Paciente',
        ]);

        DB::table('role_user')->insert(['role_id' => '1','user_id'=>'1']);

        //Permisos de Dentista
        DB::table('permission_role')->insert(['permission_id' => '1' ,  'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '2' ,  'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '11' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '12' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '16' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '17' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '21' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '22' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '23' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '24' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '25' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '26' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '27' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '34' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '35' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '36' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '37' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '38' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '39' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '40' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '41' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '42' , 'role_id'=>'2']);
        DB::table('permission_role')->insert(['permission_id' => '43' , 'role_id'=>'2']);
        //email receta
        DB::table('permission_role')->insert(['permission_id' => '47' , 'role_id'=>'2']);

        //Permisos Asistente
        DB::table('permission_role')->insert(['permission_id' => '2' ,  'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '6' ,  'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '7' ,  'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '8' ,  'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '9' ,  'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '10' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '11' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '12' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '13' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '14' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '16' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '17' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '18' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '19' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '20' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '21' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '22' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '23' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '24' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '25' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '26' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '27' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '29' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '30' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '31' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '32' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '33' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '44' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '45' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '48' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '49' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '50' , 'role_id'=>'3']);
        DB::table('permission_role')->insert(['permission_id' => '51' , 'role_id'=>'3']);
        //Permisos Paciente
        DB::table('permission_role')->insert(['permission_id' => '16' , 'role_id'=>'5']);
        DB::table('permission_role')->insert(['permission_id' => '17' , 'role_id'=>'5']);
        DB::table('permission_role')->insert(['permission_id' => '21' , 'role_id'=>'5']);
        
    }
}
