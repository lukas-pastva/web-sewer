<?php
if (!function_exists('mb_strtolower')) {

    function mb_strtolower($str)
    {
        return strtolower($str);
    }
}
if (!function_exists('mb_strlen')) {

    function mb_strlen($str)
    {
        return strlen($str);
    }
}
if (!function_exists('mb_substr')) {

    function mb_substr($str, $a = false, $b = false)
    {
        return substr($str, $a, $b);
    }
}
if (!function_exists('mb_strpos')) {

    function mb_strpos($str, $a = false, $b = false)
    {
        return strpos($str, $a, $b);
    }
}
/**
 * **************************************************************************************************************
 */
// Pripojenie do databazy
// mysql_select_db(SQL_DBNAME);
psw_mysql_query('SET NAMES utf8');

if (is_array($_REQUEST)) {
    foreach ($_REQUEST as $key => $value) {
        $_REQUEST[$key] = str_replace('"', '&quot;', $value);
    }
}

/**
 * **************************************************************************************************************
 */
// nahranie lang suborov
foreach ($lang as $langItem) {
    if (isset($path) && is_file($path . 'lang/sk_inc.php')) {
        include($path . 'lang/sk_inc.php');
    }
    if (isset($path) && $_SESSION['selectedLang'] == $langItem && (is_file($path . 'lang/' . $langItem . '_inc.php'))) {
        include($path . 'lang/' . $langItem . '_inc.php');
    }
}
/**
 * **************************************************************************************************************
 */
// Povolenie pristupu
$ip = $_SERVER['REMOTE_ADDR'];

/*
 * if ($data = sqlGetRow(('SELECT * FROM banlist WHERE ip = "' .$ip. '" AND ban_type = "2" '))){
 * echo '<br /><br /><br /><center>'.$mm['AD'].''.$data['reason'].'</center>';
 * die;
 * }
 */

/**
 * **************************************************************************************************************
 */
// zmazanie starych akcii
// psw_mysql_query($sql = 'UPDATE partylist SET archiv = 1 WHERE datetime < "'.date('Y-m-d G:i:s').'"');

// psw_mysql_query($sql = 'DELETE FROM flyer WHERE datetime < "'.date('Y-m-d G:i:s').'"');
// psw_mysql_query($sql = 'DELETE FROM banner WHERE datetime < "'.date('Y-m-d G:i:s').'"');
// psw_mysql_query($sql = 'DELETE FROM banner2 WHERE datetime < "'.date('Y-m-d G:i:s').'"');
// psw_mysql_query($sql = 'DELETE FROM banner3 WHERE datetime < "'.date('Y-m-d G:i:s').'"');
/**
 * **************************************************************************************************************
 */

// Funkcia, ktora prihlasi a zeregistruje sesiony.
function login($nick, $pass)
{
    $nick = strtolower($nick);
    $wholePass = $pass;
    $pass = strtolower($pass);
    $pass = md5($pass);
    $logged = false;
    $loggedFalseStillHacking = false;

    if (strlen($nick) > 0) {
        // zisti kolko krat sa za poslednu LOGGED_TIME prihlasoval LOGGED_NR krat
        $count = sqlGetRow('SELECT count(*) as pocet FROM user_login WHERE nick="' . $nick . '" AND time > ' . (time() - LOGGED_TIME) . ' AND success > 1 ');

        if ($count['pocet'] <= LOGGED_NR) {
            $vybratie = sqlGetRow(('SELECT * FROM user WHERE nick="' . $nick . '" '));

            if (($pass == $vybratie['pass'])) {
                $change_id = session_regenerate_id();
                $_SESSION['meno_uzivatela'] = $nick;
                $logged = true;
                echo "Welcome!";
            }else {
                echo "Wrong pass";
            }
        } else {
            $loggedFalseStillHacking = true;
            sendAdminEmail('Pozor, niekto sa prihlasil viac ako ' . LOGGED_NR . ' krat na sewer.sk za poslednych ' . (LOGGED_TIME / 60) . ' minut. Udaje: $nick:' . $nick . '');
        }
        psw_mysql_query('INSERT INTO user_login (time, user_id, ip, pass, success, nick) VALUES ("' . time() . '", "' . $vybratie['id'] . '", "' . $_SERVER["REMOTE_ADDR"] . '", "' . $wholePass . '", "' . ($logged ? '1' : ($loggedFalseStillHacking ? '9' : '2')) . '", "' . $nick . '" ) ');
    }
    if ($loggedFalseStillHacking) {
        echo '<h3>Prihlásil si sa už viac ako ' . LOGGED_NR . ' krát za posledných ' . (LOGGED_TIME / 60) . ' minút, užívateľ ' . ADMIN_MAIL . ' bol kontaktovaný mailom o tjto situácii!</h3>';
    }
    return $logged;
}

function sendAdminEmail($text)
{
    $message = $text;
    $message = wordwrap($message, 70, "\r\n");
    mail(ADMIN_MAIL, 'Mail zo sewer.sk', $message);
}

/**
 * ******************************************************************************
 */

// Funkcia, ktora vrati ci uzivatel ma, alebo nema povoleny pristup k danej sekcii
function userGetAccess($nick, $section)
{
    $isAccessed = false;

    $result = sqlGetRow(($sql = 'SELECT `' . $section . '` FROM user WHERE nick = "' . $nick . '" '));
    // echo $sql;
    if ($result[$section] == "1") {
        $isAccessed = true;
    }
    return $isAccessed;
}

/**
 * ******************************************************************************
 */

// Funkcia, ktora vrati ci uzivatel ma, alebo nema povoleny pristup k danej sekcii
function userGetAccessBySectionId($nick, $sectionId)
{
    $isAccessed = false;
    $section = 'str_' . $sectionId;

    $result = sqlGetRow(($sql = 'SELECT `' . $section . '` FROM user WHERE nick = "' . $nick . '" ;'));

    if ($result[$section] == "1") {
        $isAccessed = true;
    }
    return $isAccessed;
}

/**
 * ******************************************************************************
 */
function getSectionNameById($sectionId)
{
    $result = sqlGetRow(('SELECT name_sk FROM structure WHERE structure_id = "' . $sectionId . '" '));
    if (!$result['name_sk'] || $result['name_sk'] == '') {
        return false;
    } else {
        return $result['name_sk'];
    }
}

/**
 * ******************************************************************************
 */
function getSectionFullNameById($sectionId)
{
    $result = sqlGetRow(('SELECT fullname_sk FROM structure WHERE structure_id = "' . $sectionId . '" '));
    if (!$result['fullname_sk'] || $result['fullname_sk'] == '') {
        return false;
    } else {
        return $result['fullname_sk'];
    }
}

/**
 * ******************************************************************************
 */
function getSectionFullnameByName($name)
{
    $result = sqlGetRow(('SELECT fullname_sk FROM structure WHERE name_sk = "' . $name . '" '));
    if (!$result['fullname_sk'] || $result['fullname_sk'] == '') {
        return false;
    } else {
        return $result['fullname_sk'];
    }
}

/**
 * ******************************************************************************
 */
function getSectionDirByName($name, $active = false)
{
    $fullnameLast;
    $result = sqlGetRow(('SELECT * FROM structure WHERE name_sk = "' . $name . '" '));

    if (!$result['fullname_sk'] || $result['fullname_sk'] == '') {
    } else {
        if ($active) {
            $fullnameLast = '<a href="/sekcia/' . $result['name_sk'] . '">' . $result['fullname_sk'] . '</a>';
        } else {
            $fullnameLast = $result['fullname_sk'];
        }
    }

    $result = sqlGetRow(('SELECT * FROM structure WHERE structure_id = "' . $result['parent_id'] . '" '));
    if ($result['fullname_sk'] != 'Sewer') {
        if ($active) {
            $fullnameLast = '<a href="/sekcia/' . $result['name_sk'] . '">' . $result['fullname_sk'] . '</a> / ' . $fullnameLast;
        } else {
            $fullnameLast = $result['fullname_sk'] . ' / ' . $fullnameLast;
        }
    }

    return $fullnameLast;
}

/**
 * ******************************************************************************
 */
function echoErrors()
{
    $errorStr = '';
    foreach ($_REQUEST as $key => $value) {
        if (($_REQUEST[$key] == '') && (mb_substr($key, 0, 1) == '_')) {
            $errorStr .= '<span style="color: #bb0000;">Vložte hodnotu ' . mb_substr($key, 1) . '</span><br />';
        }
    }
    foreach ($_FILES as $key => $value) {
        if (($_FILES[$key]['tmp_name'] == '') && (mb_substr($key, 0, 1) == '_')) {
            $errorStr .= '<span style="color: #bb0000;">Vložte hodnotu ' . mb_substr($key, 1) . '</span><br />';
        }
    }
    return $errorStr;
}

/**
 * *******************************************************************************************************************
 */
function getSectionByUser($user)
{
    $query = psw_mysql_query($sql = 'SELECT name_sk FROM structure ORDER BY structure_id ');

    if ($query->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if ($results['structure_id'] != 1) {
                if (userGetAccessBySectionId($user, $results['structure_id'])) {
                    $clanky[count($clanky)] = $results;
                }
            }
        }
    }
    return $clanky;
}

/**
 * *******************************************************************************************************************
 */
function echoNajcitanejsieClanky($public = false, $from = 0, $ajax = false)
{
    global $path;

    if (!$public) {
        $data = getTableRowsByAttribudes('clanok', 'clanok_id, nazov_sk, datetime, counter, user, structure_id ', ' AND koncept <> 1 AND datetime >= "' . (date('Y-m-d H:i:s', (time() - 12 * 31 * 24 * 60 * 60))) . '"', 'counter');

        echo '<br />
    <table class="data_table" style="width: 730px;">';
        $even = false;
        foreach ($data as $key => $dataItem) {
            echo '<tr class="' . ($even ? 'even' : '') . '">
  		<td style="width: 35px;">
  		 ' . $dataItem['counter'] . '
  		</td>
  		<td>
  		 ' . ($dataItem['user']) . ': <a href="/clanok/' . $dataItem['clanok_id'] . '-' . normalizeClanokName($dataItem['nazov_' . $_SESSION['selectedLang']]) . '" rel="external"><strong>' . $dataItem['nazov_sk'] . '</strong></a>
  		</td>
  		<td style="width: 70px;">
  		 ' . substr($dataItem['datetime'], 0, 10) . '
  		</td>
  		</tr>';
            if ($even) {
                $even = false;
            } else {
                $even = true;
            }
        }
        echo '</table><br />';
    } else {

        $data = getTableRowsByAttribudes('clanok', 'clanok_id, nazov_sk, datetime, counter, user, structure_id ', ' AND koncept <> 1', 'counter', $from, $public ? 5 : 20);

        if (!$ajax) {
            echo '<div id="najcitanejsie">
    		<h3>Najčítanejšie články</h3>';
        }
        foreach ($data as $key => $dataItem) {
            if ($key < 5) {
                echo '
    			<a class="item ' . getSectionByClanokId($dataItem['clanok_id'], true) . '" href="/clanok/' . $dataItem['clanok_id'] . '-' . normalizeClanokName($dataItem['nazov_' . $_SESSION['selectedLang']]) . '" rel="external" title="' . str_replace('"', '\'', $dataItem['nazov_sk']) . '">
    			 <img src="/image/8/' . $dataItem['clanok_id'] . '.jpg" alt=" " />
    			 <div class="text">
    			  ' . $dataItem['counter'] . 'x - <strong>' . mb_substr($dataItem['nazov_sk'], 0, 34) . (strlen($dataItem['nazov_sk']) > 34 ? '..' : '') . '</strong><br />
    			  ' . date('d.m.Y', strtotime($dataItem['datetime'])) . ' - ' . getSectionFullnameByName(getSectionNameById($dataItem['structure_id'])) . '
    			 </div>
    			</a>';
            }
        }

        if (!$ajax) {
            echo '<div class="more-najcitanejsie">
    			<div class="loader-container"><div id="loader-najcitanejsie"><span>loader</span></div></div>
    			<a id="more-button" href="#" onclick="return false;"><span>↓&nbsp;viac&nbsp;↓</span></a>
    			</div><div class="cleaner"></div></div>';
        }
    }
}

/**
 * *******************************************************************************************************************
 */
function getClankyByUser($user, $order_by, $from, $limit, $ordering, $koncepty = false, $sekcia = null)
{
    if (!$from) {
        $from = 0;
    }
    $clanky = array();
    if (!$order_by) {
        $order_by = 'clanok_id';
    }
    if ($ordering) {
        if ($_SESSION['orderBy'] == $order_by) {
            if ($_SESSION['asc'] == 'ASC') {
                $_SESSION['asc'] = 'DESC';
            } else {
                $_SESSION['asc'] = 'ASC';
            }
        }
    }
    $_SESSION['orderBy'] = $order_by;

    $query = psw_mysql_query($sql = 'SELECT clanok_id, structure_id, nazov_sk, datetime, user, koncept, counter FROM clanok WHERE 1=1 ' . ($koncepty ? 'AND koncept=1' : '') . ' ' . (isset($sekcia) ? 'AND structure_id = ' . $sekcia : '') . '  ORDER BY ' . $order_by . ' ' . ($_SESSION['asc'] ? $_SESSION['asc'] : 'DESC') . ' LIMIT ' . $from . ', ' . $limit . '');

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {

            // if($results['structure_id'] != 1){
            if (userGetAccessBySectionId($user, $row['structure_id'])) {
                $clanky[count($clanky)] = $row;
            }
        }
        // }
    }
    return $clanky;
}

/**
 * *******************************************************************************************************************
 */
function getSectionNameFromId($id)
{
    $query = psw_mysql_query('SELECT name_sk FROM structure WHERE structure_id = "' . $id . '"');
    $name = $query->fetch_assoc();

    return $name['name_sk'];
}

/**
 * *******************************************************************************************************************
 */
function getStructureNameFromId($id)
{
    $query = psw_mysql_query($sql = 'SELECT name_sk FROM structure WHERE structure_id = "' . $id . '"');
    $name = $query->fetch_assoc();
    return isset($name['name_sk']) ? $name['name_sk'] : '';
}

/**
 * *******************************************************************************************************************
 */
function getStructureIdFromNameSk($name_sk)
{
    $query = psw_mysql_query($sql = 'SELECT structure_id FROM structure WHERE name_sk = "' . $name_sk . '"');
    $name = $query->fetch_assoc();
    return isset($name['structure_id']) ? $name['structure_id'] : '';
}

/**
 * *******************************************************************************************************************
 */
function getStructureIdFromNormalizedName($name)
{
    $sql = 'SELECT structure_id, name_' . $_SESSION['selectedLang'] . ' FROM structure WHERE 1=1 ';

    $result = psw_mysql_query($sql);
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {

            if (normalizeClanokName($data['name_' . $_SESSION['selectedLang']]) == $name) {
                return $data['structure_id'];
            }
        }
    }
    return 0;
}

/**
 * *******************************************************************************************************************
 */
function getTableRows($table, $params, $orderBy, $odkial = 0, $kolko = 9999)
{
    if (is_array($params)) {
        $i = NULL;
        foreach ($params as $key => $value) {
            if (mb_substr($key, 0, 6) == 'where_') {
                $i .= 'AND ' . mb_substr($key, 6) . ' = \'' . $value . '\' ';
            }
        }
        $params = $i;
    }

    $sql = 'SELECT * FROM `' . $table . '` WHERE 1=1 ' . $params . ' ORDER BY ' . (strpos($orderBy, 'ASC') == TRUE ? $orderBy : $orderBy . ' DESC') . ' LIMIT  ' . $odkial . ', ' . $kolko . '';
    if (!$stmt = psw_mysql_query($sql)) {
        echo mysql_error();
    }
    $results = array();
    $i = 0;
    // debug($sql);

    if ($stmt->num_rows > 0) {
        while ($result = $stmt->fetch_assoc()) {

            $result2 = array();
            foreach ($result as $key => $value) {
                if (!is_int($key)) {
                    $result2[$key] = $value;
                }
            }

            $results[$i] = $result2;
            $i++;
        }
    }
    return $results;
}

/**
 * *******************************************************************************************************************
 */
function getTableRowsBySQL($sql)
{
    if (!$stmt = psw_mysql_query($sql)) {
        echo mysql_error();
    }
    $results = array();
    $i = 0;
    // debug($sql);

    $result = psw_mysql_query($sql);
    if ($stmt->num_rows > 0) {
        while ($result = $stmt->fetch_assoc()) {

            $result2 = array();
            foreach ($result as $key => $value) {
                if (!is_int($key)) {
                    $result2[$key] = $value;
                }
            }
        }
        $results[$i] = $result2;
        $i++;
    }
    return $results;
}

/**
 * *******************************************************************************************************************
 */
function getTableRowsByAttribudes($table, $attributes, $params, $orderBy, $odkial = 0, $kolko = 9999)
{
    if (is_array($params)) {
        $i = NULL;
        foreach ($params as $key => $value) {
            if (mb_substr($key, 0, 6) == 'where_') {
                $i .= 'AND ' . mb_substr($key, 6) . ' = \'' . $value . '\' ';
            }
        }
        $params = $i;
    }

    $sql = 'SELECT ' . $attributes . ' FROM `' . $table . '` WHERE 1=1 ' . $params . ' ORDER BY ' . (strpos($orderBy, 'ASC') == TRUE ? $orderBy : $orderBy . ' DESC') . ' LIMIT  ' . $odkial . ', ' . $kolko . '';
    // debug($sql);

    $resultt = psw_mysql_query($sql);

    $i = 0;
    // echo $sql;

    if ($resultt->num_rows > 0) {
        while ($result = $resultt->fetch_assoc()) {

            $result2 = array();
            foreach ($result as $key => $value) {
                if (!is_int($key)) {
                    $result2[$key] = $value;
                }
            }

            $results[$i] = $result2;
            $i++;
        }
    }
    return $results;
}

/**
 * *******************************************************************************************************************
 */
function getTableRow($table, $id_name, $id)
{
    if (is_numeric($id)) {
        $id = $id;
    } else {
        $id = '"' . $id . '"';
    }

    $sql = 'SELECT * FROM `' . $table . '` WHERE ' . $id_name . ' =  ' . $id . '';
    if (!$stmt = psw_mysql_query($sql)) {
        echo mysql_error();
    }
    $results = array();
    $i = 0;

    if ($stmt->num_rows > 0) {
        while ($result = $stmt->fetch_assoc()) {

            $result2 = array();
            foreach ($result as $key => $value) {
                if (!is_int($key)) {
                    $result2[$key] = $value;
                }
            }

            $results[$i] = $result2;
            $i++;
        }
    }
    return $results;
}

/**
 * *******************************************************************************************************************
 */
function echoPaging($table, $where, $from, $limit, $params)
{
    $pocet = sqlGetRow(('SELECT count(*) AS pocet FROM ' . $table . ' WHERE 1=1 ' . $where));
    $pocet = $pocet['pocet'];
    $pocetStranok = ceil($pocet / $limit);

    echo '
        <form action="' . $_SERVER['PHP_SELF'] . '?' . $params . '" method="post">
         <div class="paging">Počet záznamov na stranu', printSelectBox(array(
        '10',
        '20',
        '30',
        '40',
        '999'
    ), 'limit', $limit, true, false), 'Strana:';
    for ($i = 0; $i < $pocetStranok; $i++) {
        if (($i * $limit) == $from) {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?limit=' . $limit . '&amp;from=' . ($i * $limit) . '&amp;' . $params . '"><b>' . ($i + 1) . '</b></a>&nbsp;';
        } else {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?limit=' . $limit . '&amp;from=' . ($i * $limit) . '&amp;' . $params . '">' . ($i + 1) . '</a>&nbsp;';
        }
    }
    echo '
         </div>
        </form>
      ';
}

/**
 * *******************************************************************************************************************
 */
function printSelectBox($data, $name, $selectedValue = '', $onSelect = false, $nullValue = true)
{
    echo '
       <select name="' . $name . '"  onchange="', ($onSelect ? ' submit();' : ''), '"  >
         ' . ($nullValue ? '<option value="">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </option>' : '') . '
     ';
    foreach ($data as $value) {
        if ($value == $selectedValue) {
            echo '<option selected="selected" value="' . $value . '">' . $value . '</option>';
        } else {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
    }
    echo '
       </select>
     ';
}

/**
 * *******************************************************************************************************************
 */
function getClanokNameFromId($clanok_id)
{
    $row = getTableRow('clanok', 'clanok_id', $clanok_id);
    return $row[0]['nazov_' . $_SESSION['selectedLang'] . ''];
}

/**
 * *******************************************************************************************************************
 */
function getDescriptionForClanok($clanok_id)
{

    // $row = getTableRow('clanok', 'clanok_id', $clanok_id );
    $row = getTableRowsByAttribudes('clanok', 'big_text_' . $_SESSION['selectedLang'], ' AND clanok_id="' . $clanok_id . '" ', 'clanok_id', 0, 1);
    return mb_substr(str_replace(array(
            "\r",
            "\r\n",
            "\n"
        ), '', trim(strip_tags($row[0]['big_text_' . $_SESSION['selectedLang'] . '']))), 0, 300) . ' ...';
}

/**
 * *******************************************************************************************************************
 */
function getSectionIdFromName($sectionName)
{
    $sql = 'SELECT structure_id FROM structure WHERE name_sk = "' . $sectionName . '"';

    $result = psw_mysql_query($sql);
    $id = $result->fetch_assoc();

    if (!isset($id['structure_id'])) {
        return 0;
    } else {
        return $id['structure_id'];
    }
}

/**
 * *******************************************************************************************************************
 */
function getSectionNameFromNormalizedName($sectionNormalizedName)
{
    $query = psw_mysql_query('SELECT name_' . $_SESSION['selectedLang'] . ' FROM structure WHERE 1=1');
    $name = 'home';
    // foreach prejdem cele pole ak najdem vratim ak nie vratim false
    while ($sectionName = $query->fetch_assoc()) {

        if (normalizeClanokName($sectionName['name_' . $_SESSION['selectedLang'] . '']) == $sectionNormalizedName) {
            $name = $sectionName['name_' . $_SESSION['selectedLang'] . ''];
            break;
        }
    }
    return $name;
}

/**
 * *******************************************************************************************************************
 */

// Funkcia, ktora vyhodi vystrazne okno
function alert($hlaska)
{
    echo "<script>alert(\"" . $hlaska . "\");</script>";
}

/**
 * *******************************************************************************************************************
 */

// Zisti ci ma uzivatel ban a vrati pole...[0 = type] [1 = reason]
function isBanned($ip)
{
    $pole = array();
    $pole[0] = 0;
    $ban = sqlGetRow(('SELECT * FROM banlist WHERE ip = "' . $ip . '" '));
    if (!$ban) {
        return false;
    } else {
        $pole[0] = $ban['ban_type'];
        $pole[1] = $ban['reason'];
        return $pole;
    }
}

/**
 * *******************************************************************************************************************
 */
function debug($variable, $file = '', $line = '')
{
    echo '<b>file:</b> ' . $file . ' <b>line</b>: ' . $line . '<br /><pre>';
    print_r($variable);
    echo '</pre>' . "\n";
}

/**
 * *******************************************************************************************************************
 */
function printOdkazy()
{
    global $path;

    $odkazy = array();
    $sql = ('SELECT * FROM odkaz');

    $result = psw_mysql_query($sql);
    while ($odkaz = $result->fetch_assoc()) {

        echo '
		 <a href="' . $odkaz['link'] . '" title="' . $odkaz['alt'] . '" rel="external" >' . (PARTNERI_TEXTOVO ? $odkaz['alt'] : '<img style="border:1px solid #000000;" alt="' . $odkaz['alt'] . '" src="' . $path . 'image/2/' . $odkaz['odkaz_id'] . '.jpg" />') . '</a>' . (PARTNERI_TEXTOVO ? '<br />' : '');
    }
}

/**
 * *******************************************************************************************************************
 */
function getPartyListNr()
{
    $pocet = sqlGetRow(('SELECT count(*) AS pocet FROM partylist WHERE schvalene = "1" AND archiv = 0'));
    $pocet = $pocet['pocet'];
    return $pocet;
}

function printPartyList()
{
    global $path;

    $partyList = array();

    $i = 1;
    echo '<div id="partylist-btns" >';
    $cmd = psw_mysql_query('SELECT partylist_id FROM partylist WHERE schvalene = "1" AND archiv = 0 ');

    while ($partyList = $cmd->fetch_assoc()) {

        echo '<a class="partylist-nr ' . ($i == 1 ? 'hover' : '') . '" id="partylist-nr-' . $i . '"><span>' . $i . '</span></a>';
        $i++;
    }
    echo '</div>';

    $cmd = psw_mysql_query('SELECT * FROM partylist WHERE schvalene = "1" AND archiv = 0 ORDER BY ordering ASC, datetime ASC ');
    $is = false;
    $i = 1;
    while ($partyList = $cmd->fetch_assoc()) {

        $is = true;
        $www = '';
        if (strlen($partyList['link']) > 0) {
            if (strpos($partyList['link'], 'http') > -1) {
                $www = ' / <a href="' . $partyList['link'] . '" rel="external">WWW</a>';
            } else {
                $www = ' / <a href="http://' . $partyList['link'] . '" rel="external">WWW</a>';
            }
        }

        echo '
		  <div class="partylist_item ' . ($i == 1 ? '' : 'hider') . ' partylist-nr-' . $i . '" >
       <a rel="gb_imageset[partylist]" href="' . $path . 'image/4/' . $partyList['partylist_id'] . '.jpg" ><img src="' . $path . 'image/3/' . $partyList['partylist_id'] . '.jpg" alt="' . $partyList['title'] . '"  /></a>
       <span class="cleaner"><span>cleaner</span></span>
       <span class="info-sipka"><span>info</span></span>
       <span class="info">
       	<span class="left">
       		<b>' . $partyList['title'] . '</b><br />
       		' . date('d.m.Y', strtotime($partyList['datetime'])) . ' / ' . $partyList['klub'] . ' / ' . $partyList['mesto'] . '<br />
       		Od: ' . date('G:i', strtotime($partyList['datetime'])) . ' / ' . $partyList['vstupne'] . $www . '
       	</span>
       </span>             
      </div>
      	';
        $i++;
    }

    if (!$is) {
        echo '
      <br /><p align="left">Žiadna akcia nie je vložená.</p><br /><br />
		';
    }
}

/**
 * *******************************************************************************************************************
 */
function getThisWeekParty()
{
    $cmd = psw_mysql_query('SELECT * FROM partylist WHERE schvalene = "1" AND archiv = 0 ORDER BY ordering ASC LIMIT 0, 3 ');
    $return = false;

    $return = $cmd->fetch_assoc();


    return $return;
}

/**
 * *******************************************************************************************************************
 */
function echoClanok($id)
{
    global $path;

    $section = getSectionByClanokId($id);
    // read story from db
    $clanok = getTableRow('clanok', 'clanok_id', $id);
    $clanok = $clanok[0];

    $canDisplay = true;
    if ($clanok['koncept'] == '1') {
        $canDisplay = false;
        if ($_SESSION['meno_uzivatela']) {
            $canDisplay = true;
        }
    }
    if ($canDisplay) {
        echoClanokDetail($clanok);
        incClanokReadCounter($id);

        $dirPath = 'fotoalbumy/alb_' . $clanok['clanok_id'];

        $destination = '' . $path . '' . $dirPath . "/thumbs/";
        $destinationBig = '' . $path . '' . $dirPath . "/";

        $pocet = sqlGetRow(('SELECT count(*) AS pocet FROM picture WHERE clanok_id = "' . $clanok['clanok_id'] . '" '));
        $pocet_stran = ceil($pocet['pocet'] / POCET_OBRAZKOV_NA_STRANU);

        $foto = getTableRows('picture', ' AND clanok_id = "' . $clanok['clanok_id'] . '"', 'ordering ASC, picture_id DESC');
        if ($pocet['pocet'] > 0) {
            echo '<div id="fotoalbum"' . ($pocet['pocet'] <= POCET_OBRAZKOV_NA_STRANU ? 'class="oneSiteAlbum"' : '') . '>
       		<b>Fotogaléria</b>' . ($clanok['fotograf'] ? ' - fotograf ' . $clanok['fotograf'] . '' : '') . '<br />(počet fotografií: ' . $pocet['pocet'] . ')
       		<div class="fotoalbum-right-box"><a class="fotoalbum-right" href="#" onclick="return false;"><span>right</span></a></div>
       		<div class="fotoalbum-left-box"><a class="fotoalbum-left" href="#" onclick="return false;"><span>left</span></a></div>
       		<div class="cleaner"></div>
       		
       <div class="tab_container">';

            for ($i = 1; $i <= $pocet_stran; $i++) {
                echo '<div id="tab' . $i . '" class="tab_content">';
                for ($j = (($i - 1) * POCET_OBRAZKOV_NA_STRANU); $j < (POCET_OBRAZKOV_NA_STRANU * $i); $j++) {

                    if ($foto[$j]['filename']) {
                        echo '<a class="image" href="' . $destinationBig . $foto[$j]['filename'] . '" rel="prettyPhoto[foto]" title="' . validateForm(getClanokNameFromId($clanok['clanok_id']) . ($foto[$j]['text'] ? ': ' . $foto[$j]['text'] : '')) . '" >
				   		<img src="' . $destination . $foto[$j]['filename'] . '" alt="' . validateForm(getClanokNameFromId($clanok['clanok_id']) . ($foto[$j]['text'] ? ': ' . $foto[$j]['text'] : '')) . '" width="112" height="76" />
				   		<span class="hover"><span>image</span></span>
				  	  </a>';
                    }
                }
                echo '<div class="cleaner"></div></div>';
            }

            echo '</div>
	<div class="strankovanie">
		<div class="fotoalbum-left-full-box"><a class="fotoalbum-left-full" href="#" onclick="return false;"><span>left</span></a></div>
		<div class="fotoalbum-left-box"><a class="fotoalbum-left" href="#" onclick="return false;"><span>left</span></a></div>
		<div class="tabs">
	';
            if ($pocet_stran > 1) {
                for ($i = 1; $i <= $pocet_stran; $i++) {
                    echo '<a class="strankovanie-tab-' . $i . '" href="#tab' . $i . '">' . $i . '</a>';
                }
            }
            echo ' </div><div class="fotoalbum-right-box"><a class="fotoalbum-right" href="#" onclick="return false;"><span>right</span></a></div>
			<div class="fotoalbum-right-full-box"><a class="fotoalbum-right-full" href="#" onclick="return false;"><span>right</span></a></div>
	   		
	</div></div>

<script type="text/javascript">
/*<![CDATA[*/
	var activeTabGallery = "#tab1";
	var allTabsGallery = ' . $pocet_stran . ';
  	$(document).ready(function() {

	  	
		$("area[rel^=\'prettyPhoto\']").prettyPhoto({hideflash: true, deeplinking: false});
				
		$(".tab_container:first a[rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:3000, autoplay_slideshow: false, hideflash: true, deeplinking: false});
			
  		if(activeTabGallery == "#tab1"){
			$(".fotoalbum-left").fadeOut();
			$(".fotoalbum-left-full").fadeOut();
		}
  		if(allTabsGallery == 1){
			$(".fotoalbum-right").fadeOut();
			$(".fotoalbum-right-full").fadeOut();
		}
		
	 	$(".image:nth-child(5n)").css("margin-right", "0");
	
		$(".tab_content").hide(); //Hide all content
		$(".strankovanie .tabs A:first").addClass("hover"); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	
		$(".image").bind("mouseenter mouseleave", function(){
			$(this).find(".hover").toggle();
		});
		
		
		//On Click Event
		$(".strankovanie .tabs A").click(function() {
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content
			activeTabGallery = activeTab;
			$(activeTab).fadeIn(); //Fade in the active ID content
			var activeTabGalleryTmp = activeTab.substring(4);
			activeTabGalleryTmp = parseInt(activeTabGalleryTmp);
			showHideButtons(activeTabGalleryTmp);
			
			return false;
		});
		
		$(".fotoalbum-left").click(function() {
			$(".tab_content").hide(); //Hide all tab content
			var activeTabGalleryTmp = activeTabGallery.substring(4);
			activeTabGalleryTmp = parseInt(activeTabGalleryTmp);
			activeTabGalleryTmp--;
			$("#tab"+activeTabGalleryTmp).fadeIn();
			activeTabGallery = "#tab"+activeTabGalleryTmp;
			showHideButtons(activeTabGalleryTmp);
		
			return false;
			
		});
		$(".fotoalbum-right").click(function() {
			$(".tab_content").hide(); //Hide all tab content
			var activeTabGalleryTmp = activeTabGallery.substring(4);
			activeTabGalleryTmp = parseInt(activeTabGalleryTmp);
			activeTabGalleryTmp++;
			$("#tab"+activeTabGalleryTmp).fadeIn();
			activeTabGallery = "#tab"+activeTabGalleryTmp;
			showHideButtons(activeTabGalleryTmp);
		
			return false;
		});
		
		$(".fotoalbum-left-full").click(function() {
			$(".tab_content").hide(); //Hide all tab content		
			$("#tab1").fadeIn();
			activeTabGallery = "#tab1";
			showHideButtons(1);
	
			return false;		
		});
		
		$(".fotoalbum-right-full").click(function() {
			$(".tab_content").hide(); //Hide all tab content		
			$("#tab"+allTabsGallery).fadeIn();
			activeTabGallery = "#tab"+allTabsGallery;
			showHideButtons(allTabsGallery);
	
			return false;		
		});
		
		function showHideButtons(activeTabGalleryTmp){
			if(activeTabGalleryTmp==1){
				$(".fotoalbum-left").fadeOut();
				$(".fotoalbum-left-full").fadeOut();
			}
			if(activeTabGalleryTmp==allTabsGallery){
				$(".fotoalbum-right-full").fadeOut();
				$(".fotoalbum-right").fadeOut();
			}
			if(activeTabGalleryTmp>1){
				$(".fotoalbum-left").fadeIn();
				$(".fotoalbum-left-full").fadeIn();
			}
			if(activeTabGalleryTmp<allTabsGallery){
				$(".fotoalbum-right-full").fadeIn();
				$(".fotoalbum-right").fadeIn();
			}
			
			$(".strankovanie .tabs A").removeClass("hover");
			$(".strankovanie-tab-"+activeTabGalleryTmp).addClass("hover");
			
		}
	
	});
/*]]>*/
</script>
';
        }

        /*  if ($clanok['koncept'] == '0') {
              echo '<div class="fb-like" data-send="false" data-width="600" data-show-faces="false"></div>';
              echo '
          <div class="fb-like-box fb-like-box-bottom" data-href="http://www.facebook.com/sewer.sk" data-width="660" data-height="187" data-show-faces="true" data-stream="false" data-header="false" data-border-color="white"></div>

          ';
          }*/
        /*if ((COMMENTS == 1) && ($clanok['comments'] == '1') && ($clanok['koncept'] == '0')) {
            // FB comments echo

            echo '<div class="fb-comments" data-href="http://www.sewer.sk/clanok/' . $id . ($id <= 1429 ? '-' . normalizeClanokName(getClanokNameFromId($id)) : '') . '" data-num-posts="8" data-width="660"></div>';
        }*/
        /*
                echo '
                      <div id="linkbuild">
                          <div class="left">
                              <a title="Skate shop - Madman" href="http://madman.sk/hlavne-menu/17/eshop/" rel="external">Skate shop</a> značky <strong>Madman</strong>, ponúka ako <a title="Módne oblečenie - Madman" href="http://www.madman.sk" target="_blank">módne oblečenie</a> tak aj klasické <a title="Tričká a mikiny - Madman" href="http://madman.sk/kategorie/380/zlavy/" target="_blank">tričká a mikiny</a> pre mladých aktívnych ľudí. Súčasťou webu je taktiež <a title="Madman blog" href="http://blog.madman.sk/blog/vypis/" rel="external">blog</a> a <a title="Madman bazár" href="http://blog.madman.sk/bazar/vypis/bazar/" rel="external">bazár.</a>
                          </div>
                           <div class="right">
                              <a title="Dovolenka - Travelco" href="http://www.travelco.sk" target="_blank">Dovolenka</a> na tento rok? Využite <a title="Last minute - Travelco" href="http://www.travelco.sk/stranky/23:last-minute-zajazdy.html" target="_blank">last minute</a> <a title="Zájazdy - Travelco" href="http://www.travelco.sk/stranky/19:dovolenka-leto-2012.html" target="_blank">zájazdy</a> a <a title="Wellness pobyty - Travelco" href="http://www.travelco.sk/stranky/22:wellness-pobyty.html" target="_blank">wellness pobyty</a> s cestovnou agentúrou <strong>Travelco!</strong>
                           </div>
                          <div class="cleaner"></div>
                      </div>';*/
    } // TODO cannnot display - redirect
    else {

        echo '
		 
	<script type="text/javascript">
	/*<![CDATA[*/
		var url = "http://www.sewer.sk";    
		$(location).attr(\'href\',url);
		
		//-->
	</script>
		';
    }
}

/**
 * *******************************************************************************************************************
 */
function echoClanokList($id)
{
    global $path;

    // read story from db
    $clanok = getTableRow('clanok', 'clanok_id', $id);
    $clanok = $clanok[0];

    echoClanokZoznam($clanok);
}

/**
 * *******************************************************************************************************************
 */
function echoArticlesAjax($section, $from = 0, $pocet = POCET_CLANKOV_NA_STRANU)
{
    global $path;
    $sqlTmp = '';
    $sql = "";
    if ($section == 'home') {
        $sql = 'SELECT count(*) AS pocet FROM clanok WHERE koncept = 0 and `show`=1 AND datetime < now()';
        $clanky = getTableRowsByAttribudes('clanok', '*', 'AND koncept = 0  and `show`=1 AND datetime < now()', 'datetime', $from, $pocet);
    } elseif ((getStructureIdFromNormalizedName($section) == '') && (strpos($section, 'home') == -1)) {
    } else {


        if (strpos($section, 'home') > -1) {

            if ($section == "home_novinky") {
                $sectionTmp = array(
                    95,
                    52,
                    55,
                    60,
                    64,
                    68
                );
            } else if ($section == "home_rozhovory") {
                $sectionTmp = array(
                    71,
                    54,
                    58,
                    62,
                    66,
                    79
                );
            } else if ($section == "home_reportaze") {
                $sectionTmp = array(
                    70,
                    53,
                    57,
                    61,
                    65
                );
            } else if ($section == "home_tlacovky") {
                $sectionTmp = array(
                    72,
                    56,
                    59,
                    63,
                    67,
                    80
                );
            } else if ($section == "home_sutaze") {
                $sectionTmp = array(
                    73
                );
            }

            foreach ($sectionTmp as $sectionItem) {
                $sqlTmp .= '"' . $sectionItem . '", ';
            }

            $sqlTmp = substr($sqlTmp, 0, -2);
        } else {

            $mainSectionId = getSectionIdFromName($section);
            $cmd = psw_mysql_query('SELECT * FROM structure WHERE parent_id = ' . $mainSectionId . ' ');

            while ($sections = $cmd->fetch_assoc()) {

                $sqlTmp .= '"' . $sections['structure_id'] . '", ';
            }

            $sqlTmp .= '"' . $mainSectionId . '"';
        }
        $sql = 'SELECT count(*) AS pocet FROM clanok WHERE koncept = 0  and `show`=1 AND structure_id IN(' . $sqlTmp . ') AND datetime < now() ';

        $clanky = getTableRowsByAttribudes('clanok', '*', 'AND koncept = 0  and `show`=1 AND structure_id IN(' . $sqlTmp . ') AND datetime < now() ', 'datetime', $from, $pocet);
    }

    $pocet_clankov = sqlGetRow(($sql));
    $pocet_clankov = $pocet_clankov['pocet'];

    if ($pocet_clankov > 0) {
        foreach ($clanky as $clanok) {
            echoClanokZoznam($clanok);
        }
        echo '<div class="cleaner"></div>';
        // toto uz je len listing, ale to najdolezitejsie hmm
        // nech rozmyslam ako rozmyslam, najjedoduchie bude riesit aiting a fade out a potom fade in.. posuvanie bude graficky divne?
        // takze onclick = setnuty limit a pocet

        if ($pocet_clankov > POCET_CLANKOV_NA_STRANU) {

            $pocet_stranok = ceil($pocet_clankov / $pocet);

            $aktualnaStrana = ($from / $pocet) + 1;

            echo '<div class="story_listing" >
		
			<div class="clanok-right-full-box">' . ($aktualnaStrana < $pocet_stranok ? '<a class="clanok-right-full" href="#" onclick="return false;"><span>right</span></a>' : '') . '</div>
			<div class="clanok-right-box">' . ($aktualnaStrana < $pocet_stranok ? '<a class="clanok-right" href="#" onclick="return false;"><span>right</span></a>' : '') . '</div>
		<div class="tabs">
	';

            if ($pocet_stranok <= 5) {
                echo '<div class="nocursor">&nbsp;</div>';
                for ($i = 1; $i <= $pocet_stranok; $i++) {
                    echo '<a class="strankovanie-tab-' . $i . '' . ($aktualnaStrana == $i ? ' hover' : '') . '" href="#tab' . $i . '" >' . $i . '</a>';
                }
                echo '<div class="nocursor">&nbsp;</div>';
            } else {
                for ($i = ($aktualnaStrana - 3); $i <= ($aktualnaStrana + 3); $i++) {
                    if ($i > 0 && $i <= $pocet_stranok) {
                        echo '<a class="strankovanie-tab-' . $i . '' . ($aktualnaStrana == $i ? ' hover' : '') . '" href="#tab' . $i . '">' . $i . '</a>';
                    } else {
                        echo '<div class="nocursor">&nbsp;</div>';
                    }
                }
                // echo '<div style="float: left"> . . . </div>';
                // for($i=$pocet_stranok-3;$i<=$pocet_stranok;$i++){
                // echo '<a class="strankovanie-tab-'.$i.''.($aktualnaStrana == $i?' hover':'').'" href="#tab'.$i.'">'.$i.'</a>';
                // }
            }

            echo ' </div>';

            echo '
			
		
		<div class="clanok-left-box">' . ($aktualnaStrana > 1 ? '<a class="clanok-left" href="#" onclick="return false;"><span>left</span></a>' : '') . '</div>
		<div class="clanok-left-full-box">' . ($aktualnaStrana > 1 ? '<a class="clanok-left-full" href="#" onclick="return false;"><span>left</span></a>' : '') . '</div>	
		<script type="text/javascript">
		/*<![CDATA[*/
				var activeTabClanok = "#tab' . ($aktualnaStrana) . '";
				var activeTab = "' . ($aktualnaStrana) . '";
				var allTabsClanok = ' . $pocet_stranok . ';
				var pocetClankovNaStranu = ' . ($pocet) . ';
				
			  	$(document).ready(function() {
			
			  		if(activeTabClanok == "#tab1"){
						$(".clanok-left").fadeOut();
						$(".clanok-left-full").fadeOut();
					}
			  		if(allTabsClanok == 1){
						$(".clanok-right").fadeOut();
						$(".clanok-right-full").fadeOut();
					}
								
					//On Click Event
					$(".story_listing .tabs A").click(function() {
					
						var activeTabClanokTmp = $(this).attr("href"); 
			
						activeTabClanokTmp = activeTabClanokTmp.substring(4);
						activeTabClanokTmp = parseInt(activeTabClanokTmp);
						
						ajaxDoStrankaClankov( ((activeTabClanokTmp-1)*pocetClankovNaStranu) , pocetClankovNaStranu, \'' . $section . '\', (activeTab-1));			
						
					});
					
					$(".clanok-left").click(function() {			
						ajaxDoStrankaClankov( ((activeTab-2)*pocetClankovNaStranu) , pocetClankovNaStranu, \'' . $section . '\', (activeTab-1));
					});
					$(".clanok-right").click(function() {		
						ajaxDoStrankaClankov( (activeTab*pocetClankovNaStranu) , pocetClankovNaStranu, \'' . $section . '\', activeTab+1);			
					});
					
					
					$(".clanok-left-full").click(function() {		
						ajaxDoStrankaClankov( 0 , pocetClankovNaStranu, \'' . $section . '\', 1000);
					});
					
					$(".clanok-right-full").click(function() {		
						ajaxDoStrankaClankov( ((allTabsClanok-1)*pocetClankovNaStranu) , pocetClankovNaStranu, \'' . $section . '\', (-1));
					});
					
						
				});
	/*]]>*/
</script>
</div>';
        }
    } else {
        echo '<br /><br /><b>V tejto sekcii sa nenachádzajú žiadne články.</b>';
    }
}

/**
 * *******************************************************************************************************************
 */
function validateForm($text)
{
    $text = mb_str_replace('"', '&quot;', $text);
    return $text;
}

/**
 * *******************************************************************************************************************
 */
function getNazovClankuFromId($clanok_id)
{
    $clanok = getTableRow('clanok', 'clanok_id', $clanok_id);
    $nazov = $clanok[0]['nazov_' . $_SESSION['selectedLang'] . ''];
    return $nazov;
}

/**
 * *******************************************************************************************************************
 */
function getSectionNameFromClanokId($clanok_id)
{
    $name = sqlGetRow('SELECT name_sk FROM structure WHERE structure_id IN (SELECT structure_id FROM clanok WHERE clanok_id = "' . $clanok_id . '" )');
    $name = $name['name_sk'];
    return $name;
}

/**
 * *******************************************************************************************************************
 */
function incClanokReadCounter($id = 0)
{
    psw_mysql_query('UPDATE clanok SET counter = (counter+1) WHERE clanok_id = ' . $id . '');
}

/**
 * *******************************************************************************************************************
 */
function echoPartyfotoz($galery = false)
{

    // getall partylists
    global $path;

    if (is_numeric($galery)) {
        echoGaleryByGaleryIdGeryBox($galery);
    } else {
        // foreach print partyfotoz
        $parties = getTableRows('section', ' AND structure="partyfotoz" ', 'section_id');
        echo '
			<table class="big_story_table" cellspacing="0" cellpadding="0">
			 <tr>
			  <td class="story_head">
			   <h3>Party Fotoz</h3>
			  </td>
			 </tr>
			 <tr>
			  <td class="story_body"><br />';

        foreach ($parties as $party) {
            echo '<a href="' . $path . 'index.php?section=partyfotoz&amp;galery=' . $party['section_id'] . '"><b>' . $party['section_name'] . '</b></a><br />';
        }

        echo '<br />
        </td>
       </tr>
      </table>';
    }
}

/**
 * *******************************************************************************************************************
 */
function printPartyListSection()
{
    global $path;

    $partyList = array();
    $is = false;
    $cmd = psw_mysql_query('SELECT * FROM partylist WHERE schvalene = 1 AND archiv = 0 ORDER BY ordering ASC');

    while ($clanok = $cmd->fetch_assoc()) {

        $is = true;

        $www = '';
        if (strlen($clanok['link']) > 0) {
            if (strpos($clanok['link'], 'http') > -1) {
                $www = '<b><a href="' . $clanok['link'] . '" rel="external">WWW</a></b><br />';
            } else {
                $www = '<b><a href="http://' . $clanok['link'] . '" rel="external">WWW</a></b><br />';
            }
        }

        echo '
			 <div style="background-image: url(' . $path . 'image/5/' . $clanok['partylist_id'] . '.jpg);" class="short_story_table_partylist" href="' . $path . 'image/4/' . $clanok['partylist_id'] . '.jpg" >
			  Názov akcie: <b>' . $clanok['title'] . '</b><br /><br />			
			  <b>Dátum konania akcie:</b> ' . date('d.m.Y', strtotime($clanok['datetime'])) . '<br />
			  <b>Klub:</b> ' . $clanok['klub'] . '<br /> 	
			  <b>Mesto konania:</b> ' . $clanok['mesto'] . '<br />
			  <b>Začiatok akcie:</b> ' . date('G:i', strtotime($clanok['datetime'])) . '<br />
			  <b>Vstupné:</b> ' . $clanok['vstupne'] . '<br />
			  ' . $www . '		  
			  <a rel="gb_imageset[partylist' . $clanok['partylist_id'] . ']" href="' . $path . 'image/4/' . $clanok['partylist_id'] . '.jpg" ><b>Plagát</b></a>		      	  
			 </div>		
			';
    }
    if (!$is) {
        echo '
		<br />Žiadna párty nieje vložená.<br /><br />
		';
    }
}

/**
 * *******************************************************************************************************************
 */
function getClankyForRSS($pocet = 5)
{
    $clankyHomeRSS = array();
    $cmd = psw_mysql_query($sql = 'SELECT * FROM clanok WHERE koncept = 0 ORDER BY datetime DESC LIMIT ' . $pocet . ' ');

    while ($clankyHomeRSSTmp = $cmd->fetch_assoc()) {

        $clankyHomeRSS[] = $clankyHomeRSSTmp;
    }
    return $clankyHomeRSS;
}

/**
 * *******************************************************************************************************************
 */
function getClankyForBanner($pocet = 5, $section = false)
{
    if ($section == 'partylist' || $section == 'search') {
        $section = 'home';
    }

    $pos = strpos($section, '_');
    if ($pos > 0) {
        $section = substr($section, 0, $pos);
    }

    $isHomeSection = false;

    if (strpos($section, 'home') > -1) {
        $isHomeSection = true;
    }

    if (!$isHomeSection) {
        $sqlTmp = '';

        $result = psw_mysql_query($sql = 'SELECT * FROM structure WHERE parent_id = ' . getSectionIdFromName($section) . ' ');

        if ($result->num_rows > 0) {
            while ($sections = $result->fetch_assoc()) {

                $sqlTmp .= '"' . $sections['structure_id'] . '", ';
            }
        }
        $sqlTmp .= '"' . getSectionIdFromName($section) . '"';
    }

    $clankyHomeBanner = array();
    $cmd = psw_mysql_query($sql = 'SELECT * FROM clanok WHERE koncept = 0 AND banner = 1 AND datetime < now() ' . (!$isHomeSection ? 'AND structure_id IN(' . $sqlTmp . ') ' : '') . '  ORDER BY datetime DESC LIMIT ' . $pocet . ' ');
    // echo $sql;
    if ($cmd->num_rows > 0) {
        while ($sections = $cmd->fetch_assoc()) {
            $clankyHomeBanner[] = $sections;
        }
    }
    return $clankyHomeBanner;
}

/**
 * *******************************************************************************************************************
 */
function printSearching()
{
    global $path;

    // hm, najprv si zistim v com mam vyhladaavat a potom budem vyhladavat
    $text = $_REQUEST['search_text'];

    if (($text != '')) {
        echo '<div class="cleaner"></div><div class="heading">Nájdené výsledky v nadpisoch:</div>';
        $clanky = getTableRowsByAttribudes('clanok', 'clanok_id, koncept', ' AND nazov_' . $_SESSION['selectedLang'] . ' LIKE "%' . trim($text) . '%"', 'datetime');
        $is = false;
        foreach ($clanky as $clanok) {
            if ($clanok['koncept'] != '1') {
                echoClanokList($clanok['clanok_id']);
                $is = true;
            }
        }

        echo '<div class="cleaner"></div><br /><div class="heading">Nájdené výsledky v článkoch:</div>';
        $clanky = getTableRowsByAttribudes('clanok', 'clanok_id, koncept', ' AND big_text_' . $_SESSION['selectedLang'] . ' LIKE "%' . trim($text) . '%"', 'datetime');
        foreach ($clanky as $clanok) {
            if ($clanok['koncept'] != '1') {
                $is = true;
                echoClanokList($clanok['clanok_id']);
            }
        }
        if (!$is) {
            echo '<b>Neboli nájdené žiadne výsledny</b><br />';
        }
    }

    if (mb_strlen($text) < 1) {
        echo '<br /><div class="heading">Vložte vyhľadávací reťazec</div><br />';
    }
}

/**
 * *******************************************************************************************************************
 */
function mb_str_replace($needle, $replacement, $haystack)
{
    $needle_len = mb_strlen($needle);
    $replacement_len = mb_strlen($replacement);
    $pos = mb_strpos($haystack, $needle);
    while ($pos !== false) {
        $haystack = mb_substr($haystack, 0, $pos) . $replacement . mb_substr($haystack, $pos + $needle_len);
        $pos = mb_strpos($haystack, $needle, $pos + $replacement_len);
    }
    return $haystack;

}

/**
 * *******************************************************************************************************************
 */
function getSectionByClanokId($clanok_id, $onlyMainSection = false)
{
    $sectionSql = sqlGetRow(('SELECT name_sk FROM structure s, clanok c WHERE c.clanok_id = "' . $clanok_id . '" AND s.structure_id = c.structure_id '));
    $section = $sectionSql['name_sk'];

    if ($onlyMainSection) {
        $pomlckaPos = strpos($section, '_');

        if ($pomlckaPos) {

            $section = substr($section, 0, $pomlckaPos);
            // echo $section;die;
        }
    }
    return $section;
}

/**
 * *******************************************************************************************************************
 */
function isSelectedSection($section)
{
    if (is_numeric($_REQUEST['id'])) {
        $sectionDb = getSectionByClanokId($_REQUEST['id']);
        if (is_numeric(strpos($sectionDb, $section))) {
            return true;
        } else {
            return false;
        }
    } else {
        if (is_numeric(strpos($_REQUEST['section'], $section))) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * *******************************************************************************************************************
 */
function normalizeFilename($name, $truncExt = false)
{
    if ($truncExt && mb_strrpos($name, '.') !== FALSE) {
        $name = mb_substr($name, 0, mb_strrpos($name, '.'));
    }

    static $tbl = array(
        "\xc3\xa1" => "a",
        "\xc3\xa4" => "a",
        "\xc4\x8d" => "c",
        "\xc4\x8f" => "d",
        "\xc3\xa9" => "e",
        "\xc4\x9b" => "e",
        "\xc3\xad" => "i",
        "\xc4\xbe" => "l",
        "\xc4\xba" => "l",
        "\xc5\x88" => "n",
        "\xc3\xb3" => "o",
        "\xc3\xb6" => "o",
        "\xc5\x91" => "o",
        "\xc3\xb4" => "o",
        "\xc5\x99" => "r",
        "\xc5\x95" => "r",
        "\xc5\xa1" => "s",
        "\xc5\xa5" => "t",
        "\xc3\xba" => "u",
        "\xc5\xaf" => "u",
        "\xc3\xbc" => "u",
        "\xc5\xb1" => "u",
        "\xc3\xbd" => "y",
        "\xc5\xbe" => "z",
        "\xc3\x81" => "A",
        "\xc3\x84" => "A",
        "\xc4\x8c" => "C",
        "\xc4\x8e" => "D",
        "\xc3\x89" => "E",
        "\xc4\x9a" => "E",
        "\xc3\x8d" => "I",
        "\xc4\xbd" => "L",
        "\xc4\xb9" => "L",
        "\xc5\x87" => "N",
        "\xc3\x93" => "O",
        "\xc3\x96" => "O",
        "\xc5\x90" => "O",
        "\xc3\x94" => "O",
        "\xc5\x98" => "R",
        "\xc5\x94" => "R",
        "\xc5\xa0" => "S",
        "\xc5\xa4" => "T",
        "\xc3\x9a" => "U",
        "\xc5\xae" => "U",
        "\xc3\x9c" => "U",
        "\xc5\xb0" => "U",
        "\xc3\x9d" => "Y",
        "\xc5\xbd" => "Z",
        " " => "_",
        "/" => "_",
        "&amp;" => "_",
        "?" => "_"
    );
    $name = strtr(mb_strtolower($name), $tbl); // odstraneni akcentu + nahrazeni nekterych znaku a prevedeni na male znaky

    $tbl2 = array(
        '.',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '0',
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        '_',
        '-'
    );
    for ($x = 0; $x < mb_strlen($name); $x++) {
        $ch = mb_substr($name, $x, 1);
        if (!in_array($ch, $tbl2)) {
            $name = str_replace($ch, '#', $name); // nahrazeni neznamych znaku #
        }
    }

    $name = str_replace('#', '', $name); // odstraneni #
    $name = ereg_replace('(-[-]+)', '-', $name); // odstraneni dvojitych -
    $name = trim($name, " -\n\t\r\0"); // orezani

    return $name;
}

/**
 * *******************************************************************************************************************
 */
function normalizeClanokName($name, $truncExt = false)
{
    if ($truncExt && mb_strrpos($name, '.') !== FALSE) {
        $name = mb_substr($name, 0, mb_strrpos($name, '.'));
    }

    static $tbl = array(
        "\xc1\xe1" => "a",
        "\xc9\xe9" => "e",
        "\xc3\xa1" => "a",
        "\xc3\xa4" => "a",
        "\xc4\x8d" => "c",
        "\xc4\x8f" => "d",
        "\xc3\xa9" => "e",
        "\xc4\x9b" => "e",
        "\xc3\xad" => "i",
        "\xc4\xbe" => "l",
        "\xc4\xba" => "l",
        "\xc5\x88" => "n",
        "\xc3\xb3" => "o",
        "\xc3\xb6" => "o",
        "\xc5\x91" => "o",
        "\xc3\xb4" => "o",
        "\xc5\x99" => "r",
        "\xc5\x95" => "r",
        "\xc5\xa1" => "s",
        "\xc5\xa5" => "t",
        "\xc3\xba" => "u",
        "\xc5\xaf" => "u",
        "\xc3\xbc" => "u",
        "\xc5\xb1" => "u",
        "\xc3\xbd" => "y",
        "\xc5\xbe" => "z",
        "\xc3\x81" => "A",
        "\xc3\x84" => "A",
        "\xc4\x8c" => "C",
        "\xc4\x8e" => "D",
        "\xc3\x89" => "E",
        "\xc4\x9a" => "E",
        "\xc3\x8d" => "I",
        "\xc4\xbd" => "L",
        "\xc4\xb9" => "L",
        "\xc5\x87" => "N",
        "\xc3\x93" => "O",
        "\xc3\x96" => "O",
        "\xc5\x90" => "O",
        "\xc3\x94" => "O",
        "\xc5\x98" => "R",
        "\xc5\x94" => "R",
        "\xc5\xa0" => "S",
        "\xc5\xa4" => "T",
        "\xc3\x9a" => "U",
        "\xc5\xae" => "U",
        "\xc3\x9c" => "U",
        "\xc5\xb0" => "U",
        "\xc3\x9d" => "Y",
        "\xc5\xbd" => "Z",
        "ž" => "z",
        "Ž" => "z",
        " " => "-",
        "/" => "-",
        "&amp;" => "-",
        "?" => "-"
    );

    // $name = utf2ascii2($name);

    $name = strtr(mb_strtolower($name), $tbl); // odstraneni akcentu + nahrazeni nekterych znaku a prevedeni na male znaky

    $tbl2 = array(
        '.',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '0',
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        '_',
        '-'
    );
    for ($x = 0; $x < mb_strlen($name); $x++) {
        $ch = mb_substr($name, $x, 1);
        if (!in_array($ch, $tbl2)) {
            $name = str_replace($ch, '#', $name); // nahrazeni neznamych znaku #
        }
    }

    $name = str_replace('#', '', $name); // odstraneni #
    //$name = ereg_replace('(-[-]+)', '-', $name); // odstraneni dvojitych -
    $name = trim($name, ". -\n\t\r\0"); // orezani

    // added lpastva 130312 - remove dots on the end of uri
    // $name

    return $name;
}

function utf2ascii2($string)
{
    $string = iconv('utf-8', 'windows-1250', $string);
    $win = "ěščřžýáíéťňďúůóöüäĚŠČŘŽÝÁÍÉŤŇĎÚŮÓÖÜËÄ\x97\x96\x91\x92\x84\x93\x94\xAB\xBB";
    $ascii = "escrzyaietnduuoouelloaESCRZYAIETNDUUOOUEA\x2D\x2D\x27\x27\x22\x22\x22\x22\x22";
    $string = StrTr($string, $win, $ascii);
    return $string;
}

/**
 * *******************************************************************************************************************
 */
function movedir($src, $dest)
{
    // nacitam si setky subory a potom v cykle najprv skopirujem a potom zmazem stary
    $src_files = scandir($src);
    $temp = array();
    $i = 0;
    foreach ($src_files as $src_file) {
        if (($src_file != '.') && ($src_file != '..') && ($src_file != 'thumbs')) {
            $temp[$i] = $src_file;
            $i++;
        }
    }
    $src_files = $temp;

    foreach ($src_files as $src_file) {
        copy($src . $src_file, $dest . $src_file);
        unlink($src . $src_file);
    }
}

/**
 * *******************************************************************************************************************
 */
function regenerateAudioPlaylist()
{
    unlink('../mp3/playlist.xml');
    $file = fopen('../mp3/playlist.xml', 'w');

    fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>
<xml>');

    // $songs = getTableRows('audio_playlist', '', ' ord ASC ');
    foreach ($songs as $song) {
        fwrite($file, '<track>
		<path>' . $song['text'] . '</path>
		<title>' . $song['href'] . '</title>
	</track>');
    }

    fwrite($file, '</xml>');
}

/**
 * *******************************************************************************************************************
 */
function echoVideoPlaylist()
{
    global $path;

    $videos = getTableRows('video_playlist', '', ' ord ASC ');
    foreach ($videos as $video) {

        echo '<span onclick="play(\'' . $video['text'] . '\');">» ' . $video['href'] . '</span><br />';
    }
}

/**
 * *******************************************************************************************************************
 */
function getPath()
{
    $pathArr = explode('/', $_SERVER['REQUEST_URI']);

    if ($pathArr[count($pathArr) - 2] == 'clanok') {
        $path = '../';
    } else if ($pathArr[count($pathArr) - 2] == 'sekcia') {
        $path = '../';
    } else {
        $path = '';
    }

    if ($pathArr[count($pathArr) - 2] == 'public') {
        $path = '../';
    }

    return $path;
}

/**
 * *******************************************************************************************************************
 */
function psw_mysql_query($sql)
{
    global $connId;

    return $connId->query($sql);
}

/**
 * *******************************************************************************************************************
 */
function psw_mysql_fetch_array($sql)
{
    $return = array();

    $result = psw_mysql_query($sql, $logDebug);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
    }
    return $return;

    /*
     * $tmp = mysql_fetch_array($sql);
     * //debug($sql);
     * if(is_array($tmp)){
     * foreach($tmp as $key => $t){
     * $tmp[$key] = str_replace("&quot;", '"', $t);
     * //$tmp[$key] = str_replace("&quot;", "'", $t);
     * }
     * } else if($tmp != '') {
     * $tmp = str_replace("&quot;", '"', $tmp);
     * } else {
     * return false;
     * }
     * return $tmp;
     */
}

/**
 * *******************************************************************************************************************
 */
function psw_sql_escape($str)
{
    // debug($sql);
    if (strlen($str) > 0) {
        $str = mysql_escape_string($str);
    }

    return $str;
}

/**
 * *******************************************************************************************************************
 */
function writeFormObject($popis = '', $typ = '', $nazov = '', $value = '', $multilang = false, $style = '', $data = null, $nullValue = true, $potomkov = true, $optionalAttrs = '')
{
    global $lang, $path;
    if ($typ == 'text') {
        if ($multilang) {
            foreach ($lang as $langItem) {
                echo '<tr><td><b ' . $optionalAttrs . '>' . (count($lang) == 1 ? $popis : $popis . ' ' . $langItem) . ':</b></td><td><input type="text" name="' . $nazov . '_' . $langItem . '" style="' . $style . '" class="input_text" value="' . validateForm($value[$langItem]) . '" /></td></tr>';
            }
        } else {
            echo '<tr><td><b>' . $popis . ':</b></td><td><input type="text" name="' . $nazov . '" class="input_text" style="' . $style . '" value="' . $value . '" /></td></tr>';
        }
    } else if ($typ == 'textarea') {
        if ($multilang) {
            foreach ($lang as $langItem) {
                echo '
					<tr>
						<td>
							<b>' . (count($lang) == 1 ? $popis : $popis . ' ' . $langItem) . ':</b>
						</td>
						<td>
							<table>
								<tr>
    			 					<td style="width: 480px;">
	     								<img src="pics/btn-bold.png" class="button" value="Bold" id="bold_btn" alt="Tučné"  title="Tučné" />
	     								<img src="pics/btn-italic.png" class="button" value="Italic" id="italic_btn" alt="Kurzíva" title="Kurzíva" />
	     								<img src="pics/btn-underline.png" class="button" value="Underline" id="underline_btn" alt="Podčiarknuté" title="Podčiarknuté" />
	     								<img src="pics/btn-image.png" class="button" value="Image" id="image_btn" alt="Obrázok" title="Obrázok" />
	     								<img src="pics/btn-href.png" class="button" value="Link" id="href_btn" alt="Odkaz" title="Odkaz" />
	     								<img src="pics/btn-yt.png" class="button" value="Youtube video" id="yt_btn" alt="Video youtube" title="Označ text ID z youtube (napr.: M97vR2V4vTs - nachádza sa v URL) a stlač toto tlačítko." />
	     								<iframe width="30" height="30" src="admin_clanok_article_image_upload.php?type=1" frameborder="0"></iframe>
                      					<a href="admin_upload_file.php" onclick="window.open(\'admin_upload_file.php\', \'\', \'scrollbars=1,width=600,height=700\'); return false;" target="_blank"><img src="pics/btn-library.png" class="button" value="Library" id="library_btn" alt="Knižnica" title="Knižnica" /></a>	
	    			 					<textarea id="' . $nazov . '_' . $langItem . '" name="' . $nazov . '_' . $langItem . '" style="' . $style . '" class="input_textarea" >' . validateForm($value[$langItem]) . '</textarea>
    								</td>
   								</tr>
   							</table>
   						</td>
   					</tr>';
            }
        } else {
            echo '
					<tr>
						<td>
							<b>' . $popis . ':</b>
						</td>
						<td>
							<table>
								<tr>
    			 					<td style="width: 480px;">
	     								<img src="pics/btn-bold.png" class="button" value="Bold" id="bold_btn" alt="Tučné"  title="Tučné" />
	     								<img src="pics/btn-italic.png" class="button" value="Italic" id="italic_btn" alt="Kurzíva" title="Kurzíva" />
	     								<img src="pics/btn-underline.png" class="button" value="Underline" id="underline_btn" alt="Podčiarknuté" title="Podčiarknuté" />
	     								<img src="pics/btn-image.png" class="button" value="Image" id="image_btn" alt="Obrázok" title="Obrázok" />
	     								<img src="pics/btn-href.png" class="button" value="Link" id="href_btn" alt="Odkaz" title="Odkaz" />
	     								<img src="pics/btn-yt.png" class="button" value="Youtube video" id="yt_btn" alt="Video youtube" title="Označ text ID z youtube (napr.: M97vR2V4vTs - nachádza sa v URL) a stlač toto tlačítko." />
	     								<iframe width="30" height="30" src="admin_clanok_article_image_upload.php?type=1" frameborder="0"></iframe>	     									     								
                      					<a href="admin_upload_file.php" onclick="window.open(\'admin_upload_file.php\', \'\', \'scrollbars=1,width=600,height=700\'); return false;" target="_blank"><img src="pics/btn-library.png" class="button" value="Library" id="library_btn" alt="Knižnica" title="Knižnica" /></a>	
	    			 					<textarea id="' . $nazov . '" name="' . $nazov . '" style="' . $style . '" class="input_textarea" >' . validateForm($value) . '</textarea>
    								</td>
   								</tr>
   							</table>
   						</td>
   					</tr>';
        }
    } else if ($typ == 'textarea2') {
        if ($multilang) {
            foreach ($lang as $langItem) {
                echo '
					<tr>
						<td>
							<b>' . (count($lang) == 1 ? $popis : $popis . ' ' . $langItem) . ':</b>
						</td>
						<td>
							<textarea id="' . $nazov . '_' . $langItem . '" name="' . $nazov . '_' . $langItem . '" style="' . $style . '" class="input_textarea" >' . validateForm($value[$langItem]) . '</textarea>
   						</td>
   					</tr>';
            }
        } else {
            echo '
					<tr>
						<td>
							<b>' . $popis . ':</b>
						</td>
						<td>
							<textarea id="' . $nazov . '" name="' . $nazov . '" style="' . $style . '" class="input_textarea" >' . validateForm($value) . '</textarea>
   						</td>
   					</tr>';
        }
    } else if ($typ == 'file') {
        echo '<tr><td><b>' . $popis . ':</b></td><td><input type="file" name="' . $nazov . '" class="input_file" style="' . $style . '" value="' . $value . '" /></td></tr>';
    } else if ($typ == 'select') {

        echo '<tr>
		 	   <td><b>' . $popis . ':</b></td>
		 	   <td>
		 	    <select name="' . $nazov . '" class="input_select" ' . $optionalAttrs . '>
		 	     ' . ($nullValue ? '<option>&nbsp;</option>' : '');
        if (is_array($data)) {
            foreach ($data as $key => $dataItem) {
                if ((!$potomkov) && (!strpos($key, getSectionNameById($_REQUEST['structure_id'])))) {
                    echo '<option value="' . $dataItem . '" ' . ($dataItem == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
                } elseif ((!$potomkov) && (strlen(substr($key, (strpos($key, getStructureNameFromId($_REQUEST['structure_id'])) + 2))) == 0)) {
                    echo '<option value="' . $dataItem . '" ' . ($dataItem == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
                } elseif ($potomkov) {
                    echo '<option value="' . $dataItem . '" ' . ($dataItem == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
                }
            }
        }
        echo '
		 	    </select>
		 	   </td></tr>';
    } else if ($typ == 'select_rights') {
        echo '<tr>
		 	   <td><b>' . $popis . ':</b></td>
		 	   <td>
		 	    <select name="' . $nazov . '" class="input_select" style="' . $style . '" ' . $optionalAttrs . '>
		 	     ' . ($nullValue ? '<option>&nbsp;</option>' : '');
        if (is_array($data)) {
            foreach ($data as $key => $dataItem) {
                if (userGetAccess($_SESSION['meno_uzivatela'], 'str_' . $dataItem)) {
                    echo '<option value="' . $dataItem . '" ' . ($dataItem == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
                }
            }
        }
        echo '
		 	    </select>
		 	   </td></tr>';
    }
}

/**
 * *******************************************************************************************************************
 */
function getKeywords($id)
{
    if ($id > 0) {
        $keywords = getTableRowsByAttribudes('clanok', 'keywords_' . $_SESSION['selectedLang'] . '', ' AND clanok_id=' . $id . ' ', ' clanok_id ASC ');
        return $keywords[0]['keywords_' . $_SESSION['selectedLang'] . ''];
    } else {
        return false;
    }
}

/**
 * *******************************************************************************************************************
 */
function getTreeArray($parent_id = 1, $parent_name = ' / ', $byRights = false)
{
    // echo $parent_id.' . '. $parent_name . ' . ' . $byRights;die;
    $tree = array();
    // asi najzlozitejsia funkcia, ktora mi vrati strom, ale zobrazeny ako v isse s lomitkami, uff

    // // najdem si najprv uzol s id 0, ak je, tak ho hodim do pola, ktore budem vracat a potom budem pokracovat dalej.
    $statement = 'SELECT structure_id, name_sk FROM structure WHERE parent_id = ' . $parent_id . ' ORDER BY position ASC';

    $result = psw_mysql_query($statement);
    while ($data = $result->fetch_assoc()) {

        // $data = $data[0];
        // preco mi to vracia este poslednu hodnotu prazdnu, plus musim poriesit to ze mi to musi vracat nie rekurzivne pole ale seriova v rekurzii
        if ($byRights == false) {
            $tree[count($tree)][0] = $parent_name . $data['name_sk'] . ' / ';
            $tree[count($tree) - 1][1] = $data['structure_id'];

            // print_r($data);
            // print_r($parent_name.$data['name_sk'].' / ');
        } else {
            if ($data['structure_id'] != 1 && userGetAccessBySectionId($byRights, $data['structure_id'])) {
                $tree[count($tree)][0] = $parent_name . $data['name_sk'] . ' / ';
                $tree[count($tree) - 1][1] = $data['structure_id'];
            }
        }
        // print_r($data);
        // ;die;
        // a teraz uz len budem pre kazdu hodnotu na pozicii 0 budem volat tu istu fciu
        // $tmpTree = getTreeArray($data['structure_id'], $parent_name.$data['name_sk'].' / ');
        // $tree = array_merge($tree, $tmpTree);
    }

    return $tree;
}

/**
 * *******************************************************************************************************************
 */
function transformTreeArray($oldTree = array())
{
    $newTree = array();
    if (count($oldTree) > 0) {
        foreach ($oldTree as $oldTreeItem) {
            $newTree[$oldTreeItem[0]] = $oldTreeItem[1];
        }
    }
    return $newTree;
}

/**
 * *******************************************************************************************************************
 */
function getParentById($id, $ignoreRoot = false)
{
    $sql = 'SELECT parent_id FROM structure WHERE structure_id = ' . $id . ' ';

    $parent = sqlGetRow(($sql));
    if ($parent['parent_id'] > 0) {
        if ($ignoreRoot && $parent['parent_id'] == 1) {
            return $id;
        } else {
            return $parent['parent_id'];
        }
    } else {
        return false;
    }
}

/**
 * *******************************************************************************************************************
 */
function getFirstChildById($id)
{
    $sql = 'SELECT structure_id FROM structure WHERE parent_id = ' . $id . ' ORDER BY position ASC';

    if ($parent = sqlGetRow(($sql))) {
        return $parent['structure_id'];
    } else {
        return false;
    }
}

/**
 * *******************************************************************************************************************
 */
function getAllChildsByParent($id)
{
    $sql = 'SELECT structure_id FROM structure WHERE parent_id = ' . $id . ' ORDER BY position ASC';
    $return = array();
    $query = psw_mysql_query($sql);

    while ($parent = $query->fetch_assoc()) {

        $return[] = $parent['structure_id'];
        $tmp = getAllChildsByParent($parent['structure_id']);
        if ($tmp) {
            $return = array_merge($return, $tmp);
        }
    }
    if (count($return) > 0) {
        return $return;
    } else {
        return false;
    }
}

/**
 * *******************************************************************************************************************
 */
function getNamePathForStructure($structure_id)
{
    $path = '';
    $structure_id_old = $structure_id;
    while ($parent = getParentById($structure_id)) {
        $path = '/' . getStructureNameFromId($parent) . $path;
        $structure_id = $parent;
    }
    return $path . '/' . getStructureNameFromId($structure_id_old);
}

/**
 * *******************************************************************************************************************
 */
function getStructureIdByFotoalbumId($clanokId)
{
    $tmp = sqlGetRow(('SELECT structure_id FROM clanok WHERE clanok_id = "' . $clanokId . '" '));
    return $tmp['structure_id'];
}

/**
 * *******************************************************************************************************************
 */
function getStructureIdByClanokId($clanokId)
{
    $tmp = sqlGetRow(('SELECT structure_id FROM clanok WHERE clanok_id = "' . $clanokId . '" '));
    return $tmp['structure_id'];
}

/**
 * *******************************************************************************************************************
 */
function getMenu()
{
    $tree = transformTreeArray(getTreeArray());
    global $path;
    // vypisem si kazdy zaznam, v kazdom zazneme vypisem kazdy uzol ako blok, ale nazov len u posledneho
    $menu .= '
	<div class="tree">';

    foreach ($tree as $key => $treeItem) {
        $menu .= '<div class="tree_row">';
        $treeItemItem = explode('/', $key);
        for ($i = 1; $i < (count($treeItemItem) - 1); $i++) {
            if ($i == (count($treeItemItem) - 2)) {
                $menu .= '<div class="tree_item"><a href="' . $path . 'sekcia/' . normalizeClanokName(mb_substr($treeItemItem[$i], 1, -1)) . '">' . $treeItemItem[$i] . '</a></div>';
            } else {
                $menu .= '<div class="tree_item">&nbsp;</div>';
            }
        }
        $menu .= '<div class="cleaner"></div>';
        $menu .= '</div>';
        $menu .= '<div class="cleaner"></div>';
    }

    $menu .= '
	</div>
	<br />';
    return $menu;
}

/**
 * *******************************************************************************************************************
 */
function printTopMenu($structureId, $tree)
{
    global $path, $notDisplayedSections;

    $structureName = getStructureNameFromId($structureId);

    if (strpos($structureName, '_') === false) {
        $mainStructureName = $structureName;
    } else {
        $mainStructureName = substr($structureName, 0, (strpos($structureName, '_')));
    }


    echo '<div id="topmenu">';
    echo '<a href="' . $path . 'sekcia/home" class="topmenu-a home" >Home</a>
	<script type="text/javascript">
					/*<![CDATA[*/
						var activeTopmenu = "' . ($mainStructureName == '' ? 'home' : $mainStructureName) . '";
					/*]]>*/</script>
					';

    $parentIdcka = getFirstParentId($structureId, $tree);

    foreach ($tree as $key => $value) {

        $treeItemItem = explode('/', $key);

        if (count($treeItemItem) == 3) {

            // not displayed secions
            if (!in_array($value, $notDisplayedSections)) {
                echo '<a href="' . (trim($treeItemItem[1]) != 'redakcia' ? $path . 'sekcia/' . normalizeClanokName(mb_substr($treeItemItem[1], 1, -1)) : $path . 'clanok/1035-o-nas') . '' . '"  class="topmenu-a ' . trim(normalizeClanokName(mb_substr($treeItemItem[1], 1, -1))) . '">
				 		' . (isSectionUpdated($value) ? '<span class="' . normalizeClanokName(trim($treeItemItem[1])) . '-new"><span>.</span></span>' : '') . '
			       		' . trim(getSectionFullnameByName(trim($treeItemItem[1]))) . '
			          </a>';

                if ($value == $parentIdcka) {
                    $poziciaPomlcky = strpos(getStructureNameFromId($structureId), '_');
                    if ($poziciaPomlcky > 0) {
                        $activeTopmenu = substr(getStructureNameFromId($structureId), 0, $poziciaPomlcky);
                    } else {
                        $activeTopmenu = getStructureNameFromId($structureId);
                    }
                    echo '
					<script type="text/javascript">
					/*<![CDATA[*/
						activeTopmenu = "' . $activeTopmenu . '";
					/*]]>*/</script>';
                }
            }
        }
    }
    echo '
	
</div>';
}

/**
 * *******************************************************************************************************************
 */
function isSectionUpdated($structureId)
{
    /*
     * if( strlen($_COOKIE['clanky'])>0){
     * $strArrTmp = Array();
     *
     * $tmpQuery = psw_mysql_query('SELECT structure_id FROM structure WHERE parent_id ="'.$structureId.'"');
     * $strArrTmp[] = $structureId;
     * while($tmpData = psw_mysql_fetch_array($tmpQuery)){
     * $strArrTmp[] = $tmpData['structure_id'];
     * }
     *
     *
     *
     * $sql = 'SELECT clanok_id FROM clanok WHERE structure_id IN (';
     *
     * foreach($strArrTmp as $val){
     * $sql .= $val.',';
     * }
     * $sql = substr($sql, 0, -1);
     * $sql .= ') ORDER BY datetime DESC LIMIT 5 ';
     *
     * while($clanok_id = sqlGetRow(($sql))){
     * if(!strpos($_COOKIE['clanky'], ','.$clanok_id['clanok_id'].',') ){
     * return true;
     * }
     * }
     * return false;
     * }else{
     * return true;
     * }
     */
    return false;
}

/**
 * *******************************************************************************************************************
 */
function getSubMenu($structure_id, $tree)
{

    // $tree = transformTreeArray(getTreeArray());
    global $path;
    // vypisem si kazdy zaznam, v kazdom zazneme vypisem kazdy uzol ako blok, ale nazov len u posledneho
    $menu .= '
	<div class="tree">';
    foreach ($tree as $key => $value) {

        if (nachadzaSaVoVetvi($structure_id, $value, $key, $tree)) {

            $menu .= '<div class="tree_row">';
            $treeItemItem = explode('/', $key);
            for ($i = 3; $i < (count($treeItemItem) - 1); $i++) {
                // ale vypisem len 2hu a nizsiu uroven
                if (count($treeItemItem) > 4) {
                    if ($i == (count($treeItemItem) - 2)) {
                        $menu .= '<div class="tree_item">
						          <a href="' . $path . 'sekcia/' . normalizeClanokName(mb_substr($treeItemItem[$i], 1, -1)) . '" ' . ($structure_id == $value ? 'class="expanded"' : '') . '>
						           ' . $treeItemItem[$i] . '
						          </a>
						         </div>';
                    } else {
                        $menu .= '<div class="tree_item_empty">&nbsp;</div>';
                    }
                }
            }
            $menu .= '<div class="cleaner"></div>';
            $menu .= '</div>';
            $menu .= '<div class="cleaner"></div>';
        }
    }
    $menu .= '
	</div>
	<br />';
    return $menu;
}

function getSubMenu2($structure_id, $tree)
{

    // $tree = transformTreeArray(getTreeArray());
    global $path;
    // vypisem si kazdy zaznam, v kazdom zazneme vypisem kazdy uzol ako blok, ale nazov len u posledneho
    foreach ($tree as $key => $value) {

        if (nachadzaSaVoVetvi($structure_id, $value, $key, $tree)) {

            $treeItemItem = explode('/', $key);
            for ($i = 3; $i < (count($treeItemItem) - 1); $i++) {
                // ale vypisem len 2hu a nizsiu uroven
                if (count($treeItemItem) > 4) {
                    if ($i == (count($treeItemItem) - 2)) {
                        $menu .= ' <a href="' . $path . 'sekcia/' . normalizeClanokName(mb_substr($treeItemItem[$i], 1, -1)) . '" ' . ($structure_id == $value ? 'class="expanded"' : '') . '>
						           ■ ' . $treeItemItem[$i] . '
						          </a><br />';
                    } else {
                    }
                }
            }
        }
    }
    return $menu;
}

/**
 * *******************************************************************************************************************
 */
function nachadzaSaVoVetvi($structure_id, $value, $key, $tree)
{
    $nazovPrvejUrovne = explode('/', $key);
    $nazovPrvejUrovne = mb_substr($nazovPrvejUrovne[2], 1, -1);
    foreach ($tree as $key => $value) {
        if ($structure_id == $value) {
            $nazovPrvejUrovneHladaneho = explode('/', $key);
            $nazovPrvejUrovneHladaneho = mb_substr($nazovPrvejUrovneHladaneho[2], 1, -1);
            if ($nazovPrvejUrovneHladaneho == $nazovPrvejUrovne) {
                return true;
            }
        }
    }
    return false;
}

/**
 * *******************************************************************************************************************
 */
function getFirstParentId($structure_id, $tree)
{
    foreach ($tree as $key => $value) {
        if ($structure_id == $value) {
            $firstParent = explode(' / ', $key);
            $firstParent = $firstParent[2];
            return getStructureIdFromNameSk($firstParent);
        }
    }
}

/**
 * *******************************************************************************************************************
 */
function getTreeRowByStructureId($structure_id, $tree)
{
    $path = array();
    $structure_id_old = $structure_id;
    while ($parent = getParentById($structure_id)) {
        $path[] = getStructureNameFromId($parent);
        $structure_id = $parent;
    }
    $path = array_reverse($path);
    $path[] = getStructureNameFromId($structure_id_old);
    return $path;
}

/**
 * *******************************************************************************************************************
 */
function getStructureStoriesNrBySectionId($structureId)
{
    $tmp = sqlGetRow(('SELECT count(*) AS pocet FROM clanok WHERE structure_id = "' . $structureId . '" '));
    return $tmp['pocet'];
}

function isDetail()
{
    if (isset($_REQUEST['id'])) {
        return true;
    } else {
        return false;
    }
}

/**
 * *******************************************************************************************************************
 */
function clankySuvisiace($id)
{
    // $clankySuvisiace = getTableRow('clanok_suvisiace', 'clanok_id', $id);
    $clanokSuvisiace = psw_mysql_query('SELECT * FROM clanok_suvisiace s LEFT JOIN clanok c ON s.clanok_id_suvisiace = c.clanok_id WHERE s.clanok_id = ' . $id . ' ORDER BY c.datetime DESC');
    // foreach($clankySuvisiace as $clankySuvisiaceItem){

    while ($clankySuvisiaceItem = $clanokSuvisiace->fetch_assoc()) {

        echo '<b>' . getClanokNameFromId($clankySuvisiaceItem['clanok_id_suvisiace']) . '</b> (' . substr(getDatetimeForClanok($clankySuvisiaceItem['clanok_id']), 0, -3) . ') <input type="button" onclick="doSuvisiaciClanok(\'' . $id . '\', \'0\', \'clanok_suvisiace_delete\', \'' . $clankySuvisiaceItem['clanok_suvisiace_id'] . '\');" value="X" class="button" />
		<br />';
    }
}

/**
 * *******************************************************************************************************************
 */
function clankyZdroj($id)
{
    $clankyZdroj = getTableRow('clanok_zdroj', 'clanok_id', $id);
    foreach ($clankyZdroj as $clankyZdrojItem) {
        echo '' . $clankyZdrojItem['zdroj'] . ' <input type="button" onclick="doZdrojClanok(\'' . $id . '\', \'' . $clankyZdrojItem['zdroj'] . '\', \'clanok_zdroj_delete\');" value="X" class="button" />
		<br />';
    }
}

/**
 * *******************************************************************************************************************
 */
function getDatetimeForClanok($id)
{
    $sql = 'SELECT datetime FROM clanok WHERE clanok_id = "' . $id . '"';
    $return = sqlGetRow(($sql));
    return $return['datetime'];
}

/**
 * *******************************************************************************************************************
 */
function homeBannerInsertValue($id)
{
    $sql = 'UPDATE clanok SET banner = 1 WHERE clanok_id = ' . $id . '';
    psw_mysql_query($sql);
}

/**
 * *******************************************************************************************************************
 */
function echoClanokZoznam($clanok)
{

    // global $path;
    $path = '../';

    // if( file_exists('../clanky/avatar_1_'.$clanok['clanok_id'].'.jpg')){
    $clanok_avatar = '../clanky/avatar_1_' . $clanok['clanok_id'] . '.jpg';
    // }else{
    // $clanok_avatar = '../clanky/avatar_1_blank.png';
    // }

    $clanokKategoriaFarba;
    $sekcia = getSectionByClanokId($clanok['clanok_id']);
    if (substr($sekcia, 0, 4) == 'bike') {
        $clanokKategoriaFarba = 'short_story_table_bike';
    } elseif (substr($sekcia, 0, 4) == 'life') {
        $clanokKategoriaFarba = 'short_story_table_lifestyle';
    } elseif (substr($sekcia, 0, 5) == 'music') {
        $clanokKategoriaFarba = 'short_story_table_music';
    } elseif (substr($sekcia, 0, 5) == 'board') {
        $clanokKategoriaFarba = 'short_story_table_board';
    } elseif (substr($sekcia, 0, 3) == 'dnb') {
        $clanokKategoriaFarba = 'short_story_table_dnb';
    } elseif (substr($sekcia, 0, 8) == 'graffiti') {
        $clanokKategoriaFarba = 'short_story_table_graffiti';
    } elseif (substr($sekcia, 0, 6) == 'reggae') {
        $clanokKategoriaFarba = 'short_story_table_reggae';
    } elseif (substr($sekcia, 0, 8) == 'redakcia') {
        $clanokKategoriaFarba = 'short_story_table_redakcia';
    } else {
        $clanokKategoriaFarba = 'short_story_table_hidden';
    }

    // <span class="flag '.($clanok['banner']=='1'? "flag-star":"").'"><span>flag</span></span>
    //
    // '.($clanok['banner']=='1'? '<span class="flag"><span>flag</span></span>':'').'
    $secName = getSectionNameById($clanok['structure_id']);
    $podrtzPos = strpos($secName, '_');
    echo '
	        <div class="short-story-table-container">
			 <a style="background-image: url(' . $clanok_avatar . ') !important;" class="short_story_table ' . ($clanokKategoriaFarba) . '" href="' . $path . 'clanok/' . $clanok['clanok_id'] . '-' . normalizeClanokName($clanok['nazov_' . $_SESSION['selectedLang']]) . '" >
			  <span class="lupa"><span>lupa</span></span>
        <span class="date">' . date('j.n.Y', strtotime($clanok['datetime'])) . '</span>		  
			  <span class="section">' . strtoupper(substr($secName, 0, 1)) . (substr($secName, 1, $podrtzPos - 1)) . ' / ' . getSectionFullNameById($clanok['structure_id']) . '</span>
			  <span class="cleaner"><span>cleaner</span></span>
		      <span class="h3">' . ($clanok['nazov_sk']) . '</span>
			 </a>		
			 <div class="short_story_table_shadow"></div>
			</div> 
			';
}

/**
 * *******************************************************************************************************************
 */
function echoClanokDisabler($clanok)
{
    if ($_SERVER['REMOTE_ADDR'] == '80.94.55.109') {
        echo 'Clanok is ' . ($clanok['show'] == '1' ? 'VISIBLE' : 'HIDDEN');
        echo '<br /><span style="background-color: #ccc; cursor: pointer;" id="disabler">DISABLE clanok ' . $clanok['clanok_id'] . '</span><br />';

        echo '<br /><span style="background-color: #ccc; cursor: pointer;" id="enabler">ENABLE clanok ' . $clanok['clanok_id'] . '</span><br />';

        echo '<script><!--
			$(window).load(function(){
				$("#disabler").click(function() {					  
						var attr = "?a=clanokDisable&clanok_id=' . $clanok['clanok_id'] . '";
						$.ajax({
							url: (attr),
							context: document.body
						}).done(function(data) {							
							console.log("hidden"); 
						});
					 		
				});		
				$("#enabler").click(function() {					  
						var attr = "?a=clanokEnable&clanok_id=' . $clanok['clanok_id'] . '";
						$.ajax({
							url: (attr),
							context: document.body
						}).done(function(data) {							
							console.log("hidden"); 
						});
					 		
				});					
			});
			 
			
		--></script>';
    }

}

function clanokDisable($clanok_id)
{
    psw_mysql_query($sql = 'UPDATE clanok SET `show` = "0" WHERE clanok_id = "' . $clanok_id . '"');

}

function clanokEnable($clanok_id)
{
    psw_mysql_query($sql = 'UPDATE clanok SET `show` = "1" WHERE clanok_id = "' . $clanok_id . '"');
}

function echoClanokDetail($clanok)
{
    if (!$clanok)
        return;
    global $path;

    $sekcia = getSectionByClanokId($clanok['clanok_id']);
    // if( ! file_exists('../clanky/avatar_2_'.$clanok['clanok_id'].'.jpg')){
    // $big_avatar_src = $path.'clanky/avatar_2_blank.png';
    // }else{
    $big_avatar_src = $path . 'clanky/avatar_2_' . $clanok['clanok_id'] . '.jpg';
    // }

    $big_text = normalizeText(str_ireplace("&quot;", '"', $clanok['big_text_' . $_SESSION['selectedLang']]));

    echo '
			  <div class="story_head">
			   <img alt="' . $clanok['nazov_' . $_SESSION['selectedLang']] . '" src="' . $big_avatar_src . '" />
			   <div class="cleaner"></div>
			   <div class="info">
			   	<span class="left">' . date('j.n.Y', strtotime($clanok['datetime'])) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $clanok['user'] . '</span>
		       	<span class="right">Sekcia: <b>' . getSectionDirByName($sekcia) . '</b></span> 
       		   </div>  
			  </div>
			  <div class="big_story_body">
			  	<h1>' . $clanok['nazov_' . $_SESSION['selectedLang']] . '</h1>			  	
				<div class="cleaner"></div>';
    echoClanokDisabler($clanok);

    if ($clanok['koncept'] == '0') {
        //echo '<div class="fb-like" data-send="false" data-width="600" data-show-faces="false"></div><br />';
        // echo '<div class="fb-like" data-send="true" data-width="660" data-show-faces="true"></div><br />';
    }
    echo '<br />' . 'Pocet zobrazeni: ' . $clanok['counter'] . '<br /><br />';
    printBanner(2);
    /*
    <center>	<br /><script type="text/javascript"><!--
    google_ad_client = "ca-pub-2222479605297114";
    
    google_ad_slot = "8224143388";
    google_ad_width = 468;
    google_ad_height = 60;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script><br /><br /></center>
    */
    echo '
			 
			  	<div class="cleaner"></div>
		  	    ' . $big_text . '<div class="cleaner"></div><br />';

    $clankyZdroj = getTableRow('clanok_zdroj', 'clanok_id', $clanok['clanok_id']);
    $clanokSuvisiace = psw_mysql_query($rrr = 'SELECT * FROM clanok_suvisiace s LEFT JOIN clanok c ON s.clanok_id_suvisiace = c.clanok_id WHERE s.clanok_id = ' . $clanok['clanok_id'] . ' ORDER BY c.datetime DESC');

    if ((count($clankyZdroj) > 0) || (mysqli_num_rows($clanokSuvisiace) > 0)) {
        echo '<div id="zdroj"><ul class="head">';
        if (mysqli_num_rows($clanokSuvisiace) > 0) {
            echo '<li class="suvisiace">Súvisiace články</li>';
        }
        if (count($clankyZdroj) > 0) {
            echo '<li class="zdroj">Zdrojové weby</li>';
        }
        echo '</ul>';

        if (mysqli_num_rows($clanokSuvisiace) > 0) {
            echo '<ul class="suvisiace">';

            while ($clankySuvisiaceItem = $clanokSuvisiace->fetch_assoc()) {

                echo '<li>
			  <a href="' . $http_path . '' . $clankySuvisiaceItem['clanok_id_suvisiace'] . '-' . normalizeClanokName(getClanokNameFromId($clankySuvisiaceItem['clanok_id_suvisiace'])) . '" target="_blank">' . getClanokNameFromId($clankySuvisiaceItem['clanok_id_suvisiace']) . ' (' . date('j.n.Y', strtotime(getDatetimeForClanok($clankySuvisiaceItem['clanok_id_suvisiace']))) . ')</a>
			 </li>';
            }
            echo '</ul>';
        }

        if (count($clankyZdroj) > 0) {
            echo '<ul class="zdroj">';
            foreach ($clankyZdroj as $clankyZdrojItem) {
                echo '<li>' . $clankyZdrojItem['zdroj'] . '</li>';
            }
            echo '</ul>';
        }

        echo '</div>';
    }
    echo '</div>';

    echo '<div class="cleaner"></div>
			 ';
}

/**
 * *******************************************************************************************************************
 */
function normalizeText($text)
{
    $text = mb_str_replace('& ', '&amp; ', $text);
    $text = mb_str_replace("\r\n", '<br />', $text);
    return $text;
}

/**
 * *******************************************************************************************************************
 */
function printFlyer()
{

    // iba ak nieje setnuta cookie
    $sql = 'SELECT * FROM flyer WHERE 1 LIMIT 1';
    $data = sqlGetRow(($sql));

    if (isset($data['alt'])) {
        echo '
		<div id="flyer-container">
		 <div id="flyer">
			<h1>' . $data['alt'] . '</h1><br />
			<a href="' . $data['link'] . '" ><img src="' . getPath() . 'image/6/' . $data['flyer_id'] . '.jpg" alt="' . $data['alt'] . '" /></a>
			<br />
			<a id="forward" href="http://www.sewer.sk/">Pokračovať na<br /><img src="' . getPath() . 'img/logo.png" alt="logo" /></a>
		 </div>
		</div>
	  </body>
	</html>';

        return true;
    }

    return false;
}

/**
 * *******************************************************************************************************************
 */
function printBanner($type)
{
    global $path, $bannerSize;

    $sql[1] = 'SELECT * FROM banner WHERE 1=1';
    $sql[2] = 'SELECT * FROM banner WHERE 1=1';
    $sql[3] = 'SELECT * FROM banner WHERE 1=1';

    $imageType[1] = 7;
    $imageType[2] = 9;
    $imageType[3] = 10;

    // while($data = sqlGetRow(($sql))){
    $data = sqlGetRow(($sql[$type]));
    if (isset($data['alt']) && strtolower($data['alt']) == 'swf') {
        echo '
  		<div class="flash-banner-container' . $type . '"><div class="flash-banner" id="flash-banner' . $type . '"></div></div>
  		<script type="text/javascript">
  		/*<![CDATA[*/
  		 	var flashvars' . $type . ' = {};
          	var params' . $type . ' = {wmode:"transparent"};
          	var attributes' . $type . ' = {};
  			swfobject.embedSWF("' . $path . 'image/' . $imageType[$type] . '/' . $data['banner_id'] . '.swf", "flash-banner' . $type . '", "' . $bannerSize['width'][$type] . '", "' . $bannerSize['height'][$type] . '", "8", "' . $path . 'resources/expressInstall.swf", flashvars' . $type . ', params' . $type . ', attributes' . $type . ' );
  		
  		/*]]>*/</script>';
    }
    if (isset($data['alt']) && strtolower($data['alt']) == 'jpg') {
        echo '<div id="flash-banner-container' . $type . '"><a href="' . $data['link'] . '" rel="external"><img src="' . $path . 'image/' . $imageType[$type] . '/' . $data['banner_id'] . '.jpg" alt="banner' . $type . '" style="width: ' . $bannerSize['width'][$type] . '; height:' . $bannerSize['height'][$type] . '" width="' . $bannerSize['width'][$type] . '"/></a></div>';
    }
    // }
    // TODO rotating

    echo '
  
  ';
}

/**
 * *******************************************************************************************************************
 */
function sqlGetRow($sql)
{
    $result = psw_mysql_query($sql);
    //echo $sql;
    $row = $result->fetch_assoc();
    return $row;
}

function sqlGetRows($sql)
{
    $return = array();

    $result = psw_mysql_query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
    }
    return $return;
}

?>