<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/raphael-min.js"></script>

<style>
.buttons-grp{
    float: right;
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
</style>

<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
  <form name="post" action="" method="post">
    <input type="hidden" name="uid" value="<?= $item->id ?>">
    <input type="hidden" name="act" value="save">
  
    <div class="buttons-grp">
      <input type="submit" value="Сохранить" class="button button-primary button-large">
      <a href="/wp-admin/admin.php?page=vspostman-mails" class="button button-large">Отмена</a>
      <? if ($item->id > 0) { ?><a href="/wp-admin/admin.php?page=vspostman-mails&act=delete&uid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a><? } ?>
      
    </div>
    
    <h2><?= $item->title ?></h2>
    
    <div id="titlediv">
      <div id="titlewrap">
        <input type="text" name="name" size="30" value="<?= $item->name ?>" id="title" autocomplete="off" placeholder="Введите название воронки">
      </div>
    </div>
    
  </form>
  
  <div style="margin: 10px 0 5px;">
  
    <div style="float: right;">
      <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail-add&uid=<?= $item->id ?>">Добавить письмо</a>
    </div>
  
    <input type="submit" value="+" class="button button-large" onclick="zoom('+')">
    <input type="submit" value="-" class="button button-large" onclick="zoom('-')">
    <input type="submit" value="Сбросить" class="button button-large" onclick="zoom()">
  </div>
  
  <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;">
  </div>
  
  <div style="margin: 10px 0 5px;">
    <div style="float: right;">
      <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail-add&uid=<?= $item->id ?>">Добавить письмо</a>
    </div>
  </div>
  
  <h3>Список отдельных писем</h3>
  
</div>

<script>
var $ = jQuery;

var zoomIndex = 0;

var mailsMix = $.parseJSON('<?= $item->mails ?>');

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

function rectangle(options){
    this.rap = paper;
    this.opt = jQuery.extend({
        text: '',
        X: 0,
        Y: 0,
        bg: '#fc0',
        width: 150,
        height: 50,
        radius: 2,
        textColor: 'blue',
        fontSize: 12,
    }, options);
    
    this.rect = this.rap.rect(opt.X*1, opt.Y*1, opt.width*1, opt.height*1, opt.radius*1);
    this.rect.attr({
        title: opt.text,
        fill: opt.bg
    });
    
    this.text = paper.text(opt.X*1, opt.Y*1+10, opt.text);
    this.text.attr({
        'fill': opt.textColor,
        'font-size': opt.fontSize
    });
    
    this.element = this.rap.set();
    this.element.push(this.rect);
    this.element.push(this.text);
    
    this.element.drag(
        function (dx, dy) {
            // Move main element
            var att = this.type == "ellipse" ? {cx: this.ox + dx, cy: this.oy + dy} : {x: this.ox + dx, y: this.oy + dy};
            this.attr(att);
            // Move paired element
            att = this.pair.type == "ellipse" ? {cx: this.pair.ox + dx, cy: this.pair.oy + dy} : {x: this.pair.ox + dx, y: this.pair.oy + dy};
            this.pair.attr(att);
            // Move connections
            //for (i = connections.length; i--;) {
            //    paper.connection(connections[i]);
            //}
            paper.safari();
        },
        function () {
            console.log(this);
            this.ox = this.attr("x");
            this.oy = this.attr("y");
        }
    );
    
    return this.element; 
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
            {x: bb1.x + bb1.width / 2,   y: bb1.y + bb1.height + 1},
            {x: bb1.x - 1,               y: bb1.y + bb1.height / 2},
            {x: bb1.x + bb1.width + 1,   y: bb1.y + bb1.height / 2},
            {x: bb2.x + bb2.width / 2,   y: bb2.y - 1},
            {x: bb2.x + bb2.width / 2,   y: bb2.y + bb2.height + 1},
            {x: bb2.x - 1,               y: bb2.y + bb2.height / 2},
            {x: bb2.x + bb2.width + 1,   y: bb2.y + bb2.height / 2}
        ],
        d = {}, dis = [];
        
    for (var i = 0; i < 4; i++) {
        for (var j = 4; j < 8; j++) {
            var dx = Math.abs(p[i].x - p[j].x),
                dy = Math.abs(p[i].y - p[j].y);
            if ((i == j - 4) || (((i != 3 && j != 6) || p[i].x < p[j].x) && ((i != 2 && j != 7) || p[i].x > p[j].x) && ((i != 0 && j != 5) || p[i].y > p[j].y) && ((i != 1 && j != 4) || p[i].y < p[j].y))) {
                dis.push(dx + dy);
                d[dis[dis.length - 1]] = [i, j];
            }
        }
    }
    
    if (dis.length == 0) {
        var res = [0, 4];
    } else {
        res = d[Math.min.apply(Math, dis)];
    }
    var x1 = p[res[0]].x,
        y1 = p[res[0]].y,
        x4 = p[res[1]].x,
        y4 = p[res[1]].y;
    dx = Math.max(Math.abs(x1 - x4) / 2, 10);
    dy = Math.max(Math.abs(y1 - y4) / 2, 10);
    var x2 = [x1, x1, x1 - dx, x1 + dx][res[0]].toFixed(3),
        y2 = [y1 - dy, y1 + dy, y1, y1][res[0]].toFixed(3),
        x3 = [0, 0, 0, 0, x4, x4, x4 - dx, x4 + dx][res[1]].toFixed(3),
        y3 = [0, 0, 0, 0, y1 + dy, y1 - dy, y4, y4][res[1]].toFixed(3);
    var path = ["M", x1.toFixed(3), y1.toFixed(3), "C", x2, y2, x3, y3, x4.toFixed(3), y4.toFixed(3)].join(",");
    //var path = ["M", x1.toFixed(3), y1.toFixed(3), "Q", x2, y2, x4.toFixed(3), y4.toFixed(3)].join(",");
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

function dragger() {
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
            }).drag(move, dragger).dblclick(function(){
                //console.log(this);
            });
            
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