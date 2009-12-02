<?php

class DocAction
{

    function edit_method()
    {
        $coll = MongoAdmin::db()->selectCollection($_REQUEST['coll']);
        $cur  = $coll->find(array('_id' => $_REQUEST['doc']));
        $doc  = $cur->getNext();
        
        $vars['current_coll'] = $coll;
        $vars['doc'] = $doc;
        $vars['key'] = $cur->key();
        MongoAdmin::tpl('doc-edit', $vars);
    }

    function update_method()
    {
        $json = trim($_REQUEST['json']);
        $vars = json_decode($json, true);
        $coll = MongoAdmin::db()->selectCollection($_REQUEST['coll']);
        $coll->update(array('_id' => $_REQUEST['doc']), $vars);
        redirect(url('doc.edit', array('coll' => $coll->getName(), 'doc' => $_REQUEST['doc'])));
    }

    function drop_method()
    {
        $coll = MongoAdmin::db()->selectCollection($_REQUEST['coll']);
        $coll->remove(array('_id' => $_REQUEST['doc']));
        redirect(url('default.index', array('coll' => $coll->getName())));
    }

    function _fetch_arr()
    {
        $arr = array();
        print_r($_REQUEST);
        foreach ($_REQUEST as $key => $value)
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

