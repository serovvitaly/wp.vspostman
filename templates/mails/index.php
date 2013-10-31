<style>
.wrap .add-new-h2.active{
    color: #F0F0F0;
    background: #585858;
    text-shadow: none;
}
</style>

<div class="wrap">
  <div class="icon32"><img src="/wp-content/plugins/vspostman/img/blue-document-text-image.png" alt=""></div>
  <h2>Воронки продаж 
    <a href="/wp-admin/admin.php?page=vspostman-mails" class="add-new-h2 active">Список воронок</a>
    <a href="/wp-admin/admin.php?page=vspostman-mails&act=add" class="add-new-h2">Добавить воронку</a>
  </h2>


<!--ul class="subsubsub">
    <li class="all"><a href="plugins.php?plugin_status=all" class="current">Все <span class="count">(4)</span></a> |</li>
    <li class="active"><a href="plugins.php?plugin_status=active">Активный <span class="count">(1)</span></a> |</li>
    <li class="inactive"><a href="plugins.php?plugin_status=inactive">Неактивные <span class="count">(3)</span></a></li>
</ul-->


<form method="post" action="">

<input type="hidden" name="plugin_status" value="all">
<input type="hidden" name="paged" value="1">

<input type="hidden" id="_wpnonce" name="_wpnonce" value="0879c54e6a"><input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php">    <div class="tablenav top">

        <div class="actions">
            <div style="float: right;">
              <a href="/wp-admin/admin.php?page=vspostman-mails&act=add" class="button button-primary">Добавить воронку</a>
            </div>
        
        </div>
    <div class="tablenav-pages one-page" style="display: none;">
        <span class="pagination-links"><a class="first-page disabled" title="Перейти на первую страницу" href="http://wordpress/wp-admin/plugins.php">«</a>
        <a class="prev-page disabled" title="Перейти на предыдущую страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">‹</a>
        <span class="paging-input"><input class="current-page" title="Текущая страница" type="text" name="paged" value="1" size="1"> из <span class="total-pages">1</span></span>
        <a class="next-page disabled" title="Перейти на следующую страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">›</a>
        <a class="last-page disabled" title="Перейти на последнюю страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">»</a></span>
    </div>
        <br class="clear">
    </div>
<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <!--th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th-->
        <th scope="col" class="manage-column column-name" style="">Воронка</th>
        <th scope="col" class="manage-column column-name" style="">Создана</th>
        <th scope="col" class="manage-column column-name" style="">Подписчики</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <!--th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th-->
        <th scope="col" class="manage-column column-name" style="">Воронка</th>
        <th scope="col" class="manage-column column-name" style="">Создана</th>
        <th scope="col" class="manage-column column-name" style="">Подписчики</th>
      </tr>
    </tfoot>

    <tbody id="the-list">
    
    <?
        if (count($items) > 0) {
            foreach ($items AS $item) {
    ?>
        <tr class="inactive">
          <!--th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="<?= $item->id ?>"></th-->
          <td class="title">
            <strong><?= $item->name ?></strong>
            <div class="row-actions-visible">
              <span class="duplicate"><a href="/wp-admin/admin.php?page=vspostman-mails&act=duplicate&uid=<?= $item->id ?>" onclick="if (!confirm('Будет создана копия воронки “<?= $item->name ?>”, только без подписчиков. Продолжить?')) return false;">Дублировать</a> | </span>
              <span class="edit"><a href="/wp-admin/admin.php?page=vspostman-mails&act=edit&uid=<?= $item->id ?>">Редактировать</a> | </span>
              <span class="stat"><a href="/wp-admin/admin.php?page=vspostman-stats&act=stat&uid=<?= $item->id ?>">Статистика</a> | </span>
              <span class="delete"><a href="/wp-admin/admin.php?page=vspostman-mails&act=delete&uid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Воронка “<?= $item->name ?>” будет удалена. Продолжить?')) return false;">Удалить</a></span></div>
          </td>
          
          <td class="">
            <?= $item->created ?>
          </td>
          
          <td class="">
            <a href="#" title="Показать список подписчиков"><?= $item->readers ?></a>
          </td>

        </tr>
    <?
            }
        }    
    ?>    
    </tbody>
</table>
    <div class="tablenav bottom">

        <div class="actions">
            <div style="float: right;">
              <a href="/wp-admin/admin.php?page=vspostman-mails&act=add" class="button button-primary">Добавить воронку</a>
            </div> 
        </div>
<div class="tablenav-pages one-page">
<span class="pagination-links"><a class="first-page disabled" title="Перейти на первую страницу" href="http://wordpress/wp-admin/plugins.php">«</a>
<a class="prev-page disabled" title="Перейти на предыдущую страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">‹</a>
<span class="paging-input">1 из <span class="total-pages">1</span></span>
<a class="next-page disabled" title="Перейти на следующую страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">›</a>
<a class="last-page disabled" title="Перейти на последнюю страницу" href="http://wordpress/wp-admin/plugins.php?paged=1">»</a></span></div>
        <br class="clear">
    </div>
</form>

</div>