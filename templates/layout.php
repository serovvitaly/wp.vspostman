<script src="/wp-content/plugins/vspostman/libs/jquery.tmpl.min.js"></script>
<script src="/wp-content/plugins/vspostman/libs/jquery.form.min.js"></script>

<script src="/wp-content/plugins/vspostman/libs/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/vspostman/libs/jquery-ui/css/ui-lightness/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/vspostman/libs/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">

<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/Chart.min.js"></script>

<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jQuery-Mask-Plugin/jquery.mask.min.js"></script>

<script>
$ = jQuery;

function ajaxForm(opts){
    this.targer = opts.id;
    delete opts.id;
    
    this.config = $.extend({
        url: '/wp-content/plugins/vspostman/ajax.php',
        type: 'POST',
        dataType: 'json',
    }, opts);
    
    $('#'+this.targer).ajaxForm(this.config);
}

function getUniqueId(){
    var d = new Date();
    return d.valueOf() + '' + d.getUTCMilliseconds();
}

function _ajax(options){
    
    var data = $.extend({
        controller: options.controller,
        act: options.action,
    }, options.data);
    
    var opts = $.extend({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {}
    }, options);
    
    opts.data = data;
    
    $.ajax(opts);
}



function removeComment(comId){
    
    if (!confirm('Комментарий будет удален. Продолжить?')) {
        return;
    }
    
    _ajax({
        controller: 'clients',
        action: 'remove_comment',
        data: {
            comment_id: comId,
            //contact_id: '<?= $id ?>'
        },
        success: function(data){
            if (data.success === true) {
                
            }
        }
    });
    
    window.location = window.location;
    
    //$('#clients-comments-item-' + comId).fadeOut(200, function(){ $(this).remove() });
    
}
</script>

<style>
.widefat tbody th.check-column {
    padding: 9px 0 7px;
}
.wrap table td{
    vertical-align: top;
}
.wrap .tab-container{
    padding: 20px;
    border: 1px solid #C9C9C9;
    border-top: 0;
}
.wrap .filter-params{
    display: inline-block;
    margin-right: 80px;
    vertical-align: top;
}
.wrap .filter-params input, .wrap .filter-params select{
    margin-top: -1px;
}
.wrap .filter-item{
    margin: 30px 0 10px;
    display: none;
}
.wrap .filter-item .remove-button{
    color: red;
    padding-left: 10px;
    text-decoration: none;
    display: none;
}
.wrap .filter-item .remove-button:hover{
    text-decoration: underline;
}
.wrap .filter-params td{
    padding: 0 0 20px;
}
.wrap .add-new-h2.active{
    color: #F0F0F0;
    background: #585858;
    text-shadow: none;
}
.big-text{
    padding: 3px 8px;
    font-size: 1.7em;
    line-height: 100%;
    height: 1.7em;
    width: 100%;
    outline: 0;
    margin: 1px 0;
}
.progresser{
    border: 1px solid #B6B6B6;
    width: 246px;
    margin-top: 10px;
}
.progresser .inner{
    height: 2px;
    width: 0%;
    background: red;
}
.chart-table{
    display: inline-block;
    vertical-align: top;
    margin-left: 40px;
}
.chart-table td{
    font-size: 14px;
    padding: 5px;
}
.chart-table td.value{
    font-weight: bold;
    font-size: 18px;
}
.hasDatepicker{
    background: url(/wp-content/plugins/vspostman/img/date-trigger.png) no-repeat right;
}

.pre-view{
    line-height: 25px;
    padding-left: 5px;
    display: inline-block;
}
.pre-view.textarea{
    line-height: 18px;
}
.pre-view.hidden{
    display: none;
}
td.td-title{
    line-height: 25px;
}
.edit-view{
    border-color: #AFC8DB !important;
    width: 210px;
    display: inline-table;
}
.edit-view.hidden{
    display: none;
}
textarea.edit-view{
    width: 210px;
    height: 110px;
}
.clients-unsubscribe-contact{
    font-size: 13px;
    font-weight: bold;
    text-decoration: none;
    color: #F00;
    display: inline-block;
    margin: -3px 0 0 2px;
}
</style>

<div class="wrap">
  <div<?= $icon_id ? ' id="'.$icon_id.'"' : '' ?> class="icon32"><img src="<?= $icon ?>" alt=""></div>
  <h2><?= $title ?> 
  <?
      if (isset($top_menu) AND is_array($top_menu) AND count($top_menu) > 0) {
          
          $menu_items = array();
          foreach ($top_menu AS $menu_item) {
              $menu_items[] = '<a href="' . $menu_item['href'] . '" class="add-new-h2'.($action == $menu_item['act'] ? ' active' : '').'">' . $menu_item['text'] . '</a>';
          }
          
          echo implode(' ', $menu_items);
      }
  ?>
  </h2>

  <div>
  <?= $content ?>
  </div>

  <div id="ajax-response"></div>
  <br class="clear">
</div>