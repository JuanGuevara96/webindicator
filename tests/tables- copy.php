<?php 
if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	}
require './config/conn.php';
require_once './config/lifetime.php'; #contador de inatividad
?>
<link rel="stylesheet" type="text/css" href="css/styles_tab.css">

<!-- DateBox  -->
<div class="container">	
	<form class="datebox row w-50" action="content.php" method="post">
		<div class="input-group">
		<div class="input-group-prepend">
    		<span class="input-group-text bg-success text-white" id="inputGroup-sizing-sm"><i class="fas fa-calendar"></i></span>
  		</div>
		<select class="custom-select w-50" name="month">
			<?php for ($i=1; $i <= 12; $i++) { //todos los meses
	        echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
	        if (isset($_POST['month'])) {if ($_POST['month'] == $i) echo "selected='1'";} elseif (date('m') == $i) echo "selected='1'";
	        echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
        </select>
		<input  class="custom-select w-25" type="number" name="year" min="2000" max="2099" value="<?php if(isset($_POST['year'])) echo $_POST['year']; else echo date('Y'); ?>" pattern="\d{4}" maxlength="4" minlength="4" >
		<button class="btn btn-success button" type="submit"><i class="icon fas fa-search"></i></button>
		</div>
	</form>
</div>

<?php 
// if (isset($_POST['year']) && isset($_POST['month'])) {
// 	$_POST['date'] = $_POST['year']."-".$_POST['month'];
// }
	if (isset($_POST['date'])) { //optimizar isset
		$date = date('Ym',strtotime($_POST['date']));
		$dateshow = dateshow($_POST['date']);
		$_SESSION['date'] = $_POST['date'];
	} else { $date = date('Ym'); $dateshow = date('F j, Y'); $_SESSION['date'] = $date;}
	
	$tiempo_inicio = microtime_float(); #contar tiempo de carga


	
	//variables
	$section = ''; $sectionaux = '';
	//funcion extrae array company
	//$length = count($arrcom);
 	$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0]; 
 	$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0]; $lastmov = '';


	//$arrcom = squery('CALL myp_accounts('.substr($date, 0, -2).')');
	//ciclo de tablas

	#$section = strtoupper($arrcom[$i]['section']); //save section in upper 

	//!!!!!!!!!!!!!modificacion para prueba alimentos
	$sections = squery("Select s.idsection, UPPER(s.section_name) section, s.moneda from permisos p left join sections s ON p.idsection = s.idsection where s.status = '1' and p.iduser =  '".$_SESSION['ID']."'");

	#$pvls = str_split($_SESSION['sections'],3); //extrae el privilegio
	 //$section = $section_name;
	//foreach ($pvls as $section) {


/* Inicio de ciclo por division*/
	
	for ($h=0; $h < count($sections); $h++) { 
	 	extract($sections[$h], EXTR_OVERWRITE);

	//query suma de proyeccion
	$pyglobal = oquery("SELECT sum(netsales) netsales, sum(opexp) opexp, sum(gaexp) gaexp FROM proyec WHERE pydate = '$date' AND section = '$section'");

echo "<section class='section my-4' id='$section'>
			<div class='container'>
				<h2 class='text-center'>".$section."</h2>
					<h5 class='text-right'>miles, ".$moneda."</h5>";

echo '<div id="global" class="divBorder">
		<table class="h-table">
			<thead>
			<tr>
			<th colspan="2" >GLOBAL '.$section.'</th>			
			</tr>
			</thead>
			<table class="b-table table table-borderless table-sm">
			<thead>
			<tr class="text-center">
				<td>-</td>
				<td>REAL</td>
				<td>BUDGET</td>
				<td>VARIATION REALvsBUDG</td>
				<td>VARIATION REALvsBUDG(%)</td>
				<td>PROJECTION</td>
				<td>VARIATION PROJvsBUDG</td>
				<td>VARIATION PROJvsBUDG(%)</td>
			</tr>
			</thead>
			<tbody>
			<tr class="text-right">
				<td class="text-left">Net Sales </td>
				<td id="'.$section.'netsales"></td>
				<td style="color:blue;" id="'.$section.'pmsales"></td>
				<td id="'.$section.'varsales" ></td>
				<td id="'.$section.'porcentsales" ></td>
				<td class="py">'.number_format($pyglobal['netsales']).'</td>
				<td id="'.$section.'pyvarsales"></td>
				<td id="'.$section.'pyporcentsales"></td>
			</tr>
			<tr class="text-right">
				<td class="text-left">Operative Expenses </td>
				<td id="'.$section.'netgv"></td>
				<td style="color:blue;" id="'.$section.'pmgv"></td>
				<td id="'.$section.'vargv" ></td>
				<td id="'.$section.'porcentgv"></td>
				<td class="py">'.number_format($pyglobal['opexp']).'</td>
				<td id="'.$section.'pyvargv"></td>
				<td id="'.$section.'pyporcentgv"></td>
			</tr>
			<tr class="text-right">
				<td class="text-left">G&A Expenses </td>
				<td id="'.$section.'netga"></td>
				<td style="color:blue;" id="'.$section.'pmga"></td>
				<td id="'.$section.'varga" ></td>
				<td id="'.$section.'porcentga" ></td>
				<td class="py">'.number_format($pyglobal['gaexp']).'</td>
				<td id="'.$section.'pyvarga"></td>
				<td id="'.$section.'pyporcentga"></td>
			</tr>
			</tbody>
			</table>
		</table>
	 </div>';

	/*ciclo cuentas por empresa*/
	$arrcom = squery("select c.idcompany, c.subcompany, c.company, c.divisor, p.numpre from company c left join presup p 
				ON c.idcompany = p.idcompany and ejercicio = '".substr($date, 0, -2)."' where c.idsection = '".$idsection."' ");
	for ($i=0; $i < count($arrcom); $i++) {


			extract($arrcom[$i], EXTR_OVERWRITE); #array associative name las convierte en variables 
			$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
			$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0];
			$lastmov ="";
			$acc = squery("SELECT idaccount, type, op, presup FROM 
			accounts where idcompany = '".$idcompany."' ORDER BY idcompany, type");
			for ($j=0; $j < count($acc); $j++) {
				#imprime tablas por compañia
				extract($acc[$j], EXTR_OVERWRITE);

			$PM = ocifunction($idcompany,$date, $idaccount, $numpre);
					//$PM = array_map("numabs", $PM);
			//categorias ventas, ingresos, administrativo
					switch ($type) {
						case 'I':
							$lastmov = sqlmov($idcompany, $idaccount, $lastmov);
							$sum['netsales'] = sum($op, $sum['netsales'], $PM[':movnetos']);
							$pmref = &$sumpm['pmsales'];
							break;
						case 'V':
							$sum['netgv'] = sum($op, $sum['netgv'], $PM[':movnetos']);
							$pmref = &$sumpm['pmgv'];
							break;
						case 'A':
							$sum['netga'] = sum($op, $sum['netga'], $PM[':movnetos']);
							$pmref = &$sumpm['pmga'];
							break;	
					}

			$pmref += ($presup == '1' && $PM[':pm'] != 0) ? $PM[':pm'] : 0;

			} #end for acc
			#!!!!!!!!!!!!! remplazar por javascript la division de numeros
			$sum['netsales'] = $sum['netsales'] / $divisor; 
			$sum['netgv'] = $sum['netgv'] / $divisor;
			$sum['netga'] = $sum['netga'] / $divisor;
			$sumpm = array_map('miles', $sumpm);
	 		$arrcompany = array_merge($sum, array_map('presupday', $sumpm));
			$arrcompany  += ['lastmov' => $lastmov];

	//calculos de tablas
	/*calculos para variacion mes vs presupuesto*/		
	$varsales = $arrcompany['netsales'] - $arrcompany['pmsales']; 
	$vargv =  $arrcompany['netgv'] - $arrcompany['pmgv'];
	$varga =   $arrcompany['netga'] - $arrcompany['pmga'];
	/*calculos para porcentaje de variacion mes vs presupuesto */
	$porcentsales = ($sumpm['pmsales']) ? $varsales / $arrcompany['pmsales'] * 100 : 0;
	$porcentgv = ($sumpm['pmgv']) ? $vargv / $arrcompany['pmgv'] * 100 : 0;
	$porcentga = ($sumpm['pmga']) ? $varga / $arrcompany['pmga'] * 100 : 0;
	$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0]; $sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0]; 
	$lastmov = '';
	$pcount = pold_count($idcompany,$dateshow);
	$pcolor = ($pcount != "0") ? 'style="background-color:yellow;"' : ''; //color para polizas no afectadas
	$py = oquery("SELECT netsales, opexp, gaexp FROM proyec WHERE idcompany = '$idcompany' AND pydate = '$date'");
	/*calculos para variacion mes vs proyeccion*/
	$pyvarsales = $py['netsales'] - $arrcompany['pmsales'];
	$pyvargv = $py['opexp'] - $arrcompany['pmgv'];
	$pyvarga = $py['gaexp'] - $arrcompany['pmga'];

	/*calculos para porcentaje de variacion mes vs proyeccion */
	$pyporcentsales = ($arrcompany['pmsales']) ? round($pyvarsales) / $arrcompany['pmsales'] * 100 :0; 
	$pyporcentgv = ($arrcompany['pmgv']) ? round($pyvargv) / $arrcompany['pmgv'] * 100 :0;
	$pyporcentga = ($arrcompany['pmga']) ? round($pyvarga) / $arrcompany['pmga'] * 100 :0;
	
	echo '<div class="divBorder">
		<table class="h-table">
			<thead>
			<tr>
			<th colspan="2" id="'.$idcompany.'">'.$company.'</th>			
			</tr>
			</thead>
			<table class="b-table table table-borderless table-sm">
			<thead>
			<tr class="text-center">
				<td>-</td>
				<td>REAL</td>
				<td>BUDGET</td>
				<td>VARIATION REALvsBUDG</td>
				<td>VARIATION REALvsBUDG(%)</td>
				<td>PROJECTION</td>
				<td>VARIATION PROJvsBUDG</td>
				<td>VARIATION PROJvsBUDG(%)</td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="4">Last Sale Mov: '.$arrcompany['lastmov'].' </td>
				<td colspan="4" class="text-right">
					<span '.$pcolor.'>Not Affected: '.$pcount.' </span>
					<a class="btn btn-success btn-sm" href="popup.php/?company='.$idcompany.'&name='.$company.'&section='.$section.'&date='.$dateshow.'" target="_blank" onclick="window.open(this.href,this.target,\'width=950,height=400,top=200,left=200,toolbar=no,location=no,directories=no,status=no,menubar=no\');return false;" id="plus">
					<i class="icon fas fa-plus" ></i>
					</a></td>
			</tr>
			</tfoot>
			<tbody>
			<tr class="text-right">
				<td class="text-left">Net Sales </td>
				<td class="netsales">'.number_format(round($arrcompany['netsales'])).'</td>
				<td class="pmsales">'.number_format($arrcompany['pmsales']).'</td>
				<td class="varsales">'.number_format($varsales).'</td>
				<td class="porcentsales">'.number_format($porcentsales).'%</td>
				<td class="py">'.number_format($py['netsales']).'</td>
				<td class="pyvarsales">'.number_format(round($pyvarsales)).'</td>
				<td class="pyporcentsales">'.number_format(round($pyporcentsales)).'%</td>
			</tr>
			<tr class="text-right">
				<td class="text-left">Operative Expenses </td>
				<td class="netgv">'.number_format(round($arrcompany['netgv'])).'</td>
				<td class="pmgv">'.number_format($arrcompany['pmgv']).'</td>
				<td class="vargv">'.number_format($vargv).'</td>
				<td class="porcentgv">'.number_format($porcentgv).'%</td>
				<td class="py">'.number_format($py['opexp']).'</td>
				<td class="pyvargv">'.number_format(round($pyvargv)).'</td>
				<td class="pyporcentgv">'.number_format(round($pyporcentgv)).'%</td>
			</tr>
			<tr class="text-right">
				<td class="text-left">G&A Expenses </td>
				<td class="netga">'.number_format(round($arrcompany['netga'])).'</td>
				<td class="pmga">'.number_format($arrcompany['pmga']).'</td>
				<td class="varga">'.number_format($varga).'</td>
				<td class="porcentga">'.number_format($porcentga).'%</td>
				<td class="py">'.number_format($py['gaexp']).'</td>
				<td class="pyvarga">'.number_format(round($pyvarga)).'</td>
				<td class="pyporcentga">'.number_format(round($pyporcentga)).'%</td>
			</tr>
			</tbody>
			</table>
		</table>
		</div>';

		}  #end for arrcom

	/*cierre ciclo por divisiones*/
		echo "</div>
			</section>";

	} #end foreach pvls



	
	##codigo nuevo reemplazar
	// $pvls = str_split($_SESSION['sections'], 3); #check priviliges
	// foreach ($pvls as $value) {
	// 		$arrcom = squery("select idcompany, subcompany, company, section, divisor from company where section like '".$value."%'");
	// 	for ($i=0; $i < count($arrcom); $i++) { 
	// 		#imprime tabla global
	// 		extract($arrcom[$i], EXTR_OVERWRITE); #array associative name las convierte en variables 
	// 		#$idcompany = $arrcom[$i]['idcompany'];
	// 		#$subcompany = $arrcom[$i]['subcompany'];
	// 		#$company = $arrcom[$i]['company'];
	// 		#$section = $arrcom[$i]['section'];
	// 		#$divisor = $arrcom[$i]['divisor'];
	// 		$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
	// 		$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0];

	// 		$acc = squery("SELECT A.idaccount, A.type, A.op, A.presup, P.numpre FROM 
	// 		accounts A LEFT JOIN presup P ON A.idcompany=P.idcompany 
	// 		AND P.ejercicio = '".substr($date,0,4)."' where A.idcompany = '".$idcompany."' ORDER BY A.idcompany, A.type");
	// 		for ($j=0; $j < count($acc); $j++) {
	// 			#imprime tablas por compañia
	// 			extract($acc[$j], EXTR_OVERWRITE);
	// 			#$idaccount = $acc[$j]['idaccount'];
	// 			#$type = $acc[$j]['type'];
	// 			#$op = $acc[$j]['op'];
	// 			#$presup = $acc[$j]['presup'];
	// 			#$numpre = $acc[$j]['numpre'];

	// 		} #end for acc
	// 	} #end for arrcom
	// } #end foreach pvls


	// #if (privileges(substr($section,0,3))) {
	// 	$idcompany = $arrcom[$i]['subcompany'];
	// 	if ($sectionaux != $section) {
	// 		if (!empty($sectionaux)) {
	// 		$buttonpy = '<div class="down"><form action="./projection.php" method="post"><input type="hidden" name="section" value="'.$sectionaux.'"><button type="submit" class="button">Projection <i class="fas fa-step-forward"></i></button></form></div>';
	// 			echo $buttonpy.'</div>'; #cierra div section
	// 		}
	// 		#proyeccion global por seccion
	// 		$pyglobal = oquery("SELECT sum(netsales) netsales, sum(opexp) opexp, sum(gaexp) gaexp FROM proyec WHERE pydate = '$date' AND section = '$section'");

	// 		echo '<h2>'.$section.'</h2><h5>miles, '.cash($idcompany).'</h5><div class="section" id="'.$section.'">'; #abre div section
	// 		echo '<div id="global" class="divBorder">
	// 	<table class="h-table">
	// 		<thead>
	// 		<tr>
	// 		<th colspan="2" >GLOBAL '.$section.'</th>			
	// 		</tr>
	// 		</thead>
	// 		<table class="b-table">
	// 		<thead>
	// 		<tr><td>-</td><td>REAL</td><td>BUDGET</td><td>VARIATION REALvsBUDG</td><td>VARIATION REALvsBUDG(%)</td><td>PROJECTION</td><td>VARIATION PROJvsBUDG</td><td>VARIATION PROJvsBUDG(%)</td></tr>
	// 		</thead>
	// 		<tbody>
	// 		<tr><td>Net Sales </td>
	// 			<td id="'.$section.'netsales"></td>
	// 			<td style="color:blue;" id="'.$section.'pmsales"></td>
	// 			<td id="'.$section.'varsales" ></td>
	// 			<td id="'.$section.'porcentsales" ></td>
	// 			<td class="py">'.number_format($pyglobal['netsales']).'</td>
	// 			<td id="'.$section.'pyvarsales"></td>
	// 			<td id="'.$section.'pyporcentsales"></td>
	// 		</tr>
	// 		<tr><td>Operative Expenses </td>
	// 			<td id="'.$section.'netgv"></td>
	// 			<td style="color:blue;" id="'.$section.'pmgv"></td>
	// 			<td id="'.$section.'vargv" ></td>
	// 			<td id="'.$section.'porcentgv"></td>
	// 			<td class="py">'.number_format($pyglobal['opexp']).'</td>
	// 			<td id="'.$section.'pyvargv"></td>
	// 			<td id="'.$section.'pyporcentgv"></td>
	// 		</tr>
	// 		<tr><td>G&A Expenses </td>
	// 			<td id="'.$section.'netga"></td>
	// 			<td style="color:blue;" id="'.$section.'pmga"></td>
	// 			<td id="'.$section.'varga" ></td>
	// 			<td id="'.$section.'porcentga" ></td>
	// 			<td class="py">'.number_format($pyglobal['gaexp']).'</td>
	// 			<td id="'.$section.'pyvarga"></td>
	// 			<td id="'.$section.'pyporcentga"></td>
	// 		</tr>
	// 		</tbody>
	// 		</table>
	// 	</table>
	// 	</div>';
	// 			$sectionaux = $section;
	// 	} //end if section global 
	// 	$PM = ocifunction($arrcom[$i]['idcompany'],$date, $arrcom[$i]['idaccount'], $arrcom[$i]['numpre']);
	// 				//$PM = array_map("numabs", $PM);
	// 				switch ($arrcom[$i]['type']) {
	// 					case 'I':
	// 						$lastmov = sqlmov($arrcom[$i]['idcompany'], $arrcom[$i]['idaccount'], $lastmov);
	// 						$sum['netsales'] = sum($arrcom[$i]['op'], $sum['netsales'], $PM[':movnetos']);
	// 						$pmref = &$sumpm['pmsales'];
	// 						break;
	// 					case 'V':
	// 						$sum['netgv'] = sum($arrcom[$i]['op'], $sum['netgv'], $PM[':movnetos']);
	// 						$pmref = &$sumpm['pmgv'];
	// 						break;
	// 					case 'A':
	// 						$sum['netga'] = sum($arrcom[$i]['op'], $sum['netga'], $PM[':movnetos']);
	// 						$pmref = &$sumpm['pmga'];
	// 						break;	
	// 				}
	// 	//if ($arrcom[$i]['presup'] == '1' && $PM[':pm'] != 0) {
	// 		#modificacion miles por divisor
	// 	$pmref += ($arrcom[$i]['presup'] == '1' && $PM[':pm'] != 0) ? $PM[':pm'] : 0; //modificar
	// 	if ($i+1 != $length) { //verificar
	// 		$idaux = $arrcom[$i+1]['subcompany'];
	// 	} else {$idaux = '';}
	// 	if ($idcompany != $idaux) {
	// 		#$sum = array_map("miles", $sum);
	// 		// if ($idcompany == 305) {
	// 		// 	$sum =array_map("half", $sum);
	// 		// }
	// 		$sum['netsales'] = $sum['netsales'] / $arrcom[$i]['divisor']; 
	// 		$sum['netgv'] = $sum['netgv'] / $arrcom[$i]['divisor'];
	// 		$sum['netga'] = $sum['netga'] / $arrcom[$i]['divisor'];
	// 		$sumpm = array_map('miles', $sumpm); //posible error divided by zero
	//  		$company = array_merge($sum, array_map('presupday', $sumpm));
	// 		$company  += ['lastmov' => $lastmov];		
	
	// //calculos de tablas
	// $varsales = $company['netsales'] - $company['pmsales']; 
	// $vargv =  $company['netgv'] - $company['pmgv'];
	// $varga =   $company['netga'] - $company['pmga'];
	// $porcentsales = ($sumpm['pmsales']) ? $varsales / $company['pmsales'] * 100 : 0;
	// $porcentgv = ($sumpm['pmgv']) ? $vargv / $company['pmgv'] * 100 : 0;
	// $porcentga = ($sumpm['pmga']) ? $varga / $company['pmga'] * 100 : 0;
	// $sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0]; $sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0]; 
	// $lastmov = '';
	// $pcount = pold_count($idcompany,$dateshow);
	// $pcolor = ($pcount != "0") ? 'style="background-color:yellow;"' : ''; //color para polizas no afectadas
	// $py = oquery("SELECT netsales, opexp, gaexp FROM proyec WHERE idcompany = '$idcompany' AND pydate = '$date'");
	// $pyvarsales = $py['netsales'] - $company['pmsales'];
	// $pyvargv = $py['opexp'] - $company['pmgv'];
	// $pyvarga = $py['gaexp'] - $company['pmga'];
	// $pyporcentsales = ($company['pmsales']) ? round($pyvarsales) / $company['pmsales'] * 100 :0; 
	// $pyporcentgv = ($company['pmgv']) ? round($pyvargv) / $company['pmgv'] * 100 :0;
	// $pyporcentga = ($company['pmga']) ? round($pyvarga) / $company['pmga'] * 100 :0;
	// // imprimir tablas
	// echo '<div class="divBorder">
	// 	<table class="h-table">
	// 		<thead>
	// 		<tr>
	// 		<th colspan="2" id="'.$idcompany.'">'.$arrcom[$i]['company'].'</th>			
	// 		</tr>
	// 		</thead>
	// 		<table class="b-table">
	// 		<thead>
	// 		<tr><td>-</td><td>REAL</td><td>BUDGET</td><td>VARIATION REALvsBUDG</td><td>VARIATION REALvsBUDG(%)</td><td>PROJECTION</td><td>VARIATION PROJvsBUDG</td><td>VARIATION PROJvsBUDG(%)</td></tr>
	// 		</thead>
	// 		<tfoot>
	// 		<tr><td colspan="4">Last Sale Mov: '.$company['lastmov'].' </td>
	// 		<td colspan=4><span '.$pcolor.'>Not Affected: '.$pcount.' </span> <a class="button" href="popup.php/?company='.$idcompany.'&name='.$arrcom[$i]['company'].'&section='.$section.'&date='.$dateshow.'" target="_blank" onclick="window.open(this.href,this.target,\'width=950,height=400,top=200,left=200,toolbar=no,location=no,directories=no,status=no,menubar=no\');return false;" id="plus"><i class="icon fas fa-plus" ></i></a></td></tr>
	// 		</tfoot>
	// 		<tbody>
	// 		<tr>
	// 			<td >Net Sales </td>
	// 			<td class="netsales">'.number_format(round($company['netsales'])).'</td>
	// 			<td class="pmsales">'.number_format($company['pmsales']).'</td>
	// 			<td class="varsales">'.number_format($varsales).'</td>
	// 			<td class="porcentsales">'.number_format($porcentsales).'%</td>
	// 			<td class="py">'.number_format($py['netsales']).'</td>
	// 			<td class="pyvarsales">'.number_format(round($pyvarsales)).'</td>
	// 			<td class="pyporcentsales">'.number_format(round($pyporcentsales)).'%</td>
	// 		</tr>
	// 		<tr>
	// 			<td >Operative Expenses </td>
	// 			<td class="netgv">'.number_format(round($company['netgv'])).'</td>
	// 			<td class="pmgv">'.number_format($company['pmgv']).'</td>
	// 			<td class="vargv">'.number_format($vargv).'</td>
	// 			<td class="porcentgv">'.number_format($porcentgv).'%</td>
	// 			<td class="py">'.number_format($py['opexp']).'</td>
	// 			<td class="pyvargv">'.number_format(round($pyvargv)).'</td>
	// 			<td class="pyporcentgv">'.number_format(round($pyporcentgv)).'%</td>
	// 		</tr>
	// 		<tr>
	// 			<td >G&A Expenses </td>
	// 			<td class="netga">'.number_format(round($company['netga'])).'</td>
	// 			<td class="pmga">'.number_format($company['pmga']).'</td>
	// 			<td class="varga">'.number_format($varga).'</td>
	// 			<td class="porcentga">'.number_format($porcentga).'%</td>
	// 			<td class="py">'.number_format($py['gaexp']).'</td>
	// 			<td class="pyvarga">'.number_format(round($pyvarga)).'</td>
	// 			<td class="pyporcentga">'.number_format(round($pyporcentga)).'%</td>
	// 		</tr>
	// 		</tbody>
	// 		</table>
	// 	</table>
	// 	</div>';
 // 			$idcompany = $idaux;
	// 	} #end if 
	// } # end if privileges
 // }# end ciclo
 
 //buton projection obsoleto
//  $buttonpy = '<div class="down"><form action="./projection.php" method="post"><input type="hidden" name="section" value="'.$sectionaux.'"><button type="submit" class="button">Projection <i class="fas fa-step-forward"></i></button></form></div>';
// echo $buttonpy.'</div>'; 
//ultima division		
//$tiempo_fin = microtime_float();
//echo "<br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),2) . " seg.";


/* custom functions */

function presupday($pmref){ //calcula el presupuesto al dia actual
	global $date;
	if (date('Ym') == $date) {
		$day = date('d');
		$month =  new \DateTime('now');
		$month_days = $month->format('t');	
		return $pmref / $month_days * $day;
	}
	else {
		return $pmref;
	}
}
function cash($idcompany){
	$cash = ($idcompany > 800) ? "dll" : "mxn";
	return $cash;
}
function pyselect($idcompany, $date){
	$row = oquery("SELECT netsales, opexp, gaexp FROM proyec WHERE idcompany = '$idcompany' AND pydate = '$date'");
	return $row;
}

 ?>

 <!-- borra boton flotante en division corporativo!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
 	<script type="text/javascript">
	// $("#CORPORATIVO").append('<div class="down"><a class="download button" href="./scheme-ppt.php">Presentation <i class="fas fa-tv"></i></a>');	
	</script>