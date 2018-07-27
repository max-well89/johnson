<form method="POST" enctype="multipart/form-data">
    <input type="file" name="member"/>
    <input type="submit" name="submit" value="отправить"/>
</form>

<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('lib/AbstractCsvReader.php');
require_once('lib/CsvFileREader.php');
require_once('lib/Database.class.php');

function getIdRegion($conn, $name)
{
    $id_region = false;
    $sql_sel_region = 'select id_region from t_region where lower(name) = lower(trim(:name)) limit 1';
    $sql_ins_region = 'insert into t_region(name) values(trim(:name)) returning id_region';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_region);
            $stmt->bindValue('name', $name);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_region = $row['id_region'];
            } else {
                $stmt = $conn->prepare($sql_ins_region);
                $stmt->bindValue('name', $name);

                if ($stmt->execute()) {
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $id_region = $row['id_region'];
                    }
                }
            }
        }

    } catch (exception $e) {
    }

    return $id_region;
}

function getIdCity($conn, $id_region, $name)
{
    $id_city = false;
    $sql_sel_city = 'select id_city from t_city where lower(name) = lower(trim(:name)) and id_region = :id_region limit 1';
    $sql_ins_city = 'insert into t_city(name, id_region) values(trim(:name), :id_region) returning id_city';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_city);
            $stmt->bindValue('name', $name);
            $stmt->bindValue('id_region', $id_region, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_city = $row['id_city'];
            } else {
                $stmt = $conn->prepare($sql_ins_city);
                $stmt->bindValue('name', $name);
                $stmt->bindValue('id_region', $id_region, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $id_city = $row['id_city'];
                    }
                }
            }
        }
    } catch (exception $e) {
    }

    return $id_city;
}

function getIdArea($conn, $id_city, $name)
{
    $id_area = false;
    $sql_sel_area = 'select id_area from t_area where lower(name) = lower(trim(:name)) and id_city = :id_city limit 1';
    $sql_ins_area = 'insert into t_area(name, id_city) values(trim(:name), :id_city) returning id_area';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_area);
            $stmt->bindValue('name', $name);
            $stmt->bindValue('id_city', $id_city, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_area = $row['id_area'];
            } else {
                $stmt = $conn->prepare($sql_ins_area);
                $stmt->bindValue('name', $name);
                $stmt->bindValue('id_city', $id_city, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $id_area = $row['id_area'];
                    }
                }
            }
        }
    } catch (exception $e) {
    }

    return $id_area;
}

function checkExistMember($conn, $id_region, $id_city, $id_area, $name, $surname)
{
    $id_member = false;
    $sql_sel_member = '
        select id_member 
        from t_member 
        where lower(name) = lower(trim(:name))
        and lower(surname) = lower(trim(:surname))
        and id_region = :id_region
        and id_city = :id_city
        and id_area = :id_area
        limit 1';

    try {
        $stmt = $conn->prepare($sql_sel_member);

        $stmt->bindValue('name', $name);
        $stmt->bindValue('surname', $surname);
        $stmt->bindValue('id_region', $id_region, PDO::PARAM_INT);
        $stmt->bindValue('id_city', $id_city, PDO::PARAM_INT);
        $stmt->bindValue('id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_member = $row['id_member'];
        }
    } catch (exception $e) {
    }

    return $id_member;
}

function generatePasswd()
{
    return rand(111111, 999999);
}

function insertMember($conn, $values)
{
    $id_member = false;
    $sql_ins_member = '
            insert into t_member(
                name,
                surname,
                id_region,
                id_city,
                id_area,
                login,
                passwd,
                id_role,
                id_status
            ) 
            values(
                trim(:name),
                trim(:surname),
                :id_region,
                :id_city,
                :id_area,
                :login,
                :passwd,
                2,
                1
            ) 
            returning id_member
        ';


    try {
        $stmt = $conn->prepare($sql_ins_member);
        $stmt->bindValue('name', $values['name']);
        $stmt->bindValue('surname', $values['surname']);
        $stmt->bindValue('id_region', $values['id_region'], PDO::PARAM_INT);
        $stmt->bindValue('id_city', $values['id_city'], PDO::PARAM_INT);
        $stmt->bindValue('id_area', $values['id_area'], PDO::PARAM_INT);
//            $stmt->bindValue('passwd', generatePasswd());
        $stmt->bindValue('login', $values['login']);
        $stmt->bindValue('passwd', $values['passwd']);

        if ($stmt->execute()) {
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_member = $row['id_member'];
            }
        }

    } catch (exception $e) {
        logToFile(date('d.m.Y H:i:s') . ', id_row: ' . @$values['id_row'] . ', error');
    }

    return $id_member;
}

function logToFile($str)
{
    $fd = fopen("log.log", 'a');
    fwrite($fd, $str . "\n");
    fclose($fd);
}

/*
function insertError($conn, $id_row, $reason){
    $sql_ins_error = 'insert into T_JOIN_REQUEST_UPLOAD_ERROR (id_row, reason) values(:id_row, :reason)';

    try {
        $stmt = $conn->prepare($sql_ins_error);
        $stmt->bindValue('id_row', $id_row);
        $stmt->bindValue('reason', $reason);
        $stmt->execute();
    }
    catch(exception $e){}
}*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database('pgsql:host=localhost;port=5432;dbname=postgres;', 'johnson', 'MyNameIsJohnson');
    $conn = $db->getConnection();

    if (isset($_FILES['member']['tmp_name']) && !empty($_FILES['member']['tmp_name'])) {
        $csvreader = new CsvFileReader($_FILES['member']['tmp_name'], ",", "\"");
        $header = $csvreader->getRow();


        $defColumns = array(
            'name' => array('name' => 'name'),
            'surname' => array('name' => 'surname'),
            'region' => array('name' => 'region'),
            'city' => array('name' => 'city'),
            'area' => array('name' => 'area'),
            'login' => array('name' => 'login'),
            'passwd' => array('name' => 'passwd')
        );

        $columns = $csvreader->getColumns($header, $defColumns);

        //var_dump($columns); exit;
        if (!empty($columns)) {
            $cnt = 0;
            $cnt_errors = 0;
            $cnt_success = 0;

            while ($row = $csvreader->getRow()) {
                $cnt++;

                $id_member = false;
                $name = false;
                $surname = false;
                $id_region = false;
                $id_city = false;
                $id_area = false;
                $login = false;
                $passwd = false;

                try {
                    foreach ($columns as $key => $val) {
                        if ($val == 'region') {
                            $$val = $row[$key];
                            $id_region = getIdRegion($conn, $row[$key]);
                        } elseif ($val == 'city') {
                            $$val = $row[$key];
                            $id_city = getIdCity($conn, $id_region, $row[$key]);
                        } elseif ($val == 'area') {
                            $$val = $row[$key];
                            $id_area = getIdArea($conn, $id_city, $row[$key]);
                        } else {
                            $$val = $row[$key];
                        }
                    }

                    if ($name && $surname) {
                        $values['name'] = $name;
                        $values['surname'] = $surname;

                        $values['id_region'] = $id_region;
                        $values['id_city'] = $id_city;
                        $values['id_area'] = $id_area;

                        $values['login'] = $login;
                        $values['passwd'] = $passwd;

                        $id_member = checkExistMember($conn, $id_region, $id_city, $id_area, $name, $surname);

                        if (empty($id_member)) {
                            insertMember($conn, $values);
                            $cnt_success++;
                        } else {

                        }
                    } else {
                        logToFile(date('d.m.Y H:i:s') . ', id_row: ' . @$values['id_row'] . ', error');
                        //insertError($conn, $id_row, '1');
                        //var_dump($id, ' reason 1');
                        $cnt_errors++;
                    }
                } catch (exception $e) {
                    logToFile(date('d.m.Y H:i:s') . ', id_row: ' . @$values['id_row'] . ', error');
                    //insertError($conn, $id_row, '0');
                    //var_dump($id, ' reason 0');
                    $cnt_errors++;
                }

            }
        }
    }

    $conn->commit();
    var_dump($cnt, $cnt_success, $cnt_errors);
    exit;
}
?>
