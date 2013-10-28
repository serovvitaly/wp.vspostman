<div style="margin: 20px 0;">
  <button class="button">Добавить настраиваемое поле</button>
</div>

<div>
  <table id="clients-custom-fields">
    <tr>
      <td>Наименование поля:</td>
      <td><input type="text" style="width: 170px;" name="field_label"></td>
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
          <option value="">-- выберите --</option>
          <option value="text">текст</option>
          <option value="number">число</option>
          <option value="date">дата</option>
          <option value="phone">телефон</option>
          <option value="textarea">многострочный текст</option>
          <option value="radio">переключатель</option>
          <option value="checkbox">флажок</option>
          <option value="single_select">выпадающий список</option>
          <option value="multi_select">множественный выбор</option>
        </select>
      </td>
    </tr>
  </table>
  <button class="button button-primary" onclick="saveCustomField()">Сохранить</button>
</div>

<script>
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
 /*   if (field_name == '') {
        $('#clients-custom-fields [name="field_name"]').css('border-color', 'red');
        error = true;
    } */
    if (field_type == '') {
        $('#clients-custom-fields [name="field_type"]').css('border-color', 'red');
        error = true;
    }
    
    if (field_values.length > 0) {
        for (var i = 0; i < field_values.length; i++) {
            values.push(field_values[i].value.trim());
        }
    } else {
        error = true;
    }
    
    if (error === false) {
        $.ajax({
            url: '/wp-content/plugins/vspostman/ajax.php',
            dataType: 'json',
            type: 'POST',
            data: {
                controller: 'clients',
                act: 'save_custom_field',
                field_label: field_label,
                field_type: field_type,
                field_value: values,
            },
            success: function(data){
                //
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
            element = '<input name="field_value[]" class="field-date" type="text" style="width: 90px;">';
            break;
        case 'textarea' :
            element = '<textarea name="field_value[]" style="width: 300px; height: 100px"></textarea>';
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
            element = '<input name="field_value[]" type="text" style="width: 170px;">';
    }
    
    $('#clients-custom-fields').append('<tr class="custom-field"><td>Значение:</td><td class="custom-field-wrapper">'+element+'</td></tr>');
    $('.field-date').datepicker({
        dateFormat: 'dd.mm.yy'
    });
}
</script>