<?php 
require "../views/header.view.php";
require "../config/conn.php";
$tiempo_inicio = microtime_float();

 ?>

<div id="settingER" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('settingER').style.display='none';">&times;</span>

      <div class="modal-content-download">
        <div style="display: flex;align-items: center;flex-direction: column;border: 2px solid green;border-radius: 6px;margin: 8px; width: 80%;">
          
          <?php 
            $r = squery("select col_name as company, idcompany from cfg_reports_col where info_r = 'ajustes' order by idcompany");
           ?>

          <label>Company</label><select name="company" style="width: 26em;text-align: center;">
              
              <?php  for ($i=0; $i < count($r); $i++) { 
               echo "<option value='".$r[$i]['idcompany']."'> ".$r[$i]['company']." </option>";
              } unset($r);  ?>

            </select><br>
          
          <span><input type="radio" name="rdtype" value="Mes" checked="true">Mes / Month </span><br>
          <span><input type="radio" name="rdtype" value="Acum">Acumulado / Acum </span><br>
          
          <label>Description/concepto</label><select name="indexren" style="width: 26em;text-align: center;">
              <option value='2'> Unidades / Kg.</option>
              <option value='3'> Vts. Netas / Sales </option>
              <option value='4'> Costo Directo / Direct Cost </option>
              <option value='5'> Gts Venta / Sales Expenses</option>
              <option value='6'> Gts. Admon / Managerial Expenses</option>
              <option value='7'> Dep. y Amtz. </option>
              <option value='8'> Perd. o Ut. Camb. </option>
              <option value='9'> OG y OI / Other Income</option>
              <option value='10'> Gts. Fin. / Financial Expenses </option>
              <option value='11'> Prod. Fin. / Financial Income </option>
              <option value='12'> REPOMO </option>
              <option value='13'> ISR / Income Tax</option>
              <option value='14'> Franchise Tax </option>
              <option value='14'> OTROS </option>

            </select><br>
            <label> $ </label> <input type="text" name="valnum" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0"><br>
        </div>
        <div style="display: flex;align-items: center;flex-direction: column;">
          
      	   <button id="btn_saveER" type="submit" class="button">Save <i class="fas fa-save"></i></button>
        </div>

      </div>
  </div>
</div>