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
  <?php if (isset($current_coll)): ?>
    <h1><?php echo h($current_coll->getName()); ?></h1>
    <table width="100%" border="1" cellspacing="2" cellpadding="2">
      <tr>
        <th scope="col">index</th>
        <th scope="col">key</th>
        <th scope="col">fields</th>
        <th scope="col">more</th>
      </tr>
    <?php $index = 0; while ($doc = $cur->getNext()): $url_args = array('coll' => $current_coll->getName(), 'doc' => $cur->key()); ?>
      <tr>
        <th scope="row"><?php echo $index + $skip; $index++;?></th>
        <td><?php echo h($cur->key()); ?></td>
        <td><?php $fields = implode(', ', array_keys($doc)); echo substr($fields, 0, 40); if (strlen($fields) > 40) echo '...'; ?></td>
        <td><a href="<?php echo url('doc.edit', $url_args); ?>">Edit</a>
        	|
            <a href="<?php echo url('doc.drop', $url_args); ?>" onclick="return confirm('are you sure?');">Drop</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </table>
    
    <p>doc count: <?php echo $cur->count(); ?>, skip: <?php echo $skip; ?>, limit: <?php echo $limit; ?></p>
    
    
    <!-- end #mainContent -->
    <?php endif; ?>
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
