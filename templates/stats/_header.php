<style>
.wrapper2{
    display: none;
}
.two-colls .wrapper1, .two-colls .wrapper2{
    width: 49%;
    display: inline-block;
}
</style>


<div style="margin: 20px 0;">

  <table>
    <tr>
      <td style="line-height: 26px;">Воронки:</td>
      <td>
        <select id="stats-funnels-list" style="width: 200px;">
          <option value="all">Все</option>
          <?
              if (count($funnels_list) > 0) {
                  foreach ($funnels_list AS $funnel) {
              ?>
            <option value="<?= $funnel->id ?>"><?= $funnel->name ?></option> 
              <?
                  }
              }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td style="line-height: 26px;">Письма:</td>
      <td>
        <select id="stats-mails-list-1" data-coll="1" class="stats-mails-list" style="width: 200px;">
          <option data-funnel="all" value="0" style="color: gray;">-- выберите письмо --</option>
          <?
              if (count($mails) > 0) {
                  foreach ($mails AS $mail) {
              ?>
            <option data-funnel="<?= $mail->funnel_id ?>" value="<?= $mail->id ?>"><?= $mail->title ?></option> 
              <?
                  }
              }
          ?>
        </select>
        <button class="button" onclick="onCompare(this)">Сравнить</button>
        <select id="stats-mails-list-2" data-coll="2" class="stats-mails-list hidden" style="width: 200px;">
          <option data-funnel="all" value="0" style="color: gray;">-- выберите письмо --</option>
          <?
              if (count($mails) > 0) {
                  foreach ($mails AS $mail) {
              ?>
            <option data-funnel="<?= $mail->funnel_id ?>" value="<?= $mail->id ?>"><?= $mail->title ?></option> 
              <?
                  }
              }
          ?>
        </select>
      </td>
    </tr>
  </table>

</div>

<script>
function onCompare(el){
    $(el).toggleClass('button-primary');
    $('#stats-mails-list-2').toggleClass('hidden');
    $('.tab-container').toggleClass('two-colls');
}
function selectMail(mail_id, coll){
    if (!coll) coll = 1;
    
    if (coll != 1 && coll != 2) return false;
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'stats',
            act: 'give_mail_data',
            id: mail_id
        },
        success: function(data){
            renderMailSelection(data.result, coll);
        }
    });
}
$('#stats-funnels-list').on('change', function(){
    this.funnel_id = $(this).val();
    $('.stats-mails-list option').attr('selected', '');
    $('.stats-mails-list option[data-funnel="all"]').attr('selected', 'selected');
    if (this.funnel_id == 'all') {
        $('.stats-mails-list option').show();
    } else {
        $('.stats-mails-list option').hide();
        $('.stats-mails-list option[data-funnel="'+this.funnel_id+'"]').show();
        $('.stats-mails-list option[data-funnel="all"]').show();
    }
});
$('.stats-mails-list').on('change', function(){
    selectMail($(this).val(), $(this).attr('data-coll'));
});

</script>

<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=index" class="nav-tab<?= $action == 'index' ? ' nav-tab-active' : '' ?>">Всего<br><span style="font-size: 12px;">отправлено: 0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=opened" class="nav-tab<?= $action == 'opened' ? ' nav-tab-active' : '' ?>">Открытых<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=clicked" class="nav-tab<?= $action == 'clicked' ? ' nav-tab-active' : '' ?>">Щелкнутых<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=targets" class="nav-tab<?= $action == 'targets' ? ' nav-tab-active' : '' ?>">Целей<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=socials" class="nav-tab<?= $action == 'socials' ? ' nav-tab-active' : '' ?>">Социальных<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=bounces" class="nav-tab<?= $action == 'bounces' ? ' nav-tab-active' : '' ?>">Отказов<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=errors" class="nav-tab<?= $action == 'errors' ? ' nav-tab-active' : '' ?>">Ошибок доставки<br><span style="font-size: 12px;">0<span></a>
  <a href="/wp-admin/admin.php?page=vspostman-stats&act=complaints" class="nav-tab<?= $action == 'complaints' ? ' nav-tab-active' : '' ?>">Жалоб<br><span style="font-size: 12px;">0<span></a>
</h2>