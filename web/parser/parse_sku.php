<form method="POST" enctype="multipart/form-data">
    <input type="file" name="sku"/>
    <input type="submit" name="submit" value="отправить"/>
</form>

<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('lib/AbstractCsvReader.php');
require_once('lib/CsvFileREader.php');
require_once('lib/Database.class.php');

function getIdSkuType($conn, $name)
{
    $id_sku_type = null;
    $sql_sel_type = 'select id_sku_type from t_sku_type where lower(name) = lower(trim(:name)) limit 1';
    $sql_ins_type = 'insert into t_sku_type(name) values(trim(:name)) returning id_sku_type';

    try {
        $stmt = $conn->prepare($sql_sel_type);
        $stmt->bindValue('name', $name);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_sku_type = $row['id_sku_type'];
        } else {
            $stmt = $conn->prepare($sql_ins_type);
            $stmt->bindValue('name', $name);

            if ($stmt->execute()) {
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_sku_type = $row['id_sku_type'];
                }
            }
        }
    } catch (exception $e) {
    }

    return $id_sku_type;
}

function getIdSkuProducer($conn, $name)
{
    $id_sku_producer = false;
    $sql_sel_sku_producer = 'select id_sku_producer from t_sku_producer where lower(name) = lower(trim(:name)) limit 1';
    $sql_ins_sku_producer = 'insert into t_sku_producer(name) values(trim(:name)) returning id_sku_producer';

    try {
        $stmt = $conn->prepare($sql_sel_sku_producer);
        $stmt->bindValue('name', $name);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_sku_producer = $row['id_sku_producer'];
        } else {
            $stmt = $conn->prepare($sql_ins_sku_producer);
            $stmt->bindValue('name', $name);

            if ($stmt->execute()) {
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_sku_producer = $row['id_sku_producer'];
                }
            }
        }
    } catch (exception $e) {
    }

    return $id_sku_producer;
}

function checkExistSKU($conn, $id_sku_type, $id_sku_producer, $name)
{
    $id_sku = false;
    $sql_sel_sku = '
        select id_sku 
        from t_sku 
        where lower(name) = lower(trim(:name))
        and id_sku_type = :id_sku_type
        and id_sku_producer = :id_sku_producer
        limit 1';

    try {
        $stmt = $conn->prepare($sql_sel_sku);

        $stmt->bindValue('name', $name);
        $stmt->bindValue('id_sku_type', $id_sku_type, PDO::PARAM_INT);
        $stmt->bindValue('id_sku_producer', $id_sku_producer, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_sku = $row['id_sku'];
        }
    } catch (exception $e) {
    }

    return $id_sku;
}

function insertSKU($conn, $values)
{
    $id_sku = false;
    $sql_ins_sku = '
        insert into t_sku(
            id_sku_type,
            id_sku_producer,
            name,
            id_status
        ) 
        values(
           :id_sku_type,
           :id_sku_producer,
           trim(:name),
           1
        ) 
        returning id_sku';

    try {
        $stmt = $conn->prepare($sql_ins_sku);
        $stmt->bindValue('id_sku_type', $values['id_sku_type']);
        $stmt->bindValue('id_sku_producer', $values['id_sku_producer']);
        $stmt->bindValue('name', $values['name']);

        if ($stmt->execute()) {
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_sku = $row['id_sku'];
            }
        }
    } catch (exception $e) {
    }

    return $id_sku;
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

    if (isset($_FILES['sku']['tmp_name']) && !empty($_FILES['sku']['tmp_name'])) {
        $csvreader = new CsvFileReader($_FILES['sku']['tmp_name'], ",", "\"");
        $header = $csvreader->getRow();

        $defColumns = array(
            'sku_type' => array('name' => 'sku_type'),
            'sku_producer' => array('name' => 'sku_producer'),
            'name' => array('name' => 'name')
        );

        $columns = $csvreader->getColumns($header, $defColumns);

        if (!empty($columns)) {
            $cnt = 0;
            $cnt_errors = 0;
            $cnt_success = 0;

            while ($row = $csvreader->getRow()) {
                $cnt++;
                $id_sku = false;
                $name = false;
                $id_sku_type = false;
                $id_sku_producer = false;
                $id_sku = false;

                try {
                    foreach ($columns as $key => $val) {
                        if ($val == 'sku_type') {
                            $$val = $row[$key];
                            $id_sku_type = getIdSkuType($conn, $row[$key]);
                        } elseif ($val == 'sku_producer') {
                            $$val = $row[$key];
                            $id_sku_producer = getIdSkuProducer($conn, $row[$key]);
                        } else {
                            $$val = $row[$key];
                        }
                    }

                    if ($name) {
                        $values['id_sku_type'] = $id_sku_type;
                        $values['id_sku_producer'] = $id_sku_producer;
                        $values['name'] = $name;

                        $id_sku = checkExistSKU($conn, $id_sku_type, $id_sku_producer, $name);

                        if (empty($id_sku)) {
                            insertSKU($conn, $values);
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
