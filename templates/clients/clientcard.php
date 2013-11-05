
<style>
.pre-view{
    line-height: 25px;
    padding-left: 5px;
    display: inline-block;
}
.pre-view.textarea{
    line-height: 18px;
}
.pre-view.hidden{
    display: none;
}
td.td-title{
    line-height: 25px;
}
.edit-view{
    border-color: #AFC8DB !important;
    width: 210px;
    display: inline-table;
}
.edit-view.hidden{
    display: none;
}
textarea.edit-view{
    width: 210px;
    height: 110px;
}
.clients-unsubscribe-contact{
    font-size: 13px;
    font-weight: bold;
    text-decoration: none;
    color: #F00;
    display: inline-block;
    margin: -3px 0 0 2px;
}
</style>

<h2 class="nav-tab-wrapper">
  <a href="#" class="nav-tab nav-tab-active">Общие</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard_mails&cid=<?= $id ?>" class="nav-tab">Письма</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard_comments&cid=<?= $id ?>" class="nav-tab">Комментарии</a>
</h2>

<div class="tab-container">
  <div style="display: inline-block; vertical-align: top; margin-right: 20px; padding-right: 20px; border-right: 1px solid #E6E6E6; width: 400px;">

  <fieldset>
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px;">Анкета</span>
      <span style="float: right;">   
        <a href="#" onclick="editContact(this); return false;">изменить</a>
        
        <span class="clients-editable-act-remove"> | <a href="/wp-admin/admin.php?page=vspostman-clients&act=client_remove&cid=<?= $id ?>" onclick="return confirm('Клиент <?= $first_name ?> <<?= $email ?>> будет удален из базы данных вместе со всей связанной с ним информацией. Продолжить?')">удалить</a></span>
        
        <a href="#" class="button button-small clients-editable-act hidden" onclick="saveContact(this); return false;">Сохранить</a>
      </span>
      
    </legend>
    <form id="clients-editable-form" action="">
      <input type="hidden" name="cid" value="<?= $id ?>">
    <table id="clients-editable-fields">
      <colgroup>
        <col style="width: 180px;">
        <col>
      </colgroup>
      <tr<?= (!isset($first_name) OR empty($first_name)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">ФИО</td>
        <td><span class="pre-view"><?= $first_name ?></span><input name="first_name" value="<?= $first_name ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($country) OR empty($country)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Страна</td>
        <td><span class="pre-view"><?= $country ?></span><input name="country" value="<?= $country ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($city) OR empty($city)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Город</td>
        <td><span class="pre-view"><?= $city ?></span><input name="city" value="<?= $city ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($address) OR empty($address)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Адрес доставки (полный)</td>
        <td><span class="pre-view"><?= $address ?></span><input name="address" value="<?= $address ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($phone) OR empty($phone)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Телефон</td>
        <td><span class="pre-view"><?= $phone ?></span><input name="phone" value="<?= $phone ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($email) OR empty($email)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Email</td>
        <td><span class="pre-view"><?= $email ?></span><input name="email" value="<?= $email ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($skype) OR empty($skype)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Skype</td>
        <td><span class="pre-view"><?= $skype ?></span><input name="skype" value="<?= $skype ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($icq) OR empty($icq)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">ICQ</td>
        <td><span class="pre-view"><?= $icq ?></span><input name="icq" value="<?= $icq ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($facebook) OR empty($facebook)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Facebook</td>
        <td><span class="pre-view"><?= $facebook ?></span><input name="facebook" value="<?= $facebook ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($vk) OR empty($vk)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Вконтакте</td>
        <td><span class="pre-view"><?= $vk ?></span><input name="vk" value="<?= $vk ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($google) OR empty($google)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Google+</td>
        <td><span class="pre-view"><?= $google ?></span><input name="google" value="<?= $google ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($web) OR empty($web)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Веб-сайт</td>
        <td><span class="pre-view"><?= $web ?></span><input name="web" value="<?= $web ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($birthdate) OR empty($birthdate)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Дата рождения</td>
        <td><span class="pre-view"><?= $birthdate ?></span><input name="birthdate" value="<?= $birthdate ?>" class="edit-view hidden datepicker" type="text"></td>
      </tr>
      <tr<?= (!isset($information) OR empty($information)) ? ' class="hidden"' : '' ?>>
        <td class="td-title">Дополнительная информация</td>
        <td><span class="pre-view textarea"><?= $information ?></span><textarea name="information" class="edit-view hidden" cols="" rows=""><?= $information ?></textarea></td>
      </tr>
      <tr class="hidden"><td colspan="2"><div style="font-weight: bold; border-bottom: 1px dotted #BEBEBE;padding-bottom: 3px;margin-bottom: 5px;">Настраиваемые поля</div></td></tr>
      <?   
          if (isset($cost_fields) AND count($cost_fields) > 0) {
              foreach ($cost_fields AS $cfield) {
                  
                  $render_value = $cfield->value;
                  
          ?>
      <tr<?= empty($cfield->value) ? ' class="hidden"' : '' ?>>
        <td class="td-title">
          <?= $cfield->field_label ?>
        </td>
        <td>
          <span class="pre-view"><?= $render_value ?></span>
          <?
          
          if (in_array($cfield->field_type, array('single_select', 'multi_select'))) {
              ?><select<?= $cfield->field_type == 'multi_select' ? ' multiple="multiple"' : '' ?> class="edit-view hidden" name="cost_fields[<?= $cfield->id ?>][]"><?
          }
          
          if (!empty($cfield->field_value)) {
              $values = json_decode($cfield->field_value);
              
              if (!is_array($values)) {
                  $values = array($values);
              }
              
              if (count($values) > 0) {
                  foreach ($values AS $value) {
                      
                      switch ($cfield->field_type) {
                          case 'radio':
                              $checked = ($value == $cfield->value) ? ' checked="checked"' : '';
                              ?><label class="edit-view hidden"><input<?= $checked ?> type="radio" value="<?= $value ?>" name="cost_fields[<?= $cfield->id ?>]"> - <?= $value ?></label><?
                              break;
                          case 'checkbox':
                              $checked = in_array($value, explode("\n", $cfield->value)) ? ' checked="checked"' : '';
                              ?><label class="edit-view hidden"><input<?= $checked ?> type="checkbox" value="<?= $value ?>" name="cost_fields[<?= $cfield->id ?>][]"> - <?= $value ?></label><?
                              break;
                          case 'single_select':
                              $selected = in_array($value, explode("\n", $cfield->value)) ? ' selected="selected"' : '';
                              ?><option<?= $selected ?> value="<?= $value ?>"><?= $value ?></option><?
                              break;
                          case 'multi_select':
                              $selected = in_array($value, explode("\n", $cfield->value)) ? ' selected="selected"' : '';
                              ?><option<?= $selected ?> value="<?= $value ?>"><?= $value ?></option><?
                              break;
                              
                          default:
                              ?><input name="cost_fields[<?= $cfield->id ?>]" value="<?= $cfield->value ?>" class="edit-view hidden" type="text"><?
                      }
                      
                  }
              }   
          }
          
          
          if (in_array($cfield->field_type, array('single_select', 'multi_select'))) {
              ?></select><?
          }
          
          ?>
        </td>
      </tr>
          <?
              }
          }
      ?>
      
      <!--tr class="clients-editable-act hidden"><td colspan="2"><a href="#" onclick="createNewField(this); return false;">Добавить новое поле</a></td></tr-->
      
    </table>
    </form>
  </fieldset>

  
  </div>
  <div style="display: inline-block; vertical-align: top;">
  
  <fieldset style="margin-bottom: 20px;">
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px; padding: 0 196px 0 0">Источник</span>
    </legend>
    <table>
      <tr>
        <td>Дата регистрации</td>
        <td><?= $created ?></td>
      </tr>
      <tr>
        <td>Страница перехода (УРЛ)</td>
        <td><a target="_blank" href="<?= $conversion_page ?>"><?= $conversion_page ?></a></td>
      </tr>
    </table>
  </fieldset>
    
  <fieldset style="margin-bottom: 20px;">
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px; padding: 0 196px 0 0">Воронки</span>
    </legend>
    <? if (count($funnels) > 0) { ?>
    <table style="width: 100%;" id="client-funnels-list">
    <?
      $statuses = array(
          -1 => 'черный список',
          0  => 'отписан',
          1  => 'активен',
          2  => 'купил',
      );
    
      foreach ($funnels AS $funnel) {
          if ($funnel->is_removal == 1) {
              $stat = 0;
          }
          elseif ($funnel->in_blacklist == 1) {
              $stat = -1;
          }
          else {
              $stat = 1;
          }
      ?>
      <tr id="clients-funnel-item-<?= $funnel->funnel_id ?>">
        <td><?= $funnel->name ?></td>
        <td class="updated_at"><?= $funnel->updated_at ?></td>
        <td class="actto"><?= $statuses[$stat] ?><?= $stat == 1 ? ' <a class="clients-unsubscribe-contact" title="Отписаться" href="#" onclick="unsubscribeContact('.$funnel->funnel_id.', \''.$funnel->name.'\'); return false;">x</a>' : '' ?></td>
      </tr>
      <?
      }  
    ?>
    </table>
    <? } else { ?>
    <table style="width: 100%;" id="client-funnels-list">
    </table>
    <p class="client-flist-empty" style="color: gray;">Нет связанных воронок</p>
    <? } ?>
    
    <div id="clients-clientcard-finfo"></div>
    <div style="margin: 5px 0;background: #EBEBEB;padding: 3px 5px;border: 1px solid #D6D6D6;">
      <label>Добавить в воронку:
        <select style="width: 250px;" id="clients-clientcard-fselect">
          <option value="0">-- выберите воронку --</option>
          <?
          foreach ($flist AS $fitem) {
          ?>
          <option value="<?= $fitem->id ?>"><?= $fitem->name ?></option>
          <?
          }
          ?>
        </select>
      </label>
      <button class="button" onclick="addContactToFunnel()">Добавить</button>
    </div>
    
  </fieldset>
    
  <fieldset>
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px; padding: 0 196px 0 0">Комментарии</span>
    </legend>
    <table style="width: 400px;" id="clients-comments-list">
<?
    if (count($comments) > 0) {
        foreach ($comments AS $com) {
    ?>
      <tr><td>
        <strong><?= $com->user_name ?></strong> <i><?= $com->created ?></i>
        <p style="margin: 5px 0 15px;"><?= $com->content ?></p>
      </td></tr>
    <?
        }
    }
?>
    </table>
    <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard_comments&cid=<?= $id ?>">показать все комментарии</a>
    <button id="clients-comment-toggle" onclick="displayCommentForm();" style="float: right;" class="button button-small button-primary">Добавить комментарий</button>
    <table id="clients-comment-buttons" style="float: right; display: none;"><tr>
       <td><button onclick="sendCommentForm();" style="float: right;" class="button button-small button-primary">Отправить</button></td>
       <td><a href="#" onclick="hideCommentForm(); return false;">отмена</a></td>
    </tr></table>
    
    <div id="clients-comment-form" style="margin: 10px 0 5px; display: none;">
      <strong><?= wp_get_current_user()->display_name ?></strong>
      <textarea cols="" rows="" style="height: 100px; width: 100%;" placeholder="Введите текст комментария"></textarea>
    </div>
    
  </fieldset>
  
  </div>
</div>

<script>

function displayCommentForm(){
    $('#clients-comment-form textarea').css('border-color', '');
    $('#clients-comment-toggle').hide();
    $('#clients-comment-buttons').show();
    $('#clients-comment-form').slideDown(100);
}

function hideCommentForm(){
    $('#clients-comment-toggle').show();
    $('#clients-comment-buttons').hide();
    $('#clients-comment-form').slideUp(100);
    $('#clients-comment-form textarea').val('');
}

function sendCommentForm(){
    var comment = $('#clients-comment-form textarea').val().trim();
    $('#clients-comment-form textarea').css('border-color', '');
    if (comment == '') {
        $('#clients-comment-form textarea').css('border-color', 'red');
        $('#clients-comment-form textarea').focus();
        return;
    }
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'add_comment',
            comment: comment,
            contact_id: '<?= $contact_id ?>'
        },
        success: function(data){
            if (data.success === true) {
                $('#clients-comments-list').prepend('<tr><td><strong><?= wp_get_current_user()->display_name ?></strong> <i>'+data.result.created+'</i><p style="margin: 5px 0 15px;">'+data.result.content+'</p></td></tr>');
                
                hideCommentForm();
            }
        }
    });
}

function unsubscribeContact(funnel_id, funnel_name){
    
    if (!confirm('Клиент <?= $first_name ?> <<?= $email ?>> будет удален из воронки "'+funnel_name+'". Продолжить?')) {
        return;
    }
    
    if (funnel_id < 1) {
        return;
    }
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'unsubscribe_contact',
            funnel_id: funnel_id,
            contact_id: '<?= $contact_id ?>'
        },
        success: function(data){
            if (data.success === true) {
                $('#clients-funnel-item-' + funnel_id + ' .updated_at').html(data.result.removal_at);
                $('#clients-funnel-item-' + funnel_id + ' .actto').html('отписан');
            }
        }
    });
}

function addContactToFunnel(){
    var funnel_id   = $('#clients-clientcard-fselect').val();
    var funnel_name = $('#clients-clientcard-fselect option[value="'+funnel_id+'"]').text();
    
    if (funnel_id < 1) {
        alert('Не выбрана воронка для добавления.');
        return;
    }
    
    $('#clients-clientcard-finfo').html('<p style="color:gray"><i>выполняется операция...</i></p>');
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'add_contact_to_funnel',
            funnel_id: funnel_id,
            contact_id: '<?= $contact_id ?>'
        },
        success: function(data){
            $('#clients-clientcard-fselect').val(0);
            $('.client-flist-empty').remove();
            if (data.success === true) {
                $('#clients-clientcard-finfo').html('');
                if (data.result.insert == 1) {
                    $('#client-funnels-list').append('<tr id="clients-funnel-item-'+funnel_id+'"><td>'+funnel_name+'</td><td class="updated_at">'+data.result.updated_at+'</td><td class="actto">активен <a class="clients-unsubscribe-contact" title="Отписаться" href="#" onclick="unsubscribeContact('+funnel_id+', \''+funnel_name+'\'); return false;">x</a></td></tr>');
                } else {
                    $('#client-funnels-list #clients-funnel-item-' + funnel_id + ' .actto').html('активен <a class="clients-unsubscribe-contact" title="Отписаться" href="#" onclick="unsubscribeContact('+funnel_id+', \''+funnel_name+'\'); return false;">x</a>');
                    $('#client-funnels-list #clients-funnel-item-' + funnel_id + ' .updated_at').html(data.result.updated_at);
                }
            } else {
                $('#clients-clientcard-finfo').html('<p style="color:red">'+data.result+'</p>');
            }
        }
    });
}

function createNewField(el){
    
    var uniqueId = getUniqueId();
    
    $(el).parents('tr').before('<tr><td class="td-title"><input placeholder="Имя нового поля" style="width: 160px;" name="cost_fields['+uniqueId+'][key]" class="edit-view" type="text"></td><td><input placeholder="Значение нового поля" name="cost_fields['+uniqueId+'][value]" value="" class="edit-view" type="text"></td></tr>');
}

function saveContact(){
    $('#clients-editable-form').ajaxSubmit({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'savecontact'
        },
        beforeSubmit: function(formData, jqForm, options){
            //
        },
        success: function(data){
            if (data.success === true) {
                window.location = window.location;
            } else {
                $('.info') = data.result 
            }
        }
    });
}

function editContact(el){
    $('#clients-editable-fields .pre-view').toggleClass('hidden');
    $('#clients-editable-fields .edit-view').toggleClass('hidden');
    
    if ($('#clients-editable-fields').hasClass('on-edit')) {
        $('#clients-editable-fields').removeClass('on-edit');
        $('#clients-editable-fields tr.hidden').hide();
        $('.clients-editable-act').hide();
        $('.clients-editable-act-remove').show();
        $(el).html('изменить');        
    } else {
        $('#clients-editable-fields').addClass('on-edit');
        $('#clients-editable-fields tr.hidden').show();
        $('.clients-editable-act').show();
        $('.clients-editable-act-remove').hide();
        $(el).html('отмена');
    }
    
}

$(document).ready(function(){
    $('.datepicker').datepicker({
        dateFormat: 'dd.mm.yy'
    });
});

</script>