<?php

function dump_doc($doc, $parent = 'field/')
{
	echo <<<EOT
<table width="100%" border="1" cellspacing="2" cellpadding="2">

EOT;
	$fields = array_keys($doc);
	foreach ($fields as $field):
		$field_t = h("{$parent}{$field}");
		$field_name = h($field);
		echo <<<EOT
<tr>
<th scope="row">{$field_name}</th>
<td>

EOT;

		if (is_array($doc[$field]))
		{
			echo dump_doc($doc[$field], "{$parent}{$field}/");
		}
		else
		{
			$value_t = h($doc[$field]);
			echo <<<EOT
<input type="text" name="{$field_t}" id="{$field_t}" size="40" value="{$value_t}" />
EOT;
		}
		
		echo <<<EOT
</td>
</tr>

EOT;
	endforeach;
	
	echo <<<EOT
</table>

EOT;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>phpMongoAdmin</title>
<link href="<?php echo $_BASE_DIR; ?>/media/twoColElsLtHdr.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<style type="text/css"> 
/* 请将所有版本的 IE 的 css 修复放在这个条件注释中 */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* 上面的专用 zoom 属性为 IE 提供避免错误所需的 hasLayout */
</style>
<![endif]-->
</head>
<body class="twoColElsLtHdr">
<div id="container">
  <div id="header">
    <h1>phpMongoAdmin - <?php echo h(MongoAdmin::db()); ?></h1>
    <!-- end #header -->
  </div>
  <div id="sidebar1">
    <h3>listCollections</h3>
    <ul>
      <?php $coll_list = MongoAdmin::db()->listCollections(); foreach ($coll_list as $coll): ?>
      <li><a href="<?php echo url('default.index', array('coll' => $coll->getName())); ?>"><?php echo h($coll->getName()); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <!-- end #sidebar1 -->
  </div>
  <div id="mainContent">
    <h1>edit - <?php echo h($current_coll->getName()); ?> - <?php echo h($key); ?></h1>
    <form id="form1" name="form1" method="post" action="<?php echo url('doc.update'); ?>">
    <?php // dump_doc($doc); ?>
    <label for="json">JSON:</label>
    <textarea name="json" id="json" cols="70" rows="20"><?php echo h(var_export($doc, true)); // echo json_encode($doc); ?></textarea>
    <p>&nbsp;</p>
    <p><input type="submit" name="Submit" value="update" />
    <input type="hidden" name="coll" id="coll" value="<?php echo h($current_coll->getName()); ?>" />
    <input type="hidden" name="doc" id="doc" value="<?php echo h($key); ?>" />
    </p>
    </form>
    <!-- end #mainContent -->
  </div>
  <!-- 这个用于清除浮动的元素应当紧跟 #mainContent div 之后，以便强制 #container div 包含所有的子浮动 -->
  <br class="clearfloat" />
  <div id="footer">
    <p>Powered by MongoDB</p>
    <!-- end #footer -->
  </div>
  <!-- end #container -->
</div>
</body>
</html>
