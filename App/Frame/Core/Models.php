<?php

/**
 * @param $req['table'] // table BDD
 * @param $req['style'] // style PDO::FETCH_ASSOC BDD
 * @return array
 */
function Find($req = [])
{
    global $db, $request;
    $req['table'] = (isset($req['table'])) ? $req['table'] : strtolower($request['request']['controller']);
    $req['style'] = (isset($req['style'])) ? $req['style'] : PDO::FETCH_ASSOC;
    $req['database'] = (isset($req['database'])) ? $req['database'] : denv('DEFAULT_BASE');
    $cond = [];
    $d = [];
    $sql = 'SELECT ';
    if (isset($req['fields'])) {
        if (is_array($req['fields'])) {
            $sql .= implode(', ', $req['fields']);
        } else {
            $sql .= $req['fields'];
        }
    } else {
        $sql .= '*';
    }
    $sql .= ' FROM ' . $req['table'] . ' ';

    if (isset($req['join'])) {
        foreach ($req['join'] as $k => $v) {
            $sql .= 'LEFT JOIN ' . $k . ' ON ' . $v . ' ';
        }
    }
    if (isset($req['where'])) {
        $sql .= "WHERE ";

        if (!is_array($req['where'])) {
            $sql .= $req['where'];
        } else {
            foreach ($req['where'] as $k => $v) {
                if (!is_numeric($v)) {
                    $v = addslashes($v);
                }
                $k2 = str_replace($req['table'] . '.', '', $k);
                if ($k !== "solo") {
                    $cond[]      = " $k=:$k2";
                    $d[":$k2"]   = $v;
                } else {
                    $cond[] = $v;
                }
            }
            $sql .= implode(' AND ', $cond);
        }
    }
    if (isset($req['order'])) {
        $sql .= ' ORDER BY ' . $req['order'];
    }
    if (isset($req['limit'])) {
        $sql .= ' LIMIT ' . $req['limit'];
    }
    $pre = $db[$req['database']]->prepare($sql);
    $pre->execute($d);
    return $pre->fetchAll($req['style']);
}

function FindSerialize($req = [])
{
    $data = find($req);
    $d = [];
    foreach ($data as $key => $value) {
        $d[] = unserialize($value['value']);
    }
    return $d;
}

function FindFirstSerialize($req)
{
    $d = unserialize(findFirst($req)['value']);
    return $d;
}

function FindFirst($req)
{
    return current(Find($req));
}

function FindField($table, $base= NULL)
{
    global $db;
    $base = ($base !== null) ? $base : denv('DEFAULT_BASE');
    return $db[$base]->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
}

function FindLike($req)
{
    global $request;
    if (!isset($req['fields'])) {
        $req['fields'] = '*';
    }
    $req['table'] = (isset($req['table'])) ? $req['table'] : strtolower($request['request']['controller']);
    $data = Find([
        'table'      => $req['table'],
        'fields' => $req['fields'],
        'where' => "CONCAT(" . implode(',', $req['champs']) . ") LIKE '%" . $req['like'] . "%'",
    ]);
    return $data;
}

function FindCount($req = [])
{
    global $request;
    $cond = '';
    $req['table'] = (isset($req['table'])) ? $req['table'] : strtolower($request['request']['controller']);
    if (!isset($req['where'])) {
        $req['where'] = "1=1";
    }
    if (!isset($req['key'])) {
        $req['key'] = 'id';
    }
    $res = FindFirst([
        'table' => $req['table'],
        'fields' => 'COUNT(' . $req['key'] . ') AS count',
        'where' => $req['where']
    ]);
    return $res['count'];
}


function FindList($req = [])
{

    if (!isset($req['key'])) {
        $req['key'] = "id";
    }
    if (!isset($req['fields'])) {
        $req['fields'] = $req['key'] . ', name';
    }

    $d = Find($req);
    $r = [];
    foreach ($d as $k => $v) {
        $r[current($v)] = next($v);
    }

    return $r;
}


function Save($req = [])
{
    global $db, $request;
    $key = isset($req['key']) ? $req['key'] : 'id';
    $req['data'] = (isset($req['data'])) ? $req['data'] : $_POST;
    $req['table'] = (isset($req['table'])) ? $req['table'] : strtolower($request['request']['controller']);
    $req['database'] = (isset($req['database'])) ? $req['database'] : denv('DEFAULT_BASE');
    $fields =  array();
    $d = array();
    foreach ($req['data'] as $k => $v) {
        if ($k != $key) {
            $fields[] = "$k=:$k";
            $d[":$k"] = $v;
        } elseif (!empty($v)) {
            $d[":$k"] = $v;
        }
    }

    if (isset($req['data'][$key]) && !empty($req['data'][$key]) && $req['data'][$key] != 0) {
        $sql = 'UPDATE ' . $req['table'] . ' SET ' . implode(',', $fields) . ' WHERE ' . $key . '=:' . $key;
        $id = $req['data'][$key];
        $action = 'updated';
    } else {
        $sql = 'INSERT INTO ' . $req['table'] . ' SET ' . implode(',', $fields);
        $action = 'insert';
    }
    $pre = $db[$req['database']]->prepare($sql);
    $pre->execute($d);

    if ($action != 'updated') {
        return $db[$req['database']]->lastInsertId();
    } else {
        return $id;
    }
}

function Delete($req, $base= null)
{

    global $db, $request;
    $base = ($base !== null) ? $base : denv('DEFAULT_BASE');
    if (!isset($req['key'])) {
        $req['key'] = 'id';
    }
    $req['table'] = (isset($req['table'])) ? $req['table'] : strtolower($request['request']['controller']);
    if (isset($req['cond'])) {
        $sql = "DELETE FROM {$req['table']} WHERE {$req['cond']} ";
    } else {
        $sql = "DELETE FROM {$req['table']} WHERE {$req['key']}={$req['id']} ";
    }
    $db[$base]->query($sql);
}

function genTable($data = [],$base= null)
{
    global $key, $db;
    $sql = "";
    $base = ($base !== null) ? $base : denv('DEFAULT_BASE');
    if(!empty($base)) {
        foreach ($data as $table => $champs) {
            if(empty($db[$base]->query("SELECT * FROM $table"))) {
                $sql .= "CREATE TABLE IF NOT EXISTS `$table`(";
                $sql .= "`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)";
                $sql .= ");";
                $db[$base]->query($sql);
            }
            foreach ($champs as $champ => $props) {
                if (empty($db[$base]->query("SELECT $champ FROM $table"))) {
                    $sql .= " ALTER TABLE `$table` ADD COLUMN `$champ` $props NULL DEFAULT NULL AFTER `$key`; ";
                }
                $key = $champ;
                foreach ($db[$base]->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC) as $dropkey => $dropValue) {
                    if (!isset($champs[$dropValue["Field"]])) {
                        $field = $dropValue["Field"];
                        $sql .= "ALTER TABLE `$table` DROP COLUMN `$field`;";
                    }
                }
            }
        }
    }
    if(!empty($sql)) {
        $db[$base]->query($sql);
    }
}
