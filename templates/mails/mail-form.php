<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jquery.form.min.js"></script>

<style>
.buttons-grp{
    float: right;
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
.params-table td{
    padding: 5px 0;
}
.mail-type-select{
    
}
.mail-type-select ul{
    width: 837px;
    margin: 0 auto;
}
.mail-type-select li{
    display: inline-block;
}
.mail-type-select label{
    border: 1px solid #DFDFDF;
    display: inline-block;
    height: 100px;
    width: 100px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    text-align: center;
}
.mail-type-select label:hover{
    border-color: #BDBDBD;
}
.mail-type-select label.active, .mail-type-select label.active:hover{
    border-color: #9C9C9C;
    border-width: 2px;
    height: 98px;
    width: 98px;
}
.mail-type-select label:hover{
    border-color: #BDBDBD;
}
.mail-type-select input{
    display: none;
}
</style>

<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
  <form id="ajaxForm" name="post" action="/wp-content/plugins/vspostman/ajax.php" method="post">
    <input type="hidden" name="uid" value="<?= $item->funnel_id ?>">
    <input type="hidden" name="mid" value="<?= $item->id ?>">
    <input type="hidden" name="act" value="mail-save">
  
    <div class="buttons-grp">
      <input type="submit" value="Сохранить" class="button button-primary button-large">
      <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="button button-large">Отмена</a>
      <? if ($item->id > 0) { ?><a href="/wp-admin/admin.php?page=vspostman-mails&act=mail-delete&uid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a><? } ?>
      
    </div>
    
    <h2><?= $item->page_title ?></h2>
    
    <div id="titlediv">
      <div id="titlewrap">
        <input type="text" name="title" size="30" value="<?= $item->title ?>" id="title" autocomplete="off" placeholder="Введите заголовок письма">
      </div>
    </div>
    
    <div class="mail-type-select">
      <ul>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="1">По подписке</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="2">По ручному добавлению</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="3">По клику</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="4">По открытию</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="5">По отправке</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="6">По заказу</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="7">По изменению данных</label></li>
        <li><label><input class="vsp-target" type="radio" name="mail_type" value="8">По специальной дате</label></li>
      </ul>
    </div>
    
    <div>
      <table class="params-table">
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="1,3,4,5,6,7,8">
        <td style="width: 130px;">Воронка:</td>
        <td>
          <select name="funnel_id" style="width: 200px;">
          <?
              if (count($funnels_list) > 0) {
                  foreach ($funnels_list AS $funnel) {
                      $selected = ($item->funnel_id == $funnel->id) ? ' selected="selected"' : '';
                  ?>
              <option<?= $selected ?> value="<?= $funnel->id ?>"><?= $funnel->name ?></option>
                  <?
                  }
              }
          ?>
          </select>
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="3,4,5">
        <td style="width: 130px;">Письмо:</td>
        <td>
          <select name="bound_id" style="width: 200px;" disabled="disabled">
          </select>
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="3">
        <td style="width: 130px;">Ссылки:</td>
        <td>
          <select name="mail_link_id" style="width: 200px;" disabled="disabled">
          </select>
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="6">
        <td style="width: 130px;">Заказы:</td>
        <td>
          <select name="order_id" style="width: 200px;" disabled="disabled">
          </select>
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="7">
        <td style="width: 130px;">Данные:</td>
        <td>
          <select name="data_modified_type">
            <option value="1">Ввел другое</option>
            <option value="2">Изменил на</option>
          </select>
          <select name="data_modified_field">
            <option>Любое поле</option>
            <option>...</option>
          </select>
          <input type="text">
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="8">
        <td style="width: 130px;">Дата:</td>
        <td>
          <select name="date_field">
            <option>День рождения</option>
          </select>
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="1,3,4,5,6,7,8">
        <td style="width: 130px;">Время отправления:</td>
        <td>
          <select name="time_mailing_type" class="vsp-target">
            <option value="1">Немедленно</option>
            <option value="2">С задержкой</option>
            <option value="3">Точно в</option>
          </select>
          
          <label style="display: none" data-vsp-target="time_mailing_type" data-vsp-values="2">
            <input type="text" name="time_mailing_delay_days" style="width: 40px; text-align: center;"> дней
          </label> 
          
          <label style="display: none" data-vsp-target="time_mailing_type" data-vsp-values="2">
            <select name="time_mailing_delay_hours">
          <?
              for ($hh = 0; $hh < 24; $hh++) {
                  $selected = '';
              ?>
            <option<?= $selected ?> value="<?= $hh ?>"><?= $hh ?></option>
              <?
              }
          ?>
          </select> часов</label>
          
          <select style="display: none" name="time_mailing_hour" data-vsp-target="time_mailing_type" data-vsp-values="3">
          <?
              for ($hh = 0; $hh < 24; $hh++) {
                  $selected = '';
                  $_hh = $hh > 9 ? $hh : '0' . $hh;
              ?>
            <option<?= $selected ?> value="<?= $hh ?>"><?= $_hh ?>:00</option>
              <?
              }
          ?>
          </select>
          
        </td>
      </tr>
      <tr style="display: none;" data-vsp-target="mail_type" data-vsp-values="1,3,4,5,6,7,8">
        <td style="width: 130px; line-height: 20px;">День недели:</td>
        <td>
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="1"> ПН 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="2"> ВТ 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="3"> СР 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="4"> ЧТ 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="5"> ПТ 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="6"> СБ 
          <input checked="checked" type="checkbox" name="time_mailing_weekday[]" value="7"> ВС
        </td>
      </tr>
      </table>
    </div>
    
    <div id="postdivrich" class="postarea edit-form-section">

    <?php wp_editor( $item->content, 'content', array(
        'media_buttons' => false,
        'dfw' => true,
        'tabfocus_elements' => 'save-post',
        'editor_height' => 540,
    ) ); ?>

    </div>
    
  </form>
  
</div>

<script>
var $ = jQuery;

function initMailType(tid){
    $('.mail-type-select input[value="'+tid+'"]').click();
}

function getData(data, success){
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        data: data,
        dataType: 'json',
        type: 'POST',
        success: success
    });
}

$('.vsp-target').on('change', function(){
    var val = this.value;
    $('[data-vsp-target="'+this.name+'"]').each(function(index, item){
        var vspValues = $(this).attr('data-vsp-values').split(',');
        if (jQuery.inArray(val, vspValues) >= 0) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

$('select[name="funnel_id"]').on('change', function(){
    var data = getData({
        act: 'mails-list',
        funnel_id: this.value
    }, function(data){
        if(data.success === true && data.result && data.result.length > 0){
            $('select[name="bound_id"]').removeAttr("disabled");
            var options = '<option value="0" style="color:gray">--- выберите письмо ---</option>';
            $.each(data.result, function(index, item){
                options += '<option value="'+item.id+'">'+item.title+'</option>';
            });
            $('select[name="bound_id"]').html(options);
        }
    });
});

$('select[name="bound_id"]').on('change', function(){
    var data = getData({
        act: 'mail-links-list',
        mail_id: this.value
    }, function(data){
        if(data.result && data.result.length > 0){
            $('select[name="mail_link_id"]').removeAttr("disabled");
            var options = '<option value="0" style="color:gray">--- выберите ссылку ---</option>';
            $.each(data.result, function(index, item){
                options += '<option value="'+item.id+'">'+item.link+'</option>';
            });
            $('select[name="mail_link_id"]').html(options);
        }
    });
});

$(document).ready(function(){
    
    <?if ($item->mail_type > 0) {?>initMailType(<?= $item->mail_type ?>);<?}?>
    
    $('.mail-type-select label').on('click', function(){
        $('.mail-type-select label').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#ajaxForm').ajaxForm({
        dataType: 'json',
        beforeSubmit: function(formData, jqForm, options){
            var fields = [];
            for (var i=0; i<formData.length; i++) {
                fields[ formData[i]['name'] ] = formData[i];
            }
            console.log(fields);
            if (!fields['mail_type'] || fields['mail_type'].value < 1) {
                // TODO: Не выбран тип письма
                alert('Не выбран тип письма');
                return false;
            }
            
            if (!fields['title'] || fields['title'].value.trim() == '') {
                // TODO: Заголовок письма - пуст
                alert('Заголовок письма не может быть пуст');
                return false;
            }
            
        },
        success: function(data, statusText, xhr, $form){
            if (data.success === true && data.result == 'mail-save-ok') {
                window.location = data.redirect_to;
            }
        }
    });
});

</script>