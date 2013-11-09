<div style="margin: 20px 0;">
  <? if (!$field_edit) { ?><button id="clients-custom-fields-button" class="button" onclick="showFieldEditForm()">Добавить настраиваемое поле</button><? } ?>
</div>

<div id="clients-custom-fields" <?= $field_edit ? '' : ' style="display: none;"' ?>>
  <input type="hidden" name="fid" value="<?= $field_edit->id ?>">
  <table>
    <tr>
      <td>Наименование поля:</td>
      <td><input type="text" style="width: 170px;" name="field_label" value="<?= $field_edit->field_label ?>"></td>
    </tr>
    <!--tr>
      <td>Уникальное имя поля:</td>
      <td><input type="text" style="width: 170px;" name="field_name">
        <i style="color:gray">Введите уникальное имя поля, допускаются только: большие и маленькие латинские буквы, цифры и подчеркивания.</i>
      </td>
    </tr-->
    <tr>
      <td>Тип поля:</td>
      <td>
        <select name="field_type" onchange="ChangeFormElements(this.value)" style="width: 170px;">
          <option<?= $field_edit->field_type == '' ? ' selected="selected"' : '' ?> value="">-- выберите --</option>
          <option<?= $field_edit->field_type == 'text' ? ' selected="selected"' : '' ?> value="text">текст</option>
          <option<?= $field_edit->field_type == 'number' ? ' selected="selected"' : '' ?> value="number">число</option>
          <option<?= $field_edit->field_type == 'date' ? ' selected="selected"' : '' ?> value="date">дата</option>
          <!--option value="phone">телефон</option-->
          <option<?= $field_edit->field_type == 'textarea' ? ' selected="selected"' : '' ?> value="textarea">многострочный текст</option>
          <option<?= $field_edit->field_type == 'radio' ? ' selected="selected"' : '' ?> value="radio">переключатель</option>
          <option<?= $field_edit->field_type == 'checkbox' ? ' selected="selected"' : '' ?> value="checkbox">флажок</option>
          <option<?= $field_edit->field_type == 'single_select' ? ' selected="selected"' : '' ?> value="single_select">выпадающий список</option>
          <option<?= $field_edit->field_type == 'multi_select' ? ' selected="selected"' : '' ?> value="multi_select">множественный выбор</option>
        </select>
      </td>
    </tr>
    <?
    if ($field_edit AND $field_edit->field_value) {
        $values = json_decode($field_edit->field_value);
        if (count($values) > 0) {
            echo '<tr class="custom-field"><td>Значения:</td><td class="custom-field-wrapper">';
            foreach ($values AS $value) {
            ?>
            <div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;" value="<?= $value ?>"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>
            <?
            }
            echo '</td></tr>';
        }
    }
    ?>
  </table>
  <button class="button button-primary" onclick="saveCustomField()">Сохранить</button> <a onclick="hideFieldEditForm(); return false;" href="#" style="padding: 0 0 3px 10px;">отмена</a>
</div>

<? if ($custom_fields AND count($custom_fields) > 0) { ?>
<table class="wp-list-table widefat" style="margin-top: 20px; width: 500px;">
  <?
  
  $types = array(
      'text'          => 'текст',
      'number'        => 'число',
      'date'          => 'дата',
      'phone'         => 'телефон',
      'textarea'      => 'многострочный текст',
      'radio'         => 'переключатель',
      'checkbox'      => 'флажок',
      'single_select' => 'выпадающий список',
      'multi_select'  => 'множественный выбор',
  ); 
  
  foreach ($custom_fields AS $fields) {                
  ?>
  <tr>
    <td><?= $fields->field_label ?></td>
    <td><?= $types[$fields->field_type] ?></td>
    <td style="width: 60px;">
      <a href="/wp-admin/admin.php?page=vspostman-clients&act=custom_fields&edit=<?= $fields->id ?>" title="Редактировать">ред.</a> | 
      <a onclick="return confirm('Вместа с полем будут удалены все связанные с ним данные клиентов. Продолжить?')" href="/wp-admin/admin.php?page=vspostman-clients&act=custom_fields&remove=<?= $fields->id ?>" title="Удалить">уд.</a>
    </td>
  </tr>
  <? } ?>
</table>
<? } else { ?>
<p><i style="color: gray;">Список пуст</i></p>
<? } ?>

<script>
function showFieldEditForm(){
    $('#clients-custom-fields').slideDown(100);
    $('#clients-custom-fields-button').hide();
}
function hideFieldEditForm(){
    $('#clients-custom-fields').slideUp(100);
    $('#clients-custom-fields-button').show();
    $('#clients-custom-fields [name="fid"]').val('');
    $('#clients-custom-fields [name="field_label"]').val('');
    $('#clients-custom-fields [name="field_type"]').val('');
    $('#clients-custom-fields .custom-field').remove();
}
function saveCustomField(){
    
    $('#clients-custom-fields [name="field_label"]').css('border-color', '');
    //$('#clients-custom-fields [name="field_name"]').css('border-color', '');
    $('#clients-custom-fields [name="field_type"]').css('border-color', '');
    
    var field_label   = $('#clients-custom-fields [name="field_label"]').val().trim();
    //var field_name    = $('#clients-custom-fields [name="field_name"]').val().trim();
    var field_type    = $('#clients-custom-fields [name="field_type"]').val().trim();
    var field_values  = $('#clients-custom-fields [name="field_value[]"]');
    
    var values = [], error = false;
    
    if (field_label == '') {
        $('#clients-custom-fields [name="field_label"]').css('border-color', 'red');
        error = true;
    }
    
    if (field_type == '') {
        $('#clients-custom-fields [name="field_type"]').css('border-color', 'red');
        error = true;
    }
    
    if (field_values.length > 0) {
        for (var i = 0; i < field_values.length; i++) {
            values.push(field_values[i].value.trim());
        }
    } else {
        //error = true;
    }
    
    if (error === false) {
        $.ajax({
            url: '/wp-content/plugins/vspostman/ajax.php',
            dataType: 'json',
            type: 'POST',
            data: {
                controller: 'clients',
                act: 'save_custom_field',
                fid: $('#clients-custom-fields [name="fid"]').val(),
                field_label: field_label,
                field_type: field_type,
                field_value: values,
            },
            success: function(data){
                window.location = '/wp-admin/admin.php?page=vspostman-clients&act=custom_fields';
            }
        });
    }
    
}
function addCustomField(){
    $('#clients-custom-fields .custom-field-wrapper').append('<div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>');
}
function removeCustomField(el){
    if ($('#clients-custom-fields .custom-field-wrapper .custom-field-item').length < 2) {
        return;
    }
    $(el).parents('.custom-field-item').remove();
}
function ChangeFormElements(value){
    $('#clients-custom-fields .custom-field').remove();
    if (value == '') return false;
    
    var element = '';
    switch (value) {
        case 'date' :
            //element = '<input name="field_value[]" class="field-date" type="text" style="width: 90px;">';
            element = null;
            break;
        case 'textarea' :
            //element = '<textarea name="field_value[]" style="width: 300px; height: 100px"></textarea>';
            element = null;
            break;
        case 'radio' :
            element = '<div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'checkbox' :
            element = '<div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'single_select' :
            element = '<div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'multi_select' :
            element = '<div class="custom-field-item"><input name="field_value[]" type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
            
        default:
            //element = '<input name="field_value[]" type="text" style="width: 170px;">';
            element = null;
    }
    
    if (element !== null) {
        $('#clients-custom-fields table').append('<tr class="custom-field"><td>Значения:</td><td class="custom-field-wrapper">'+element+'</td></tr>');
    }
    /*
    $('.field-date').datepicker({
        dateFormat: 'dd.mm.yy'
    }); */
}


$(document).ready(function(){
    //$('#clients-custom-fields [name="field_type"]').val('<?= $field_edit->field_type ?>');
    //ChangeFormElements('<?= $field_edit->field_type ?>');
});

</script>