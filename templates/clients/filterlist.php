<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients" class="nav-tab">Поиск контактов</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=filterlist" class="nav-tab nav-tab-active">Список фильтров</a>
</h2>

<div class="tab-container">

<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th>
        <th scope="col" class="manage-column column-name" style="">Наименование фильтра</th>
        <th scope="col" class="manage-column column-name" style="width: 300px;">Дата</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;"></th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th>
        <th scope="col" class="manage-column column-name" style="">Наименование фильтра</th>
        <th scope="col" class="manage-column column-name" style="">Дата</th>
        <th scope="col" class="manage-column column-name" style=""></th>
      </tr>
    </tfoot>

    <tbody id="the-list">
    <?
        if (count($filters) > 0) {
            foreach ($filters AS $filter) {
        ?>
        <tr>
          <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="<?= $filter->id ?>"></th>
          <td><?= $filter->name ?></td>
          <td><?= $filter->created ?></td>
          <td>
          <div class="row-actions-visible">
              <span class="edit"><a href="/wp-admin/admin.php?page=vspostman-clients&act=filteredit&uid=<?= $filter->id ?>">Редактировать</a> | </span>
              <span class="delete"><a href="/wp-admin/admin.php?page=vspostman-clients&act=filterdelete&uid=<?= $filter->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a></span>
          </div>
          </td>
        </tr>
        <?
            }
        }
    ?>
    </tbody>
</table>

</div>