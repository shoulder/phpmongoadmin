<?php

class DefaultAction
{
    function index_method()
    {
        $vars = array();
        if (!empty($_GET['coll']))
        {
            $coll = MongoAdmin::db()->selectCollection($_GET['coll']);
            $cur = $coll->find()->sort(array('_id' => 1));
            if (!empty($_GET['skip']))
            {
                $skip = intval($_GET['skip']);
            }
            else
            {
                $skip = 0;
            }
            $cur->skip($skip)->limit(30);

            $vars['current_coll'] = $coll;
            $vars['cur'] = $cur;
            $vars['skip'] = $skip;
            $vars['limit'] = 30;
        }
        MongoAdmin::tpl('index', $vars);
    }
}

