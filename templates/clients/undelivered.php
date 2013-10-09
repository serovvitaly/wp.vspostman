<div style="margin: 20px 0;">
  <p>На этой странице показоны контакты, которые удалены после трех(3) неудачных попыток доставки.</p>
  
  
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
    <?
        if (count($list) > 0) {
            foreach ($list AS $item) {
        ?>
        <tr>
          <td><?= $item->name ?></td>
          <td><?= $item->email ?></td>
          <td><?= $item->removal_at ?></td>
        </tr>
        <?
            }
        }
    ?>
    </tbody>
</table>
  
</div>