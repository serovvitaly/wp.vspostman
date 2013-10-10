
<style>
.pre-view{
    line-height: 25px;
    padding-left: 5px;
}
.edit-view{
    border-color: #AFC8DB !important;
}
textarea.edit-view{
    width: 210px;
    height: 110px;
}
</style>

<h2 class="nav-tab-wrapper">
  <a href="#" class="nav-tab nav-tab-active">Общие</a>
  <a href="#" class="nav-tab">Письма</a>
</h2>

<div class="tab-container">

  <div style="display: inline-block; vertical-align: top; margin-right: 20px; padding-right: 20px; border-right: 1px solid #E6E6E6; width: 400px;">

  <fieldset>
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px;">Анкета</span>
      <span style="float: right;">
        <span class="clients-editable-act hidden">
          <a href="unsubscribe?cid=<?= $id ?>" onclick="return confirm('Точно отписать?')">отписать</a> |  
          <a href="delete?cid=<?= $id ?>" onclick="return confirm('Точно удалить?')">удалить</a> | 
        </span>     
        <a href="#" onclick="editContact(this); return false;">изменить</a>
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
        <td>ФИО</td>
        <td><span class="pre-view"><?= $first_name ?></span><input name="first_name" value="<?= $first_name ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($country) OR empty($country)) ? ' class="hidden"' : '' ?>>
        <td>Страна</td>
        <td><span class="pre-view"><?= $country ?></span><input name="country" value="<?= $country ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($city) OR empty($city)) ? ' class="hidden"' : '' ?>>
        <td>Город</td>
        <td><span class="pre-view"><?= $city ?></span><input name="city" value="<?= $city ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($address) OR empty($address)) ? ' class="hidden"' : '' ?>>
        <td>Адрес доставки (полный)</td>
        <td><span class="pre-view"><?= $address ?></span><input name="address" value="<?= $address ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($phone) OR empty($phone)) ? ' class="hidden"' : '' ?>>
        <td>Телефон</td>
        <td><span class="pre-view"><?= $phone ?></span><input name="phone" value="<?= $phone ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($email) OR empty($email)) ? ' class="hidden"' : '' ?>>
        <td>Email</td>
        <td><span class="pre-view"><?= $email ?></span><input name="email" value="<?= $email ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($skype) OR empty($skype)) ? ' class="hidden"' : '' ?>>
        <td>Skype</td>
        <td><span class="pre-view"><?= $skype ?></span><input name="skype" value="<?= $skype ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($icq) OR empty($icq)) ? ' class="hidden"' : '' ?>>
        <td>ICQ</td>
        <td><span class="pre-view"><?= $icq ?></span><input name="icq" value="<?= $icq ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($facebook) OR empty($facebook)) ? ' class="hidden"' : '' ?>>
        <td>Facebook</td>
        <td><span class="pre-view"><?= $facebook ?></span><input name="facebook" value="<?= $facebook ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($vk) OR empty($vk)) ? ' class="hidden"' : '' ?>>
        <td>Вконтакте</td>
        <td><span class="pre-view"><?= $vk ?></span><input name="vk" value="<?= $vk ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($google) OR empty($google)) ? ' class="hidden"' : '' ?>>
        <td>Google+</td>
        <td><span class="pre-view"><?= $google ?></span><input name="google" value="<?= $google ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($web) OR empty($web)) ? ' class="hidden"' : '' ?>>
        <td>Веб-сайт</td>
        <td><span class="pre-view"><?= $web ?></span><input name="web" value="<?= $web ?>" class="edit-view hidden" type="text"></td>
      </tr>
      <tr<?= (!isset($birthdate) OR empty($birthdate)) ? ' class="hidden"' : '' ?>>
        <td>Дата рождения</td>
        <td><span class="pre-view"><?= $birthdate ?></span><input name="birthdate" name="" value="<?= $birthdate ?>" class="edit-view hidden datepicker" type="text"></td>
      </tr>
      <tr<?= (!isset($information) OR empty($information)) ? ' class="hidden"' : '' ?>>
        <td>Дополнительная информация</td>
        <td><span class="pre-view"><?= $information ?></span><textarea name="information" class="edit-view hidden" cols="" rows=""><?= $information ?></textarea></td>
      </tr>
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
        <td><a href="#">http://yandex.ru/market/foo-bar.html</a></td>
      </tr>
    </table>
  </fieldset>
    
  <fieldset style="margin-bottom: 20px;">
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px; padding: 0 196px 0 0">Воронки</span>
    </legend>
    <table style="width: 100%;">
      <tr>
        <td>Первая воронка</td>
        <td>12.07.2013 12:14:23</td>
        <td>активен</td>
      </tr>
      <tr>
        <td>вторая воронка</td>
        <td>12.07.2013 12:14:23</td>
        <td>отписался</td>
      </tr>
      <tr>
        <td>третья воронка</td>
        <td>12.07.2013 12:14:23</td>
        <td>купил</td>
      </tr>
    </table>
  </fieldset>
    
  <fieldset>
    <legend style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #D0D0D0; width: 100%;">
      <span style="font-size: 20px; padding: 0 196px 0 0">Комментарии</span>
    </legend>
    <table>
      <tr><td>
        <strong>Иван Драго</strong> <i>12.14.1333</i>
        <p>Туристический отдел создает и реализует групповые<br> и индивидуальные туры, ориентирован<br> на работу с туристическими агентствами</p>
      </td></tr>
      <tr><td>
        <strong>некто</strong> <i>12.14.1333</i>
        <p>Отдел кураторов осуществляет помощь в работе агентств<br> с компанией "ИнтАэр", призваны сделать нашу совместную<br> работу максимально приятной и продуктивной.<br> Решают все вопросы агентств: оплата, подтверждение туров,<br> цены, бонусы, услуги сервиса и многие другие.</p>
      </td></tr>
    </table>
    <a href="#">показать все комментарии</a>
  </fieldset>
  
  </div>
</div>

<script>

function saveContact(){
    $('#clients-editable-form').ajaxSubmit({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'savecontact'
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
        $(el).html('изменить');        
    } else {
        $('#clients-editable-fields').addClass('on-edit');
        $('#clients-editable-fields tr.hidden').show();
        $('.clients-editable-act').show();
        $(el).html('отмена');
    }
    
}

$(document).ready(function(){
    $('.datepicker').datepicker({
        dateFormat: 'dd.mm.yy'
    });
});

</script>