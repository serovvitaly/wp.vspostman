<script src="/wp-content/plugins/vspostman/libs/jquery.tmpl.min.js"></script>
<script src="/wp-content/plugins/vspostman/libs/jquery.form.min.js"></script>

<script>
$ = jQuery;
</script>

<style>
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
</style>

<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
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