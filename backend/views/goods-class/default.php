<?php
use jinxing\admin\helpers\Helper;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = '';
$url = Helper::getAssetUrl();
$depends = ['depends' => 'jinxing\admin\web\AdminAsset'];
$this->registerJsFile($url . '/js/jstree/jstree.min.js', $depends);
$this->registerCssFile($url . '/js/jstree/default/style.css', $depends);
?>

<style>
    .page-content{padding: 12px;}
    .page-header,.footer{display: none;}
    /*div, p, table, th, td{list-style: none;margin: 0;padding: 0;color: #333;font-size: 12px;font-family: dotum, Verdana, Arial, Helvetica, AppleGothic, sans-serif;}*/
</style>

<table height="750px" border=0 align=left>
    <tr>
        <td align=left valign=top style="border-right: #999999 1px dashed">
            <div id="tree-one" class="tree tree-selectable ztree" style="width:220px; overflow:auto;"></div>
        </td>
        <td width=100% align=left valign=top>
            <iframe id="iframe_goods_class" name="iframe_goods_class" frameborder=0  width=99% height="" src="<?= Url::toRoute(['index']) ?>"></iframe>
        </td>
    </tr>
</table>

<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var iframe;
    Array.prototype.indexOf = function(val) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == val) return i;
        }
        return -1;
    }
    Array.prototype.remove = function(val) {
        var index = this.indexOf(val);
        if (index > -1) {
            this.splice(index, 1);
        }
    }

    $("#tree-one").jstree({
        core: {
            "animation": 0,
            "check_callback": true,
            data: <?=Json::encode($trees)?>
        }
    }).on('changed.jstree', function (e, data) {
        var id          = data.instance.get_node(data.selected[0]).id;
        var parentId    = data.node.parent;
        var parents     = data.node.parents;
        var src         = 'index';

        parents.remove('j1_1');
        parents.remove('#');

        if (parentId == 'j1_1' || parentId == '#') parentId = 0;
        if (id == 'j1_1') id = 0;

        pid     = parseInt(parentId);
        id      = parseInt(id);
        length  = parseInt(parents.length);

        src += '?';
        if (length == 0 || length == 1) {
            src += 'id=' + id;
        } else {
            src += 'pid=' + pid + '&id=' + id;
        }
        iframe.attr("src", src);
    });

    $(document).ready(function () {
        iframe = $("#iframe_goods_class");
        iframe.bind("load", loadReady);
    });

    function loadReady() {
        var bodyH = iframe.contents().find("body").get(0).scrollHeight,
            htmlH = iframe.contents().find("html").get(0).scrollHeight,
            maxH = Math.max(bodyH, htmlH), minH = Math.min(bodyH, htmlH),
            h = iframe.height() >= maxH ? minH : maxH;
        if (h < 530) h = 800;
        iframe.height(h);
    }
</script>
<?php $this->endBlock(); ?>
