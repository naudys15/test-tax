<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Clients;
use App\Models\Type_client;
use App\Models\Type_document_id;
use App\Models\Countries;
use App\Models\Provinces;
use App\Models\Cantons;
use App\Models\Districts;

class Users extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function trimAccent($text)
    {
		$text = preg_replace("/á|à|â|ã|ª/","a",$text);
		$text = preg_replace("/Á|À|Â|Ã/","A",$text);
		$text = preg_replace("/é|è|ê/","e",$text);
		$text = preg_replace("/É|È|Ê/","E",$text);
		$text = preg_replace("/í|ì|î/","i",$text);
		$text = preg_replace("/Í|Ì|Î/","I",$text);
		$text = preg_replace("/ó|ò|ô|õ|º/","o",$text);
		$text = preg_replace("/Ó|Ò|Ô|Õ/","O",$text);
		$text = preg_replace("/ú|ù|û/","u",$text);
		$text = preg_replace("/Ú|Ù|Û/","U",$text);
		$text = str_replace("ñ","n",$text);
		$text = str_replace("Ñ","N",$text);
        return $text;
    }

	public function getUserByUsername($username)
	{
		$user = Clients::where('clie_username', '=', $username)->first();
		if ($user != '') {
			return $user;
		}
	}

	public function getUser(Request $request, $user)
	{
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = Clients::find($user);
			if ($user != '') {
                $type_client = Type_client::where('tycl_id', '=', $user->tycl_id)->first();
                $type_document_id = Type_document_id::where('tydi_id', '=', $user->tydi_id)->first();
                $country = Countries::where('coun_id', '=', $user->coun_id)->first();
                $province = Provinces::where('prov_id', '=', $user->prov_id)->first();
                $canton = Cantons::where('cant_id', '=', $user->cant_id)->first();
                $district = Districts::where('dist_id', '=', $user->dist_id)->first();
				$data = [
					'id'                   => $user->clie_id,
					'firstname'            => $user->clie_firstname,
                    'lastname'             => $user->clie_lastname,
                    'phone_number'         => $user->clie_phonenumber,
					'email'                => $user->clie_email,
					'username'             => $user->clie_username,
                    'rol'                  => $type_client->tycl_title,
                    'dni'                  => $user->clie_dni,
                    'type_document_id'     => (isset($type_document_id->tydi_description))?$this->trimAccent($type_document_id->tydi_description):'',
                    'name_business'        => (isset($user->clie_business_name))?$user->clie_business_name:'',
                    'legal_dni'            => (isset($user->clie_legal_dni))?$user->clie_legal_dni:'',
                    'country'              => (isset($country->coun_description))?$country->coun_description:'',
                    'province'             => (isset($province->prov_description))?$province->prov_description:'',
                    'canton'               => (isset($canton->cant_description))?$canton->cant_description:'',
					'district'             => (isset($district->dist_description))?$district->dist_description:'',
					'address'              => (isset($user->clie_address))?$user->clie_address:'',
                ];
				return response()->json(['message' => $data, 'status' => 'Success'], 200);
			} else {
				return response()->json(['message' => 'No existe un usuario con el id insertado', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
		}
	}

	public function checkUser($user, $pass)
	{
		if ($user != '' && $pass != '') {
			$us = Clients::where('clie_username', '=', $user)->first();
			if ($us != '') {
				$comp = Hash::check($pass, $us->clie_password);
				if ($comp == true) {
					return 'Correcto';
				} else {
					return 'El usuario o la contraseña es incorrecta';
				} 
			} else {
				return 'El usuario o la contraseña es incorrecta';
			} 
		} elseif ($user == '' && $pass != '') {
			return 'El nombre de usuario es requerido';
			
		} elseif ($user != '' && $pass == '') {
			return 'La contraseña es requerida';
		}
    }

	public function login(Request $request)
	{
		$resp = $request->json()->all();
		$info = $resp['info'];
		$user = $info['user'];
		$password = $info['pass'];
		$check = $this->checkUser($user, $password);
		if ($check == 'Correcto') {
			$client = Clients::where('clie_username', '=', $user)->first();
			$type_client = Type_client::where('tycl_id', '=', $client->tycl_id)->first();
			$data = [
				'id'                   => $client->clie_id,
				'firstname'            => $client->clie_firstname,
				'lastname'             => $client->clie_lastname,
				'email'                => $client->clie_email,
				'username'             => $client->clie_username,
				'password'             => $client->clie_password,
				'rol'                  => $type_client->tycl_title,
			];
			return response()->json(['message' => 'Usuario logueado satisfactoriamente', 'user' => $data, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
		}
	}


	public function updateUser(Request $request, $user)
	{
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$resp = $request->json()->all();
			if (isset($resp['info'])) {
				$info = $resp['info'];
				$validate = [
					'clie_firstname'                => $info['firstname'], 
					'clie_lastname'                 => $info['lastname'], 
					'clie_email'                    => $info['email'], 
					'clie_username'                 => $info['username'], 
					'clie_password'                 => $info['password'],
                    'clie_password_confirmation'    => $info['password_confirmation'],
                    'clie_phonenumber'              => $info['phone_number'],
                    'tycl_id'                       => $info['role'],
                    'clie_dni'                      => $info['dni'],
                    'tydi_id'                       => $info['type_document_id'],
                    'clie_business_name'            => $info['name_business'],
                    'clie_legal_dni'                => $info['legal_dni'],
                    'coun_id'                       => $info['country'],
                    'prov_id'                       => $info['province'],
                    'cant_id'                       => $info['canton'],
					'dist_id'                       => $info['district'],
					'clie_address'                  => $info['address'],
				];
				$validar = $this->validationFields($validate, false, $user);
				if ($validar->fails()) {
					return response()->json(['message' => $validar->errors(), 'status' => 'Error'], 422);
				}
				$client = Clients::findOrFail($user);

				$client->clie_firstname = $info['firstname'];    
				$client->clie_lastname = $info['lastname']; 
				$client->clie_email = $info['email'];
				$client->clie_username = $info['username']; 
                $client->clie_password = Hash::make($info['password']);
                $client->clie_phonenumber = $info['phone_number'];
                $client->tycl_id = $info['role'];
                $client->clie_dni = $info['dni'];
                $client->tydi_id = $info['type_document_id'];
                $client->clie_business_name = $info['name_business'];
                $client->clie_legal_dni = $info['legal_dni'];
                $client->coun_id = $info['country'];
                $client->prov_id = $info['province'];
                $client->cant_id = $info['canton'];
                $client->dist_id = $info['district'];
                $client->clie_address = $info['address'];
				$client->save();
				return response()->json(['message' => 'El cliente fue actualizado satisfactoriamente', 'status' => 'Success'], 200);
			} else {
				return response()->json(['message' => 'Se debe enviar información para procesar la solicitud', 'status' => 'Error'], 200);
			}     
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
		}
	}

	public function register(Request $request)
	{
		try {
			$resp = $request->json()->all();
			$info = $resp['info'];
			$validate = [
				'clie_firstname'                => $info['firstname'], 
				'clie_lastname'                 => $info['lastname'], 
				'clie_email'                    => $info['email'], 
				'clie_username'                 => $info['username'], 
				'clie_password'                 => $info['password'],
				'clie_password_confirmation'    => $info['password_confirmation'],
			];

		    $validar = $this->validationFields($validate, true);

	    	if ($validar->fails()) {
	    		return response()->json(['message' => $validar->errors(), 'status' => 'Error'], 422);
			}
			
			$data = [
				'tycl_id'                       => 2,
				'clie_firstname'                => $info['firstname'], 
				'clie_lastname'                 => $info['lastname'], 
                'clie_email'                    => $info['email'], 
				'clie_username'                 => $info['username'], 
                'clie_password'                 => Hash::make($info['password']),
                'clie_phonenumber'              => '',
                'clie_dni'                      => 0,
                'tydi_id'                       => 1,
                'clie_business_name'            => '',
                'clie_legal_dni'                => 0,
                'coun_id'                       => 1,
                'prov_id'                       => 1,
                'cant_id'                       => 1,
                'dist_id'                       => 1,
                'clie_address'                  => '',
			];
            
            $client = Clients::create($data);           

        	return response()->json(['message' => 'El cliente fue creado satisfactoriamente', 'status' => 'Success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => $e, 'status' => 'Error'], 200);
    	}
	}

	public function validationFields(array $data, $register, $clie_id = null)
	{
		$messages = [
			'clie_firstname.required' => 'El campo nombre es requerido',
			'clie_firstname.string' => 'El campo nombre debe ser una cadena',
			'clie_firstname.min' => 'El campo nombre debe tener por lo menos 3 caracteres',
			'clie_lastname.string' => 'El campo apellido debe ser una cadena',
			'clie_email.required' => 'El correo es requerido',
			'clie_email.email' => 'El correo debe tener un formato válido',
			'clie_email.unique' => 'El correo ingresado ya existe, intente otro',
			'clie_username.required' => 'El nombre de usuario es requerido',
			'clie_username.string' => 'El nombre de usuario debe tener un formato válido',
			'clie_username.min' => 'El nombre de usuario debe tener por lo menos 5 caracteres',
			'clie_username.unique' => 'El nombre de usuario ingresado ya existe, intente otro',
			'clie_password.required' => 'La contraseña es requerida',
			'clie_password.min' => 'La contraseña debe tener por lo menos 6 caracteres',
			'clie_password.confirmed' => 'El campo de confirmación de contraseña no coincide',
			'clie_password.regex' => 'La contraseña debe tener por lo menos una letra mayúscula, una minúscula, un número y alguno de estos caracteres (- + _ ! @  # $ % ^ & * . , ; ?)',
		];

    	$rules = [
			'clie_firstname' => 'required|string|min:3',
        	'clie_lastname' => 'nullable|string',
			'clie_email' => 'required|email|unique:tbl_clients',
			'clie_username' => 'required|string|min:5|unique:tbl_clients',
        	'clie_password' => 'required|min:6|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*\d)(?=.*([-+_!@#$%^&*.,;?])).+$/', 
        ];
        if ($register == false) {
            $messages['clie_phonenumber.required'] = 'El número de teléfono es requerido';
            $messages['clie_dni.required'] = 'El número de cédula o dni es requerido';
            $messages['tycl_id.required'] = 'El tipo de cliente es requerido';
            $messages['tycl_id.exists'] = 'El tipo de cliente es inválido';
            $messages['tydi_id.required'] = 'El tipo de documento de identidad es requerido';
            $messages['tydi_id.exists'] = 'El tipo de documento de identidad es inválido';
            $messages['clie_business_name.required'] = 'La razón social es requerido';
            $messages['clie_legal_dni.required'] = 'El nit o número de identificación tributario es requerido';
            $messages['clie_address.required'] = 'La dirección es requerida';
            $messages['coun_id.required'] = 'El país es requerido';
            $messages['coun_id.exists'] = 'El país es inválido';
            $messages['prov_id.required'] = 'La provincia es requerido';
            $messages['prov_id.exists'] = 'La provincia es inválido';
            $messages['cant_id.required'] = 'El cantón es requerido';
            $messages['cant_id.exists'] = 'El cantón es inválido';
            $messages['dist_id.required'] = 'El distrito es requerido';
            $messages['dist_id.exists'] = 'El distrito es inválido';

            $rules['clie_phonenumber'] = 'required';
            $rules['clie_dni'] = 'required';
            $rules['tycl_id'] = 'required|exists:tbl_typeclient,tycl_id';
            $rules['tydi_id'] = 'required|exists:tbl_typedocumentid,tydi_id';
            $rules['clie_business_name'] = 'required';
            $rules['clie_legal_dni'] = 'required';
            $rules['clie_address'] = 'required';
            $rules['coun_id'] = 'required|exists:tbl_countries,coun_id';
            $rules['prov_id'] = 'required|exists:tbl_provinces,prov_id';
            $rules['cant_id'] = 'required|exists:tbl_cantons,cant_id';
            $rules['dist_id'] = 'required|exists:tbl_districts,dist_id';
        }
		if ($clie_id) {
			$rules['clie_email'] = Rule::unique('tbl_clients', 'clie_email')->ignore($clie_id, 'clie_id');
			$rules['clie_username'] = Rule::unique('tbl_clients', 'clie_username')->ignore($clie_id, 'clie_id');
    	}

    	return Validator::make($data, $rules, $messages);
	}

	/*public function login(Request $request)
	{
        $user = $request->header('user');
		$password = $request->header('pass');
		$isAuthenticated = $this->isAuthenticated($request);
		if (!$isAuthenticated) {
			$check = $this->checkUser($user, $password);
			if ($check == 'Correcto') {
				$client = Clients::where('clie_username', '=', $user)->first();
				$request->session()->put('clie_id', $client->clie_id);
				$request->session()->put('username', $client->clie_username);
				$request->session()->put('tycl_id', $client->tycl_id);
				return response()->json(['message' => 'Usuario logueado satisfactoriamente', 'status' => 'Success'], 200);
			} else {
				return response()->json(['message' => $check, 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => 'Ya existe un usuario logueado', 'status' => 'Error'], 200);
		}
	}*/

	/*public function logout(Request $request)
	{
		$isAuthenticated = $this->isAuthenticated($request);
		if ($isAuthenticated) {
			$request->session()->pull('clie_id');
			$request->session()->pull('username');
			$request->session()->pull('tycl_id');
			return response()->json(['message' => 'Ha cerrado sesión satisfactoriamente', 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => 'No existe un usuario logueado', 'status' => 'Error'], 200);
		}
	}*/

	/*public function isAuthenticated($request)
	{
		if ($request->session()->get('clie_id') && $request->session()->get('username') && $request->session()->get('tycl_id')) {
			return true;
		} else {
			return false;
		}
	}*/

	/*public function getUserAuthenticated(Request $request)
	{
		$isAuthenticated = $this->isAuthenticated($request);
		if ($isAuthenticated) {
			$client = Clients::where('clie_username', '=', $request->session()->get('username'))->first();
			$type_client = Type_client::where('tycl_id', '=', $client->tycl_id)->first();
			$data = [
				'id'                   => $client->clie_id,
				'firstname'            => $client->clie_firstname,
				'lastname'             => $client->clie_lastname,
				'email'                => $client->clie_email,
				'tycl_id'              => $client->tycl_id,
				'tycl_title'           => $type_client->tycl_title,
				'tycl_description'     => $type_client->tycl_description
			];
			return response()->json(['message' => json_encode($data), 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => 'No existe un usuario logueado', 'status' => 'Error'], 200);
		}
	}*/
}


