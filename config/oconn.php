<?php 
Class ociDB
{
    private $user;
    private $host;
    private $pass;
    private $db;

    public $oci = null;
    public $err = null;

    public function __construct(){
        $this->user = "INFOFIN";
        $this->host = "(DESCRIPTION=( ADDRESS_LIST= (ADDRESS = (PROTOCOL = TCP) (HOST = 10.0.1.4) (PORT=1521)))( CONNECT_DATA = (SERVICE_NAME = PDBinfofin.sub01192138581.vcnexelco.oraclevcn.com) ))";
        $this->pass = "Passw0rd";
    }

    public function connect(){
    	$this->oci = oci_connect($this->user,$this->pass,$this->host, "UTF8");
        if (!($this->oci))
            return "fail connect";
    }

    public function execute($sql){
        $stmt = oci_parse($this->oci,$sql);
        oci_execute($stmt);
        return $stmt;
    }

    public function close(){
        oci_close($this->oci);
    }

    public function errorConnect(){
        if (!($this->oci)){
            $e = oci_error();   // Para errores de oci_connect errors, no pase un gestor
            return trigger_error(htmlentities($e['message']), E_USER_ERROR);
        }
        else
            return false;
    }

    public function prepare($array, $sql){
        $stmt = oci_parse($this->oci,$sql);
        foreach ($array as $key => $value) {
            oci_bind_by_name($stmt, $key, $array[$key],200); //warning buffer error
        }
        $oci_result = oci_execute($stmt);
        if (!$oci_result) {
            oci_execute($stmt); //query execute again for error spctb procedure.
            }
        oci_free_statement($stmt);
        return $array;  
    }

    public function getErrors(){
        $this->err;
    }

    public function getRows($sql, $mode=OCI_ASSOC){
        $rows = [];
        $stmt = oci_parse($this->oci,$sql);
        $r = oci_execute($stmt);
        $this->err = (!$r) ? oci_error($stmt) : null;
            while ($row = oci_fetch_array($stmt, $mode)){
              $rows[] = $row;
            }
        oci_free_statement($stmt);
        return $rows;
    }

    public function getRow($sql){
        $stmt = oci_parse($this->oci,$sql);
        oci_define_by_name($stmt, 'CAMPO', $cell);
        oci_execute($stmt);
        oci_fetch($stmt);
        oci_free_statement($stmt);
        return $cell;
    }


}
?>