<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/draw2d/shifty.js"></script>
<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/draw2d/raphael.js"></script>
<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/draw2d/Class.js"></script>

<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/draw2d/draw2d.js"></script>

<div id="canva" style="width: 100%; height: 1500px; cursor: default;"></div>

<script>
var $ = jQuery;

var zoomIndex = 0;

var mailsMix = $.parseJSON('<?= $item->mails_json ?>');

var mailsMix2 = [
    {
        "type": "draw2d.shape.basic.Rectangle",
        "id": "354fa3b9-a834-0221-2009-abc2d6bd852a",
        "x": 225,
        "y": 97,
        "width": 201,
        "height": 45,
        "radius": 1
    },{
        "type": "draw2d.shape.basic.Rectangle",
        "id": "ebfb35bb-5767-8155-c804-14bda7759dc2",
        "x": 72,
        "y": 45,
        "width": 50,
        "height": 45,
        "radius": 1
    },{
        "type": "draw2d.shape.node.Start",
        "id": "354fa3b9-a834-0221-2009-abc2d6bd852as",
        "x": 25,
        "y": 97,
        "width": 50,
        "height": 45,
        "radius": 1
    },{
        "type": "draw2d.shape.node.End",
        "id": "ebfb35bb-5767-8155-c804-14bda7759dc2e",
        "x": 272,
        "y": 45,
        "width": 50,
        "height": 45,
        "radius": 1
    },{
        "type": "draw2d.Connection",
        "id": "74ce9e7e-5f0e-8642-6bec-4ff9c54b3f0ac",
        "source": {
            "node": "354fa3b9-a834-0221-2009-abc2d6bd852as",
            "port": "output0"
        },
        "target": {
            "node": "ebfb35bb-5767-8155-c804-14bda7759dc2e",
            "port": "input0"
        }
    }
];


$(document).ready(function(){
  var canva = new draw2d.Canvas("canva");
  
  canva.installEditPolicy( new draw2d.policy.canvas.SnapToGridEditPolicy(50) );
  
  var reader = new draw2d.io.json.Reader();
  reader.unmarshal(canva, mailsMix);
  
  
  console.log( canva.getAllPorts() );
  
});

</script>