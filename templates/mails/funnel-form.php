<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/raphael-min.js"></script>

<style>
.draggable{
    width: 150px;
    height: 50px;
    background: #808080;
    text-align: center;
    color: #FFF;
    float: left;
    cursor: initial;
}
.level-sending{
    height: 50px;
    padding: 10px;
    border-bottom: 1px dotted #ACACAC;
}
.level-sending .meter{
    font-size: 33px;
    color: #E1E1E1;
    position: relative;
    float: left;
}
.level-sending.empty{
    display: none;
}
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
      <input type="submit" value="Сохранить" class="button button-primary">
      <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="button">Отмена</a>
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
    <input type="submit" value="+" class="button" onclick="zoom('+')">
    <input type="submit" value="-" class="button" onclick="zoom('-')">
    <input type="submit" value="Сбросить" class="button" onclick="zoom()">
  </div>
  
  <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;">
  </div>
  
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

function dragger() {
        // Original coords for main element
    this.ox = this.type == "ellipse" ? this.attr("cx") : this.attr("x");
    this.oy = this.type == "ellipse" ? this.attr("cy") : this.attr("y");
        
        // Original coords for pair element
    this.pair.ox = this.pair.type == "ellipse" ? this.pair.attr("cx") : this.pair.attr("x");
    this.pair.oy = this.pair.type == "ellipse" ? this.pair.attr("cy") : this.pair.attr("y");           
}
function move (dx, dy) {
        // Move main element
    var att = this.type == "ellipse" ? {cx: this.ox + dx, cy: this.oy + dy} : {x: this.ox + dx, y: this.oy + dy};
    this.attr(att);
    
        // Move paired element
    att = this.pair.type == "ellipse" ? {cx: this.pair.ox + dx, cy: this.pair.oy + dy} : {x: this.pair.ox + dx, y: this.pair.oy + dy};
    this.pair.attr(att);            
    
        // Move connections
    for (i = connections.length; i--;) {
        paper.connection(connections[i]);
    }
    paper.safari();
}

var paper = Raphael("myholder", $('myholder').width(), $('myholder').height());

var connections = [], shapes = [], texts = [], tempS, tempT;

for (var ofs = 0; ofs < 10; ofs++) {
    
    if (mailsMix[ofs] && mailsMix[ofs].length > 0) {
        var offset = 100 * ofs;
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
                fill: '#fc0',
                cursor: "move"
            }).drag(move, dragger).dblclick(function(){
                //console.log(this);
            });
            
            tempT = paper.text(item.left*1, offset+40, item.title).attr({
                fill: 'blue',
                cursor: "move",
                'font-size': 12
            }).drag(move, dragger);
            
            tempS.pair = tempT;
            tempT.pair = tempS;
            
            shapes.push(tempS);
            texts.push(tempT);
        }        
    }
}

</script>