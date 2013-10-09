<div style="margin: 10px 0; text-align: left"><a href="#" class="button button-primary" onclick="$('#removal-box').toggleClass('hidden');return false;">Добавить контакты в черный список</a></div>
<div id="removal-box" class="hidden">
  <div><span style="vertical-align: top; display: inline-block; width: 200px;">Введите список email для добавления в черный список, по одному на каждой строке.</span>
    <div style="display: inline-block;">
      <form id="clients-blacklistgo-form" action="">
        <textarea name="removal_list" style="width: 300px; height: 200px;" cols="" rows=""></textarea><br>
        <select name="funnel_id">
          <option value="0">Для всех воронок</option>
        <?
          if (count($funnels_list) > 0) {
              foreach ($funnels_list AS $funnel) {
          ?>
          <option value="<?= $funnel->id ?>"><?= $funnel->name ?></option>
          <?
              }
          }
        ?>
        </select><br>
        <div class="info"></div>
        <input type="submit" class="button" value="Выполнить">
        <a href="#" onclick="$('#removal-box').toggleClass('hidden');return false;" style="color:red">отмена</a>
      </form>
    </div>
    
  </div>
</div>

<div style="margin: 20px 0 0;">

<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Имя</th>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Email</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Воронка</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Дата</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;"></th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-name">Имя</th>
        <th scope="col" class="manage-column column-name">Email</th>
        <th scope="col" class="manage-column column-name">Воронка</th>
        <th scope="col" class="manage-column column-name">Дата</th>
        <th scope="col" class="manage-column column-name"></th>
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
          <td>---</td>
          <td><?= $item->removal_at ?></td>
          <td><a href="#">удалить из списка</a></td>
        </tr>
        <?
            }
        }
    ?>
    </tbody>
</table>

</div>

<script>
$(document).ready(function(){
    ajaxForm({
        id: 'clients-blacklistgo-form',
        data: {
            controller: 'clients',
            act: 'blacklistgo'
        },
        beforeSubmit: function(formData, jqForm, options){
            var infoBox = $('#clients-blacklistgo-form .info');
            infoBox.html('');
            
            infoBox.html('<i>Выполняется операция...</i>');
        },
        success: function(data){
            $('#clients-blacklistgo-form .info').html(data.result);
        }
    });
});
</script>
