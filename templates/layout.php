<style>
.wrap .add-new-h2.active{
    color: #F0F0F0;
    background: #585858;
    text-shadow: none;
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