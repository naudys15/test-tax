<?php

namespace App\Http\Controllers;

use Session;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Type_client;
use App\Models\Type_document_id;
use App\Models\Type_document;
use App\Models\Type_measure_unit;
use App\Models\Type_payment_method;
use App\Models\Type_report;
use App\Models\Type_sale_terms;
use App\Models\Type_tax_iva;
use App\Models\Type_tax;
use App\Models\Countries;
use App\Models\Provinces;
use App\Models\Cantons;
use App\Models\Districts;
use App\Http\Controllers\Users;

class Types extends Controller
{
    public $users;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Obtener todos los tipos de documentos de identidad
     */
    public function getTypeDocumentId(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_document_id = Type_document_id::all();
            for ($i = 0; $i < count($type_document_id); $i++) {
                $type_document_id[$i]['id'] = $type_document_id[$i]['tydi_id'];
                unset($type_document_id[$i]['tydi_id']);
                $type_document_id[$i]['code'] = $type_document_id[$i]['tydi_code'];
                unset($type_document_id[$i]['tydi_code']);
                $type_document_id[$i]['description'] = $type_document_id[$i]['tydi_description'];
                unset($type_document_id[$i]['tydi_description']);
            }
            return response()->json(['message' => $type_document_id, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de documentos o facturas
     */
    public function getTypeDocument(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_document = Type_document::all();
            for ($i = 0; $i < count($type_document); $i++) {
                $type_document[$i]['id'] = $type_document[$i]['tydo_id'];
                unset($type_document[$i]['tydo_id']);
                $type_document[$i]['title'] = $type_document[$i]['tydo_title'];
                unset($type_document[$i]['tydo_title']);
                $type_document[$i]['description'] = $type_document[$i]['tydo_description'];
                unset($type_document[$i]['tydo_description']);
            }
            return response()->json(['message' => $type_document, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de unidades de medida
     */
    public function getTypeMeasureUnit(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_measure_unit = Type_measure_unit::all();
            for ($i = 0; $i < count($type_measure_unit); $i++) {
                $type_measure_unit[$i]['id'] = $type_measure_unit[$i]['tymu_id'];
                unset($type_measure_unit[$i]['tymu_id']);
                $type_measure_unit[$i]['title'] = $type_measure_unit[$i]['tymu_title'];
                unset($type_measure_unit[$i]['tymu_title']);
                $type_measure_unit[$i]['description'] = $type_measure_unit[$i]['tymu_description'];
                unset($type_measure_unit[$i]['tymu_description']);
            }
            return response()->json(['message' => $type_measure_unit, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de métodos de pago
     */
    public function getTypePaymentMethod(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_payment_method = Type_payment_method::all();
            for ($i = 0; $i < count($type_payment_method); $i++) {
                $type_payment_method[$i]['id'] = $type_payment_method[$i]['typm_id'];
                unset($type_payment_method[$i]['typm_id']);
                $type_payment_method[$i]['code'] = $type_payment_method[$i]['typm_code'];
                unset($type_payment_method[$i]['typm_code']);
                $type_payment_method[$i]['description'] = $type_payment_method[$i]['typm_description'];
                unset($type_payment_method[$i]['typm_description']);
            }
            return response()->json(['message' => $type_payment_method, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de reportes
     */
    public function getTypeReport(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_report = Type_report::all();
            for ($i = 0; $i < count($type_report); $i++) {
                $type_report[$i]['id'] = $type_report[$i]['tyre_id'];
                unset($type_report[$i]['tyre_id']);
                $type_report[$i]['title'] = $type_report[$i]['tyre_title'];
                unset($type_report[$i]['tyre_title']);
                $type_report[$i]['description'] = $type_report[$i]['tyre_description'];
                unset($type_report[$i]['tyre_description']);
            }
            return response()->json(['message' => $type_report, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de condiciones de venta
     */
    public function getTypeSaleTerms(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_sale_terms = Type_sale_terms::all();
            for ($i = 0; $i < count($type_sale_terms); $i++) {
                $type_sale_terms[$i]['id'] = $type_sale_terms[$i]['tyst_id'];
                unset($type_sale_terms[$i]['tyst_id']);
                $type_sale_terms[$i]['code'] = $type_sale_terms[$i]['tyst_code'];
                unset($type_sale_terms[$i]['tyst_code']);
                $type_sale_terms[$i]['description'] = $type_sale_terms[$i]['tyst_description'];
                unset($type_sale_terms[$i]['tyst_description']);
            }
            return response()->json(['message' => $type_sale_terms, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de impuestos de iva
     */
    public function getTypeTaxIva(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_tax_iva = Type_tax_iva::all();
            for ($i = 0; $i < count($type_tax_iva); $i++) {
                $type_tax_iva[$i]['id'] = $type_tax_iva[$i]['tiva_id'];
                unset($type_tax_iva[$i]['tiva_id']);
                $type_tax_iva[$i]['code'] = $type_tax_iva[$i]['tiva_code'];
                unset($type_tax_iva[$i]['tiva_code']);
                $type_tax_iva[$i]['percentage'] = $type_tax_iva[$i]['tiva_percentage'];
                unset($type_tax_iva[$i]['tiva_percentage']);
                $type_tax_iva[$i]['description'] = $type_tax_iva[$i]['tiva_description'];
                unset($type_tax_iva[$i]['tiva_description']);
            }
            return response()->json(['message' => $type_tax_iva, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de impuestos
     */
    public function getTypeTax(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_tax = Type_tax::all();
            for ($i = 0; $i < count($type_tax); $i++) {
                $type_tax[$i]['id'] = $type_tax[$i]['tax_id'];
                unset($type_tax[$i]['tax_id']);
                $type_tax[$i]['code'] = $type_tax[$i]['tax_code'];
                unset($type_tax[$i]['tax_code']);
                $type_tax[$i]['description'] = $type_tax[$i]['tax_description'];
                unset($type_tax[$i]['tax_description']);
            }
            return response()->json(['message' => $type_tax, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de iva de venta para configuración
     */
    public function getTypesIvaSale(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_tax = Type_tax_iva::where('tiva_description', 'like', '%reducida%')
                                    ->orWhere('tiva_description', 'like', '%general%')
                                    ->get();
            for ($i = 0; $i < count($type_tax); $i++) {
                unset($type_tax[$i]['tiva_id']);
                unset($type_tax[$i]['tiva_code']);
                unset($type_tax[$i]['tiva_description']);
                $type_tax[$i]['percentage'] = $type_tax[$i]['tiva_percentage'];
                unset($type_tax[$i]['tiva_percentage']);
            }
            return response()->json(['message' => $type_tax, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de iva de venta para seleccionar por producto
     */
    public function getAllTypesIvaSale(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $type_tax = Type_tax_iva::where('tiva_description', 'like', '%reducida%')
                                    ->orWhere('tiva_description', 'like', '%general%')
                                    ->get();
            for ($i = 0; $i < count($type_tax); $i++) {
                unset($type_tax[$i]['tiva_id']);
                unset($type_tax[$i]['tiva_code']);
                unset($type_tax[$i]['tiva_description']);
                $type_tax[$i]['percentage'] = $type_tax[$i]['tiva_percentage'];
                unset($type_tax[$i]['tiva_percentage']);
            };
            $type_tax[] = array("percentage" => "Sin definir");
            $type_tax[] = array("percentage" => "Exento con crédito fiscal");
            return response()->json(['message' => $type_tax, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los paises
     */
    public function getCountries(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $countries = Countries::all();
            for ($i = 0; $i < count($countries); $i++) {
                $countries[$i]['id'] = $countries[$i]['coun_id'];
                $countries[$i]['description'] = $countries[$i]['coun_description'];
                unset($countries[$i]['coun_id']);
                unset($countries[$i]['coun_description']);
            };
            return response()->json(['message' => $countries, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos las provincias
     */
    public function getProvinces(Request $request, $country){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $provinces = Provinces::where('coun_id', '=', $country)
                                    ->get();
            for ($i = 0; $i < count($provinces); $i++) {
                $provinces[$i]['id'] = $provinces[$i]['prov_id'];
                $provinces[$i]['description'] = $provinces[$i]['prov_description'];
                unset($provinces[$i]['prov_id']);
                unset($provinces[$i]['coun_id']);
                unset($provinces[$i]['prov_description']);
            };
            return response()->json(['message' => $provinces, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los cantones
     */
    public function getCantons(Request $request, $country, $province){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $cantons = Cantons::where('coun_id', '=', $country)
                                ->where('prov_id', '=', $province)
                                ->get();
            for ($i = 0; $i < count($cantons); $i++) {
                $cantons[$i]['id'] = $cantons[$i]['cant_id'];
                $cantons[$i]['description'] = $cantons[$i]['cant_description'];
                unset($cantons[$i]['cant_id']);
                unset($cantons[$i]['coun_id']);
                unset($cantons[$i]['prov_id']);
                unset($cantons[$i]['cant_description']);
            };
            return response()->json(['message' => $cantons, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los distritos
     */
    public function getDistricts(Request $request, $country, $province, $canton){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $districts = Districts::where('coun_id', '=', $country)
                                    ->where('prov_id', '=', $province)
                                    ->where('cant_id', '=', $canton)
                                    ->get();
            for ($i = 0; $i < count($districts); $i++) {
                $districts[$i]['id'] = $districts[$i]['dist_id'];
                $districts[$i]['description'] = $districts[$i]['dist_description'];
                unset($districts[$i]['dist_id']);
                unset($districts[$i]['coun_id']);
                unset($districts[$i]['prov_id']);
                unset($districts[$i]['cant_id']);
                unset($districts[$i]['dist_description']);
            };
            return response()->json(['message' => $districts, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
     * Obtener todos los tipos de cliente
     */
    public function getTypeClient(Request $request){
        $this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $tycl = Type_client::all();
            for ($i = 0; $i < count($tycl); $i++) {
                $tycl[$i]['id'] = $tycl[$i]['tycl_id'];
                $tycl[$i]['description'] = $tycl[$i]['tycl_title'];
                unset($tycl[$i]['tycl_id']);
                unset($tycl[$i]['tycl_title']);
                unset($tycl[$i]['tycl_description']);
            };
            return response()->json(['message' => $tycl, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }
}


