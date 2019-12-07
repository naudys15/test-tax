<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//Endpoints de la versión 1 de la aplicación
Route::group(['prefix' => 'api/v1/'], function() {
    //Logearse
    Route::post('login', 'Users@login');
    //Obtener usuario con un id específico
    Route::get('getUser/{user}', 'Users@getUser');
    //Actualizar usuario
    Route::put('updateUser/{user}', 'Users@updateUser');
    Route::patch('updateUser/{user}', 'Users@updateUser');
    //Registrar un nuevo usuario
    Route::post('register', 'Users@register');
    //Subir una factura de tipo xml
    Route::post('uploadFiles', 'Documents@uploadFiles');
    //Subir una factura de forma manual
    Route::post('uploadFilesManually', 'Documents@uploadFilesManually');
    //Subir una factura excel
    Route::post('uploadFilesExcel', 'Documents@uploadFilesExcel');
    //Obtener datos de las compras en un período específico
    Route::get('getPurchases/{month}/{year}', 'Documents@getPurchases');
    //Obtener datos de las ventas en un período específico
    Route::get('getSales/{month}/{year}', 'Documents@getSales');
    //Obtener datos de los gastos en un período específico
    Route::get('getExpenses/{month}/{year}', 'Documents@getExpenses');
    //Borrar una factura
    Route::delete('deleteInvoice/{id}/{type}', 'Documents@deleteInvoice');
    //Calcular la proporcionalidad de un cliente específico
    Route::post('calculateProportionality', 'Documents@calculateProportionality');
    //Calcular la proporcionalidad de un cliente específico
    Route::get('getProportionality', 'Documents@getProportionality');
    //Calcular la prorrata general
    Route::post('calculateGeneralProrata', 'Documents@calculateGeneralProrata');
    //Guardar la prorrata general
    Route::post('saveGeneralProrata', 'Documents@saveGeneralProrata');
    //Calcular la prorrata especial
    Route::post('calculateSpecialProrata', 'Documents@calculateSpecialProrata');
    //Guardar la prorrata especial
    Route::post('saveSpecialProrata', 'Documents@saveSpecialProrata');
    //Guardar la nueva configuración del cliente
    Route::post('setConfiguration', 'Documents@setConfiguration');
    //Obtener la configuración del cliente
    Route::get('getConfiguration', 'Documents@getConfiguration');
    //Guardar el cierre de mes del cliente
    Route::post('setEndOfMonth', 'Documents@setEndOfMonth');
    //Obtener el cierre de mes del cliente
    Route::get('getEndOfMonth/{month}/{year}', 'Documents@getEndOfMonth');
    //Obtener el cierre de mes actual o en curso del cliente
    Route::get('getCurrentEndOfMonth', 'Documents@getCurrentEndOfMonth');
    //Guardar los resultados de mes de un cliente
    Route::post('setSummaryOfMonth', 'Documents@setSummaryOfMonth');
    //Generar borradores
    Route::post('generateReport', 'Documents@generateReport');
    //Obtener los reportes de un cliente por tipo en un mes especifico
    // Route::get('getReportsByMonth/{type}/{month}/{year}', 'Documents@getReportsByMonth');
    //Obtener los reportes de un cliente por tipo en un año especifico
    // Route::get('getReportsByYear/{type}/{year}', 'Documents@getReportsByYear');
    //Obtener los reportes de un cliente por un año especifico
    Route::get('getReportsByYear/{year}', 'Documents@getReportsByYear');
    //Obtener los reportes de un cliente por tipo
    Route::get('getReports/{type}', 'Documents@getReports');

    //Obtener los tipos de documentos de identidad
    Route::get('getTypeDocumentId', 'Types@getTypeDocumentId');
    //Obtener los tipos de documentos
    Route::get('getTypeDocument', 'Types@getTypeDocument');
    //Obtener los tipos de unidades de medida
    Route::get('getTypeMeasureUnit', 'Types@getTypeMeasureUnit');
    //Obtener los tipos de métodos de pago
    Route::get('getTypePaymentMethod', 'Types@getTypePaymentMethod');
    //Obtener los tipos de reportes
    Route::get('getTypeReport', 'Types@getTypeReport');
    //Obtener los tipos de condiciones de venta
    Route::get('getTypeSaleTerms', 'Types@getTypeSaleTerms');
    //Obtener los tipos de impuestos de iva
    Route::get('getTypeTaxIva', 'Types@getTypeTaxIva');
    //Obtener los tipos de impuestos
    Route::get('getTypeTax', 'Types@getTypeTax');
    //Obtener los tipos de iva de venta 
    Route::get('getTypesIvaSale', 'Types@getTypesIvaSale');
    //Obtener todos los tipos de iva de venta 
    Route::get('getAllTypesIvaSale', 'Types@getAllTypesIvaSale');
    //Obtener todos los tipos de cliente
    Route::get('getTypeClient', 'Types@getTypeClient');
    //Obtener los paises
    Route::get('getCountries', 'Types@getCountries');
    //Obtener las provincias
    Route::get('getProvinces/{country}', 'Types@getProvinces');
    //Obtener los cantones
    Route::get('getCantons/{country}/{province}', 'Types@getCantons');
    //Obtener los distritos
    Route::get('getDistricts/{country}/{province}/{canton}', 'Types@getDistricts');
    //Cerrar sesión
    //Route::post('logout', 'Users@logout');
    //Obtener usuario actual en la sesión
    //Route::get('getUserAuthenticated', 'Users@getUserAuthenticated');
});

Route::get('/', function(){
    echo "Tax project, welcome";
});



