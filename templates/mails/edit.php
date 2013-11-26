<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/raphael-min.js"></script>

<style>
.buttons-grp{
    text-align: right;
    margin-top: 10px;
}
.buttons-grp .button{
    margin-right: 5px;
}
.buttons-grp a.delete{
    color: red;
}
.buttons-grp a.delete:hover{
    color: black;
}
.mails-dt-info{
    margin: 10px 2px;
    color: green;
}
.mails-dt-info .red{
    color: red;
}
</style>



    <input type="hidden" name="fid" value="<?= $item->id ?>">
  
    <div class="buttons-grp" style="margin-bottom: 10px;">
      <input type="submit" value="Сохранить" class="button button-primary button-large" onclick="saveFunnel(); return false;">
      <a href="/wp-admin/admin.php?page=vspostman-mails" class="button button-large">Отмена</a>
      <? if ($item->id > 0) { ?><a href="/wp-admin/admin.php?page=vspostman-mails&act=delete&fid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a><? } ?>
    </div>
    
    <div id="titlediv">
      <div id="titlewrap">
        <input type="text" name="name" size="30" value="<?= $item->name ?>" id="title" autocomplete="off" placeholder="Введите название воронки">
      </div>
      
      <div class="mails-dt-info" style="display: none;"></div>
      
    </div>
    
    <div style="float: right;<? if ($item->id < 1) { ?> display: none<? } ?>" class="hidden1">
        <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail_add&fid=<?= $item->id ?>">Добавить письмо</a>
    </div>
    
  
  <div style="display: none;">
  
      <div style="margin: 10px 0 5px;">
      
        <div style="float: right;">
          <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail_add&fid=<?= $item->id ?>">Добавить письмо</a>
        </div>
      
        <input type="submit" value="+" class="button button-large" onclick="zoom('+')">
        <input type="submit" value="-" class="button button-large" onclick="zoom('-')">
        <input type="submit" value="Сбросить" class="button button-large" onclick="zoom()">
      </div>
      
      <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;">
      </div>
      
      <div style="margin: 10px 0 5px;">
        <div style="float: right;">
          <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail-add&fid=<?= $item->id ?>">Добавить письмо</a>
        </div>
      </div>
  
  </div>
<? if (count($item->mails) > 0) { ?>
  <div>
    <h3>Независимые письма, входящие в воронку</h3>
    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" class="manage-column column-name" style="">Наименование</th>
            <th scope="col" class="manage-column column-name" style="">Дата</th>
            <th scope="col" class="manage-column column-name" style="">Тип</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th scope="col" class="manage-column column-name" style="">Наименование</th>
            <th scope="col" class="manage-column column-name" style="">Дата</th>
            <th scope="col" class="manage-column column-name" style="">Тип</th>
          </tr>
        </tfoot>
        <tbody id="the-list">
        <?
                $_mail_types = array(
                    1 => 'По подписке',
                    2 => 'По ручному добавлению',
                    3 => 'По клику',
                    4 => 'По открытию',
                    5 => 'По отправке',
                    6 => 'По заказу',
                    7 => 'По изменению данных',
                    8 => 'По специальной дате',
                );
                
                foreach ($item->mails AS $mail) {
        ?>
            <tr class="inactive">
              <td class="title">
                <strong><?= $mail->title ?></strong>
                <div class="row-actions-visible">
                  <span class="duplicate"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_duplicate&mid=<?= $mail->id ?>" onclick="if (!confirm('Точно дублировать?')) return false;">Дублировать</a> | </span>
                  <span class="edit"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_edit&mid=<?= $mail->id ?>">Редактировать</a> | </span>
                  <span class="stat"><a href="/wp-admin/admin.php?page=vspostman-stats&act=mail_stat&mid=<?= $mail->id ?>">Статистика</a> | </span>
                  <span class="delete"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_delete&mid=<?= $mail->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a></span></div>
              </td>
              <td class="">
                <?= $mail->created ?>
              </td>
              <td class="">
                <?= $_mail_types[$mail->mail_type] ?>
              </td>
            </tr>
        <? } ?>
        </tbody>
    </table>
  </div>
<? } ?>    


<script>
var $ = jQuery;

function clearForm(){
    $('.mails-dt-info').slideUp(100, function(){$(this).html('')});
    $('input[name="fid"]').val('');
    $('input[name="name"]').val('');
}

function saveFunnel(){
    var funnelId   = $('input[name="fid"]').val();
    var funnelName = $('input[name="name"]').val();
    
    var self = this;
    
    $('.mails-dt-info').slideUp(100, function(){$(this).html('')});
    
    this.save = function(){
        _ajax({
            controller: 'mails',
            action: 'save',
            data: {
                funnel_id: funnelId,
                funnel_name: funnelName,
            },
            success: function(data){
                if (data.success === true) {
                    $('input[name="fid"]').val(data.result);
                    $('.mails-dt-info').html('Воронка сохранена. Теперь Вы можете добавить в нее письма.').slideDown(100);
                    $('.hidden1').fadeIn(100);
                }
            }
        });
    }
    
    if (funnelId > 0) {
        self.save();
    } else {
        _ajax({
            controller: 'mails',
            action: 'check_funnel',
            data: {
                funnel_name: funnelName,
            },
            success: function(data){
                if (data.success === true) {
                    self.save();
                } else {
                    $('.mails-dt-info').html('<span class="red">Невозможно добавить воронку, так как воронка с названием “'+funnelName+'” уже существует.</span> <input class="button button-small" type="submit" onclick="clearForm(); return false;" value="OK">').slideDown(100);
                }
            }
        });
    }

}

var zoomIndex = 0;

var mailsMix = $.parseJSON('<?= $item->mails_json ?>');

function zoom(z){
    switch (z) {
        case '+':
            zoomIndex--;
            break;
        case '-':
            zoomIndex++;
            break;
        default:
            zoomIndex = 0;
    }
    if (zoomIndex < 0) zoomIndex = 0;
    if (zoomIndex > 5) zoomIndex = 5;
    
    var multiplier = 300 * zoomIndex;
    var width  = paper.width  + multiplier;
    var height = paper.height + multiplier;
    
    paper.setViewBox(0, 0, width, height);
}


Raphael.fn.connection = function (obj1, obj2, line, bg) {
    if (!obj1) return false;
    if (obj1.line && obj1.from && obj1.to) {
        line = obj1;
        obj1 = line.from;
        obj2 = line.to;
    }
    var bb1 = obj1.getBBox(),
        bb2 = obj2.getBBox(),
        p = [
            {x: bb1.x + bb1.width / 2,   y: bb1.y - 1},
            {x: bb2.x + bb2.width / 2,   y: bb2.y + bb2.height + 1}
        ],
        d = {}, dis = [];
    
    if (dis.length == 0) {
        var res = [0, 1];
    } else {
        res = d[Math.min.apply(Math, dis)];
    }
    var x1 = p[res[0]].x,
        y1 = p[res[0]].y,
        x4 = p[res[1]].x,
        y4 = p[res[1]].y;
    dx = Math.max(Math.abs(x1 - x4) / 4, 10);
    dy = Math.max(Math.abs(y1 - y4) / 4, 10);
    var x2 = [x1, x1, x1 - dx, x1 + dx][res[0]].toFixed(3),
        y2 = [y1 - 12, y1, y1, y1][res[0]].toFixed(3),
        x3 = [x4, x4, x4 - dx, x4 + dx][res[1]].toFixed(3),
        y3 = [y1, y1 - 12, y4, y4][res[1]].toFixed(3);
    //var path = ["M", x1.toFixed(3), y1.toFixed(3), "C", x2, y2, x3, y3, x4.toFixed(3), y4.toFixed(3)].join(",");
    var path = ["M", x1.toFixed(3), y1.toFixed(3), "L", x2, y2, x3, y3, x4.toFixed(3), y4.toFixed(3)].join(",");
    if (line && line.line) {
        line.bg && line.bg.attr({path: path});
        line.line.attr({path: path});
    } else {
        var color = typeof line == "string" ? line : "#000";
        return {
            bg: bg && bg.split && this.path(path).attr({stroke: bg.split("|")[0], fill: "none", "stroke-width": bg.split("|")[1] || 3}),
            line: this.path(path).attr({stroke: color, fill: "none"}),
            from: obj1,
            to: obj2
        };
    }
};

function dragstop(){
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        data: {
            act: 'set-param',
            source: 'mail',
            param: 'left',
            mid: this.mid,
            value: this.getBBox().x
        },
        type: 'post'
    });
}
function dragger(){
    // Original coords for main element
    this.ox = this.attr("x");
    this.oy = this.attr("y");
        
    // Original coords for pair element
    this.pair.ox = this.pair.attr("x");
    this.pair.oy = this.pair.attr("y");           
}
function move (dx, dy) {
    // Move main element
    var att = {x: this.ox + dx, y: this.oy};
    this.attr(att);
    
    // Move paired element
    att = {x: this.pair.ox + dx, y: this.pair.oy};
    this.pair.attr(att);            
    
    // Move connections
    for (i = connections.length; i--;) {
        paper.connection(connections[i]);
    }
    paper.safari();
}

var paper = Raphael("myholder", $('myholder').width(), $('myholder').height());

var connections = [], shapes = [], texts = [], tempS, tempT;

var iterate = 0;
for (var ofs = 0; ofs < 100; ofs++) {
    
    if (mailsMix[ofs] && mailsMix[ofs].length > 0) {
        var offset = 100 * iterate;
        iterate++;
        paper.text(30, offset + 15, ofs).attr({
            'fill': '#C0C0C0',
            'font-size': 30
        });
        
        paper.path('M0 '+(offset+95)+'H'+paper.width).attr({
            'fill': '#D0D0D0',
            'stroke' : '#D0D0D0',
            'stroke-width': 1
        });
        for (var it = 0; it < mailsMix[ofs].length; it++) {
            var item = mailsMix[ofs][it];
            
            tempS = paper.rect(item.left*1, offset+20, 150, 50, 2).attr({
                title: item.title,
                fill: '#F0F0F0',
                cursor: "move"
            }).drag(move, dragger, dragstop).dblclick(function(){
                //console.log(this);
            });
            
            tempS.mouseover(function(){ return;
                if (!this.popover) {
                    this.popover = paper.rect(this.getPointAtLength().x*1-5, this.getPointAtLength().y-5, 160, 60, 2).attr({
                        fill: 'red',
                        opacity: 0.6,
                        cursor: "pointer",
                        'stroke-width': 0
                    });
                    
                } else this.popover.show();
                
            }).mouseout(function(e){ return;
                var bb = this.popover.getBBox();
                console.log(e.offsetX, '<', bb.x, 'OR', e.offsetX, '>', bb.x2, 'AND', e.offsetY, '<', bb.y, 'OR', e.offsetY, '>', bb.y2);
                if (this.popover && (e.offsetX < bb.x || e.offsetX > bb.x2) && (e.offsetY < bb.y || e.offsetY > bb.y2)) {
                    console.log('HIDE');
                    this.popover.hide();
                } else console.log('NoN');
            });
            
            tempS.mid = item.id;
            tempS.bound_id = item.bound_id;
            
            tempT = paper.text(item.left*1+75, offset+40, item.title).attr({
                fill: '#585858',
                cursor: "move",
                'font-size': 12
            }).drag(move, dragger);
            
            tempS.pair = tempT;
            tempT.pair = tempS;
            
            shapes[item.id] = tempS;
            texts[item.id] = tempT;
            
        }               
    }
}

$.each(shapes, function(index, item){
    if (item && item.bound_id > 0) {
        connections[item.id] = paper.connection(shapes[index], shapes[item.bound_id], "#000");
    }
});

</script>


<div>

</div>