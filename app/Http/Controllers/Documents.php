<?php

namespace App\Http\Controllers;

use Session;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Users;
use App\Models\Clients;
use App\Models\Client_prorata_info;
use App\Models\Credit_and_debit_notes;
use App\Models\Configurations;
use App\Models\End_of_month;
use App\Models\Type_tax;
use App\Models\Invoices;
use App\Models\Reports;
use App\Models\Summary_of_month;
use App\Models\Sale_invoices;
use App\Models\Purchase_invoices;
use App\Models\Products_invoices;
use App\Models\Type_code_line_invoice;
use App\Models\Type_document_id;
use App\Models\Type_measure_unit;
use App\Models\Type_payment_method;
use App\Models\Type_sale_terms;
use App\Models\Type_tax_iva;
use App\Models\type_document;
use Barryvdh\DomPDF\PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Documents extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
	public $users;
	public $list_xml = [];
	public $user_name;
	public $prefix;
	public $name_file;
    public $resp_upload = [];

	/**
	 *  Comprueba que en la estructura de los archivos no haya errores
	 */
	public function checkFiles($no_files, $files)
	{
		if (count($files) == $no_files) {
			for ($i = 0; $i < $no_files; $i++) {
				if ($no_files == 1) {
					$file = $files['file'];
				} elseif ($no_files > 1) {
					$file = $files['file'.$i.''];
				}
				//$file = $files['file['.$i.']'];
				$type_file = (isset($file["type_file"])) ? (int)$file["type_file"] : '';

				if ($type_file == '') {
					return 'El campo type_file no puede estar vacío';
				} elseif ($type_file < 0 && $type_file > 5) {
					return 'El campo type_file no puede ser mayor que 5 ni menor que 0';
				}
				$xml = (isset($file["xml"])) ? $file["xml"] : '';
				if ($xml == '') {
					return 'El campo xml en alguno de los archivos no existe o es incorrecto';
				}
				if(@simplexml_load_string($xml)) {
					$data = [];
					$data['type_file'] = $type_file;
					$data['xml'] = simplexml_load_string($xml);
					$data['xml_string'] = $xml;
					$this->list_xml[] = $data;

				} else {
					return 'Hay un error en alguno de los archivos xml, está malformado o le falta algún dato';
				}
			}
		} else {
			return 'El número de archivos no coincide con el indicado en el campo number_of_files';
		}
		return 'Correcto';
	}
	/**
	 * Permite guardar el archivo xml en el servidor
	 */
	public function saveFile($routeFile, $xml_string) {
		try {
			$file = fopen($routeFile, 'w+');
			fwrite($file, $xml_string);
			fclose($file);
		} catch (Exception $e) {
			return 'Hay un error en alguno de los archivos xml, está malformado o le falta algún dato';
		}
		return "Archivo subido correctamente";
	}
	/**
	 * Permite formatear las respuestas de las facturas
	 */
	public function parseFiles($files, $number, $type_file)
	{
		$new_files = [];
		$subtotal = 0;
		$total_tax = 0;
		$total = 0;
		for ($i = 0; $i < $number; $i++) {
			if ($type_file == "purchases") {
				$new_files[$i]['id'] = $files[$i]['puin_id'];
				$new_files[$i]['activity_code'] = $files[$i]['puin_activity_code'];
				$new_files[$i]['code'] = $files[$i]['puin_consecutive_code'];
				$new_files[$i]['date'] = $files[$i]['puin_date'];
				$new_files[$i]['upload_date'] = $files[$i]['puin_upload_date'];
				$new_files[$i]['provider'] = $files[$i]['puin_provider_name'];
				$new_files[$i]['document_number_id'] = $files[$i]['puin_provider_document_number'];
				$products = Products_invoices::where('puin_id', '=', $new_files[$i]['id'])->get();
			} elseif ($type_file == "sales") {
				$new_files[$i]['id'] = $files[$i]['sain_id'];
				$new_files[$i]['activity_code'] = $files[$i]['sain_activity_code'];
				$new_files[$i]['code'] = $files[$i]['sain_consecutive_code'];
				$new_files[$i]['date'] = $files[$i]['sain_date'];
				$new_files[$i]['upload_date'] = $files[$i]['sain_upload_date'];
				$new_files[$i]['client'] = $files[$i]['sain_client_name'];
				$new_files[$i]['document_number_id'] = $files[$i]['sain_client_document_number'];
				$products = Products_invoices::where('sain_id', '=', $new_files[$i]['id'])->get();
			}
			$new_files[$i]['type'] = $files[$i]['tydo_id'];
			$new_files[$i]['type_prorata'] = "general";
			if (count($products) > 0) {
				$subtotal = 0;
				$total_tax = 0;
				$total = 0;
				$new_files[$i]['products_or_services'] = [];
				$special_prorata = 0;
				for ($j = 0; $j < count($products); $j++) {
					$data = [];
					$tax = Type_tax_iva::where('tiva_id', '=', $products[$j]->tiva_id)->first();
					if ($tax->tiva_percentage == "2%" || $tax->tiva_percentage == "4%" || $tax->tiva_percentage == "8%") {
						$special_prorata = 1;
					}
                    $data['id'] = $products[$j]->prin_id;
                    $data['name'] = $products[$j]->prin_name;
					$data['tax_percent'] = $tax->tiva_percentage;
					$data['percent_of_exoneration'] = $products[$j]->prin_exoneration;
					$data['quantity'] = $products[$j]->prin_quantity;
					$data['tax_base'] = round($products[$j]->prin_amount_bt, 2);
					$data['total'] = round($products[$j]->prin_total, 2);
					$subtotal += $data['tax_base'];
					$total_tax += $data['total'] - $data['tax_base'];
					$total += $data['total'];
					$new_files[$i]['products_or_services'][$j] = [];
					$new_files[$i]['products_or_services'][$j] = $data;
				}
				if ($special_prorata == 1) {
					$new_files[$i]['type_prorata'] = "special";
				}
			}
			$new_files[$i]['tax_base'] = round($subtotal, 2);
			$new_files[$i]['tax'] = round($total_tax, 2);
			$new_files[$i]['total'] = round($total, 2);
			if ($type_file == "purchases") {
                $new_files[$i]['supported_iva'] = round($files[$i]['puin_received_iva_total'], 2);
                $new_files[$i]['paid_out_one_percent'] = round($files[$i]['puin_paid_out_iva_one_percent'], 2);
                $new_files[$i]['paid_out_two_percent'] = round($files[$i]['puin_paid_out_iva_two_percent'], 2);
                $new_files[$i]['paid_out_four_percent'] = round($files[$i]['puin_paid_out_iva_four_percent'], 2);
                $new_files[$i]['paid_out_eight_percent'] = round($files[$i]['puin_paid_out_iva_eight_percent'], 2);
                $new_files[$i]['paid_out_thirteen_percent'] = round($files[$i]['puin_paid_out_iva_thirteen_percent'], 2);
                $new_files[$i]['paid_out_prorata'] = round($files[$i]['puin_paid_out_iva_total_prorata'], 2);
				$new_files[$i]['paid_out_iva'] = round($files[$i]['puin_paid_out_iva_total'], 2);
				$new_files[$i]['expenses'] = round($files[$i]['puin_expenses_total'], 2);
			} elseif ($type_file == "sales") {
				$new_files[$i]['one_percent_sales'] = round($files[$i]['sain_amount_one_percent_total'], 2);
				$new_files[$i]['two_percent_sales'] = round($files[$i]['sain_amount_two_percent_total'], 2);
				$new_files[$i]['four_percent_sales'] = round($files[$i]['sain_amount_four_percent_total'], 2);
				$new_files[$i]['eight_percent_sales'] = round($files[$i]['sain_amount_eight_percent_total'], 2);
				$new_files[$i]['thirteen_percent_sales'] = round($files[$i]['sain_amount_thirteen_percent_total'], 2);
				$new_files[$i]['with_credit_percent_sales'] = round($files[$i]['sain_amount_exempt_with_fiscal_credit_total'], 2);
				$new_files[$i]['without_credit_percent_sales'] = round($files[$i]['sain_amount_exempt_without_fiscal_credit_total'], 2);
				$new_files[$i]['total_sales'] = round($files[$i]['sain_amount_total'], 2);
			}
		}
		return $new_files;
	}

	/**
	 * Validar los campos de la factura al subirla de forma manual
	 */

	public function validationFields(array $data, $type_invoice, $manually, $clie_id = null)
	{
		if ($type_invoice == "purchases") {
			$messages = [
				'clie_id.required' => 'El cliente que emite la factura es requerido',
				'puin_consecutive_code.required' => 'El campo número de factura consecutivo es requerido',
				'puin_activity_code.required' => 'El campo código de actividad es requerido',
				'puin_date.required' => 'El campo fecha de emisión de factura es requerido',
				'tydi_provider_id.required' => 'El campo tipo de documento de identidad es requerido',
				'tydi_provider_id.exists' => 'El campo tipo de documento de identidad debe ser válido',
				'puin_provider_document_number.required' => 'El campo número de documento de identidad es requerido',
				'puin_provider_name.required' => 'El campo nombre del proveedor es requerido',
				'tydo_id.required' => 'El campo tipo de documento es requerido',
				'tydo_id.exists' => 'El campo tipo de documento es inválido',
				'puin_amount_bt.required' => 'El campo base imponible es requerido',
				'puin_tax_amount.required' => 'El campo total impuesto es requerido',
				'puin_total.required' => 'El campo total es requerido',
				'puin_received_iva_total.required' => 'El campo iva devengado es requerido',
				'puin_paid_out_iva_total.required' => 'El campo iva soportado es requerido',
				'puin_expenses_total.required' => 'El campo iva al gasto es requerido',
				'puin_change_value.required' => 'El campo valor de cambio es requerido',
            ];
			$rules = [
				'clie_id' => 'required',
				'puin_consecutive_code' => 'required',
				'puin_activity_code' => 'required',
				'puin_date' => 'required',
				'tydi_provider_id' => 'required|exists:tbl_typedocumentid,tydi_id',
				'puin_provider_document_number' => 'required',
				'puin_provider_name' => 'required',
				'tydo_id' => 'required|exists:tbl_typedocument,tydo_id',
				'puin_amount_bt' => 'required',
				'puin_tax_amount' => 'required',
				'puin_total' => 'required',
				'puin_received_iva_total' => 'required',
				'puin_paid_out_iva_total' => 'required',
				'puin_expenses_total' => 'required',
				'puin_change_value' => 'required',
            ];
            if ($manually == false) {
                $messages['puin_unique_code.required'] = 'El campo número de factura único es requerido';
                //$messages['puin_unique_code.unique'] = 'El campo código debe ser único';
                $messages['tyst_id.required'] = 'El campo tipo de venta es requerido';
                $messages['tyst_id.exists'] = 'El campo tipo de venta es inválido';
                $messages['typm_id.required'] = 'El campo tipo de pago es requerido';
                $messages['typm_id.exists'] = 'El campo tipo de pago es inválido';
                $messages['enom_id.required'] = 'El campo cierre de mes es requerido';
                $messages['enom_id.exists'] = 'El campo cierre de mes es inválido';
                $messages['puin_change_type.required'] = 'El campo tipo de cambio es requerido';
                $messages['puin_exempt.required'] = 'El campo exención de factura es requerido';

                //$rules['puin_unique_code'] = 'required|unique:tbl_purchaseinvoices,puin_unique_code';
                $rules['puin_unique_code'] = 'required';
                $rules['tyst_id'] = 'required|exists:tbl_typesaleterms,tyst_id';
                $rules['typm_id'] = 'required|exists:tbl_paymentmethod,typm_id';
                $rules['enom_id'] = 'required|exists:tbl_endofmonth,enom_id';
                $rules['puin_change_type'] = 'required';
                $rules['puin_exempt'] = 'required';
            } elseif ($manually == true) {
                if (isset($data['unique_code'])) {
                    $messages['puin_unique_code.required'] = 'El campo número de factura único es requerido';
                    //$messages['puin_unique_code.unique'] = 'El campo código debe ser único';
                    //$rules['puin_unique_code'] = 'required|unique:tbl_purchaseinvoices,puin_unique_code';
                    $rules['puin_unique_code'] = 'required';
                }
                if (isset($data['type_sale_terms_id'])) {
                    $messages['tyst_id.required'] = 'El campo tipo de venta es requerido';
                    $messages['tyst_id.exists'] = 'El campo tipo de venta es inválido';
                    $rules['tyst_id'] = 'required|exists:tbl_typesaleterms,tyst_id';
                }
                if (isset($data['type_payment_method_id'])) {
                    $messages['typm_id.required'] = 'El campo tipo de pago es requerido';
                    $messages['typm_id.exists'] = 'El campo tipo de pago es inválido';
                    $rules['typm_id'] = 'required|exists:tbl_paymentmethod,typm_id';
                }
                if (isset($data['puin_change_type'])) {
                    $messages['puin_change_type.required'] = 'El campo tipo de pago es requerido';
                    $rules['puin_change_type'] = 'required';
                }
            }
			if ($clie_id) {
				$rules['puin_unique_code'] = Rule::unique('tbl_purchaseinvoices', 'puin_unique_code')->ignore($clie_id, 'clie_id');
			}
		} elseif ($type_invoice == "sales") {
			$messages = [
				'clie_id.required' => 'El cliente que emite la factura es requerido',
				'sain_consecutive_code.required' => 'El campo número de factura consecutivo es requerido',
				'sain_activity_code.required' => 'El campo código de actividad es requerido',
				'sain_date.required' => 'El campo fecha de emisión de factura es requerido',
				'tydi_client_id.required' => 'El campo tipo de documento de identidad es requerido',
				'tydi_client_id.exists' => 'El campo tipo de documento de identidad debe ser válido',
				'sain_client_document_number.required' => 'El campo número de documento de identidad es requerido',
				'sain_client_name.required' => 'El campo nombre del proveedor es requerido',
				'tydo_id.required' => 'El campo tipo de documento es requerido',
				'tydo_id.exists' => 'El campo tipo de documento es inválido',
				'sain_amount_bt.required' => 'El campo base imponible es requerido',
				'sain_tax_amount.required' => 'El campo total impuesto es requerido',
				'sain_total.required' => 'El campo total es requerido',
				'sain_amount_one_percent_total.required' => 'El campo total de ventas al 1% es requerido',
				'sain_amount_two_percent_total.required' => 'El campo total de ventas al 2% es requerido',
				'sain_amount_four_percent_total.required' => 'El campo total de ventas al 4% es requerido',
				'sain_amount_eight_percent_total.required' => 'El campo total de ventas al 8% es requerido',
				'sain_amount_thirteen_percent_total.required' => 'El campo total de ventas al 13% es requerido',
				'sain_amount_exempt_with_fiscal_credit_total.required' => 'El campo total de ventas con derecho a crédito es requerido',
				'sain_amount_exempt_without_fiscal_credit_total.required' => 'El campo total de ventas sin derecho a crédito es requerido',
				'sain_amount_total.required' => 'El campo total de ventas es requerido',
				'sain_change_value.required' => 'El campo valor de cambio es requerido',
				'sain_exempt.required' => 'El campo exención de factura es requerido',
			];
			$rules = [
				'clie_id' => 'required',
				'sain_consecutive_code' => 'required',
				'sain_activity_code' => 'required',
				'sain_date' => 'required',
				'tydi_client_id' => 'required|exists:tbl_typedocumentid,tydi_id',
				'sain_client_document_number' => 'required',
				'sain_client_name' => 'required',
				'tydo_id' => 'required|exists:tbl_typedocument,tydo_id',
				'sain_amount_bt' => 'required',
				'sain_tax_amount' => 'required',
				'sain_total' => 'required',
				'sain_amount_one_percent_total' => 'required',
				'sain_amount_two_percent_total' => 'required',
				'sain_amount_four_percent_total' => 'required',
				'sain_amount_eight_percent_total' => 'required',
				'sain_amount_thirteen_percent_total' => 'required',
				'sain_amount_exempt_with_fiscal_credit_total' => 'required',
				'sain_amount_exempt_without_fiscal_credit_total' => 'required',
				'sain_amount_total' => 'required',
				'sain_change_value' => 'required',
            ];
            if ($manually == false) {
                $messages['sain_unique_code.required'] = 'El campo número de factura único es requerido';
                //$messages['sain_unique_code.unique'] = 'El campo código debe ser único';
                $messages['tyst_id.required'] = 'El campo tipo de venta es requerido';
                $messages['tyst_id.exists'] = 'El campo tipo de venta es inválido';
                $messages['typm_id.required'] = 'El campo tipo de pago es requerido';
                $messages['typm_id.exists'] = 'El campo tipo de pago es inválido';
                $messages['sain_change_type.required'] = 'El campo tipo de cambio es requerido';
                $messages['sain_exempt.required'] = 'El campo exención de factura es requerido';

                //$rules['sain_unique_code'] = 'required|unique:tbl_saleinvoices,sain_unique_code';
                $rules['sain_unique_code'] = 'required';
                $rules['tyst_id'] = 'required|exists:tbl_typesaleterms,tyst_id';
                $rules['typm_id'] = 'required|exists:tbl_paymentmethod,typm_id';
                $rules['sain_change_type'] = 'required';
                $rules['sain_exempt'] = 'required';
            } elseif ($manually == true) {
                if (isset($data['unique_code'])) {
                    $messages['sain_unique_code.required'] = 'El campo número de factura único es requerido';
                    //$messages['sain_unique_code.unique'] = 'El campo código debe ser único';
                    //$rules['sain_unique_code'] = 'required|unique:tbl_saleinvoices,sain_unique_code';
                    $rules['sain_unique_code'] = 'required';
                }
                if (isset($data['type_sale_terms_id'])) {
                    $messages['tyst_id.required'] = 'El campo tipo de venta es requerido';
                    $messages['tyst_id.exists'] = 'El campo tipo de venta es inválido';
                    $rules['tyst_id'] = 'required|exists:tbl_typesaleterms,tyst_id';
                }
                if (isset($data['type_payment_method_id'])) {
                    $messages['typm_id.required'] = 'El campo tipo de pago es requerido';
                    $messages['typm_id.exists'] = 'El campo tipo de pago es inválido';
                    $rules['typm_id'] = 'required|exists:tbl_paymentmethod,typm_id';
                }
                if (isset($data['sain_change_type'])) {
                    $messages['sain_change_type.required'] = 'El campo tipo de pago es requerido';
                    $rules['sain_change_type'] = 'required';
                }
            }
			if ($clie_id) {
				$rules['sain_unique_code'] = Rule::unique('tbl_saleinvoices', 'sain_unique_code')->ignore($clie_id, 'clie_id');
			}
		} elseif ($type_invoice == "credit_note" || $type_invoice == "debit_note") {
            $messages = [
				'clie_id.required' => 'El cliente que emite la factura es requerido',
				'cadn_consecutive_code.required' => 'El campo número de factura consecutivo es requerido',
                'cadn_date.required' => 'El campo fecha de emisión de factura es requerido',
                'cadn_upload_date.required' => 'El campo fecha de subida de factura es requerido',
				'tydo_id.required' => 'El campo tipo de documento es requerido',
				'tydo_id.exists' => 'El campo tipo de documento es inválido',
				'cadn_amount_bt.required' => 'El campo base imponible es requerido',
				'cadn_tax_amount.required' => 'El campo total impuesto es requerido',
                'cadn_total.required' => 'El campo total es requerido',
                'cadn_received_iva_total.required' => 'El campo iva devengado es requerido',
				'cadn_paid_out_iva_total.required' => 'El campo iva soportado es requerido',
				'cadn_expenses_total.required' => 'El campo iva al gasto es requerido',
				'cadn_change_value.required' => 'El campo valor de cambio es requerido',
			];
			$rules = [
				'clie_id' => 'required',
				'cadn_consecutive_code' => 'required',
                'cadn_date' => 'required',
                'cadn_upload_date' => 'required',
				'tydo_id' => 'required|exists:tbl_typedocument,tydo_id',
				'cadn_amount_bt' => 'required',
				'cadn_tax_amount' => 'required',
                'cadn_total' => 'required',
                'cadn_received_iva_total' => 'required',
				'cadn_paid_out_iva_total' => 'required',
				'cadn_expenses_total' => 'required',
				'cadn_change_value' => 'required',
            ];
            if ($manually == false) {
                $messages['cadn_change_type.required'] = 'El campo tipo de cambio es requerido';

                $rules['cadn_change_type'] = 'required';
            } elseif ($manually == true) {
                if (isset($data['cadn_change_type'])) {
                    $messages['cadn_change_type.required'] = 'El campo tipo de pago es requerido';
                    $rules['cadn_change_type'] = 'required';
                }
            }
        }
    	return Validator::make($data, $rules, $messages);
	}

	/**
	 * Validar los productos asociados a una factura al subirla de forma manual
	 */

	public function validationFieldsProducts(array $data, $manually = null)
	{
		$messages = [
			'tiva_id.required' => 'El campo tipo de iva es requerido',
            'tiva_id.exists' => 'El campo tipo de impuesto de iva debe ser válido',
            'prin_name.required' => 'El campo nombre es requerido',
			'prin_quantity.required' => 'El campo cantidad es requerido',
			'prin_amount_bt.required' => 'El campo base imponible es requerido',
			'prin_amount_tax.required' => 'El impuesto es requerido',
			'prin_total.required' => 'El campo total es requerido',
			'prin_credit_fiscal.required' => 'El campo crédito fiscal debe ser válido',
			'prin_iva_sale.required' => 'El campo iva de venta es requerido'
		];
		$rules = [
            'tiva_id' => 'required|exists:tbl_typeivatax,tiva_id',
            'prin_name' => 'required',
			'prin_quantity' => 'required',
			'prin_amount_bt' => 'required',
			'prin_amount_tax' => 'required',
			'prin_total' => 'required',
			'prin_credit_fiscal' => 'required',
			'prin_iva_sale' => 'required',
        ];
        if ($manually == false) {
            $messages['tymu_id.required'] = 'El campo tipo de unidad de medida es requerido';
            $messages['tymu_id.exists'] = 'El campo tipo de unidad de medida debe ser válido';
            $rules['tymu_id'] = 'required|exists:tbl_typemeasureunit,tymu_id';
        } elseif ($manually == true) {
            if (isset($data['tymu_id'])) {
                $messages['tymu_id.required'] = 'El campo tipo de unidad de medida es requerido';
                $messages['tymu_id.exists'] = 'El campo tipo de unidad de medida debe ser válido';
                $rules['tymu_id'] = 'required|exists:tbl_typemeasureunit,tymu_id';
            }
        }
    	return Validator::make($data, $rules, $messages);
	}

	/**
	 * Permite calcular la prorrata de una factura
	 */
	public function setProrataOfInvoice($dataInInvoice, $type_of_invoice, $tax, $clie_id)
	{
		$received_iva = 0;
		$paid_out_iva = 0;
		$iva_with_credit = 0;
		$deducted_iva = 0;
        $expenses = 0;
        $expenses_two = 0;
		$band_visible_taxes_one = 0;
		$band_visible_taxes_two = 0;
		$band_visible_taxes_four = 0;
		$band_visible_taxes_eight = 0;
		$band_visible_taxes_thirteen = 0;
		$band_visible_taxes_exempt_with_fiscal_credit = 0;
		$paid_out_tax_amount_total = 0;
		$paid_out_tax_amount_one_percent_total = 0;
		$paid_out_tax_amount_two_percent_total = 0;
		$paid_out_tax_amount_four_percent_total = 0;
		$paid_out_tax_amount_eight_percent_total = 0;
		$paid_out_tax_amount_thirteen_percent_total = 0;
		$paid_out_tax_amount_exempt_with_fiscal_credit_total = 0;
		$paid_out_tax_amount_one_percent_prorata = 0;
		$paid_out_tax_amount_two_percent_prorata = 0;
		$paid_out_tax_amount_four_percent_prorata = 0;
		$paid_out_tax_amount_eight_percent_prorata = 0;
        $paid_out_tax_amount_thirteen_percent_prorata = 0;
        $paid_out_tax_amount_one_percent_visible = 0;
        $paid_out_tax_amount_two_percent_visible = 0;
        $paid_out_tax_amount_four_percent_visible = 0;
        $paid_out_tax_amount_eight_percent_visible = 0;
        $paid_out_tax_amount_thirteen_percent_visible = 0;
        $total_amount_exempt_with_fiscal_credit = 0;
        $paid_out_amount_prorata = 0;
        $paid_out_amount_visible_one = 0;
        $paid_out_amount_visible_two = 0;
        $paid_out_amount_visible_four = 0;
        $paid_out_amount_visible_eight = 0;
        $paid_out_amount_visible_thirteen = 0;
		$data = [];
		$data = $dataInInvoice;
		$_data = [];
		$iva_sale = [];
		$iva_purchase = [];
        $amount_bt = [];
        $amount_tax = [];
		if ($type_of_invoice == "purchases") {
			for ($i = 0; $i < count($data); $i++) {
                $iva_type = Type_tax_iva::where('tiva_id', '=', $data[$i]['tiva_id'])->first();
                $iva_sale[] = $data[$i]['prin_iva_sale'];
                $iva_purchase[] = $iva_type->tiva_percentage;
                $amount_bt[] = (float)$data[$i]['prin_amount_bt'];
                $amount_tax[] = (float)$data[$i]['prin_amount_tax'];
                if ($iva_type->tiva_percentage == "0%") {
                    $total_amount_exempt_with_fiscal_credit += (float)$data[$i]['prin_amount_bt'];
                }
            }
		}

		$taxInfo = Client_prorata_info::where('clie_id', '=', $clie_id)->first();
		$prorata_in_one_percent = ($taxInfo->clpi_proportionality_special_one_percent_prorata / 100);
		$prorata_in_two_percent = ($taxInfo->clpi_proportionality_special_two_percent_prorata / 100);
		$prorata_in_four_percent = ($taxInfo->clpi_proportionality_special_four_percent_prorata / 100);
		$prorata_in_eight_percent = ($taxInfo->clpi_proportionality_special_eight_percent_prorata / 100);
		$prorata_in_thirteen_percent = ($taxInfo->clpi_proportionality_special_thirteen_percent_prorata / 100);
		$prorata_exempt_with_credit_percent = ($taxInfo->clpi_proportionality_special_exempt_with_credit_prorata / 100);
		
		if ($taxInfo->clpi_type_prorata == "general") {
			$received_iva = $tax;
			$general_proportionality = ($taxInfo->clpi_proportionality_general_prorata / 100);
			$paid_out_iva = ($tax * $general_proportionality);
			$iva_with_credit = (($total_amount_exempt_with_fiscal_credit * 0.13) * $general_proportionality);
			$deducted_iva = $paid_out_iva + $iva_with_credit;
			$expenses = $received_iva - $deducted_iva;
		} elseif ($taxInfo->clpi_type_prorata == "special") {
			$received_iva = $tax;
			if ($type_of_invoice == "purchases") {
				for ($l = 0; $l < count($iva_sale); $l++) {
					$comps = strpos($iva_sale[$l], '%');
					$compp = strpos($iva_purchase[$l], '%');
					if ($comps > 0 && $compp > 0) {
						$tax_convertedp = str_replace('%', '', $iva_purchase[$l]);
						$tax_converteds = str_replace('%', '', $iva_sale[$l]);
						if ((int)$tax_converteds >= (int)$tax_convertedp && $tax_convertedp == 1) {
                            $band_visible_taxes_one = 1;
                            $paid_out_tax_amount_one_percent_visible += $amount_tax[$l];
						}
						if ((int)$tax_converteds >= (int)$tax_convertedp && $tax_convertedp == 2) {
                            $band_visible_taxes_two = 1;
                            $paid_out_tax_amount_two_percent_visible += $amount_tax[$l];
						}
						if ((int)$tax_converteds >= (int)$tax_convertedp && $tax_convertedp == 4) {
                            $band_visible_taxes_four = 1;
                            $paid_out_tax_amount_four_percent_visible += $amount_tax[$l];
						}
						if ((int)$tax_converteds >= (int)$tax_convertedp && $tax_convertedp == 8) {
                            $band_visible_taxes_eight = 1;
                            $paid_out_tax_amount_eight_percent_visible += $amount_tax[$l];
						}
						if ((int)$tax_converteds >= (int)$tax_convertedp && $tax_convertedp == 13) {
                            $band_visible_taxes_thirteen = 1;
                            $paid_out_tax_amount_thirteen_percent_visible += $amount_tax[$l];
						}
					}
					if ($iva_sale[$l] == "Sin definir") {
                        $paid_out_tax_amount_one_percent_prorata += (($amount_bt[$l] * $prorata_in_one_percent) * 0.01);
                        $paid_out_tax_amount_two_percent_prorata += (($amount_bt[$l] * $prorata_in_two_percent) * 0.02);
                        $paid_out_tax_amount_four_percent_prorata += (($amount_bt[$l] * $prorata_in_four_percent) * 0.04);
                        $paid_out_tax_amount_eight_percent_prorata += (($amount_bt[$l] * $prorata_in_eight_percent) * 0.08);
                        $paid_out_tax_amount_thirteen_percent_prorata += (($amount_bt[$l] * $prorata_in_thirteen_percent) * 0.13);
                        $paid_out_tax_amount_exempt_with_fiscal_credit_total += (($amount_bt[$l] * $prorata_exempt_with_credit_percent) * 0.13);
                    }
					if ($iva_sale[$l] == "Credito") {
						$paid_out_tax_amount_exempt_with_fiscal_credit_total += (($amount_bt[$l] * $prorata_exempt_with_credit_percent) * 0.13);
					}
                }
                $paid_out_amount_visible_one = (($paid_out_tax_amount_one_percent_visible * $band_visible_taxes_one));
                $paid_out_amount_visible_two = (($paid_out_tax_amount_two_percent_visible * $band_visible_taxes_two));
                $paid_out_amount_visible_four = (($paid_out_tax_amount_four_percent_visible * $band_visible_taxes_four));
                $paid_out_amount_visible_eight = (($paid_out_tax_amount_eight_percent_visible * $band_visible_taxes_eight));
                $paid_out_amount_visible_thirteen = (($paid_out_tax_amount_thirteen_percent_visible * $band_visible_taxes_thirteen));
				$paid_out_tax_amount_one_percent_total = (($paid_out_amount_visible_one) + $paid_out_tax_amount_one_percent_prorata);
				$paid_out_tax_amount_two_percent_total = (($paid_out_amount_visible_two) + $paid_out_tax_amount_two_percent_prorata);
				$paid_out_tax_amount_four_percent_total = (($paid_out_amount_visible_four) + $paid_out_tax_amount_four_percent_prorata);
				$paid_out_tax_amount_eight_percent_total = (($paid_out_amount_visible_eight) + $paid_out_tax_amount_eight_percent_prorata);
				$paid_out_tax_amount_thirteen_percent_total = (($paid_out_amount_visible_thirteen) + $paid_out_tax_amount_thirteen_percent_prorata);
                $paid_out_amount_prorata = ($paid_out_tax_amount_one_percent_prorata + $paid_out_tax_amount_two_percent_prorata + $paid_out_tax_amount_four_percent_prorata + $paid_out_tax_amount_eight_percent_prorata + $paid_out_tax_amount_thirteen_percent_prorata + $paid_out_tax_amount_exempt_with_fiscal_credit_total);
                $paid_out_tax_amount_total = ($paid_out_tax_amount_one_percent_total + $paid_out_tax_amount_two_percent_total + $paid_out_tax_amount_four_percent_total + $paid_out_tax_amount_eight_percent_total + $paid_out_tax_amount_thirteen_percent_total + $paid_out_tax_amount_exempt_with_fiscal_credit_total);
				$deducted_iva = $paid_out_tax_amount_total;
				$expenses_amount_total = ($received_iva - $deducted_iva);
                $expenses = $expenses_amount_total;
			}
		}
		if ($type_of_invoice == "purchases") {
			$_data = [
				'puin_received_iva_total'                    => round($received_iva, 2),
				'puin_deducted_iva_total'                    => round($deducted_iva, 2),
                'puin_expenses_iva_total'                    => round($expenses, 2),
                'puin_paid_out_iva_one_percent'              => round($paid_out_amount_visible_one, 2),
                'puin_paid_out_iva_two_percent'              => round($paid_out_amount_visible_two, 2),
                'puin_paid_out_iva_four_percent'             => round($paid_out_amount_visible_four, 2),
                'puin_paid_out_iva_eight_percent'            => round($paid_out_amount_visible_eight, 2),
                'puin_paid_out_iva_thirteen_percent'         => round($paid_out_amount_visible_thirteen, 2),
                'puin_paid_out_iva_one_percent_prorata'      => round($paid_out_tax_amount_one_percent_prorata, 2),
                'puin_paid_out_iva_two_percent_prorata'      => round($paid_out_tax_amount_two_percent_prorata, 2),
                'puin_paid_out_iva_four_percent_prorata'     => round($paid_out_tax_amount_four_percent_prorata, 2),
                'puin_paid_out_iva_eight_percent_prorata'    => round($paid_out_tax_amount_eight_percent_prorata, 2),
                'puin_paid_out_iva_thirteen_percent_prorata' => round($paid_out_tax_amount_thirteen_percent_prorata, 2),
                'puin_paid_out_iva_exempt_with_credit'       => round($paid_out_tax_amount_exempt_with_fiscal_credit_total, 2),
                'puin_paid_out_iva_exempt_without_credit'    => 0,
                'puin_paid_out_iva_total_prorata'            => round($paid_out_amount_prorata, 2),
                
                
			];
		}
		return $_data;
	}
	/**
	 * Escribe los archivos y sube la información de las facturas en la base de datos
	 */
	public function uploadInfo($confirm = null)
	{
		$type_document = "";
		$list_xml = $this->list_xml;
		for ($i = 0; $i < count($list_xml); $i++) {
			$dataInvoice = [];
			$dataProductsInvoice = [];
			$exempt_invoice = 0;
			$userInfo = $this->users->getUserByUsername($this->user_name);
			$this->prefix = "FAC";
			$this->nameFile = '-'.$userInfo->clie_id.'-'.$list_xml[$i]['type_file'].'-'.date('m').'-'.date('Y').'-'.((string)$list_xml[$i]['xml']->NumeroConsecutivo);
			$nameXML = $this->prefix.''.$this->nameFile.'.xml';
            $routeFile = 'assets/documents/invoices/'.$nameXML;
			$dateInvoice = new DateTime((string)$list_xml[$i]['xml']->FechaEmision);
			
			$id_sender = Type_document_id::where('tydi_code', '=', (int)$list_xml[$i]['xml']->Emisor->Identificacion->Tipo)->first();
			$id_receiver = Type_document_id::where('tydi_code', '=', (int)$list_xml[$i]['xml']->Receptor->Identificacion->Tipo)->first();
			$sale_terms = Type_sale_terms::where('tyst_code', '=', (int)$list_xml[$i]['xml']->CondicionVenta)->first();
			$payment_method = Type_payment_method::where('typm_code', '=', (int)$list_xml[$i]['xml']->MedioPago)->first();  

			if (((float)$list_xml[$i]['xml']->ResumenFactura->TotalIVADevuelto == (float)$list_xml[$i]['xml']->ResumenFactura->TotalComprobante) || ((float)$list_xml[$i]['xml']->ResumenFactura->TotalExento == (float)$list_xml[$i]['xml']->ResumenFactura->TotalComprobante)) {
				$exempt_invoice = 1;
			}
			if ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4) {
				$type_document = "purchases";
				$date_period_year = $dateInvoice->format('Y');
                $date_period_month = $dateInvoice->format('m');
                $date_invoice_formatted = $dateInvoice->format('Y').'-'.$dateInvoice->format('m').'-'.$dateInvoice->format('d').'';
                $endofmonth = $this->getCurrentPeriod($date_invoice_formatted, $userInfo->clie_id);
				if ($endofmonth != false) {            
                    $start_date = strtotime($endofmonth->enom_start_date);
                    $end_date = strtotime($endofmonth->enom_end_date);
                    $date = strtotime($date_invoice_formatted);
                    if (($date >= $start_date) && ($date <= $end_date)) {
                        $configuration = Configurations::where('clie_id', '=', $userInfo->clie_id)->first();
                        if ($configuration == "") {
                            return 'No se pueden subir facturas si la configuración inicial no ha sido cargada';
                        }
                        $data = [
                            'puin_consecutive_code'                      => (string)$list_xml[$i]['xml']->NumeroConsecutivo,
                            'puin_unique_code'                           => (string)$list_xml[$i]['xml']->Clave,
                            'puin_activity_code'                         => (string)$list_xml[$i]['xml']->CodigoActividad,
                            'tydi_provider_id'                           => $id_sender->tydi_id,
                            'puin_provider_document_number'              => (int)$list_xml[$i]['xml']->Emisor->Identificacion->Numero,
                            'puin_provider_name'                         => (string)$list_xml[$i]['xml']->Emisor->NombreComercial,
                            'tyst_id'                                    => $sale_terms->tyst_id,
                            'typm_id'                                    => $payment_method->typm_id,
                            'clie_id'                                    => $userInfo->clie_id,
                            'tydo_id'                                    => $list_xml[$i]['type_file'],
                            'enom_id'                                    => $endofmonth->enom_id,
                            'puin_change_type'                           => (string)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->CodigoMoneda,
                            'puin_change_value'                          => (float)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->TipoCambio,
                            'puin_date'                                  => $dateInvoice->format('Y-m-d H:m:s'),
                            'puin_upload_date'                           => date('Y-m-d H:m:s'),
                            'puin_file_name'                             => $nameXML,
                            'puin_file_url'                              => $routeFile,
                            'puin_exempt'                                => $exempt_invoice,
                            'puin_amount_bt'                             => 0,
                            'puin_tax_amount'                            => 0,
                            'puin_total'                                 => 0,
                            'puin_received_iva_total'                    => 0,
                            'puin_paid_out_iva_total'                    => 0,
                            'puin_expenses_total'                        => 0,
                            'puin_paid_out_iva_one_percent'              => 0,
                            'puin_paid_out_iva_two_percent'              => 0,
                            'puin_paid_out_iva_four_percent'             => 0,
                            'puin_paid_out_iva_eight_percent'            => 0,
                            'puin_paid_out_iva_thirteen_percent'         => 0,
                            'puin_paid_out_iva_one_percent_prorata'      => 0,
                            'puin_paid_out_iva_two_percent_prorata'      => 0,
                            'puin_paid_out_iva_four_percent_prorata'     => 0,
                            'puin_paid_out_iva_eight_percent_prorata'    => 0,
                            'puin_paid_out_iva_thirteen_percent_prorata' => 0,
                            'puin_paid_out_iva_exempt_with_credit'       => 0,
                            'puin_paid_out_iva_exempt_without_credit'    => 0,
                            'puin_paid_out_iva_total_prorata'            => 0,
                            'puin_uploaded_manually'                     => 0
                        ];
                    } else {
                        return 'La factura no entra en el período creado, debe crear otro para proceder con la carga';
                    }
				} else {
					return 'No se pueden subir facturas cuyo período no ha sido creado en el sistema';
				}
			} elseif ((int)$list_xml[$i]['type_file'] == 3) {
				$type_document = "sales";
				$data = [
					'sain_consecutive_code'                            => (string)$list_xml[$i]['xml']->NumeroConsecutivo,
					'sain_unique_code'                                 => (string)$list_xml[$i]['xml']->Clave,
					'sain_activity_code'                               => (string)$list_xml[$i]['xml']->CodigoActividad,
					'tydi_client_id'                                   => $id_receiver->tydi_id,
					'sain_client_document_number'                      => (int)$list_xml[$i]['xml']->Receptor->Identificacion->Numero,
					'sain_client_name'                                 => (string)$list_xml[$i]['xml']->Receptor->NombreComercial,
					'tyst_id'                                          => $sale_terms->tyst_id,
					'typm_id'                                          => $payment_method->typm_id,
					'clie_id'                                          => $userInfo->clie_id,
					'tydo_id'                                          => $list_xml[$i]['type_file'],
					'sain_change_type'                                 => (string)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->CodigoMoneda,
					'sain_change_value'                                => (float)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->TipoCambio,
					'sain_date'                                        => $dateInvoice->format('Y-m-d H:m:s'),
					'sain_upload_date'                                 => date('Y-m-d H:m:s'),
					'sain_file_name'                                   => $nameXML,
					'sain_file_url'                                    => $routeFile,
					'sain_exempt'                                      => $exempt_invoice,
					'sain_amount_bt'                                   => 0,
					'sain_tax_amount'                                  => 0,
					'sain_total'                                       => 0,
					'sain_amount_one_percent_total'                    => 0,
					'sain_amount_two_percent_total'                    => 0,
					'sain_amount_four_percent_total'                   => 0,
					'sain_amount_eight_percent_total'                  => 0,
					'sain_amount_thirteen_percent_total'               => 0,
					'sain_amount_exempt_with_fiscal_credit_total'      => 0,
					'sain_amount_exempt_without_fiscal_credit_total'   => 0,
					'sain_amount_total'                                => 0,
					'sain_uploaded_manually'                           => 0
				];
			} elseif ((int)$list_xml[$i]['type_file'] == 5 || (int)$list_xml[$i]['type_file'] == 6) {
                if ((int)$list_xml[$i]['type_file'] == 6) {
                    $type_document = "credit_note";
                } elseif ((int)$list_xml[$i]['type_file'] == 5) {
                    $type_document = "debit_note";
                }
                $data = [
                    'clie_id'                                          => $userInfo->clie_id,
                    'cadn_consecutive_code'                            => (string)$list_xml[$i]['xml']->NumeroConsecutivo,
                    'cadn_date'                                        => $dateInvoice->format('Y-m-d H:m:s'),
					'cadn_upload_date'                                 => date('Y-m-d H:m:s'),
					'tydo_id'                                          => $list_xml[$i]['type_file'],
					'cadn_amount_bt'                                   => 0,
					'cadn_tax_amount'                                  => 0,
                    'cadn_total'                                       => 0,
                    'cadn_received_iva_total'                          => 0,
                    'cadn_paid_out_iva_total'                          => 0,
                    'cadn_expenses_total'                              => 0,
                    'cadn_reason'                                      => (isset($list_xml[$i]['xml']->InformacionReferencia->Razon))?(string)$list_xml[$i]['xml']->InformacionReferencia->Razon:'',
                    'cadn_change_type'                                 => (string)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->CodigoMoneda,
					'cadn_change_value'                                => (float)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->TipoCambio,
                    'cadn_file_name'                                   => $nameXML,
					'cadn_file_url'                                    => $routeFile,
					'cadn_uploaded_manually'                           => 0
                ];
            }
			$validar = $this->validationFields($data, $type_document, false);
			if ($validar->fails()) {
				return $validar->errors();
            }
            if ((int)$list_xml[$i]['type_file'] == 5 || (int)$list_xml[$i]['type_file'] == 6) {
                $code_invoice_in_note = (isset($list_xml[$i]['xml']->InformacionReferencia->Numero))?(string)$list_xml[$i]['xml']->InformacionReferencia->Numero:'';
                if ($code_invoice_in_note != "") {
                    $related_sale_invoice = Sale_invoices::where('sain_consecutive_code', '=', $code_invoice_in_note)
                                                        ->where('clie_id', '=', $userInfo->clie_id)
                                                        ->first();
                    if (count($related_sale_invoice) > 0) {
                        $data['sain_id'] = $related_sale_invoice->sain_id;
                    } else {
                        $related_purchase_invoice = Purchase_invoices::where('puin_consecutive_code', '=', $code_invoice_in_note)
                                                                        ->where('clie_id', '=', $userInfo->clie_id)
                                                                        ->first();
                        if (count($related_purchase_invoice) > 0) {
                            $data['puin_id'] = $related_purchase_invoice->puin_id;
                        } else {
                            return 'La nota de débito o crédito debe estar asociada a una factura ya cargada en el sistema';
                        }
                    }
                } else {
                    return 'La nota de débito o crédito debe estar asociada a una factura';
                }
            }

			$uploadFile = $this->saveFile($routeFile, $list_xml[$i]['xml_string']);
            if ($uploadFile != "Archivo subido correctamente") {
				return 'Hubo un error al guardar el archivo';
			}
			$dataInvoice = $data;
			$type_change = (float)$list_xml[$i]['xml']->ResumenFactura->CodigoTipoMoneda->TipoCambio;
			$detailProdInvoice = $list_xml[$i]['xml']->DetalleServicio;

			$bi = 0;
			$tax = 0;
			$total = 0;
			$cant_prod_exempt = 0;
			$cant_prod_exonerated = 0;
			$paid_out_tax_amount_one_percent_visible = 0;
			$paid_out_tax_amount_two_percent_visible = 0;
			$paid_out_tax_amount_four_percent_visible = 0;
			$paid_out_tax_amount_eight_percent_visible = 0;
			$paid_out_tax_amount_thirteen_percent_visible = 0;
			$total_amount_bt_one_percent = 0;
			$total_amount_bt_two_percent = 0;
			$total_amount_bt_four_percent = 0;
			$total_amount_bt_eight_percent = 0;
			$total_amount_bt_thirteen_percent = 0;
			$total_amount_bt_exempt_with_fiscal_credit_percent = 0;
			$new_discount = 0;
			$higher_tax = 0;
			$convert_tax_2 = "";

            for ($j = 0; $j < count($detailProdInvoice->LineaDetalle); $j++) {
				if (isset($detailProdInvoice->LineaDetalle[$j]->Impuesto)) {
					$type_tax = $detailProdInvoice->LineaDetalle[$j]->Impuesto->Tarifa;
					$taxes = Type_tax_iva::all();
					$convert_tax = (int)$type_tax;
					$code_tax = 0;
					for ($k = 0; $k < count($taxes); $k++) {
						$convert_tax_2 = (int)str_replace('%', '', $taxes[$k]->tiva_percentage);
						if ($convert_tax == $convert_tax_2) {
							if ($higher_tax < $convert_tax_2) {
								$higher_tax = $convert_tax_2;
							}
							$tax_id = $taxes[$k]->tiva_id;
							$code_tax = $taxes[$k]->tiva_code;
							break;
						}
					}
					$amount_tax = (((float)$detailProdInvoice->LineaDetalle[$j]->Impuesto->Monto) * $type_change);
					$amount_bt = (((float)$detailProdInvoice->LineaDetalle[$j]->SubTotal) * $type_change);
					if ($code_tax == 01 || $code_tax == 05) {
						$cant_prod_exempt++;
						$total_amount_bt_exempt_with_fiscal_credit_percent += $amount_bt;
					}
					if ((isset($detailProdInvoice->LineaDetalle[$j]->Descuento) && (int)($detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento > 0))) {
						$new_discount = (((int)$detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento) / 100);
						$amount_bt = ($amount_bt * $new_discount);
						$amount_tax = ($amount_tax * $new_discount);
					}
					if (isset($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion) && ((float)($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion->PorcentajeExoneracion > 0) && (float)($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion->PorcentajeExoneracion < 100))) {
						$new_tax = ((float)$detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion->PorcentajeExoneracion / 100);
						$amount_exoneration = ($amount_tax * $new_tax);
						$amount_tax = ($amount_tax - $amount_exoneration);
					}
					if ($convert_tax == "1%") {
						$total_amount_bt_one_percent += $amount_bt;
						$paid_out_tax_amount_one_percent_visible += $amount_tax;
					} elseif ($convert_tax == "2%") {
						$total_amount_bt_two_percent += $amount_bt;
						$paid_out_tax_amount_two_percent_visible += $amount_tax;
					} elseif ($convert_tax == "4%") {
						$total_amount_bt_four_percent += $amount_bt;
						$paid_out_tax_amount_four_percent_visible += $amount_tax;
					} elseif ($convert_tax == "8%") {
						$total_amount_bt_eight_percent += $amount_bt;
						$paid_out_tax_amount_eight_percent_visible += $amount_tax;
					} elseif ($convert_tax == "13%") {
						$total_amount_bt_thirteen_percent += $amount_bt;
						$paid_out_tax_amount_thirteen_percent_visible += $amount_tax;
					}
					if ((isset($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion) && (int)($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion->PorcentajeExoneracion == 100))) {
                        $cant_prod_exonerated++;
                        $total_amount_bt_exempt_with_fiscal_credit_percent += $amount_bt;
					}
				} else {
                    $cant_prod_exempt++;
                    $tax_id = 8;
					$amount_tax = 0;
					$amount_bt = (((float)$detailProdInvoice->LineaDetalle[$j]->SubTotal) * $type_change);
					$total_amount_bt_exempt_with_fiscal_credit_percent += $amount_bt;
					if ((isset($detailProdInvoice->LineaDetalle[$j]->Descuento) && (int)($detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento > 0))) {
						$new_discount = (((int)$detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento) / 100);
						$new_amount_bt = ($amount_bt * $new_discount);
					}
				}

				$amount_total = $amount_bt + $amount_tax;
				$type_invoice = Type_code_line_invoice::where('tylc_code', '=', (string)$detailProdInvoice->LineaDetalle[$j]->CodigoComercial->Tipo)->first();  
				$type_measure = Type_measure_unit::where('tymu_title', '=', (string)$detailProdInvoice->LineaDetalle[$j]->UnidadMedida)->first();
				$_data = [
					'tylc_id'             => $type_invoice->tylc_id,
					'tymu_id'             => $type_measure->tymu_id,
                    'tiva_id'             => $tax_id,
                    'prin_name'           => (string)$detailProdInvoice->LineaDetalle[$j]->Detalle,
					'prin_exoneration'    => (isset($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion))?(int)($detailProdInvoice->LineaDetalle[$j]->Impuesto->Exoneracion->PorcentajeExoneracion):0,
                    'prin_quantity'       => (int)$detailProdInvoice->LineaDetalle[$j]->Cantidad,
					'prin_amount_bt'      => (string)$amount_bt,
					'prin_amount_tax'     => (string)$amount_tax,
					'prin_total'          => (string)$amount_total,
					'prin_credit_fiscal'  => ($amount_tax > 0)?1:0,
					'prin_iva_sale'       => ($amount_tax > 0)?(float)$convert_tax_2:0,
				];
				if ((isset($detailProdInvoice->LineaDetalle[$j]->Descuento) && (int)($detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento > 0))) {
					$_data['prin_discount'] = (float)$detailProdInvoice->LineaDetalle[$j]->Descuento->MontoDescuento;
				}
				if ($cant_prod_exempt > 0) {
					$_data['prin_credit_fiscal'] = 1;
				}
				$dataProductsInvoice[] = $_data;
				$bi += ($amount_bt * $type_change);
				$tax += ($amount_tax * $type_change);
				$total += ($amount_total * $type_change);
			}

			if ($confirm == null && ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4)) {
				$dataToConfirm = [];
                $dataToConfirm = $dataProductsInvoice;
                for ($l = 0; $l < count($dataToConfirm); $l++) {
                    $tax = Type_tax_iva::where('tiva_id', '=', $dataToConfirm[$l]['tiva_id'])->first();
                    $configuration = Configurations::where('clie_id', '=', $userInfo->clie_id)->first();
                    $dataToConfirm[$l]['name'] = $dataToConfirm[$l]['prin_name'];
                    $dataToConfirm[$l]['iva_purchase'] = $tax->tiva_percentage;
                    $dataToConfirm[$l]['exoneration'] = $dataToConfirm[$l]['prin_exoneration'];
                    $dataToConfirm[$l]['quantity'] = $dataToConfirm[$l]['prin_quantity'];
                    $dataToConfirm[$l]['bi'] = $dataToConfirm[$l]['prin_amount_bt'];
                    $dataToConfirm[$l]['tax'] = $dataToConfirm[$l]['prin_amount_tax'];
                    $dataToConfirm[$l]['total'] = $dataToConfirm[$l]['prin_total'];
                    $dataToConfirm[$l]['iva_sale'] = $configuration->conf_iva_sale;
                    unset($dataToConfirm[$l]['tylc_id']);
                    unset($dataToConfirm[$l]['tymu_id']);
                    unset($dataToConfirm[$l]['tiva_id']);
                    unset($dataToConfirm[$l]['prin_exoneration']);
                    unset($dataToConfirm[$l]['prin_quantity']);
                    unset($dataToConfirm[$l]['prin_amount_bt']);
                    unset($dataToConfirm[$l]['prin_amount_tax']);
                    unset($dataToConfirm[$l]['prin_total']);
                    unset($dataToConfirm[$l]['prin_credit_fiscal']);
                    unset($dataToConfirm[$l]['prin_iva_sale']);
                    unset($dataToConfirm[$l]['prin_name']);
                }
				$this->resp_upload = $dataToConfirm;
				return 'Correcto sin confirmar';
			}
			$cant_prod_categorized = 0;
			for ($k = 0; $k < count($dataProductsInvoice); $k++) {
				$cant_prod_categorized++;
			};
			if (((count($confirm) == 1 && $confirm[0] != "Todos") || $cant_prod_categorized != count($confirm)) && ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4)) {
				return "Ingrese correctamente los iva de venta de cada uno de los totales de la factura";
			}
			for ($l = 0; $l < count($confirm); $l++) {
				$dataProductsInvoice[$l]['prin_iva_sale'] = $confirm[$l];
			}
			if ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4) {
				$data = $this->setProrataOfInvoice($dataProductsInvoice, 'purchases', $tax, $userInfo->clie_id);
			}
			if ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4) {
				$invoice = Purchase_invoices::create($dataInvoice);
			} elseif ((int)$list_xml[$i]['type_file'] == 3) {
				$invoice = Sale_invoices::create($dataInvoice);
			} elseif ((int)$list_xml[$i]['type_file'] == 5 || (int)$list_xml[$i]['type_file'] == 6) {
				$invoice = Credit_and_debit_notes::create($dataInvoice);
			}
			$configuration = Configurations::where('clie_id', '=', $userInfo->clie_id)->first();
			
			for ($j = 0; $j < count($detailProdInvoice->LineaDetalle); $j++) {
				if ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4) {
					$dataProductsInvoice[$j]['puin_id'] = $invoice->puin_id;
				} elseif ((int)$list_xml[$i]['type_file'] == 3) {
					$dataProductsInvoice[$j]['sain_id'] = $invoice->sain_id;
                } elseif ((int)$list_xml[$i]['type_file'] == 5 || (int)$list_xml[$i]['type_file'] == 6) {
					$dataProductsInvoice[$j]['cadn_id'] = $invoice->cadn_id;
				}
				if (count($confirm) == 1 && $confirm[0] == "Todos") {
					$dataProductsInvoice[$j]['prin_iva_sale'] = $configuration->conf_iva_sale;
				} elseif (count($confirm) > 1) {
					$dataProductsInvoice[$j]['prin_iva_sale'] = $confirm[$j];
                }
                if ((int)$list_xml[$i]['type_file'] != 5 || (int)$list_xml[$i]['type_file'] != 6) {
					Products_invoices::create($dataProductsInvoice[$j]);
				}
			}
			if ((count($detailProdInvoice->LineaDetalle) == $cant_prod_exempt) || (count($detailProdInvoice->LineaDetalle) == $cant_prod_exonerated)) {
				$exempt_invoice = 1;
			}
			if ((int)$list_xml[$i]['type_file'] == 1 || (int)$list_xml[$i]['type_file'] == 2 || (int)$list_xml[$i]['type_file'] == 4) {
				$invoice->puin_exempt = $exempt_invoice;
				$invoice->puin_amount_bt = round($bi, 2);
				$invoice->puin_tax_amount = round($tax, 2);
				$invoice->puin_total = round($total, 2);
				$invoice->puin_received_iva_total = round($data['puin_received_iva_total'], 2);
				$invoice->puin_paid_out_iva_total = round($data['puin_deducted_iva_total'], 2);
                $invoice->puin_expenses_total = round($data['puin_expenses_iva_total'], 2);
                $invoice->puin_paid_out_iva_one_percent = round($data['puin_paid_out_iva_one_percent'], 2);
                $invoice->puin_paid_out_iva_two_percent = round($data['puin_paid_out_iva_two_percent'], 2);
                $invoice->puin_paid_out_iva_four_percent = round($data['puin_paid_out_iva_four_percent'], 2);
                $invoice->puin_paid_out_iva_eight_percent = round($data['puin_paid_out_iva_eight_percent'], 2);
                $invoice->puin_paid_out_iva_thirteen_percent = round($data['puin_paid_out_iva_thirteen_percent'], 2);
                $invoice->puin_paid_out_iva_one_percent_prorata = round($data['puin_paid_out_iva_one_percent_prorata'], 2);
                $invoice->puin_paid_out_iva_two_percent_prorata = round($data['puin_paid_out_iva_two_percent_prorata'], 2);
                $invoice->puin_paid_out_iva_four_percent_prorata = round($data['puin_paid_out_iva_four_percent_prorata'], 2);
                $invoice->puin_paid_out_iva_eight_percent_prorata = round($data['puin_paid_out_iva_eight_percent_prorata'], 2);
                $invoice->puin_paid_out_iva_thirteen_percent_prorata = round($data['puin_paid_out_iva_thirteen_percent_prorata'], 2);
                $invoice->puin_paid_out_iva_exempt_with_credit = round($data['puin_paid_out_iva_exempt_with_credit'], 2);
                $invoice->puin_paid_out_iva_exempt_without_credit = 0;
                $invoice->puin_paid_out_iva_total_prorata = round($data['puin_paid_out_iva_total_prorata'], 2);
			} elseif ((int)$list_xml[$i]['type_file'] == 3) {
				$total_sale_one_percent = ($total_amount_bt_one_percent + ($total_amount_bt_one_percent * 0.01));
				$total_sale_two_percent = ($total_amount_bt_two_percent + ($total_amount_bt_two_percent * 0.02));
				$total_sale_four_percent = ($total_amount_bt_four_percent + ($total_amount_bt_four_percent * 0.04));
				$total_sale_eight_percent = ($total_amount_bt_eight_percent + ($total_amount_bt_eight_percent * 0.08));
				$total_sale_thirteen_percent = ($total_amount_bt_thirteen_percent + ($total_amount_bt_thirteen_percent * 0.13));
				$total_invoice = ($total_sale_one_percent + $total_sale_two_percent + $total_sale_four_percent + $total_sale_eight_percent + $total_sale_thirteen_percent + $total_amount_bt_exempt_with_fiscal_credit_percent);
				$invoice->sain_exempt = $exempt_invoice;
				$invoice->sain_amount_bt = round($bi, 2);
				$invoice->sain_tax_amount = round($tax, 2);
				$invoice->sain_total = round($total, 2);
				$invoice->sain_amount_one_percent_total = round($total_sale_one_percent, 2);
				$invoice->sain_amount_two_percent_total = round($total_sale_two_percent, 2);
				$invoice->sain_amount_four_percent_total = round($total_sale_four_percent, 2);
				$invoice->sain_amount_eight_percent_total = round($total_sale_eight_percent, 2);
				$invoice->sain_amount_thirteen_percent_total = round($total_sale_thirteen_percent, 2);
				$invoice->sain_amount_exempt_with_fiscal_credit_total = round($total_amount_bt_exempt_with_fiscal_credit_percent, 2);
				$invoice->sain_amount_exempt_without_fiscal_credit_total = 0;
				$invoice->sain_amount_total = round($total_invoice, 2);
			} elseif ((int)$list_xml[$i]['type_file'] == 5 || (int)$list_xml[$i]['type_file'] == 6) {
                $total = round((float)$detailProdInvoice->ResumenFactura->TotalComprobante, 2);
                $amount_tax = round((float)$detailProdInvoice->ResumenFactura->TotalImpuesto, 2);
                $amount_bt = round(($total - $amount_tax), 2);
				$invoice->cadn_amount_bt = $amount_bt;
				$invoice->cadn_tax_amount = $amount_tax;
				$invoice->cadn_total = $total;
			}
			$invoice->save();
		}
		return "Correcto";
	}
	/**
	 * Función que genera los reportes en PDF de las declaraciones de un cliente (EN DESARROLLO)
	 */
	public function generateFile($type_file, $type_report, $id_client, $month, $year)
	{
        if ($type_file == 'pdf') {
            $pdf = app()->make('dompdf.wrapper');
            $pdf->setWarnings(false);
            //$pdf->setPaper('a4', 'landscape');
            $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            if ($type_report == 1) {
                $this->prefix = "D_104";
                $number_report = count(Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->first());
                $data = Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->where('suom_year', '=', $year)
                                                    ->where('suom_month', '=', $month)
                                                    ->first();
                $client = Clients::where('clie_id', '=', $id_client)
                                    ->first();
                if (count($data) == 0) {
                    return 'No hay datos del mes o año seleccionado para generar al reporte';
                }
                $data['id_report'] = $number_report;
                $data['date'] = date('Y-m-d');
                $data['title'] = 'D-104 IVA';
                $data['business_name'] = $client->clie_business_name;
                $view = view()->make('pdf.iva_template', compact('data'))->render();
                $pdf->loadHTML($view);
                if ((int)$month < 10) {
                    $month = (int)$month;
                    $month = (string)'0'.$month;
                } else {
                    $month = (string)$month;
                }
                $this->nameFile = '-'.$id_client.'-'.$type_report.'-'.$month.'-'.$year.'-'.$number_report;
                $nameFile = $this->prefix.''.$this->nameFile;
                $fileUrl = 'assets/reports/'.$nameFile.'.pdf';
                $pdf->save($fileUrl)->stream($nameFile);
                $now = date('Y-m-d');
                $data = [
                    'tyre_id'            => $type_report,
                    'clie_id'            => $id_client,
                    'repo_date'          => $now,
                    'repo_file_name'     => $nameFile,
                    'repo_file_pdf'      => $fileUrl
                ];
                $report = Reports::where('clie_id', '=', $id_client)
                                    ->whereMonth('repo_date', '=', date('m'))
                                    ->whereYear('repo_date', '=', date('Y'))
                                    ->first();
                if (count($report) > 0) {
                    $report->tyre_id = $type_report;
                    $report->clie_id =  $id_client;
                    $report->repo_date = $now;
                    $report->repo_file_name = $nameFile;
                    $report->repo_file_pdf = $fileUrl;
                    $report->repo_file_excel = '';
                    $report->repo_file_xml = '';
                    $report->save();
                } else {
                    $report = Reports::create($data);
                }
                return $fileUrl;
            } elseif ($type_report == 2) {
                $this->prefix = "D_101";
                $data['title'] = 'D-101 RENTA';
            } elseif ($type_report == 3) {
                $this->prefix = "D_151";
                $data['title'] = 'D-151 PROVEEDORES';
            }
            $pdf->close();
        } elseif ($type_file == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            if ($type_report == 1) {
                $this->prefix = "D_104";
                $number_report = count(Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->first());
                $data = Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->where('suom_year', '=', $year)
                                                    ->where('suom_month', '=', $month)
                                                    ->first();
                $client = Clients::where('clie_id', '=', $id_client)
                                ->first();
                if (count($data) == 0) {
                    return 'No hay datos del mes o año seleccionado para generar al reporte';
                }
                $data['id_report'] = $number_report;
                $data['date'] = date('Y-m-d');
                $data['title'] = 'D-104 IVA';
                $sheet->setCellValue('A1', 'Reporte D-104 IVA Nº: '.$data['id_report']);
                $sheet->setCellValue('A2', 'Fecha de emisión: '.$data['date']);
                $sheet->setCellValue('A3', 'Nombre o Razon Social: '.$client->clie_business_name);
                if ($data['suom_type_prorata'] == 'general') {
                    $sheet->setCellValue('A5', 'Indicador');
                    $sheet->setCellValue('E5', 'Valor');
                    $sheet->setCellValue('A6', 'IVA devengado');
                    $sheet->setCellValue('A7', 'IVA deducible');
                    $sheet->setCellValue('A8', 'IVA al gasto');
                    $sheet->setCellValue('A9', 'IVA a pagar');
                    $sheet->setCellValue('E6', $data['suom_received_iva']);
                    $sheet->setCellValue('E7', $data['suom_paid_out_iva']);
                    $sheet->setCellValue('E8', $data['suom_expenses_iva']);
                    $sheet->setCellValue('E9', $data['suom_iva_to_pay']);
                } elseif ($data['suom_type_prorata'] == 'special') {
                    $sheet->setCellValue('A5', 'Indicador');
                    $sheet->setCellValue('E5', 'Valor');
                    $sheet->setCellValue('A6', 'IVA devengado');
                    $sheet->setCellValue('A7', 'IVA deducible identificado 1%');
                    $sheet->setCellValue('A8', 'IVA deducible identificado 2%');
                    $sheet->setCellValue('A9', 'IVA deducible identificado 4%');
                    $sheet->setCellValue('A10', 'IVA deducible identificado 13%');
                    $sheet->setCellValue('A11', 'IVA deducible prorrateado 1%');
                    $sheet->setCellValue('A12', 'IVA deducible prorrateado 2%');
                    $sheet->setCellValue('A13', 'IVA deducible prorrateado 4%');
                    $sheet->setCellValue('A14', 'IVA deducible prorrateado 13%');
                    $sheet->setCellValue('A15', 'IVA deducible total');
                    $sheet->setCellValue('A16', 'IVA al gasto');
                    $sheet->setCellValue('A17', 'IVA a pagar');
                    $sheet->setCellValue('E6', $data['suom_received_iva']);
                    $sheet->setCellValue('E7', $data['suom_paid_out_one_visible']);
                    $sheet->setCellValue('E8', $data['suom_paid_out_two_visible']);
                    $sheet->setCellValue('E9', $data['suom_paid_out_four_visible']);
                    $sheet->setCellValue('E10', $data['suom_paid_out_thirteen_visible']);
                    $sheet->setCellValue('E11', $data['suom_paid_out_one_prorata']);
                    $sheet->setCellValue('E12', $data['suom_paid_out_two_prorata']);
                    $sheet->setCellValue('E13', $data['suom_paid_out_four_prorata']);
                    $sheet->setCellValue('E14', $data['suom_paid_out_thirteen_prorata']);
                    $sheet->setCellValue('E15', $data['suom_paid_out_iva']);
                    $sheet->setCellValue('E16', $data['suom_expenses_iva']);
                    $sheet->setCellValue('E17', $data['suom_iva_to_pay']);
                }
                if ((int)$month < 10) {
                    $month = (int)$month;
                    $month = (string)'0'.$month;
                } else {
                    $month = (string)$month;
                }
                $this->nameFile = '-'.$id_client.'-'.$type_report.'-'.$month.'-'.$year.'-'.$number_report;
                $nameFile = $this->prefix.''.$this->nameFile;
                $fileUrl = 'assets/reports/'.$nameFile.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($fileUrl);
                $now = date('Y-m-d');
                $data = [
                    'tyre_id'            => $type_report,
                    'clie_id'            => $id_client,
                    'repo_date'          => $now,
                    'repo_file_name'     => $nameFile,
                    'repo_file_excel'      => $fileUrl
                ];
                $report = Reports::where('clie_id', '=', $id_client)
                                    ->whereMonth('repo_date', '=', date('m'))
                                    ->whereYear('repo_date', '=', date('Y'))
                                    ->first();
                if (count($report) > 0) {
                    $report->tyre_id = $type_report;
                    $report->clie_id =  $id_client;
                    $report->repo_date = $now;
                    $report->repo_file_name = $nameFile;
                    $report->repo_file_excel = $fileUrl;
                    $report->save();
                } else {
                    $report = Reports::create($data);
                }
                return $fileUrl;
            } elseif ($type_report == 2) {
                $this->prefix = "D_101";
                $data['title'] = 'D-101 RENTA';
            } elseif ($type_report == 3) {
                $this->prefix = "D_151";
                $data['title'] = 'D-151 PROVEEDORES';
            }
        } elseif ($type_file == 'xml') {
            $xmlFile = new \XMLWriter();
            if ($type_report == 1) {
                $this->prefix = "D_104";
                $number_report = count(Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->first());
                $data = Summary_of_month::where('clie_id', '=', $id_client)
                                                    ->where('suom_year', '=', $year)
                                                    ->where('suom_month', '=', $month)
                                                    ->first();
                $client = Clients::where('clie_id', '=', $id_client)
                                ->first();
                if (count($data) == 0) {
                    return 'No hay datos del mes o año seleccionado para generar al reporte';
                }
                $data['id_report'] = $number_report;
                $data['date'] = date('Y-m-d');
                $data['title'] = 'D-104 IVA';
                if ((int)$month < 10) {
                    $month = (int)$month;
                    $month = (string)'0'.$month;
                } else {
                    $month = (string)$month;
                }
                $this->nameFile = '-'.$id_client.'-'.$type_report.'-'.$month.'-'.$year.'-'.$number_report;
                $nameFile = $this->prefix.''.$this->nameFile;
                $fileUrl = 'assets/reports/'.$nameFile.'.xml';
                $xmlFile->openMemory();
                $xmlFile->startDocument('1.0', 'UTF-8');
                $xmlFile->startElement("Reporte");
                $xmlFile->startElement("Encabezado");
                $xmlFile->writeElement("FechaReporte", $data['date']);
                $xmlFile->writeElement("IdReporte", $data['id_report']);
                $xmlFile->writeElement("RazonSocial", $client->clie_business_name);
                $xmlFile->endElement();
                if ($data['suom_type_prorata'] == 'general') {
                    $xmlFile->startElement("Resumen");
                    $xmlFile->writeElement("IvaDevengado", $data['suom_received_iva']);
                    $xmlFile->writeElement("IvaDeducible", $data['suom_paid_out_iva']);
                    $xmlFile->writeElement("IvaGasto", $data['suom_expenses_iva']);
                    $xmlFile->writeElement("IvaAPagar", $data['suom_iva_to_pay']);
                    $xmlFile->endElement();
                } elseif ($data['suom_type_prorata'] == 'special') {
                    $xmlFile->startElement("Resumen");
                    $xmlFile->writeElement("IvaDevengado", $data['suom_received_iva']);
                    $xmlFile->writeElement("IvaDeducibleIdentificado1", $data['suom_paid_out_one_visible']);
                    $xmlFile->writeElement("IvaDeducibleIdentificado2", $data['suom_paid_out_two_visible']);
                    $xmlFile->writeElement("IvaDeducibleIdentificado4", $data['suom_paid_out_four_visible']);
                    $xmlFile->writeElement("IvaDeducibleIdentificado13", $data['suom_paid_out_thirteen_visible']);
                    $xmlFile->writeElement("IvaDeducibleProrrateado1", $data['suom_paid_out_one_prorata']);
                    $xmlFile->writeElement("IvaDeducibleProrrateado2", $data['suom_paid_out_two_prorata']);
                    $xmlFile->writeElement("IvaDeducibleProrrateado4", $data['suom_paid_out_four_prorata']);
                    $xmlFile->writeElement("IvaDeducibleProrrateado13", $data['suom_paid_out_thirteen_prorata']);
                    $xmlFile->writeElement("IvaDeducibleTotal", $data['suom_paid_out_iva']);
                    $xmlFile->writeElement("IvaGasto", $data['suom_expenses_iva']);
                    $xmlFile->writeElement("IvaAPagar", $data['suom_iva_to_pay']);
                    $xmlFile->endElement();
                }
                $xmlFile->endElement();
                $xmlFile->endDocument();
                $file = fopen($fileUrl,'w');
                fwrite($file, $xmlFile->flush(true));
                fclose($file);
                $now = date('Y-m-d');
                $data = [
                    'tyre_id'            => $type_report,
                    'clie_id'            => $id_client,
                    'repo_date'          => $now,
                    'repo_file_name'     => $nameFile,
                    'repo_file_xml'      => $fileUrl
                ];
                $report = Reports::where('clie_id', '=', $id_client)
                                    ->whereMonth('repo_date', '=', date('m'))
                                    ->whereYear('repo_date', '=', date('Y'))
                                    ->first();
                if (count($report) > 0) {
                    $report->tyre_id = $type_report;
                    $report->clie_id =  $id_client;
                    $report->repo_date = $now;
                    $report->repo_file_name = $nameFile;
                    $report->repo_file_xml = $fileUrl;
                    $report->save();
                } else {
                    $report = Reports::create($data);
                }
                return $fileUrl;
            } elseif ($type_report == 2) {
                $this->prefix = "D_101";
                $data['title'] = 'D-101 RENTA';
            } elseif ($type_report == 3) {
                $this->prefix = "D_151";
                $data['title'] = 'D-151 PROVEEDORES';
            }
        }
    }
    
    /**
	 * Permite obtener el cierre de mes en curso de un cliente
	 */

	public function getCurrentPeriod($date_search, $user_id)
	{
        $date = $date_search;
        $endofmonth = End_of_month::where('clie_id', '=', $user_id)
                                    ->where('enom_start_date', '<=', $date)
                                    ->where('enom_end_date', '>=', $date)
                                    ->first();
        if (count($endofmonth) > 0) {
            return $endofmonth;
        } else {
            return false;
        }
    }

	/** 
	 *  Endpoint, /api/v1/uploadFiles
	 *  Permite subir los archivos al sistema
	 */
	public function uploadFiles(Request $request)
	{
        $this->users = new Users();
        $resp = $request->json()->all();
        $info = $resp['info'];
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$this->user_name = $user_name;
			$no_files = $info['number_of_files'];
            $files = $info["files"];
			$result = $this->checkFiles($no_files, $files);
            if ($result != 'Correcto') {
				return response()->json(['message' => $result, 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
		}
		$userInfo = $this->users->getUserByUsername($user_name);
		$taxInfo = Client_prorata_info::where('clie_id', '=', $userInfo->clie_id)->first();
		if ($taxInfo == "") {
			return response()->json(['message' => 'Debe calcular la proporcionalidad antes de cargar facturas al sistema', 'status' => 'Error'], 200);
		} else {
			if (isset($info['confirm'])) {
				$resp = $this->uploadInfo($info['confirm']['iva_sales_products']);
			} else {
				$resp = $this->uploadInfo();
			}
			if ($resp == 'Correcto sin confirmar') {
				return response()->json(['message' => $this->resp_upload, 'status' => 'Success'], 200);
			} elseif ($resp != 'Correcto') {
				return response()->json(['message' => $resp, 'status' => 'Error'], 200);
			}
		}
        return response()->json(['message' => 'Los archivos fueron cargados satisfactoriamente', 'status' => 'Success'], 200);
	}

	/** 
	 *  Endpoint, /api/v1/uploadFilesManually
	 *  Permite subir los archivos al sistema de forma manual
	 */
	public function uploadFilesManually(Request $request)
	{
        $this->users = new Users();
        $resp = $request->json()->all();
        $info = $resp['info'];
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$this->user_name = $user_name;
			$userInfo = $this->users->getUserByUsername($this->user_name);
			$taxInfo = Client_prorata_info::where('clie_id', '=', $userInfo->clie_id)->first();
			if ($taxInfo == "") {
				return response()->json(['message' => 'Debe calcular la proporcionalidad antes de cargar facturas al sistema', 'status' => 'Error'], 200);
			} else {
				$type_document = $info['type_document_id'];
				if ($type_document == 1 || $type_document == 2 || $type_document == 4) {
					$type_document = "purchases";
					$validate = [
						'clie_id'                       => $userInfo->clie_id,
						'puin_consecutive_code'         => $info['consecutive_code'],
						'puin_activity_code'            => $info['activity_code'],
						'puin_date'                     => $info['date'],
						'tydi_provider_id'              => $info['type_document_number_id'],
						'puin_provider_document_number' => $info['provider_document_number'],
						'puin_provider_name'            => $info['provider_name'],
						'tydo_id'                       => $info['type_document_id'],
						'puin_amount_bt'                => $info['bi'],
						'puin_tax_amount'               => $info['tax'],
						'puin_total'                    => $info['total'],
                        'puin_received_iva_total'       => 0,
						'puin_paid_out_iva_total'       => 0,
						'puin_expenses_total'           => 0,
						'puin_change_value'             => $info['change_value'],
						'puin_exempt'                   => 0,
						'puin_uploaded_manually'        => 1
                    ];
                    if (isset($info['unique_code'])) {
                        $validate['puin_unique_code'] = $info['unique_code'];
                    }
                    if (isset($info['type_sale_terms_id'])) {
                        $validate['tyst_id'] = $info['type_sale_terms_id'];
                    }
                    if (isset($info['type_payment_method_id'])) {
                        $validate['typm_id'] = $info['type_payment_method_id'];
                    }
                    if (isset($info['change_type'])) {
                        $validate['puin_change_type'] = $info['change_type'];
                    }
				} elseif ($type_document == 3) {
					$type_document = "sales";
					$validate = [
						'clie_id'                                            => $userInfo->clie_id,
						'sain_consecutive_code'                              => $info['consecutive_code'],
						'sain_activity_code'                                 => $info['activity_code'],
						'sain_date'                                          => $info['date'],
						'tydi_client_id'                                     => $info['type_document_number_id'],
						'sain_client_document_number'                        => $info['client_document_number'],
						'sain_client_name'                                   => $info['client_name'],
						'tydo_id'                                            => $info['type_document_id'],
						'sain_amount_bt'                                     => $info['bi'],
						'sain_tax_amount'                                    => $info['tax'],
						'sain_total'                                         => $info['total'],
                        'sain_amount_one_percent_total'                      => 0,
						'sain_amount_two_percent_total'                      => 0,
						'sain_amount_four_percent_total'                     => 0,
						'sain_amount_eight_percent_total'                    => 0,
						'sain_amount_thirteen_percent_total'                 => 0,
						'sain_amount_exempt_with_fiscal_credit_total'        => 0,
						'sain_amount_exempt_without_fiscal_credit_total'     => 0,
						'sain_amount_total'                                  => 0,
						'sain_change_value'                                  => $info['change_value'],
						'sain_exempt'                                        => 0,
						'sain_uploaded_manually'                             => 1
                    ];
                    if (isset($info['unique_code'])) {
                        $validate['sain_unique_code'] = $info['unique_code'];
                    }
                    if (isset($info['type_sale_terms_id'])) {
                        $validate['tyst_id'] = $info['type_sale_terms_id'];
                    }
                    if (isset($info['type_payment_method_id'])) {
                        $validate['typm_id'] = $info['type_payment_method_id'];
                    }
                    if (isset($info['change_type'])) {
                        $validate['sain_change_type'] = $info['change_type'];
                    }
				}
				
				$validar = $this->validationFields($validate, $type_document, true);
				if ($validar->fails()) {
                    return response()->json(['message' => $validar->errors(), 'status' => 'Error'], 422);
                }
                if ($type_document == 'purchases') {
                    $dateInvoice = new DateTime($info['date']);
                    $date_period_year = $date->format('Y');
                    $date_period_month = $date->format('m');
                    $date_invoice_formatted = $dateInvoice->format('Y').'-'.$dateInvoice->format('m').'-'.$dateInvoice->format('d');
                    $endofmonth = $this->getCurrentPeriod($data_invoice_formatted, $userInfo->clie_id);
                    if ($endofmonth != false) {
                        $start_date = strtotime($endofmonth->enom_start_date);
                        $end_date = strtotime($endofmonth->enom_end_date);
                        $date = strtotime($date);
                        if (($date >= $start_date) && ($date <= $end_date)) {
                            $validate['enom_id'] = $endofmonth->enom_id;
                        } else {
                            return response()->json(['message' => 'La factura no entra en el período creado, debe crear otro para proceder con la carga', 'status' => 'Error'], 200);
                        }
                    } else {
                        return response()->json(['message' => 'Debe crearse un cierre de mes antes de subir una factura', 'status' => 'Error'], 200);
                    }
                    if ($validate['puin_tax_amount'] == 0) {
                        $validate['puin_exempt'] = 1;
                    }
                    if ($validate['puin_change_value'] > 1) {
                        $validate['puin_change_type'] = "";
                        $validate['puin_amount_bt'] = ($validate['puin_amount_bt'] * $validate['puin_change_value']);
                        $validate['puin_tax_amount'] = ($validate['puin_tax_amount'] * $validate['puin_change_value']);
                        $validate['puin_total'] = ($validate['puin_total'] * $validate['puin_change_value']);
                    } elseif ($validate['puin_change_value'] == 1) {
                        $validate['puin_change_type'] = "CRC";
                    }
                } elseif ($type_document == "sales") {
                    if ($validate['sain_tax_amount'] == 0) {
                        $validate['sain_exempt'] = 1;
                    }
                    if ($validate['sain_change_value'] > 1) {
                        $validate['sain_change_type'] = "";
                        $validate['sain_amount_bt'] = ($validate['sain_amount_bt'] * $validate['sain_change_value']);
                        $validate['sain_tax_amount'] = ($validate['sain_tax_amount'] * $validate['sain_change_value']);
                        $validate['sain_total'] = ($validate['sain_total'] * $validate['sain_change_value']);
                    } elseif ($validate['sain_change_value'] == 1) {
                        $validate['sain_change_type'] = "CRC";
                    }
                }
                $one_percent_total = 0;
                $two_percent_total = 0;
                $four_percent_total = 0;
                $eight_percent_total = 0;
                $thirteen_percent_total = 0;
                $exempt_with_fiscal_credit_total = 0;
                $exempt_without_fiscal_credit_total = 0;
                $fiscal_credit_total = 0;
                $total = 0;
				if (isset($info['products_or_services'])) {
					$products_or_services = $info['products_or_services'];
					$quantity_pos = count($products_or_services);
                    $_data = [];
                    $tax = 0;
                    if ($type_document == "purchases") {
                        $change_value = $validate['puin_change_value'];
                    } elseif ($type_document == "sales") {
                        $change_value = $validate['sain_change_value'];
                    }
                    
					for ($i = 0; $i < $quantity_pos; $i++) {
                        $tiva = Type_tax_iva::where('tiva_percentage', '=', $products_or_services[$i]['iva_purchase'])->first();

                        if ($tiva->tiva_code == 01 || $tiva->tiva_code == 05) {
                            $exempt_with_fiscal_credit_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        } elseif ($tiva->tiva_code == 02) {
                            $one_percent_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        } elseif ($tiva->tiva_code == 03) {
                            $two_percent_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        } elseif ($tiva->tiva_code == 04) {
                            $four_percent_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        } elseif ($products_or_services[$i]['iva_purchase'] == "8%") {
                            $eight_percent_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        } elseif ($products_or_services[$i]['iva_purchase'] == "13%") {
                            $thirteen_percent_total += ((float)$products_or_services[$i]['total'] * $change_value);
                        }
                        $validate_two = [
                            'tiva_id' => $tiva->tiva_id,
                            'prin_name' => $products_or_services[$i]['name'],
							'prin_quantity' => $products_or_services[$i]['quantity'],
							'prin_amount_bt' => ($products_or_services[$i]['amount_bt'] * $change_value),
							'prin_amount_tax' => ($products_or_services[$i]['amount_tax'] * $change_value),
							'prin_total' => ($products_or_services[$i]['total'] * $change_value),
							'prin_credit_fiscal' => $products_or_services[$i]['credit_fiscal'],
							'prin_iva_sale' => $products_or_services[$i]['iva_sale'],
                        ];
                        if (isset($products_or_services[$i]['measure_unit_id'])) {
                            $validate_two['tymu_id'] = $products_or_services[$i]['measure_unit_id'];
                        }
						$validar = $this->validationFieldsProducts($validate_two, true);
						if ($validar->fails()) {
							return response()->json(['message' => $validar->errors(), 'status' => 'Error'], 422);
						}
						if (isset($products_or_services[$i]['exoneration'])) {
							$validate_two['prin_exoneration'] = $products_or_services[$i]['exoneration'];
						} else {
							$validate_two['prin_exoneration'] = 0;
						}
						if (isset($products_or_services[$i]['discount'])) {
							$validate_two['prin_discount'] = $products_or_services[$i]['discount'];
                        }
                        $tax += ((float)$products_or_services[$i]['amount_tax'] * $change_value);
                        $total += ((float)$products_or_services[$i]['total'] * $change_value);
						$_data[] = $validate_two;
                    }
                    $fiscal_credit_total = ($one_percent_total + $two_percent_total + $four_percent_total + $eight_percent_total + $thirteen_percent_total + $exempt_with_fiscal_credit_total);
                    $exempt_without_fiscal_credit_total = ($total - $fiscal_credit_total);
				}
				if (isset($info['xml'])) {
					$this->prefix = "FAC";
					$this->nameFile = '-'.$userInfo->clie_id.'-'.$info['type_document_id'].'-'.date('m').'-'.date('Y').'-'.($info['consecutive_code']);
					$nameXML = $this->prefix.''.$this->nameFile.'.xml';
					$routeFile = 'assets/documents/invoices/'.$nameXML;
				}
                $data = $validate;
                if ($type_document == 'purchases') {
                    $prorataValues = $this->setProrataOfInvoice($_data, 'purchases', $tax, $userInfo->clie_id);
                    $data['puin_received_iva_total'] = $prorataValues['puin_received_iva_total'];
                    $data['puin_paid_out_iva_total'] = $prorataValues['puin_deducted_iva_total'];
                    $data['puin_expenses_total'] = $prorataValues['puin_expenses_iva_total'];
                    $data['puin_paid_out_iva_one_percent'] = $prorataValues['puin_paid_out_iva_one_percent'];
                    $data['puin_paid_out_iva_two_percent'] = $prorataValues['puin_paid_out_iva_two_percent'];
                    $data['puin_paid_out_iva_four_percent'] = $prorataValues['puin_paid_out_iva_four_percent'];
                    $data['puin_paid_out_iva_eight_percent'] = $prorataValues['puin_paid_out_iva_eight_percent'];
                    $data['puin_paid_out_iva_thirteen_percent'] = $prorataValues['puin_paid_out_iva_thirteen_percent'];
                    $data['puin_paid_out_iva_one_percent_prorata'] = $prorataValues['puin_paid_out_iva_one_percent_prorata'];
                    $data['puin_paid_out_iva_two_percent_prorata'] = $prorataValues['puin_paid_out_iva_two_percent_prorata'];
                    $data['puin_paid_out_iva_four_percent_prorata'] = $prorataValues['puin_paid_out_iva_four_percent_prorata'];
                    $data['puin_paid_out_iva_eight_percent_prorata'] = $prorataValues['puin_paid_out_iva_eight_percent_prorata'];
                    $data['puin_paid_out_iva_thirteen_percent_prorata'] = $prorataValues['puin_paid_out_iva_thirteen_percent_prorata'];
                    $data['puin_paid_out_iva_exempt_with_credit'] = $prorataValues['puin_paid_out_iva_exempt_with_credit'];
                    $data['puin_paid_out_iva_exempt_without_credit'] = $prorataValues['puin_paid_out_iva_exempt_without_credit'];
                    $data['puin_paid_out_iva_total_prorata'] = $prorataValues['puin_paid_out_iva_total_prorata'];
                } elseif ($type_document == "sales") {
                    $data['sain_amount_one_percent_total'] = $one_percent_total;
                    $data['sain_amount_two_percent_total'] = $two_percent_total;
                    $data['sain_amount_four_percent_total'] = $four_percent_total;
                    $data['sain_amount_eight_percent_total'] = $eight_percent_total;
                    $data['sain_amount_thirteen_percent_total'] = $thirteen_percent_total;
                    $data['sain_amount_exempt_with_fiscal_credit_total'] = $exempt_with_fiscal_credit_total;
                    $data['sain_amount_exempt_without_fiscal_credit_total'] = $exempt_without_fiscal_credit_total;
                    $data['sain_amount_total'] = $total;
                }

				if ($type_document == "purchases") {
					$data['puin_upload_date'] = date('Y-m-d');
					if (isset($info['xml'])) {
						$data['puin_file_name'] = $nameXML;
						$data['puin_file_url'] = $routeFile;
					}
					$invoice = Purchase_invoices::create($data);
					for ($j = 0; $j < count($_data); $j++) {
						$_data[$j]['puin_id'] = $invoice->puin_id;
						Products_invoices::create($_data[$j]);
					}
				} elseif ($type_document == "sales") {
					$data['sain_upload_date'] = date('Y-m-d');
					if (isset($info['xml'])) {
						$data['sain_file_name'] = $nameXML;
						$data['sain_file_url'] = $routeFile;
					}
					$invoice = Sale_invoices::create($data);
					for ($j = 0; $j < count($_data); $j++) {
						$_data[$j]['sain_id'] = $invoice->sain_id;
						Products_invoices::create($_data[$j]);
					}
				}
				if (isset($info['xml'])) {
					$this->prefix = "FAC";
					$this->nameFile = '-'.$userInfo->clie_id.'-'.$info['type_document_id'].'-'.date('m').'-'.date('Y').'-'.($info['consecutive_code']);
					$nameXML = $this->prefix.''.$this->nameFile.'.xml';
					$routeFile = 'assets/documents/invoices/'.$nameXML;
					$uploadFile = $this->saveFile($routeFile, $info['xml']);
					if ($uploadFile != "Archivo subido correctamente") {
						return response()->json(['message' => $uploadFile, 'status' => 'Error'], 200);
					}
				}
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
		}
        return response()->json(['message' => 'Los archivos fueron cargados satisfactoriamente', 'status' => 'Success'], 200);
    }
    
    /** 
	 *  Endpoint, /api/v1/uploadFilesExcel
	 *  Permite subir los archivos excel al sistema
	 */
	public function uploadFilesExcel(Request $request)
	{
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }

	/**
	 * Endpoint, /api/v1/getPurchases
	 * Permite obtener las facturas de compras de un período específico
	 */

	public function getPurchases(Request $request, $month, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		if (isset($resp['info'])) {
			$info = $resp['info'];
		}
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$files = Purchase_invoices::whereRaw('month(puin_date) = ?', $month)
				->whereRaw('year(puin_date) = ?', $year)
				->where('clie_id','=',$user->clie_id)
				->whereIn('tydo_id', [1, 2, 4])
				->get();
			if (isset($info)) {
				$type_report = $info['type_report'];
			}
			$number_of_files = count($files);
			if ($number_of_files > 0) {
				$files = $this->parseFiles($files, $number_of_files, 'purchases');
				return response()->json(['message' => $files, 'number_of_invoices' => $number_of_files, 'status' => 'Success'], 200);
			} else{
				return response()->json(['message' => 'No hay facturas de compras para el período seleccionado', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/getSales
	 * Permite obtener las facturas de ventas de un período específico
	 */

	public function getSales(Request $request, $month, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		if (isset($resp['info'])) {
			$info = $resp['info'];
		}
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$files = Sale_invoices::whereRaw('month(sain_date) = ?', $month)
				->whereRaw('year(sain_date) = ?', $year)
				->where('clie_id','=',$user->clie_id)
				->where('tydo_id','=', 3)
				->get();
			if (isset($info)) {
				$type_report = $info['type_report'];
			}
			$number_of_files = count($files);
			if ($number_of_files > 0) {
				$files = $this->parseFiles($files, $number_of_files, 'sales');
				return response()->json(['message' => $files, 'number_of_invoices' => $number_of_files, 'status' => 'Success'], 200);
			} else{
				return response()->json(['message' => 'No hay facturas de ventas para el período seleccionado', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/getExpenses
	 * Permite obtener las facturas de gastos de un período específico
	 */

	public function getExpenses(Request $request, $month, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		if (isset($resp['info'])) {
			$info = $resp['info'];
		}
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$files = Invoices::whereRaw('month(invo_date) = ?', $month)
				->whereRaw('year(invo_date) = ?', $year)
				->where('clie_id','=',$user->clie_id)
				->where('tydo_id','=', 4)
				->get();
			if (isset($info)) {
				$type_report = $info['type_report'];
			}
			$number_of_files = count($files);
			if ($number_of_files > 0) {
				$files = $this->parseFiles($files, $number_of_files);
				return response()->json(['message' => $files, 'number_of_files' => $number_of_files, 'status' => 'Success'], 200);
			} else{
				return response()->json(['message' => 'No hay facturas de gastos para el período seleccionado', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/deleteInvoice
	 * Permite eliminar una factura específica
	 */

	public function deleteInvoice(Request $request, $id, $type)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
        $password = $request->header('pass');
        $check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            if (isset($type)) {
                $type_invoice = $type;
                $user = $this->users->getUserByUsername($user_name);
                if ($type_invoice == 1 || $type_invoice == 2 || $type_invoice == 4) {
                    $invoice = Purchase_invoices::where('puin_id', '=', $id)
                                    ->where('clie_id', '=', $user->clie_id)
                                    ->first();
                } else if ($type_invoice == 3) {
                    $invoice = Sale_invoices::where('sain_id', '=', $id)
                                    ->where('clie_id', '=', $user->clie_id)
                                    ->first();
                }
                if ($invoice != null) {
                    if (($type_invoice == 1 && $type_invoice == 2 || $type_invoice == 4) && $invoice->puin_file_url != NULL) {
                        unlink($invoice->puin_file_url);
                    } else if (($type_invoice == 3) && $invoice->sain_file_url != NULL) {
                        unlink($invoice->sain_file_url);
                    }
                    $invoice->delete();
                    return response()->json(['message' => 'La factura fue borrada satisfactoriamente', 'status' => 'Success'], 200);
                } else {
                    return response()->json(['message' => 'La factura con el identificador no existe', 'status' => 'Error'], 200);
                }
            } else {
                return response()->json(['message' => 'Debe especificar un tipo de factura para poder eliminar alguna', 'status' => 'Error'], 200);
            }
            
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/getProportionality
	 * Permite obtener la proporcionalidad de un cliente
	 */

	public function getProportionality(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
            $data = Client_prorata_info::where('clie_id', '=', $user->clie_id)
                                ->where('clpi_year', '=', date('Y'))
								->first();
			if ($data != null) {
                unset($data->clpi_id);
                unset($data->clie_id);
                unset($data->created_at);
                unset($data->updated_at);
                $data->year = $data->clpi_year;
                $data->description = $data->clpi_description;
                unset($data->clpi_year);
                unset($data->clpi_description);
				if ($data->clpi_type_prorata == "general") {
                    $data->type_prorata = "general";
                    $data->prorata_general = $data->clpi_proportionality_general_prorata;
                    unset($data->clpi_type_prorata);
                    unset($data->clpi_proportionality_general_prorata);
					unset($data->clpi_proportionality_special_one_percent_prorata);
                    unset($data->clpi_proportionality_special_two_percent_prorata);
                    unset($data->clpi_proportionality_special_four_percent_prorata);
                    unset($data->clpi_proportionality_special_thirteen_percent_prorata);
                    unset($data->clpi_proportionality_special_reduced_credit_percent);
					unset($data->clpi_proportionality_special_full_credit_percent);
                    unset($data->clpi_proportionality_special_exempt_with_credit_prorata);
                    unset($data->clpi_proportionality_special_exempt_without_credit_prorata);
                    unset($data->clpi_total_prorata);
				} elseif ($data->clpi_type_prorata == "special") {
                    $data->type_prorata = "special";
                    $data->proportionality_special_one_percent_prorata = $data->clpi_proportionality_special_one_percent_prorata;
                    $data->proportionality_special_two_percent_prorata = $data->clpi_proportionality_special_two_percent_prorata;
                    $data->proportionality_special_four_percent_prorata = $data->clpi_proportionality_special_four_percent_prorata;
                    $data->proportionality_special_thirteen_percent_prorata = $data->clpi_proportionality_special_thirteen_percent_prorata;
                    $data->proportionality_special_exempt_with_credit_prorata = $data->clpi_proportionality_special_exempt_with_credit_prorata;
                    $data->proportionality_special_full_credit_percent = $data->clpi_proportionality_special_full_credit_percent;
                    $data->proportionality_special_reduced_credit_percent = $data->clpi_proportionality_special_reduced_credit_percent;
                    $data->proportionality_special_exempt_without_credit_prorata = $data->clpi_proportionality_special_exempt_without_credit_prorata;
                    $data->total_prorata = $data->clpi_total_prorata;
                    unset($data->clpi_type_prorata);
                    unset($data->clpi_proportionality_general_prorata);
                    unset($data->clpi_proportionality_special_one_percent_prorata);
                    unset($data->clpi_proportionality_special_two_percent_prorata);
                    unset($data->clpi_proportionality_special_four_percent_prorata);
                    unset($data->clpi_proportionality_special_thirteen_percent_prorata);
                    unset($data->clpi_proportionality_special_exempt_with_credit_prorata);
                    unset($data->clpi_proportionality_special_exempt_without_credit_prorata);
                    unset($data->clpi_proportionality_special_reduced_credit_percent);
					unset($data->clpi_proportionality_special_full_credit_percent);
                    unset($data->clpi_total_prorata);
				}
				return response()->json(['message' => $data, 'status' => 'Success'], 200);
			} else {
				return response()->json(['message' => 'No se ha calculado la proporcionalidad para este usuario', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/calculateGeneralProrata
	 * Permite obtener la proporcionalidad de acuerdo a la prorrata general de un cliente
	 */

	public function calculateGeneralProrata(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			if ($info['sales_in_thirteen_percent'] == "" || $info['sales_exempt_with_fiscal_credit'] == "" || $info['sales_exempt_without_fiscal_credit'] == "") {
				return response()->json(['message' => 'Todos los campos son requeridos', 'status' => 'Error'], 200);
			} else {
				$operations_with_credit = round((float)($info['sales_in_thirteen_percent']) + (float)($info['sales_exempt_with_fiscal_credit']), 2);
				$total_operations = round((float)($info['sales_in_thirteen_percent']) + (float)($info['sales_exempt_with_fiscal_credit']) + (float)($info['sales_exempt_without_fiscal_credit']), 2);
				$general_tax = round($operations_with_credit / $total_operations, 2);
				$general_tax_in_percentage = round(($general_tax * 100), 2);
				return response()->json(['message' => $general_tax_in_percentage, 'status' => 'Success'], 200);
			}
			
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/saveGeneralProrata
	 * Permite guardar la prorrata general de un cliente
	 */

	public function saveGeneralProrata(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			if ($info['prorata_general'] == "") {
				return response()->json(['message' => 'Todos los campos son requeridos', 'status' => 'Error'], 200);
			} else {
                $year = date('Y');
				$infoClient = Client_prorata_info::where('clie_id', '=', $user->clie_id)->where('clpi_year', '=', $year)->first();
                $prorata_general = (((float)($info['prorata_general'])) >= 0 && ((float)($info['prorata_general'])) <= 1) ? (((float)($info['prorata_general']))):((float)($info['prorata_general']));
				if ($infoClient == null) {
					$data = [
						'clie_id'                                 => $user->clie_id,
						'clpi_type_prorata'                       => 'general',
						'clpi_proportionality_general_prorata'    => round($prorata_general, 2),
						'clpi_total_prorata'                      => 0,
						'clpi_year'                               => $year, 
						'clpi_description'                        => $year
					];
					$infoClient = Client_prorata_info::create($data);
					return response()->json(['message' => "Información de prorrata general almacenada correctamente", 'status' => 'Success'], 200);
					
				} else {
					$infoClient->clpi_type_prorata = 'general';
					$infoClient->clpi_proportionality_special_one_percent_prorata = 0;
					$infoClient->clpi_proportionality_special_two_percent_prorata = 0;
					$infoClient->clpi_proportionality_special_four_percent_prorata = 0;
					$infoClient->clpi_proportionality_special_thirteen_percent_prorata = 0;
					$infoClient->clpi_proportionality_special_exempt_with_credit_prorata = 0;
                    $infoClient->clpi_proportionality_special_exempt_without_credit_prorata = 0;
                    $infoClient->clpi_proportionality_special_reduced_credit_percent = 0;
					$infoClient->clpi_proportionality_special_full_credit_percent = 0;
					$infoClient->clpi_proportionality_general_prorata = round($prorata_general, 2);
					$infoClient->clpi_total_prorata = 0;
					$infoClient->clpi_year = date('Y'); 
					$infoClient->clpi_description = date('Y'); 
					$infoClient->save();
					return response()->json(['message' => "Información de prorrata general actualizada correctamente", 'status' => 'Success'], 200);
				}
			}
			
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/calculateSpecialProrata
	 * Permite obtener la proporcionalidad de acuerdo a la prorrata especial de un cliente
	 */

	public function calculateSpecialProrata(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			$sales_in_one_percent = (float)($info['sales_in_one_percent']);
			$sales_in_two_percent = (float)($info['sales_in_two_percent']);
			$sales_in_four_percent = (float)($info['sales_in_four_percent']);
			$sales_in_thirteen_percent = (float)($info['sales_in_thirteen_percent']);
			$sales_exempt_with_fiscal_credit = (float)($info['sales_exempt_with_fiscal_credit']);
			$sales_exempt_without_fiscal_credit = (float)($info['sales_exempt_without_fiscal_credit']);
			$total_sales = ($sales_in_one_percent + $sales_in_two_percent + $sales_in_four_percent + $sales_in_thirteen_percent + $sales_exempt_with_fiscal_credit + $sales_exempt_without_fiscal_credit);
			$prorata_one_percent = ($sales_in_one_percent / $total_sales);
			$prorata_two_percent = ($sales_in_two_percent / $total_sales);
			$prorata_four_percent = ($sales_in_four_percent / $total_sales);
			$prorata_thirteen_percent = ($sales_in_thirteen_percent / $total_sales);
			$prorata_exempt_with_fiscal_credit = ($sales_exempt_with_fiscal_credit / $total_sales);
			$prorata_exempt_without_fiscal_credit = ($sales_exempt_without_fiscal_credit / $total_sales);
			$prorata_one_percent_in_percentage = ($prorata_one_percent * 100);
			$prorata_two_percent_in_percentage = ($prorata_two_percent * 100);
			$prorata_four_percent_in_percentage = ($prorata_four_percent * 100);
			$prorata_thirteen_percent_in_percentage = ($prorata_thirteen_percent * 100);
			$prorata_exempt_with_fiscal_credit_in_percentage = ($prorata_exempt_with_fiscal_credit * 100);
			$prorata_exempt_without_fiscal_credit_in_percentage = ($prorata_exempt_without_fiscal_credit * 100);
			$data = [
				'prorata_one_percent'                => $prorata_one_percent_in_percentage,
				'prorata_two_percent'                => $prorata_two_percent_in_percentage,
				'prorata_four_percent'               => $prorata_four_percent_in_percentage,
				'prorata_thirteen_percent'           => $prorata_thirteen_percent_in_percentage,
				'prorata_exempt_with_credit'         => $prorata_exempt_with_fiscal_credit_in_percentage,
				'prorata_exempt_without_credit'      => $prorata_exempt_without_fiscal_credit_in_percentage,
			];
			return response()->json(['message' => $data, 'status' => 'Success'], 200);
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/saveSpecialProrata
	 * Permite guardar la prorrata especial de un cliente
	 */

	public function saveSpecialProrata(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			if ($info['prorata_one_percent'] == "" || $info['prorata_two_percent'] == "" || $info['prorata_four_percent'] == "" || $info['prorata_thirteen_percent'] == "" || $info['prorata_exempt_with_credit'] == "") {
				return response()->json(['message' => 'Todos los campos son requeridos', 'status' => 'Error'], 200);
			} else {
				$prorata_one_percent = (((float)($info['prorata_one_percent'])) >= 0 && ((float)($info['prorata_one_percent'])) <= 1) ? (((float)($info['prorata_one_percent']))):((float)($info['prorata_one_percent']));
				$prorata_two_percent = (((float)($info['prorata_two_percent'])) >= 0 && ((float)($info['prorata_two_percent'])) <= 1) ? (((float)($info['prorata_two_percent']))):((float)($info['prorata_two_percent']));
				$prorata_four_percent = (((float)($info['prorata_four_percent'])) >= 0 && ((float)($info['prorata_four_percent'])) <= 1) ? (((float)($info['prorata_four_percent']))):((float)($info['prorata_four_percent']));
				$prorata_thirteen_percent = (((float)($info['prorata_thirteen_percent'])) >= 0 && ((float)($info['prorata_thirteen_percent'])) <= 1) ? (((float)($info['prorata_thirteen_percent']))):((float)($info['prorata_thirteen_percent']));
				$prorata_exempt_with_credit = (((float)($info['prorata_exempt_with_credit'])) >= 0 && ((float)($info['prorata_exempt_with_credit'])) <= 1) ? (((float)($info['prorata_exempt_with_credit']))):((float)($info['prorata_exempt_with_credit']));
				$prorata_exempt_without_credit = (((float)($info['prorata_exempt_without_credit'])) >= 0 && ((float)($info['prorata_exempt_without_credit'])) <= 1) ? (((float)($info['prorata_exempt_without_credit']))):((float)($info['prorata_exempt_without_credit']));
				$total_prorata = round(($prorata_one_percent + $prorata_two_percent + $prorata_four_percent + $prorata_thirteen_percent + $prorata_exempt_with_credit + $prorata_exempt_without_credit), 2);
                if ($total_prorata == 99.99 || $total_prorata == 100) {
                    $year = date('Y');
					$infoClient = Client_prorata_info::where('clie_id', '=', $user->clie_id)->where('clpi_year', '=', $year)->first();
                    $reducedCredit = (round($prorata_two_percent, 2) + round($prorata_four_percent, 2));
                    $fullCredit = (round($prorata_one_percent, 2) + round($prorata_thirteen_percent, 2) + round($prorata_exempt_with_credit, 2));
                    if ($infoClient == null) {
						$data = [
							'clie_id'                                                             => $user->clie_id,
							'clpi_type_prorata'                                                   => 'special',
							'clpi_proportionality_special_one_percent_prorata'                    => round($prorata_one_percent, 2),
							'clpi_proportionality_special_two_percent_prorata'                    => round($prorata_two_percent, 2),
							'clpi_proportionality_special_four_percent_prorata'                   => round($prorata_four_percent, 2),
							'clpi_proportionality_special_thirteen_percent_prorata'               => round($prorata_thirteen_percent, 2),
							'clpi_proportionality_special_exempt_with_credit_prorata'             => round($prorata_exempt_with_credit, 2),
                            'clpi_proportionality_special_exempt_without_credit_prorata'          => round($prorata_exempt_without_credit, 2),
                            'clpi_proportionality_special_reduced_credit_percent'                 => round($reducedCredit, 2),
                            'clpi_proportionality_special_full_credit_percent'                    => round($fullCredit, 2),
                            'clpi_total_prorata'                                                  => $total_prorata,
							'clpi_year'                                                           => date('Y'), 
							'clpi_description'                                                    => date('Y')             
						];
						$infoClient = Client_prorata_info::create($data);
						return response()->json(['message' => "Información de prorrata especial almacenada correctamente", 'status' => 'Success'], 200);
					} else {
                        $infoClient->clpi_type_prorata = 'special';
						$infoClient->clpi_proportionality_general_prorata = 0;
						$infoClient->clpi_proportionality_special_one_percent_prorata = round($prorata_one_percent, 2);
						$infoClient->clpi_proportionality_special_two_percent_prorata = round($prorata_two_percent, 2);
						$infoClient->clpi_proportionality_special_four_percent_prorata = round($prorata_four_percent, 2);
						$infoClient->clpi_proportionality_special_thirteen_percent_prorata = round($prorata_thirteen_percent, 2);
						$infoClient->clpi_proportionality_special_exempt_with_credit_prorata = round($prorata_exempt_with_credit, 2);
                        $infoClient->clpi_proportionality_special_exempt_without_credit_prorata = round($prorata_exempt_without_credit, 2);
                        $infoClient->clpi_proportionality_special_reduced_credit_percent = round($reducedCredit, 2);
                        $infoClient->clpi_proportionality_special_full_credit_percent = round($fullCredit, 2);
						$infoClient->clpi_total_prorata = $total_prorata;
						$infoClient->clpi_year = date('Y'); 
						$infoClient->clpi_description = date('Y'); 
						$infoClient->save();
						return response()->json(['message' => "Información de prorrata especial actualizada correctamente", 'status' => 'Success'], 200);
					}
				} else {
					return response()->json(['message' => 'Los porcentajes de la prorrata son incorrectos', 'status' => 'Error'], 200);
				}
				
			}
			
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/setConfiguration
	 * Permite guardar la configuración de un cliente
	 */

	public function setConfiguration(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			if ($info['iva_sale'] != "" && $info['dolar_value'] != "") {
				$data = [
					'clie_id'               => $user->clie_id,
					'iva_sale'              => $info['iva_sale'],
					'dolar_value'           => $info['dolar_value'],
				];
				$prev_configurations = Configurations::where('clie_id', '=', $user->clie_id)->first();
				if ($prev_configurations == "") {
					$configuration = Configurations::create($data);
					return response()->json(['message' => 'Datos de configuración almacenados correctamente', 'status' => 'Error'], 200);
				} else {
					$prev_configurations->conf_iva_sale = $info['iva_sale'];
					$prev_configurations->conf_dolar_value = $info['dolar_value'];
					$prev_configurations->save();
					return response()->json(['message' => 'Datos de configuración actualizados correctamente', 'status' => 'Error'], 200);
				}
			} else {
				return response()->json(['message' => 'Todos los datos son requeridos', 'status' => 'Error'], 200);
			}		
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/getConfiguration
	 * Permite obtener la configuración de un cliente
	 */

	public function getConfiguration(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$conf = Configurations::where('clie_id', '=', $user->clie_id)->first();
			if ($conf != "") {
				$data = [
					'id'               => $conf->conf_id,
					'iva_sale'         => $conf->conf_iva_sale,
					'dolar_value'      => $conf->conf_dolar_value,
				];
				return response()->json(['message' => $data, 'status' => 'Success'], 200);
			} else {
				return response()->json(['message' => 'No hay configuración disponible para este cliente', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/setEndOfMonth
	 * Permite guardar el cierre de mes de un cliente
	 */

	public function setEndOfMonth(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			$info = $resp['info'];
			if ((isset($info['name']) && $info['name'] != "") && (isset($info['start_date']) && $info['start_date'] != "") && (isset($info['end_date']) && $info['end_date'] != "")) {
                $date_start = strtotime($info['start_date']);
                $date_end = strtotime($info['end_date']);
                $start_month = date('m', $date_start);
                $start_year = date('Y', $date_start);
                $end_month = date('m', $date_end);
                $end_year = date('Y', $date_end);
                $error_dates = false;
                if ((isset($info['type']) && $info['type'] != "")) {
                    if ($info["type"] == "monthly") {
                        $compare_month = date("m", $date_start);
                        $compare_year = date("Y", $date_start);
                        $aux_date = $compare_year.'-'.$compare_month;
                        $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                        $last_day = date('d', strtotime("{$aux} - 1 day"));
                        $compare_date = $aux_date.'-'.$last_day;
                        if ($compare_month == $end_month && $compare_year == $end_year) {
                            $error_dates = true;
                        }
                        $type_period = "monthly";
                    } else {
                        if ($info["type"] == "quarterly") {
                            $compare_month = date("m", strtotime("+2 month", $date_start));
                            $compare_year = date("Y", strtotime("+2 month", $date_start));
                            $aux_date = $compare_year.'-'.$compare_month;
                            $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                            $last_day = date('d', strtotime("{$aux} - 1 day"));
                            $compare_date = $aux_date.'-'.$last_day;
                            $type_period = "quarterly";
                        } elseif ($info["type"] == "biannual") {
                            $compare_month = date("m", strtotime("+5 month", $date_start));
                            $compare_year = date("Y", strtotime("+5 month", $date_start));
                            $aux_date = $compare_year.'-'.$compare_month;
                            $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                            $last_day = date('d', strtotime("{$aux} - 1 day"));
                            $compare_date = $aux_date.'-'.$last_day;
                            $type_period = "biannual";
                        } elseif ($info["type"] == "annual") {
                            $compare_month = date("m", strtotime("+11 month", $date_start));
                            $compare_year = date("Y", strtotime("+11 month", $date_start));
                            $aux_date = $compare_year.'-'.$compare_month;
                            $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                            $last_day = date('d', strtotime("{$aux} - 1 day"));
                            $compare_date = $aux_date.'-'.$last_day;
                            $type_period = "annual";
                        }
                        $compare_date = strtotime($compare_date);
                        if (($date_end >= $date_start) && ($date_end <= $compare_date)) {
                            $error_dates = true;
                        }
                    }
                } else {
                    $compare_month = date("m", $date_start);
                    $compare_year = date("Y", $date_start);
                    $aux_date = $compare_year.'-'.$compare_month;
                    $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                    $last_day = date('d', strtotime("{$aux} - 1 day"));
                    $compare_date = $aux_date.'-'.$last_day;
                    if ($compare_month == $end_month && $compare_year == $end_year) {
                        $error_dates = true;
                    }
                    $type_period = "monthly";
                }
                if ($error_dates == false) {
                    return response()->json(['message' => 'La fecha de fin no entra en el tipo de período seleccionado', 'status' => 'Error'], 200);
                }
				$data = [
                    'clie_id'               => $user->clie_id,
                    'enom_name'             => $info['name'],
                    'enom_description'      => '',
                    'enom_start_date'       => $info['start_date'],
                    'enom_end_date'         => $info['end_date'],
                    'enom_type_period'      => $type_period
                ];
                if (isset($info['description']) && $info['description'] != "") {
                    $data['enom_description'] = $info['description'];
                }
                $result = $this->getCurrentPeriod(date('Y-m-d'), $user->clie_id);
				if ($result == false) {
					$configuration = End_of_month::create($data);
					return response()->json(['message' => 'Cierre de mes almacenado correctamente', 'status' => 'Success'], 200);
				} else {
					return response()->json(['message' => 'Ya existe un cierre de mes creado', 'status' => 'Error'], 200);
				}
			} else {
				return response()->json(['message' => 'Todos los datos son requeridos', 'status' => 'Error'], 200);
			}		
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
	}

	/**
	 * Endpoint, /api/v1/getEndOfMonth
	 * Permite obtener el cierre de mes específico de un cliente
	 */

	public function getEndOfMonth(Request $request, $month, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
			$user = $this->users->getUserByUsername($user_name);
			if (($month != "" && (int)$month > 0 && (int)$month < 13) && ($year != ""  && (int)$year > 1900 && (int)$year < 2100)) {
                $aux_date = $year.'-'.$month;
                $aux = date('Y-m-d', strtotime("{$aux_date} + 1 month"));
                $last_day = date('d', strtotime("{$aux} - 1 day"));
                $date = $aux_date.'-'.$last_day;
                $endofmonth = $this->getCurrentPeriod($date, $user->clie_id);
				if ($endofmonth != false) {
					$data = [
						'id'               => $endofmonth->enom_id,
                        'start_date'       => $endofmonth->enom_start_date,
                        'end_date'         => $endofmonth->enom_end_date,
                        'name'             => $endofmonth->enom_name,
					];
					return response()->json(['message' => $data, 'status' => 'Success'], 200);
				} else {
					return response()->json(['message' => 'No hay cierre de mes en el lapso seleccionado', 'status' => 'Error'], 200);
				}
			} else {
				return response()->json(['message' => 'Los datos de cierre de mes no existen o la información suministrada es errónea', 'status' => 'Error'], 200);
			}
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /**
	 * Endpoint, /api/v1/getCurrentEndOfMonth
	 * Permite obtener el cierre de mes en curso de un cliente
	 */

	public function getCurrentEndOfMonth(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $user = $this->users->getUserByUsername($user_name);
            $endofmonth = $this->getCurrentPeriod(date('Y-m-d'), $user->clie_id);
            if ($endofmonth != false) {
                $data = [
                    'id'               => $endofmonth->enom_id,
                    'start_date'       => $endofmonth->enom_start_date,
                    'end_date'         => $endofmonth->enom_end_date,
                    'name'             => $endofmonth->enom_name,
                ];
                return response()->json(['message' => $data, 'status' => 'Success'], 200);
            } else {
                return response()->json(['message' => 'No se ha creado un cierre para el mes en curso, para empezar a cargar facturas debe crear un nuevo cierre de mes', 'status' => 'Error'], 200);
            }
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }
    
    /**
	 * Endpoint, /api/v1/setSummaryOfMonth
	 * Permite obtener el cierre de mes específico de un cliente
	 */

	public function setSummaryOfMonth(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
        $password = $request->header('pass');
        $info = (isset($resp['info']))?$resp['info']:'';
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            if ((isset($info['month']) && $info['month'] != '') && (isset($info['year']) && $info['year'] != '')) {
                $year = $info['year'];
                $month = $info['month'];
                $user = $this->users->getUserByUsername($user_name);
                $begin_date = $year.'-'.$month.'-01';
                $aux_month = $month;
                $aux_year = $year;
                $month = (int)$month;   
                $currentYear = date('Y');
                $currentMonth = date('m');
                $leapYear = checkdate(2, 29, ($year==NULL)? date('Y'):$year);
                if ($month < 0 || $month > 12 || $month != $currentMonth) {
                    return response()->json(['message' => 'Debe especificar un mes válido', 'status' => 'Error'], 200);
                }
                $year = (int)$year; 
                if ($year < 2018 || $year != $currentYear) {
                    return response()->json(['message' => 'Debe especificar un año válido', 'status' => 'Error'], 200);
                }
                $month = $month.'';
                $year = $year.'';
                if ($aux_month == '01' || $aux_month == '03' || $aux_month == '05' || $aux_month == '07' || $aux_month == '08' || $aux_month == '10' || $aux_month == '12') {
                    $end_date = $year.'-'.$aux_month.'-31';
                } elseif ($month == '02') {
                    if ($leapYear) {
                        $end_date = $year.'-'.$aux_month.'-29';
                    } else {
                        $end_date = $year.'-'.$aux_month.'-28';
                    }
                } else {
                    $end_date = $year.'-'.$aux_month.'-30';
                }
                $sales = Sale_invoices::whereBetween('sain_date', [$begin_date, $end_date])
                                    ->where('clie_id', '=', $user->clie_id)
                                    ->where('tydo_id','=', 3)
                                    ->get();
                $purchases = Purchase_invoices::whereBetween('puin_date', [$begin_date, $end_date])
                                    ->where('clie_id', '=', $user->clie_id)
                                    ->whereIn('tydo_id', [1, 2, 4])
                                    ->get();
                if (count($sales) > 0 && count($purchases) > 0) {
                    $supported_iva = 0;
                    $paid_out_iva = 0;
                    $deducted_iva = 0;
                    $expenses = 0;
                    $iva_to_pay = 0;
                    $one_percent_visible = 0;
                    $two_percent_visible = 0;
                    $four_percent_visible = 0;
                    $eight_percent_visible = 0;
                    $thirteen_percent_visible = 0;
                    $one_percent_prorata = 0;
                    $two_percent_prorata = 0;
                    $four_percent_prorata = 0;
                    $eight_percent_prorata = 0;
                    $thirteen_percent_prorata = 0;
                    $exempt_with_credit = 0;
                    $total_visible = 0;
                    $total_prorata = 0;
                    for ($i = 0; $i < count($sales); $i++) {
                        $supported_iva += (float)$sales[$i]->sain_tax_amount;
                    }
                    for ($i = 0; $i < count($purchases); $i++) {
                        $paid_out_iva += (float)$purchases[$i]->puin_received_iva_total;
                        $deducted_iva += (float)$purchases[$i]->puin_paid_out_iva_total;
                        $one_percent_visible += (float)$purchases[$i]->puin_paid_out_iva_one_percent;
                        $two_percent_visible += (float)$purchases[$i]->puin_paid_out_iva_two_percent;
                        $four_percent_visible += (float)$purchases[$i]->puin_paid_out_iva_four_percent;
                        $eight_percent_visible += (float)$purchases[$i]->puin_paid_out_iva_eight_percent;
                        $thirteen_percent_visible += (float)$purchases[$i]->puin_paid_out_iva_thirteen_percent;
                        $one_percent_prorata += (float)$purchases[$i]->puin_paid_out_iva_one_percent_prorata;
                        $two_percent_prorata += (float)$purchases[$i]->puin_paid_out_iva_two_percent_prorata;
                        $four_percent_prorata += (float)$purchases[$i]->puin_paid_out_iva_four_percent_prorata;
                        $eight_percent_prorata += (float)$purchases[$i]->puin_paid_out_iva_eight_percent_prorata;
                        $thirteen_percent_prorata += (float)$purchases[$i]->puin_paid_out_iva_thirteen_percent_prorata;
                        $exempt_with_credit += (float)$purchases[$i]->puin_paid_out_iva_exempt_with_credit;
                        
                    }
                    $expenses += (float)($paid_out_iva - $deducted_iva);
                    $total_visible = ($one_percent_visible + $two_percent_visible + $four_percent_visible + $eight_percent_visible + $thirteen_percent_visible);
                    $total_prorata = ($one_percent_prorata + $two_percent_prorata + $four_percent_prorata + $eight_percent_prorata + $thirteen_percent_prorata + $exempt_with_credit);
                    $iva_to_pay = (float)($supported_iva - $deducted_iva);
                    $message = '';
                    $credit_fiscal = 0;
                    $debit_fiscal = 0;
                    if ($iva_to_pay < 0) {
                        $credit_fiscal = ($iva_to_pay * (-1));
                        $debit_fiscal = 0;
                        $message = 'el usuario tiene un saldo a favor de: '.$credit_fiscal;
                    } elseif ($iva_to_pay >= 0) {
                        $credit_fiscal = 0;
                        $debit_fiscal = ($iva_to_pay);
                        $message = 'el usuario debe pagar a hacienda el valor de: '.$iva_to_pay;
                    }
                    if ($aux_month == '01') {
                        $search_month = '12';
                        $search_year = (string)(((int)$aux_year) - 1);
                    } else {
                        $search_month = (int)(((int)$aux_month) - 1);
                        if ($search_month < 10) {
                            $search_month = (string)('0'.$search_month);
                        } else {
                            $search_month = (string)($search_month);
                        }
                        $search_year = $year;
                    }
                    $prorata = Client_prorata_info::where('clie_id', '=', $user->clie_id)
                                                    ->where('clpi_year', '=', $year)
                                                    ->first();
                    $data = [
                        'clie_id'                        => $user->clie_id,
                        'suom_year'                      => $aux_year,
                        'suom_month'                     => $aux_month,
                        'suom_received_iva'              => round($supported_iva, 2),
                        'suom_paid_out_iva'              => round($deducted_iva, 2),
                        'suom_expenses_iva'              => round($expenses, 2),
                        'suom_fiscal_credit'             => round($credit_fiscal, 2),
                        'suom_fiscal_debit'              => round($debit_fiscal, 2),
                        'suom_iva_to_pay'                => round($iva_to_pay, 2),
                        'suom_paid_out_one_visible'      => round($one_percent_visible, 2),
                        'suom_paid_out_two_visible'      => round($two_percent_visible, 2),
                        'suom_paid_out_four_visible'     => round($four_percent_visible, 2),
                        'suom_paid_out_eight_visible'    => round($eight_percent_visible, 2), 
                        'suom_paid_out_thirteen_visible' => round($thirteen_percent_visible, 2), 
                        'suom_paid_out_visible_total'    => round($total_visible, 2), 
                        'suom_paid_out_one_prorata'      => round($one_percent_prorata, 2),
                        'suom_paid_out_two_prorata'      => round($two_percent_prorata, 2),
                        'suom_paid_out_four_prorata'     => round($four_percent_prorata, 2),
                        'suom_paid_out_eight_prorata'    => round($eight_percent_prorata, 2),
                        'suom_paid_out_thirteen_prorata' => round($thirteen_percent_prorata, 2),
                        'suom_paid_out_prorata_total'    => round($total_prorata, 2), 
                        'suom_type_prorata'              => (string)$prorata->clpi_type_prorata,
                        'suom_exempt_with_credit'        => round($exempt_with_credit, 2),
                        'suom_exempt_without_credit'     => 0
                    ];
                    $previousSummary = Summary_of_month::where('clie_id', '=', $user->clie_id)
                                                ->where('suom_year', '=', $search_year)
                                                ->where('suom_month', '=', $search_month)
                                                ->first();
                    if (count($previousSummary) > 0) {
                        if ($previousSummary->suom_fiscal_credit > 0) {
                            $previous_iva_to_pay = $previousSummary->suom_fiscal_credit;
                            $deducted_iva += $previous_iva_to_pay;
                            $iva_to_pay = (float)($supported_iva - $deducted_iva);
                        }
                        $data['suom_paid_out_iva'] = $deducted_iva;
                        $data['suom_iva_to_pay'] = $iva_to_pay;
                        if ($iva_to_pay < 0) {
                            
                            $data['suom_fiscal_credit'] = ($iva_to_pay * (-1));
                            $data['suom_fiscal_debit'] = 0;
                            $message = 'el usuario tiene un saldo a favor de: '.$data['suom_fiscal_credit'];
                        } elseif ($iva_to_pay >= 0) {
                            $data['suom_fiscal_credit'] = 0;
                            $data['suom_fiscal_debit'] = ($iva_to_pay);
                            $message = 'el usuario debe pagar a hacienda el valor de: '.$iva_to_pay;
                        }
                    }
                    $summary = Summary_of_month::where('clie_id', '=', $user->clie_id)
                                                    ->where('suom_year', '=', $aux_year)
                                                    ->where('suom_month', '=', $aux_month)
                                                    ->first();
                    
                    if (count($summary) == 0) {
                        $summary = Summary_of_month::create($data);
                        if ($summary->suom_type_prorata == 'general') {
                            $data = [
                                'received_iva'        => $summary->suom_received_iva,
                                'paid_out_iva'        => $summary->suom_paid_out_iva,
                                'expenses'            => $summary->suom_expenses_iva,
                                'iva_to_pay'          => $summary->suom_iva_to_pay,
                            ];
                        } elseif ($summary->suom_type_prorata == 'special') {
                            $data = [
                                'received_iva'               => $summary->suom_received_iva,
                                'paid_out_iva'               => $summary->suom_paid_out_iva,
                                'expenses'                   => $summary->suom_expenses_iva,
                                'iva_to_pay'                 => $summary->suom_iva_to_pay,
                                'one_percent_visible'        => $summary->suom_paid_out_one_visible,
                                'two_percent_visible'        => $summary->suom_paid_out_two_visible,
                                'four_percent_visible'       => $summary->suom_paid_out_four_visible,
                                'eight_percent_visible'      => $summary->suom_paid_out_eight_visible,
                                'thirteen_percent_visible'   => $summary->suom_paid_out_thirteen_visible,
                                'one_percent_prorata'        => $summary->suom_paid_out_one_prorata,
                                'two_percent_prorata'        => $summary->suom_paid_out_two_prorata,
                                'four_percent_prorata'       => $summary->suom_paid_out_four_prorata,
                                'eight_percent_prorata'      => $summary->suom_paid_out_eight_prorata,
                                'thirteen_percent_prorata'   => $summary->suom_paid_out_thirteen_prorata,
                                'exempt_with_credit_prorata' => $summary->suom_exempt_with_credit
                            ];
                        }
                        return response()->json(['message' => 'Resumen del mes creado correctamente, además '.$message, 'data' => $data, 'status' => 'Success'], 200);
                    } else {
                        $summary->suom_year = $aux_year;
                        $summary->suom_month = $aux_month;
                        $summary->suom_received_iva = round($supported_iva, 2);
                        $summary->suom_paid_out_iva = round($deducted_iva, 2);
                        $summary->suom_expenses_iva = round($expenses, 2);
                        $summary->suom_fiscal_credit = round($paid_out_iva, 2);
                        $summary->suom_fiscal_debit = round($supported_iva, 2);
                        $summary->suom_iva_to_pay = round($iva_to_pay, 2);
                        $summary->suom_paid_out_one_visible = round($one_percent_visible, 2);
                        $summary->suom_paid_out_two_visible = round($two_percent_visible, 2);
                        $summary->suom_paid_out_four_visible = round($four_percent_visible, 2);
                        $summary->suom_paid_out_eight_visible = round($eight_percent_visible, 2);
                        $summary->suom_paid_out_thirteen_visible = round($thirteen_percent_visible, 2);
                        $summary->suom_paid_out_visible_total = round($total_visible, 2);
                        $summary->suom_paid_out_one_prorata = round($one_percent_prorata, 2);
                        $summary->suom_paid_out_two_prorata = round($two_percent_prorata, 2);
                        $summary->suom_paid_out_four_prorata = round($four_percent_prorata, 2);
                        $summary->suom_paid_out_eight_prorata = round($eight_percent_prorata, 2);
                        $summary->suom_paid_out_thirteen_prorata = round($thirteen_percent_prorata, 2);
                        $summary->suom_paid_out_prorata_total = round($total_prorata, 2); 
                        $summary->suom_type_prorata = (string)$prorata->clpi_type_prorata;
                        $summary->suom_exempt_with_credit = round($exempt_with_credit, 2);
                        $summary->suom_exempt_without_credit = 0;
                        $summary->save();
                        if ($summary->suom_type_prorata == 'general') {
                            $data = [
                                'received_iva'        => $summary->suom_received_iva,
                                'paid_out_iva'        => $summary->suom_paid_out_iva,
                                'expenses'            => $summary->suom_expenses_iva,
                                'iva_to_pay'          => $summary->suom_iva_to_pay,
                            ];
                        } elseif ($summary->suom_type_prorata == 'special') {
                            $data = [
                                'received_iva'               => $summary->suom_received_iva,
                                'paid_out_iva'               => $summary->suom_paid_out_iva,
                                'expenses'                   => $summary->suom_expenses_iva,
                                'iva_to_pay'                 => $summary->suom_iva_to_pay,
                                'one_percent_visible'        => $summary->suom_paid_out_one_visible,
                                'two_percent_visible'        => $summary->suom_paid_out_two_visible,
                                'four_percent_visible'       => $summary->suom_paid_out_four_visible,
                                'eight_percent_visible'      => $summary->suom_paid_out_eight_visible,
                                'thirteen_percent_visible'   => $summary->suom_paid_out_thirteen_visible,
                                'one_percent_prorata'        => $summary->suom_paid_out_one_prorata,
                                'two_percent_prorata'        => $summary->suom_paid_out_two_prorata,
                                'four_percent_prorata'       => $summary->suom_paid_out_four_prorata,
                                'eight_percent_prorata'      => $summary->suom_paid_out_eight_prorata,
                                'thirteen_percent_prorata'   => $summary->suom_paid_out_thirteen_prorata,
                                'exempt_with_credit_prorata' => $summary->suom_exempt_with_credit
                            ];
                        }
                        return response()->json(['message' => 'Resumen del mes actualizado correctamente, además '.$message, 'data' => $data, 'status' => 'Success'], 200);
                    }
                } elseif (count($sales) == 0 && count($purchases) > 0) {
                    return response()->json(['message' => 'No existen facturas de ventas para calcular el resumen del mes', 'status' => 'Error'], 200);
                } elseif (count($sales) > 0 && count($purchases) == 0) {
                    return response()->json(['message' => 'No existen facturas de compras para calcular el resumen del mes', 'status' => 'Error'], 200);
                } elseif (count($sales) == 0 && count($purchases) == 0) {
                    return response()->json(['message' => 'No existen facturas de compras y ventas para calcular el resumen del mes', 'status' => 'Error'], 200);
                } else {
                    return response()->json(['message' => 'No existen facturas para el período seleccionado', 'status' => 'Error'], 200);
                }
            } else {
                return response()->json(['message' => 'Se debe especificar el mes y año sobre el cual desea generar el resumen de mes', 'status' => 'Error'], 200);
            }
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }
    
    /** 
	 *  Endpoint, /api/v1/generateReport
	 *  Permite generar borradores de reportes
	 */
	public function generateReport(Request $request)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $user = $this->users->getUserByUsername($user_name);
            if (isset($resp['info'])) {
                $info = $resp['info'];
            } else {
                return response()->json(['message' => 'No se ha suministrado información', 'status' => 'Error'], 200);
            }
            
			if (isset($info['type']) && $info['type'] != "" && $info['type'] >= 1 && $info['type'] < 4) {
                if (isset($info['confirm']) && ($info['confirm'] == "no" || $info['confirm'] == "yes")) {
                    $confirm = $info['confirm'];
                    if (!isset($info['month']) || !isset($info['year'])) {
                        return response()->json(['message' => 'Se debe especificar el mes y año del reporte que se desea almacenar', 'status' => 'Error'], 200);
                    } elseif ((isset($info['month']) && $info['month'] == "") || (isset($info['year']) && $info['year'] == "")) {
                        return response()->json(['message' => 'Debe especificar el mes y año del reporte que se desea almacenar', 'status' => 'Error'], 200);
                    }
                    $report = Reports::where('clie_id', '=', $user->clie_id)
                                    ->whereMonth('repo_date', '=', $info['month'])
                                    ->whereYear('repo_date', '=', $info['year'])
                                    ->where('tyre_id', '=', $info['type'])
                                    ->first();
                    if (count($report) > 0 && ($confirm == "no")) {
                        unlink($report->repo_file_pdf);
                        $report->delete();
                        return response()->json(['message' => 'El borrador no fue almacenado por petición del usuario', 'status' => 'Success'], 200);
                    } elseif ($confirm == "yes") {
                        $file = $this->generateFile('excel', $info['type'], $user->clie_id, $info['month'], $info['year']);
                        $file = $this->generateFile('xml', $info['type'], $user->clie_id, $info['month'], $info['year']);
                        return response()->json(['message' => 'Borrador almacenado correctamente', 'status' => 'Success'], 200);
                    }
                }
                $type_report = $info['type'];
                if ($type_report == 1) {
                    if (!isset($info['month']) || !isset($info['year'])) {
                        return response()->json(['message' => 'Para generar el reporte de iva se necesita el mes y el año', 'status' => 'Error'], 200);
                    } elseif ((isset($info['month']) && $info['month'] == "") || (isset($info['year']) && $info['year'] == "")) {
                        return response()->json(['message' => 'Debe especificar el mes y el año para generar el reporte', 'status' => 'Error'], 200);
                    }
                    $aux_month = $info['month'];
                    if ((int)$aux_month < 10) {
                        $aux_month = (string)('0'.$aux_month);
                    } else {
                        $aux_month = (string)($aux_month);
                    }
                    $aux_year = $info['year'];
                    $month = (int)$info['month'];
                    $year = (int)$info['year'];
                    if ($month < 0 || $month > 12) {
                        return response()->json(['message' => 'Debe especificar un mes válido', 'status' => 'Error'], 200);
                    }
                    if ($year < 2019) {
                        return response()->json(['message' => 'Debe especificar un año válido', 'status' => 'Error'], 200);
                    }
                    $file = $this->generateFile('pdf', $type_report, $user->clie_id, $month, $year);
                } 
                if ($type_report == 2) {
                    if (!isset($info['year'])) {
                        return response()->json(['message' => 'Para generar el reporte de islr se necesita el año', 'status' => 'Error'], 200);
                    } elseif ((isset($info['year']) && $info['year'] == "")) {
                        return response()->json(['message' => 'Debe especificar el año para generar el reporte', 'status' => 'Error'], 200);
                    }
                    $aux_year = $info['year'];
                    $year = (int)$info['year'];
                    if ($year < 2018) {
                        return response()->json(['message' => 'Debe especificar un año válido', 'status' => 'Error'], 200);
                    }
                    $file = $this->generateFile('pdf', $type_report, $user->clie_id, '', $aux_year);
                }
                if ($file != 'No hay datos del mes o año seleccionado para generar al reporte') {
                    return response()->json(['message' => $file, 'status' => 'Success'], 200);
                } else {
                    return response()->json(['message' => $file, 'status' => 'Error'], 200);
                }
			} else {
				return response()->json(['message' => 'Se debe indicar el tipo de reporte', 'status' => 'Error'], 200);
			}
			
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /** 
	 *  Endpoint, /api/v1/getReportsByMonth
	 *  Permite obtener los reportes de un cliente por tipo en el mes y año seleccionado
	 */
	public function getReportsByMonth(Request $request, $type, $month, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $user = $this->users->getUserByUsername($user_name);
            if ((int)$type < 0 && (int)$type > 3) {
                return response()->json(['message' => 'Debe especificar un tipo de reporte válido', 'status' => 'Error'], 200);  
            }
            if ((int)$month < 0 && (int)$month > 12) {
                return response()->json(['message' => 'Debe especificar un mes válido', 'status' => 'Error'], 200);  
            }
            if ((int)$year < 2019) {
                return response()->json(['message' => 'Debe especificar un año válido', 'status' => 'Error'], 200);  
            } 
            $reports = Reports::where('clie_id', '=', $user->clie_id)
                                ->whereMonth('repo_date', '=', $month)
                                ->whereYear('repo_date', '=', $year)
                                ->where('tyre_id', '=', $type)
                                ->get();
            if (count($reports) > 0) {
                for ($i = 0; $i < count($reports); $i++) {
                    $reports[$i]['id'] = $reports[$i]['repo_id'];
                    $reports[$i]['date'] = $reports[$i]['repo_date'];
                    $reports[$i]['name'] = $reports[$i]['repo_file_name'];
                    $reports[$i]['url_pdf'] = $reports[$i]['repo_file_pdf'];
                    $reports[$i]['url_excel'] = $reports[$i]['repo_file_excel'];
                    $reports[$i]['url_xml'] = $reports[$i]['repo_file_xml'];
                    unset($reports[$i]['repo_id']);
                    unset($reports[$i]['tyre_id']);
                    unset($reports[$i]['clie_id']);
                    unset($reports[$i]['repo_date']);
                    unset($reports[$i]['repo_file_name']);
                    unset($reports[$i]['repo_file_pdf']);
                    unset($reports[$i]['repo_file_excel']);
                    unset($reports[$i]['repo_file_xml']);
                    unset($reports[$i]['created_at']);
                    unset($reports[$i]['updated_at']);
                }
                return response()->json(['message' => $reports, 'status' => 'Success'], 200);
            } else {
                return response()->json(['message' => 'No hay reportes del tipo seleccionado en el mes y año seleccionado', 'status' => 'Error'], 200);
            }
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /** 
	 *  Endpoint, /api/v1/getReportsByYear
	 *  Permite obtener los reportes de un cliente por tipo en el año seleccionado
	 */
    // public function getReportsByYear(Request $request, $type, $year)
    public function getReportsByYear(Request $request, $year)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $user = $this->users->getUserByUsername($user_name);
            /*if ((int)$type < 0 && (int)$type > 3) {
                return response()->json(['message' => 'Debe especificar un tipo de reporte válido', 'status' => 'Error'], 200);  
            }*/
            if ((int)$year < 2019) {
                return response()->json(['message' => 'Debe especificar un año válido', 'status' => 'Error'], 200);  
            } 
            $reports = Reports::where('clie_id', '=', $user->clie_id)
                                ->whereYear('repo_date', '=', $year)
                                // ->where('tyre_id', '=', $type)
                                ->get();
            if (count($reports) > 0) {
                for ($i = 0; $i < count($reports); $i++) {
                    $reports[$i]['id'] = $reports[$i]['repo_id'];
                    $reports[$i]['date'] = $reports[$i]['repo_date'];
                    $reports[$i]['name'] = $reports[$i]['repo_file_name'];
                    $reports[$i]['url_pdf'] = $reports[$i]['repo_file_pdf'];
                    $reports[$i]['url_excel'] = $reports[$i]['repo_file_excel'];
                    $reports[$i]['url_xml'] = $reports[$i]['repo_file_xml'];
                    if ($reports[$i]['tyre_id'] == 1) {
                        $reports[$i]['type_report'] = 'iva';
                    } elseif ($reports[$i]['tyre_id'] == 2) {
                        $reports[$i]['type_report'] = 'islr';
                    } elseif ($reports[$i]['tyre_id'] == 3) {
                        $reports[$i]['type_report'] = 'providers_and_clients';
                    }
                    unset($reports[$i]['repo_id']);
                    unset($reports[$i]['tyre_id']);
                    unset($reports[$i]['clie_id']);
                    unset($reports[$i]['repo_date']);
                    unset($reports[$i]['repo_file_name']);
                    unset($reports[$i]['repo_file_pdf']);
                    unset($reports[$i]['repo_file_excel']);
                    unset($reports[$i]['repo_file_xml']);
                    unset($reports[$i]['created_at']);
                    unset($reports[$i]['updated_at']);
                }
                return response()->json(['message' => $reports, 'status' => 'Success'], 200);
            } else {
                return response()->json(['message' => 'No hay reportes en el año seleccionado', 'status' => 'Error'], 200);
            }
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

    /** 
	 *  Endpoint, /api/v1/getReports
	 *  Permite obtener todos los reportes de un cliente por tipo
	 */
	public function getReports(Request $request, $type)
	{
		$this->users = new Users();
		$resp = $request->json()->all();
		$user_name = $request->header('user');
		$password = $request->header('pass');
		$check = $this->users->checkUser($user_name, $password);
		if ($check == 'Correcto') {
            $user = $this->users->getUserByUsername($user_name);
            if ((int)$type < 0 && (int)$type > 3) {
                return response()->json(['message' => 'Debe especificar un tipo de reporte válido', 'status' => 'Error'], 200);  
            }
            $reports = Reports::where('clie_id', '=', $user->clie_id)
                                ->where('tyre_id', '=', $type)
                                ->get();
            if (count($reports) > 0) {
                for ($i = 0; $i < count($reports); $i++) {
                    $reports[$i]['id'] = $reports[$i]['repo_id'];
                    $reports[$i]['date'] = $reports[$i]['repo_date'];
                    $reports[$i]['name'] = $reports[$i]['repo_file_name'];
                    $reports[$i]['url_pdf'] = $reports[$i]['repo_file_pdf'];
                    $reports[$i]['url_excel'] = $reports[$i]['repo_file_excel'];
                    $reports[$i]['url_xml'] = $reports[$i]['repo_file_xml'];
                    unset($reports[$i]['repo_id']);
                    unset($reports[$i]['tyre_id']);
                    unset($reports[$i]['clie_id']);
                    unset($reports[$i]['repo_date']);
                    unset($reports[$i]['repo_file_name']);
                    unset($reports[$i]['repo_file_pdf']);
                    unset($reports[$i]['repo_file_excel']);
                    unset($reports[$i]['repo_file_xml']);
                    unset($reports[$i]['created_at']);
                    unset($reports[$i]['updated_at']);
                }
                return response()->json(['message' => $reports, 'status' => 'Success'], 200);
            } else {
                return response()->json(['message' => 'No hay reportes del tipo seleccionado en el año seleccionado', 'status' => 'Error'], 200);
            }
		} else {
			return response()->json(['message' => $check, 'status' => 'Error'], 200);
        }
    }

}


