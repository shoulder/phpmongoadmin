<?php

class DocAction
{

    function edit_method()
    {
        $coll = MongoAdmin::db()->selectCollection($_GET['coll']);
        $cur  = $coll->find(array('_id' => $_GET['doc']));
        $doc  = $cur->getNext();
        
        $vars['current_coll'] = $coll;
        $vars['doc'] = $doc;
        $vars['key'] = $cur->key();
        MongoAdmin::tpl('doc-edit', $vars);
    }

    function update_method()
    {
        $json = trim($_POST['json']);
        $vars = eval("return {$json};");
        
        $coll = MongoAdmin::db()->selectCollection($_POST['coll']);
        $coll->update(array('_id' => $_POST['doc']), $vars);

        redirect(url('doc.edit', array('coll' => $coll->getName(), 'doc' => $_POST['doc'])));
    }

    function _fetch_arr()
    {
        $arr = array();
        print_r($_POST);
        foreach ($_POST as $key => $value)
        {
            $keys = explode('/', $key);
            if ($keys[0] != 'field') continue;
            array_shift($keys);
            while ($key = array_pop($keys))
            {
                $value = array($key => $value);
            }
            $arr = array_merge_recursive($arr, $value);
            // $arr[$key] = $value;
        }
        return $arr;
    }
}

