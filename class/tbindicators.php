<?php 


class tbIndicators
{

    public $pyglobal = array(
        'netsales'  => 0,
        'opexp'     => 0,
        'gaexp'     => 0,
    );
    public $real        = array();
    public $budget      = array();
    public $var_RB      = array();
    public $var_RB_per  = array();
    public $projection  = array();
    public $var_PB      = array();
    public $var_PB_per  = array();
    public $moneda      = '';
    public $company     = '';
    public $idcompany   = 0;
    public $lastmov     = '';
    public $pcount      =  0;
    public $dateshow    = '';
    public $section     = '';


    function __construct(string $section) {
        
        echo "<section class='section my-4' id='$section'>
			    <div class='container'>
				    <h2 class='text-center'>".$section."</h2>
                    <h5 class='text-right'>miles, ".$this->moneda."</h5>";

        echo '<table class="table table-borderless table-sm">
                <thead>
                    <th colspan="8" class="text-center">GLOBAL '.$section.'</th>	
                </thead>
                <tbody>
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
                <tr class="text-right">
                    <td class="text-left">Net Sales </td>
                    <td id="'.$section.'netsales"></td>
                    <td style="color:blue;" id="'.$section.'pmsales"></td>
                    <td id="'.$section.'varsales" ></td>
                    <td id="'.$section.'porcentsales" ></td>
                    <td class="py">'.number_format($this->pyglobal['netsales']).'</td>
                    <td id="'.$section.'pyvarsales"></td>
                    <td id="'.$section.'pyporcentsales"></td>
                </tr>
                <tr class="text-right">
                    <td class="text-left">Operative Expenses </td>
                    <td id="'.$section.'netgv"></td>
                    <td style="color:blue;" id="'.$section.'pmgv"></td>
                    <td id="'.$section.'vargv" ></td>
                    <td id="'.$section.'porcentgv"></td>
                    <td class="py">'.number_format($this->pyglobal['opexp']).'</td>
                    <td id="'.$section.'pyvargv"></td>
                    <td id="'.$section.'pyporcentgv"></td>
                </tr>
                <tr class="text-right">
                    <td class="text-left">G&A Expenses </td>
                    <td id="'.$section.'netga"></td>
                    <td style="color:blue;" id="'.$section.'pmga"></td>
                    <td id="'.$section.'varga" ></td>
                    <td id="'.$section.'porcentga" ></td>
                    <td class="py">'.number_format($this->pyglobal['gaexp']).'</td>
                    <td id="'.$section.'pyvarga"></td>
                    <td id="'.$section.'pyporcentga"></td>
                </tr>
                </tbody>
            </table></div>';
    }

    function EndDiv() {
        echo "</section>";
    }

    function Indicator() {
    ?>
        <div class="divBorder">
            <div>
                <h6 class="font-weight-bold text-center"><?php echo $this->company;?></h6>
            </div>			
            <table class="tbcompany b-table table table-borderless table-sm" id="<?php echo $this->idcompany;?>">
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
                <td class="lastNetMov" colspan="4">Last Sale Mov: <?php echo $this->lastmov;?></td>
                <td colspan="4" class="text-right">
                    <span class="pold_count" >Not Affected: <?php echo $this->pcount;?> </span>
                <a class="btn btn-success btn-sm" href="popup.php/?company=<?php echo $this->idcompany;?>&name=<?php echo $this->company;?>&section=
                <?php echo $this->section;?>&date=<?php echo $this->dateshow;?>" target="_blank" onclick="window.open(this.href,this.target,\'width=950,height=400,top=200,left=200,toolbar=no,location=no,directories=no,status=no,menubar=no\');return false;" id="plus">
                    <i class="icon fas fa-plus" ></i>
                </a></td>
            </tr>
            </tfoot>
            <tbody>
            <tr class="text-right">
                <td class="text-left">Net Sales </td>
                <td class="netsales">   <?php echo number_format(round(
                    current($this->real)));?></td>
                <td class="pmsales">    <?php echo number_format(
                    current($this->budget)); ?></td>
                <td class="varsales">   <?php echo number_format(
                    current($this->var_RB)); ?></td>
                <td class="porcentsales"><?php echo number_format(
                    current($this->var_RB_per)); ?>%</td>
                <td class="py">         <?php echo number_format(
                    current($this->projection)); ?></td>
                <td class="pyvarsales"> <?php echo number_format(round(
                    current($this->var_PB))); ?></td>
                <td class="pyporcentsales"><?php echo number_format(round(
                    current($this->var_PB_per))); ?>%</td>
            </tr>
            <tr class="text-right">
                <td class="text-left">Operative Expenses </td>
                <td class="netgv"><?php echo number_format(round(
                    next($this->real))); ?></td>
                <td class="pmgv"><?php echo number_format(
                    next($this->budget)); ?></td>
                <td class="vargv"><?php echo number_format(
                    next($this->var_RB)); ?></td>
                <td class="porcentgv"><?php echo number_format(
                    next($this->var_RB_per)); ?>%</td>
                <td class="py"><?php echo number_format(
                    next($this->projection)); ?></td>
                <td class="pyvargv"><?php echo number_format(round(
                    next($this->var_PB))); ?></td>
                <td class="pyporcentgv"><?php echo number_format(round(
                    next($this->var_PB_per))); ?>%</td>
            </tr>
            <tr class="text-right">
                <td class="text-left">G&A Expenses </td>
                <td class="netga"><?php echo number_format(round(
                    next($this->real))); ?></td>
                <td class="pmga"><?php echo number_format(
                    next($this->budget)); ?></td>
                <td class="varga"><?php echo number_format(
                    next($this->var_RB)); ?></td>
                <td class="porcentga"><?php echo number_format(
                    next($this->var_RB_per)); ?>%</td>
                <td class="py"><?php echo number_format(
                    next($this->projection)); ?></td>
                <td class="pyvarga"><?php echo number_format(round(
                    next($this->var_PB))); ?></td>
                <td class="pyporcentga"><?php echo number_format(round(
                    next($this->var_PB_per))); ?>%</td>
            </tr>
            </tbody>
            </table>
        </div>
    <?php
    }



} #END CLASS


?>