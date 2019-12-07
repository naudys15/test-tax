<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <title>{{$data['title']}}</title>
    </head>
    <body>
    <main>
        <div id="details" class="clearfix">
            <div id="invoice">
                <h1>Reporte D-104 IVA Nº {{$data['id_report']}}</h1>
                <div class="date">Fecha de emisión: {{$data['date']}}</div>
                <div class="business_name">Nombre o razón social: {{$data['business_name']}}</div>
            </div>
        </div>
        <table class="table" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="text-center">Indicador</th>
                    <th class="text-center">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data['suom_type_prorata'] == 'general') { ?>
                    <tr>
                        <td class="iva_received text-center">IVA devengado</td>
                        <td class="iva_received text-center"><?=$data['suom_received_iva']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out text-center">IVA deducible</td>
                        <td class="iva_paid_out text-center"><?=$data['suom_paid_out_iva']?></td>
                    </tr>
                    <tr>
                        <td class="expenses text-center">IVA al gasto</td>
                        <td class="expenses text-center"><?=$data['suom_expenses_iva']?></td>
                    </tr>
                        <td class="iva_to_pay text-center">IVA a pagar</td>
                        <td class="iva_to_pay text-center"><?=$data['suom_iva_to_pay']?></td>
                    </tr>
                <?php } elseif ($data['suom_type_prorata'] == 'special') { ?>
                    <tr>
                        <td class="iva_received text-center">IVA Devengado</td>
                        <td class="iva_received text-center"><?=$data['suom_received_iva']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_one_visible text-center">IVA deducible identificado 1%</td>
                        <td class="iva_paid_out_one_visible text-center"><?=$data['suom_paid_out_one_visible']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_two_visible text-center">IVA deducible identificado 2%</td>
                        <td class="iva_paid_out_two_visible text-center"><?=$data['suom_paid_out_two_visible']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_four_visible text-center">IVA deducible identificado 4%</td>
                        <td class="iva_paid_out_four_visible text-center"><?=$data['suom_paid_out_four_visible']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_thirteen_visible text-center">IVA deducible identificado 13%</td>
                        <td class="iva_paid_out_thirteen_visible text-center"><?=$data['suom_paid_out_thirteen_visible']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_one_prorata text-center">IVA deducible prorrateado 1%</td>
                        <td class="iva_paid_out_one_prorata text-center"><?=$data['suom_paid_out_one_prorata']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_two_prorata text-center">IVA deducible prorrateado 2%</td>
                        <td class="iva_paid_out_two_prorata text-center"><?=$data['suom_paid_out_two_prorata']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_four_prorata text-center">IVA deducible prorrateado 4%</td>
                        <td class="iva_paid_out_four_prorata text-center"><?=$data['suom_paid_out_four_prorata']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_thirteen_prorata text-center">IVA deducible prorrateado 13%</td>
                        <td class="iva_paid_out_thirteen_prorata text-center"><?=$data['suom_paid_out_thirteen_prorata']?></td>
                    </tr>
                    <tr>
                        <td class="iva_paid_out_total text-center">IVA deducible total</td>
                        <td class="iva_paid_out text-center"><?=$data['suom_paid_out_iva']?></td>
                    </tr>
                    <tr>
                        <td class="expenses text-center">IVA al gasto</td>
                        <td class="expenses text-center"><?=$data['suom_expenses_iva']?></td>
                    </tr>
                    <tr>
                        <td class="iva_to_pay text-center">IVA a pagar</td>
                        <td class="iva_to_pay text-center"><?=$data['suom_iva_to_pay']?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>