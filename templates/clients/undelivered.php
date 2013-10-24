<div style="margin: 20px 0;">
  <p>На этой странице показоны контакты, которые удалены после трех(3) неудачных попыток доставки.</p>
  
<? if (count($list) > 0) { ?>  
<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Имя</th>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Email</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Дата удаления</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-name">Имя</th>
        <th scope="col" class="manage-column column-name">Email</th>
        <th scope="col" class="manage-column column-name">Дата удаления</th>
      </tr>
    </tfoot>

    <tbody id="the-list">
    <? foreach ($list AS $item) { ?>
        <tr>
          <td><?= $item->first_name ?></td>
          <td><?= $item->email ?></td>
          <td><?= $item->removal_at ?></td>
        </tr>
    <? } ?>
    </tbody>
</table>
<? } else { ?>
<p style="color: gray;">Нет результатов для отображения</p>
<? } ?>
  
</div>