<link rel="stylesheet" type="text/css" href="/wp-content/plugins/vspostman/libs/jointjs/joint.min.css">
<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jointjs/joint.min.js"></script>

<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
  <h2><?= $_['title'] ?></h2>

  <form name="post" action="post.php" method="post" id="post">

    <div id="titlediv">
      <div id="titlewrap">
        <label class="" id="title-prompt-text" for="title">Введите заголовок</label>
        <input type="text" name="post_title" size="30" value="" id="title" autocomplete="off">
      </div>
    </div>
    
  </form>
  
  <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;"></div>
  
</div>

<script>

var holder = $('#myholder');
    
var graph = new joint.dia.Graph;

var paper = new joint.dia.Paper({
    el: holder,
    width: holder.width() - 20,
    //height: holder.height(),
    height: 1000,
    model: graph
});




function addRect(rectText, X, Y, color){
    if (!color) color = 'red';
    var rect = new joint.shapes.basic.Rect({
        position: { x: X, y: Y },
        size: { width: 150, height: 50 },
        attrs: { rect: {fill: color, 'stroke-width': 0}, text: {text: rectText, fill: 'white'} }
    });
    graph.addCell(rect);
    return rect;
}

function addLink(source, target){
    var source_id = (typeof source == 'object') ? source.id : source;
    var target_id = (typeof target == 'object') ? target.id : target;
    var link = new joint.dia.Link({
        attrs: {'.marker-source': { fill: 'red', d: 'M 10 0 L 0 5 L 10 10 z' }},
        source: { id: source_id },
        target: { id: target_id }
    });
    link.set('vertices', [{ x: 300, y: 60 }]);
    graph.addCell(link);
}

var re1 = addRect('первое письмо', 100, 40, 'blue');
var re2 = addRect('второе письмо', 500, 140);

addLink(re1, re2.id);



</script>