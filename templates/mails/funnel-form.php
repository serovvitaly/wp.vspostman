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

var paper = Raphael("myholder", $('myholder').width(), $('myholder').height());

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
            paper.rect(item.left*1, offset+20, 150, 50, 2).attr({
                text: paper.text(item.left*1+75, offset+45, item.title).attr({'font-size': 14, 'width': 150}).dblclick(function(){
                    console.log(this);
                }),
                title: item.title,
                fill: '#fc0',
                'font-size': 20
            }).drag(
                function (dx, dy) {
                    this.attr({
                        x: this.ox*1 + dx*1,
                        y: this.oy
                    });
                    paper.safari();
                },
                function () {
                    this.ox = this.attr("x");
                    this.oy = this.attr("y");
                }
            ).dblclick(function(){
                console.log(this);
            });
        }        
    }
}

</script>