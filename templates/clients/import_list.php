
<div style="height: 20px;"></div>

<? if (count($list) > 0) { ?>
<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Имя</th>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Email</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Добавлен</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-name">Имя</th>
        <th scope="col" class="manage-column column-name">Email</th>
        <th scope="col" class="manage-column column-name">Добавлен</th>
      </tr>
    </tfoot>

    <tbody id="the-list">
    <? foreach ($list AS $item) { ?>
        <tr>
          <td><a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=<?= $item->id ?>"><?= $item->first_name ?></a></td>
          <td><a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=<?= $item->id ?>"><?= $item->email ?></a></td>
          <td><?= $item->created ?></td>
        </tr>
    <? } ?>
    </tbody>
</table>
<? } else { ?>
<p style="color: gray;">Нет результатов для отображения</p>
<? } ?>


