<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jquery.svg.package-1.4.5/jquery.svg.min.js"></script>
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/vspostman/libs/jquery.svg.package-1.4.5/jquery.svg.css">

<style>
.buttons-grp{
    text-align: right;
    margin-top: 10px;
}
.buttons-grp .button{
    margin-right: 5px;
}
.buttons-grp a.delete{
    color: red;
}
.buttons-grp a.delete:hover{
    color: black;
}
.mails-dt-info{
    margin: 10px 2px;
    color: green;
}
.mails-dt-info .red{
    color: red;
}
.fly-box{
    border: 2px solid #747FA8;
    padding: 5px;
    margin: 0 2px;
    display: inline-block;
    width: 100px;
    text-align: center;
    background: #FAFAEF;
    vertical-align: top;
    -webkit-border-radius: 2px;
       -moz-border-radius: 2px;
            border-radius: 2px;
}
.fly-box .title-hover{
    background: black;
    color: white;
    position: absolute;
    width: 140px;
    margin-top: -26px;
    margin-left: -26px;
    padding: 3px 5px;
    display: none;
}
.fly-box:hover .title-hover{
   /* display: block; */
}
.fly-box .title{
    height: 50px;
}
.fly-box-level{
    border-bottom: 1px dotted #C0C0C0;
    padding: 14px 2px 10px;
    text-align: center;
}
.fly-box-level .level-index{
    position: absolute;
    display: block;
    margin-top: -15px;
}
.fly-box-level .fb-clinging{
    border: 4px solid red;
    display: inline-block;
    margin-left: -4px;
    position: absolute;
    -webkit-border-radius: 4px;
       -moz-border-radius: 4px;
            border-radius: 4px;
}
.fly-box-level .fb-clinging.top{
    margin-top: -10px;
}
.fly-box-level .fb-clinging.bottom{
    margin-top: 52px;
}
#fly-box-level-0 .fly-box{
    border-color: #AF5487;
}
#fly-box-level-0 .fb-clinging.top{
    display: none;
}
.fly-box .hint-right{
    position: absolute;
    display: none;
    margin: -6px 0 0 107px;
    padding: 5px;
}
.fly-box .hint-right i{
    width: 20px;
    height: 20px;
    display: block;
    cursor: pointer;
    opacity: 0.6;
}
.fly-box .hint-right i.edit{
    background: url("/wp-content/plugins/vspostman/img/pencil_min.png") no-repeat center;
}
.fly-box .hint-right i.delete{
    background: url("/wp-content/plugins/vspostman/img/cross_min.png") no-repeat center;
}
.fly-box .hint-right i:hover{
    opacity: 1;
}
.fly-box:hover .hint-right{
    display: block;
}
</style>



    <input type="hidden" name="fid" value="<?= $item->id ?>">
  
    <div class="buttons-grp" style="margin-bottom: 10px;">
      <input type="submit" value="Сохранить" class="button button-primary button-large" onclick="saveFunnel(); return false;">
      <a href="/wp-admin/admin.php?page=vspostman-mails" class="button button-large">Отмена</a>
      <? if ($item->id > 0) { ?><a href="/wp-admin/admin.php?page=vspostman-mails&act=delete&fid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a><? } ?>
    </div>
    
    <div id="titlediv">
      <div id="titlewrap">
        <input type="text" name="name" size="30" value="<?= $item->name ?>" id="title" autocomplete="off" placeholder="Введите название воронки">
      </div>
      
      <div class="mails-dt-info" style="display: none;"></div>
      
    </div>
    
    <div style="float: right;<? if ($item->id < 1) { ?> display: none<? } ?>" class="hidden1">
        <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail_add&fid=<?= $item->id ?>">Добавить письмо</a>
    </div>
    
  
  <div style="display: none;">
  
      <div style="margin: 10px 0 5px;">
      
        <div style="float: right;">
          <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail_add&fid=<?= $item->id ?>">Добавить письмо</a>
        </div>
      
        <input type="submit" value="+" class="button button-large" onclick="zoom('+')">
        <input type="submit" value="-" class="button button-large" onclick="zoom('-')">
        <input type="submit" value="Сбросить" class="button button-large" onclick="zoom()">
      </div>
      
      <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;">
      </div>
      
      <div style="margin: 10px 0 5px;">
        <div style="float: right;">
          <a class="button button-large button-primary" href="/wp-admin/admin.php?page=vspostman-mails&act=mail-add&fid=<?= $item->id ?>">Добавить письмо</a>
        </div>
      </div>
  
  </div>
  
<? if (count($item->mails) > 0) { 
    
      $anchors = array();    
      $levels = array();
      $no_levels = array();
      foreach ($item->mails AS $mail) {
          if ($mail->bound_id > 0 OR $mail->is_root == 1) {
              $levels[$mail->level][] = $mail;
              if ($mail->bound_id > 0) {
                  $anchors[] = array(
                      'from' => $mail->id,
                      'to' => $mail->bound_id,
                  );
              }
          } else {
              $no_levels[] = $mail;
          }  
      }
      
      if (count($levels) > 0) {
          ksort($levels);
          
          echo '<div id="fly-container" style="border: 1px solid #DFDFDF; margin-top: 40px;">';
          
          ?>
          <div style="position: absolute; z-index: -1;" id="fly-line-canva">
          </div>
          <?
          
          foreach ($levels AS $level_index => $level) {
              $level_boxes = '';
              foreach ($level AS $level_box) {
                  $level_boxes .= '<div id="fly-box-'.$level_box->id.'" class="fly-box"><div class="title-hover">'.$level_box->title.'</div><div class="hint-right"><i class="edit" onclick="editMail('.$level_box->id.')"></i><i class="delete" onclick="deleteMail('.$level_box->id.')"></i></div><i class="fb-clinging top"></i><i class="fb-clinging bottom"></i><div class="title">'.$level_box->title.'</div></div>';
              }
          ?>
          <div id="fly-box-level-<?= $level_index ?>" class="fly-box-level">
            <div class="level-index"><?= $level_index ?> день</div>
            <?= $level_boxes ?>
          </div>
          <?
          }

          echo '</div>';
      }
      
      if (count($no_levels) > 0) {
      ?>
      <div>
        <h3>Независимые письма, входящие в воронку</h3>
        <table class="wp-list-table widefat" cellspacing="0">
            <thead>
              <tr>
                <th scope="col" class="manage-column column-name" style="">Наименование</th>
                <th scope="col" class="manage-column column-name" style="">Дата</th>
                <th scope="col" class="manage-column column-name" style="">Тип</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th scope="col" class="manage-column column-name" style="">Наименование</th>
                <th scope="col" class="manage-column column-name" style="">Дата</th>
                <th scope="col" class="manage-column column-name" style="">Тип</th>
              </tr>
            </tfoot>
            <tbody id="the-list">
            <?
                    $_mail_types = array(
                        1 => 'По подписке',
                        2 => 'По ручному добавлению',
                        3 => 'По клику',
                        4 => 'По открытию',
                        5 => 'По отправке',
                        6 => 'По заказу',
                        7 => 'По изменению данных',
                        8 => 'По специальной дате',
                    );
                    
                    foreach ($no_levels AS $mail) {
            ?>
                <tr class="inactive">
                  <td class="title">
                    <strong><?= $mail->title ?></strong>
                    <div class="row-actions-visible">
                      <span class="duplicate"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_duplicate&mid=<?= $mail->id ?>" onclick="if (!confirm('Точно дублировать?')) return false;">Дублировать</a> | </span>
                      <span class="edit"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_edit&mid=<?= $mail->id ?>">Редактировать</a> | </span>
                      <span class="stat"><a href="/wp-admin/admin.php?page=vspostman-stats&act=mail_stat&mid=<?= $mail->id ?>">Статистика</a> | </span>
                      <span class="delete"><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail_delete&mid=<?= $mail->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a></span></div>
                  </td>
                  <td class="">
                    <?= $mail->created ?>
                  </td>
                  <td class="">
                    <?= $_mail_types[$mail->mail_type] ?>
                  </td>
                </tr>
            <? } ?>
            </tbody>
        </table>
      </div>
      <?
      }


} ?>    


<script>
var $ = jQuery;

var svg = null;

function buildLine(startId, stopId){
    var canva = $('#fly-line-canva');
    
    var canvaTop = canva.position().top;
    
    var start = $('#fly-box-'+startId).position();
    var stop  = $('#fly-box-'+stopId).position();
    
    if (start && stop) {
        svg.polyline([[start.left + 58, start.top - canvaTop], [stop.left + 58, stop.top - canvaTop + 62]], {stroke: 'black', strokeWidth: 1});
    }    
}

function renderLines(){
    
    $('#fly-line-canva').width($('#fly-container').width());
    $('#fly-line-canva').height($('#fly-container').height());
    
    if (svg) {
        $('#fly-line-canva').svg('destroy')
    }
    
    $('#fly-line-canva').svg();
    
    svg = $('#fly-line-canva').svg('get');
    
    var anchors = $.parseJSON('<?= json_encode($anchors) ?>');

    $.each(anchors, function(index, item){
        buildLine(item.from, item.to);
    });
}

renderLines();


function deleteMail(id){
    if (confirm('Письмо будет удалено. Продолжить?')) {
        _ajax({
            type: 'GET',
            url: '/wp-admin/admin.php?page=vspostman-mails&act=mail_delete&mid='+id
        });
        var removeEl = $('#fly-box-'+id);
        var parentLevel = removeEl.parent('.fly-box-level');
        removeEl.remove();
        if (parentLevel.find('.fly-box').length < 1) {
            parentLevel.remove();
        }
        renderLines();
    }
}

function editMail(id){
    window.location = '/wp-admin/admin.php?page=vspostman-mails&act=mail_edit&mid='+id;
}

function clearForm(){
    $('.mails-dt-info').slideUp(100, function(){$(this).html('')});
    $('input[name="fid"]').val('');
    $('input[name="name"]').val('');
}

function saveFunnel(){
    var funnelId   = $('input[name="fid"]').val();
    var funnelName = $('input[name="name"]').val();
    
    var self = this;
    
    $('.mails-dt-info').slideUp(100, function(){$(this).html('')});
    
    this.save = function(){
        _ajax({
            controller: 'mails',
            action: 'save',
            data: {
                funnel_id: funnelId,
                funnel_name: funnelName,
            },
            success: function(data){
                if (data.success === true) {
                    $('input[name="fid"]').val(data.result);
                    $('.mails-dt-info').html('Воронка сохранена. Теперь Вы можете добавить в нее письма.').slideDown(100);
                    $('.hidden1').fadeIn(100);
                }
            }
        });
    }
    
    if (funnelId > 0) {
        self.save();
    } else {
        _ajax({
            controller: 'mails',
            action: 'check_funnel',
            data: {
                funnel_name: funnelName,
            },
            success: function(data){
                if (data.success === true) {
                    self.save();
                } else {
                    $('.mails-dt-info').html('<span class="red">Невозможно добавить воронку, так как воронка с названием “'+funnelName+'” уже существует.</span> <input class="button button-small" type="submit" onclick="clearForm(); return false;" value="OK">').slideDown(100);
                }
            }
        });
    }

}

$(window).resize(function(){
    renderLines();
});

</script>


<div>

</div>