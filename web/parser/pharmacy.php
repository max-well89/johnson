<form method="POST" enctype="multipart/form-data">
    <input type="file" name="pharmacy"/>
    <div>
        <label for="database_en">en</label>
        <input id="database_en" type="radio" name="id_database" value="1" />
    </div>
    <div>
        <label for="database_ru">ru</label>
        <input id="database_ru" type="radio" checked name="id_database" value="2" />
    </div>
    <input type="submit" name="submit" value="отправить"/>
</form>

<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('lib/AbstractCsvReader.php');
require_once('lib/CsvFileREader.php');
require_once('lib/Database.class.php');


function deactivate_pharmacy($conn, $id_database, $ids_crm)
{
    // try {
    $sql_upd_pharmacy = 'update t_pharmacy set id_status = 0, dt_updated = now() bind_str';
    $bind_str = ' where id_database = :id_database and id_crm not in (str)';
    $str = '';

    if (is_array($ids_crm) && !empty($ids_crm)) {
        foreach ($ids_crm as $key => $id_crm) {
            $str .= isset($ids_crm[$key + 1]) ? ":id_crm_$key, " : ":id_crm_$key";
        }

        $bind_str = str_replace('str', $str, $bind_str);
        $sql_upd_pharmacy = str_replace('bind_str', $bind_str, $sql_upd_pharmacy);
    }

    $stmt = $conn->prepare($sql_upd_pharmacy);
    $stmt->bindValue('id_database', $id_database);

    foreach ($ids_crm as $key => $id_crm) {
        $stmt->bindValue("id_crm_$key", $id_crm);
    }

    $stmt->execute();
    //      }catch(exception $e){}
}


function getIdCategory($conn, $name, $id_database = null)
{
    $id_category = false;
    $sql_sel_category = 'select id_category from t_category where id_database = :id_database and lower(name) = lower(trim(:name)) limit 1';
    $sql_ins_category = 'insert into t_category(id_database, name) values(:id_database, trim(:name)) returning id_category';

    try {
        $stmt = $conn->prepare($sql_sel_category);
        $stmt->bindValue('id_database', $id_database);
        $stmt->bindValue('name', $name);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_category = $row['id_category'];
        } else {
            $stmt = $conn->prepare($sql_ins_category);
            $stmt->bindValue('id_database', $id_database);
            $stmt->bindValue('name', $name);

            if ($stmt->execute()) {
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_category = $row['id_category'];
                }
            }
        }
    } catch (exception $e) {
    }

    return $id_category;
}

function getIdRegion($conn, $name, $id_database = null)
{
    $id_region = false;
    $sql_sel_region = 'select id_region from t_region where id_database = :id_database and lower(name) = lower(trim(:name)) limit 1';
    $sql_ins_region = 'insert into t_region(id_database, name) values(:id_database, trim(:name)) returning id_region';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_region);
            $stmt->bindValue('id_database', $id_database);
            $stmt->bindValue('name', $name);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_region = $row['id_region'];
            } else {
                $stmt = $conn->prepare($sql_ins_region);
                $stmt->bindValue('id_database', $id_database);
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

function getIdCity($conn, $id_region, $name, $id_database = null)
{
    $id_city = false;
    $sql_sel_city = 'select id_city from t_city where id_database = :id_database and lower(name) = lower(trim(:name)) and id_region = :id_region limit 1';
    $sql_ins_city = 'insert into t_city(id_database, name, id_region) values(:id_database, trim(:name), :id_region) returning id_city';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_city);
            $stmt->bindValue('id_database', $id_database);
            $stmt->bindValue('name', $name);
            $stmt->bindValue('id_region', $id_region, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_city = $row['id_city'];
            } else {
                $stmt = $conn->prepare($sql_ins_city);
                $stmt->bindValue('id_database', $id_database);
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

function getIdArea($conn, $id_city, $name, $id_database = null)
{
    $id_area = false;
    $sql_sel_area = 'select id_area from t_area where id_database = :id_database and lower(name) = lower(trim(:name)) and id_city = :id_city limit 1';
    $sql_ins_area = 'insert into t_area(id_database, name, id_city) values(:id_database, trim(:name), :id_city) returning id_area';

    try {
        if (!empty($name)) {
            $stmt = $conn->prepare($sql_sel_area);
            $stmt->bindValue('id_database', $id_database);
            $stmt->bindValue('name', $name);
            $stmt->bindValue('id_city', $id_city, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_area = $row['id_area'];
            } else {
                $stmt = $conn->prepare($sql_ins_area);
                $stmt->bindValue('id_database', $id_database);
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

function getIdMember($conn, $id_region, $id_city, $id_area, $fio, $id_database = null)
{
    $id_member = false;
    $sql_sel_member = '
        select id_member 
        from t_member 
        where id_database = :id_database 
        and lower(trim(trim(name) || \' \' ||trim(surname))) = lower(trim(:fio)) 
--        and id_region = :id_region
--        and id_city = :id_city
--        and id_area = :id_area
        limit 1';

    try {
        $stmt = $conn->prepare($sql_sel_member);
        $stmt->bindValue('id_database', $id_database);
        $stmt->bindValue('fio', $fio);
//            $stmt->bindValue('id_region', $id_region, PDO::PARAM_INT);
//            $stmt->bindValue('id_city', $id_city, PDO::PARAM_INT);
//            $stmt->bindValue('id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_member = $row['id_member'];
        } else {
            $rnd = generatePasswd();
            $values_member['id_database'] = $id_database;
            $values_member['name'] = $fio;
            $values_member['surname'] = '';
            $values_member['id_region'] = $id_region;
            $values_member['id_city'] = $id_city;
            $values_member['id_area'] = $id_area;
            $values_member['login'] = 'user' . $rnd;
            $values_member['passwd'] = $rnd;

            if ($fio)
                $id_member = insertMember($conn, $values_member);
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
                id_database,
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
                :id_database,
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
        $stmt->bindValue('id_database', $values['id_database']);
        $stmt->bindValue('name', $values['name']);
        $stmt->bindValue('surname', $values['surname']);
        $stmt->bindValue('id_region', $values['id_region'], PDO::PARAM_INT);
        $stmt->bindValue('id_city', $values['id_city'], PDO::PARAM_INT);
        $stmt->bindValue('id_area', $values['id_area'], PDO::PARAM_INT);
        $stmt->bindValue('login', $values['login']);
        $stmt->bindValue('passwd', (!empty($values['passwd']) ? $values['passwd'] : generatePasswd()));

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


function checkExistPharmacy($conn, $values)
{
    $id_pharmacy = false;
    $sql_sel_pharmacy = '
            select t0.id_pharmacy as id_pharmacy
            from (
                select id_pharmacy 
                from t_pharmacy 
                where id_database = :id_database 
                and id_crm = trim(:id_crm)
                order by dt desc
            ) t0
            limit 1
        ';
    try {
        if (isset($values['id_crm']) && !empty($values['id_crm'])) {
            $stmt = $conn->prepare($sql_sel_pharmacy);
            $stmt->bindValue('id_database', $values['id_database']);
            $stmt->bindValue('id_crm', $values['id_crm']);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_pharmacy = $row['id_pharmacy'];
            }
        }
    } catch (exception $e) {
    }

    return $id_pharmacy;
}

function updatePharmacy($conn, $id_pharmacy, $values)
{
    $sql_upd_pharmacy = '
            update t_pharmacy set
                id_crm = trim(:id_crm),
                name = trim(:name), 
                address = trim(:address),
                id_category = :id_category,
                id_region = :id_region,
                id_city = :id_city,
                id_area = :id_area,
                id_member = :id_member,
                dt_updated = now(),
                id_status = 1
            where id_database = :id_database
            and id_pharmacy = :id_pharmacy
        ';

    try {
        $stmt = $conn->prepare($sql_upd_pharmacy);
        $stmt->bindValue('id_database', $values['id_database']);
        $stmt->bindValue('id_crm', $values['id_crm']);
        $stmt->bindValue('name', $values['name']);
        $stmt->bindValue('address', $values['address']);
        $stmt->bindValue('id_category', $values['id_category'], PDO::PARAM_INT);
        $stmt->bindValue('id_region', $values['id_region'], PDO::PARAM_INT);
        $stmt->bindValue('id_city', $values['id_city'], PDO::PARAM_INT);
        $stmt->bindValue('id_area', $values['id_area'], PDO::PARAM_INT);
        $stmt->bindValue('id_member', $values['id_member'], PDO::PARAM_INT);
        $stmt->bindValue('id_pharmacy', $id_pharmacy);

        if ($stmt->execute()) {
            return true;
        }
    } catch (exception $e) {
    }

    return false;
}

function insertPharmacy($conn, $values)
{
    $id_pharmacy = false;
    $sql_ins_pharmacy = '
            insert into t_pharmacy(
                id_database, 
                id_crm, 
                name, 
                address,
                id_category,
                id_region,
                id_city,
                id_area,
                id_member,
                id_status
            ) 
            values(
                :id_database,
                trim(:id_crm), 
                trim(:name), 
                trim(:address),
                :id_category,
                :id_region,
                :id_city,
                :id_area,
                :id_member,
                1
            ) 
            returning id_pharmacy
        ';

    try {
        $stmt = $conn->prepare($sql_ins_pharmacy);
        $stmt->bindValue('id_database', $values['id_database']);
        $stmt->bindValue('id_crm', $values['id_crm']);
        $stmt->bindValue('name', $values['name']);
        $stmt->bindValue('address', $values['address']);
        $stmt->bindValue('id_category', $values['id_category'], PDO::PARAM_INT);
        $stmt->bindValue('id_region', $values['id_region'], PDO::PARAM_INT);
        $stmt->bindValue('id_city', $values['id_city'], PDO::PARAM_INT);
        $stmt->bindValue('id_area', $values['id_area'], PDO::PARAM_INT);
        $stmt->bindValue('id_member', $values['id_member'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_pharmacy = $row['id_pharmacy'];
            }
        }
    } catch (exception $e) {
    }

    return $id_pharmacy;
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
        //var_dump($stmt->errorInfo()); exit;
    }
    catch(exception $e){}
}*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database('pgsql:host=localhost;port=5432;dbname=postgres;', 'johnson', 'MyNameIsJohnson');
    $conn = $db->getConnection();

    if (isset($_FILES['pharmacy']['tmp_name']) && !empty($_FILES['pharmacy']['tmp_name']) && !empty($_POST['id_database'])) {
        $id_database = $_POST['id_database'] ? $_POST['id_database'] : 2;

        $csvreader = new CsvFileReader($_FILES['pharmacy']['tmp_name'], ",", "\"");
        $header = $csvreader->getRow();

        $defColumns = array(
            'id_crm' => array('name' => 'id_crm'),
            'name' => array('name' => 'name'),
            'address' => array('name' => 'address'),
            'category' => array('name' => 'category'),
            'region' => array('name' => 'region'),
            'city' => array('name' => 'city'),
            'area' => array('name' => 'area'),
            'member' => array('name' => 'member'),
            'id_row' => array('name' => 'id_row')
        );

        $columns = $csvreader->getColumns($header, $defColumns);

        //var_dump($columns); exit;
        if (!empty($columns)) {
            $cnt = 0;
            $cnt_errors = 0;
            $cnt_ins = 0;
            $cnt_upd = 0;
            $ids_crm = array();

            while ($row = $csvreader->getRow()) {
                $cnt++;

                $id_crm = false;
                $id_pharmacy = false;
                $id_category = false;
                $name = false;
                $id_region = false;
                $id_city = false;
                $id_area = false;
                $id_member = false;
                $id_row = false;

                try {
                    foreach ($columns as $key => $val) {
                        if ($val == 'category') {
                            $$val = $row[$key];
                            $id_category = getIdCategory($conn, $row[$key], $id_database);
                        } elseif ($val == 'region') {
                            $$val = $row[$key];
                            $id_region = getIdRegion($conn, $row[$key], $id_database);
                        } elseif ($val == 'city') {
                            $$val = $row[$key];
                            $id_city = getIdCity($conn, $id_region, $row[$key], $id_database);
                        } elseif ($val == 'area') {
                            $$val = $row[$key];
                            $id_area = getIdArea($conn, $id_city, $row[$key], $id_database);
                        } elseif ($val == 'member') {
                            $$val = $row[$key];
                            $id_member = getIdMember($conn, $id_region, $id_city, $id_area, $row[$key], $id_database);
                        } else {
                            $$val = $row[$key];
                        }
                    }

                    if ($name && $id_crm && $id_member) {
                        $ids_crm[] = $id_crm;
                        $values['id_database'] = $id_database;

                        $values['id_crm'] = $id_crm;
                        $values['name'] = $name;
                        $values['address'] = $address;

                        $values['id_category'] = $id_category;
                        $values['id_region'] = $id_region;
                        $values['id_city'] = $id_city;
                        $values['id_area'] = $id_area;
                        $values['id_member'] = $id_member;

                        //var_dump($values); exit;
                        $id_pharmacy = checkExistPharmacy($conn, $values);

                        if (empty($id_pharmacy)) {
                            insertPharmacy($conn, $values);
                            $cnt_ins++;
                        } else {
                            updatePharmacy($conn, $id_pharmacy, $values);
                            $cnt_upd++;
                        }
                    } else {
                        logToFile(date('d.m.Y H:i:s') . ', id_row: ' . @$id_row . ', error 1');
                        //insertError($conn, $id_row, '1');
                        //var_dump($id, ' reason 1');
                        $cnt_errors++;
                    }


                } catch (exception $e) {
                    logToFile(date('d.m.Y H:i:s') . ', id_row: ' . @$id_row . ', error 2');
                    //insertError($conn, $id_row, '0');
                    //var_dump($id, ' reason 0');
                    $cnt_errors++;
                }
            }

            deactivate_pharmacy($conn, $id_database, $ids_crm);
        }
    }

    $conn->commit();
    var_dump($cnt, $cnt_ins, $cnt_upd, $cnt_errors);
    exit;
}
?>