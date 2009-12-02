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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script language="javascript" type="text/javascript">
// http://joncom.be/code/javascript-json-formatter/

function FormatJSON(oData, sIndent)
{
  if (arguments.length < 2) {
    var sIndent = "";
  }
  var sIndentStyle = "    ";
  var sDataType = RealTypeOf(oData);

  // open object
  if (sDataType == "array") {
    if (oData.length == 0) {
      return "[]";
    }
    var sHTML = "[";
  } else {
    var iCount = 0;
    $.each(oData, function() {
      iCount++;
      return;
    });
    if (iCount == 0) { // object is empty
      return "{}";
    }
    var sHTML = "{";
  }

  // loop through items
  var iCount = 0;
  $.each(oData, function(sKey, vValue) {
    if (iCount > 0) {
      sHTML += ",";
    }
    if (sDataType == "array") {
      sHTML += ("\n" + sIndent + sIndentStyle);
    } else {
      sHTML += ("\n" + sIndent + sIndentStyle + "\"" + sKey + "\"" + ": ");
    }

    // display relevant data type
    switch (RealTypeOf(vValue)) {
      case "array":
      case "object":
        sHTML += FormatJSON(vValue, (sIndent + sIndentStyle));
        break;
      case "boolean":
      case "number":
        sHTML += vValue.toString();
        break;
      case "null":
        sHTML += "null";
        break;
      case "string":
        sHTML += ("\"" + vValue + "\"");
        break;
      default:
        sHTML += ("TYPEOF: " + typeof(vValue));                                                                                              }
        // loop
        iCount++;
    }
  )

  // close object
  if (sDataType == "array") {
    sHTML += ("\n" + sIndent + "]");
  } else {
    sHTML += ("\n" + sIndent + "}");
  }

  // return
  return sHTML;
}

function RealTypeOf(v)
{
  if (typeof(v) == "object") {
    if (v === null) return "null";
    if (v.constructor == (new Array).constructor) return "array";
    if (v.constructor == (new Date).constructor) return "date";
    if (v.constructor == (new RegExp).constructor) return "regex";
    return "object";
  }
  return typeof(v);
}

function SortObject(oData)
{
  var oNewData = {};
  var aSortArray = [];

  // sort keys
  $.each(oData, function(sKey) {
    aSortArray.push(sKey);
  });
  aSortArray.sort(SortLowerCase);

  // create new data object
  $.each(aSortArray, function(i) {
    if( RealTypeOf(oData[(aSortArray[i])]) == "object" ) {
      oData[(aSortArray[i])] = SortObject(oData[(aSortArray[i])]);
    }
    oNewData[(aSortArray[i])] = oData[(aSortArray[i])];
  });

  return oNewData;

  function SortLowerCase(a,b) {
    a = a.toLowerCase();
    b = b.toLowerCase();
    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
  }
}

</script>
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
    <textarea name="json" id="json" cols="70" rows="20"></textarea>
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
<script language="javascript" type="text/javascript">
// http://joncom.be/code/javascript-json-formatter/
var json_data = <?php echo json_encode($doc); ?>;
document.getElementById("json").value = FormatJSON(json_data);
</script>
</body>
</html>
