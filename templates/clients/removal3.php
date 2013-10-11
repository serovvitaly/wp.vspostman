<div style="margin: 10px 0; text-align: left"><a href="#" class="button button-primary" onclick="$('#removal-box').toggleClass('hidden');return false;">Отписать контакт</a></div>
<div id="removal-box" class="hidden">
  <div><span style="vertical-align: top; display: inline-block; width: 200px;">Введите список email для удаления, по одному на каждой строке.</span>
    <div style="display: inline-block;">
      <form id="clients-removal-form" action="">
        <textarea name="removal_list" style="width: 300px; height: 200px;" cols="" rows=""></textarea><br>
        <select name="funnel_id">
          <option value="0">Удалить из всех воронок</option>
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
        <textarea name="reason" style="width: 300px; height: 50px;" cols="" rows="" placeholder="Причина удаления"></textarea><br>
        <div class="info"></div>
        <input type="submit" class="button" value="Выполнить">
        <a href="#" onclick="$('#removal-box').toggleClass('hidden');return false;" style="color:red">отмена</a>
      </form>
    </div>
    
  </div>
</div>

<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=removal" class="nav-tab">Удалены вручную (<?= isset($totals[1]) ? $totals[1] : 0 ?>)</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=removal2" class="nav-tab">Удалены автоматически (<?= isset($totals[2]) ? $totals[2] : 0 ?>)</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=removal3" class="nav-tab nav-tab-active">Удалены, нажав ссылку (<?= isset($totals[3]) ? $totals[3] : 0 ?>)</a>
</h2>

<div class="tab-container">

<table class="wp-list-table widefat" cellspacing="0">
    <thead>
      <tr>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Имя</th>
        <th scope="col" class="manage-column column-name" style="width: 200px;">Email</th>
        <th scope="col" class="manage-column column-name" style="">Причина удаления</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Дата удаления</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-name">Имя</th>
        <th scope="col" class="manage-column column-name">Email</th>
        <th scope="col" class="manage-column column-name">Причина удаления</th>
        <th scope="col" class="manage-column column-name">Дата удаления</th>
      </tr>
    </tfoot>

    <tbody id="the-list">
    <?
        if (count($list) > 0) {
            foreach ($list AS $item) {
        ?>
        <tr>
          <td><?= $item->first_name ?></td>
          <td><?= $item->email ?></td>
          <td><?= $item->removal_reason ?></td>
          <td><?= $item->removal_at ?></td>
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
        id: 'clients-removal-form',
        data: {
            controller: 'clients',
            act: 'removalgo'
        },
        beforeSubmit: function(formData, jqForm, options){
            var infoBox = $('#clients-removal-form .info');
            infoBox.html('');
            
            infoBox.html('<i>Выполняется операция...</i>');
        },
        success: function(data){
            $('#clients-removal-form .info').html(data.result);
        }
    });
});
</script>
