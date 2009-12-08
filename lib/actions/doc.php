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

        require_once MongoAdmin::$root_path . '/deps/zend_json_decoder.php';
        $vars = Zend_Json_Decoder::decode($json, Zend_Json_Decoder::TYPE_ARRAY);
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

}

