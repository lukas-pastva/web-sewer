<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

echo '
<h3>Správa vrchného banneru</h3>
    <div class="clanok_autor">
     Šablony, ktore sa budu používať pre rýchlejšiu mailovú komunikáciu.
    </div><br />
    
    
    
Vec: Oslovenie organizátora podujatia/koncertu/party<br />
<textarea style="width: 780px; height: 200px;" >
Edituj ma cez ftp cez adin_sablony.php    
    </textarea>
    
';


?>