<div style="margin: 20px 0;">
  <button class="button">Добавить настраиваемое поле</button>
</div>

<div>
  <table id="clients-custom-fields">
    <tr>
      <td>Наименование поля:</td>
      <td><input type="text" style="width: 170px;"></td>
    </tr>
    <tr>
      <td>Уникальное имя поля:</td>
      <td><input type="text" style="width: 170px;">
        <i style="color:gray">Введите уникальное имя поля, допускаются только: большие и маленькие латинские буквы, цифры и подчеркивания.</i>
      </td>
    </tr>
    <tr>
      <td>Тип поля:</td>
      <td>
        <select name="" onchange="ChangeFormElements(this.value)" style="width: 170px;">
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
  <button class="button button-primary">Сохранить</button>
</div>

<script>
function addCustomField(){
    $('#clients-custom-fields .custom-field-wrapper').append('<div class="custom-field-item"><input type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>');
}
function removeCustomField(el){
    if ($('#clients-custom-fields .custom-field-wrapper .custom-field-item').length < 2) {
        return;
    }
    $(el).parents('.custom-field-item').remove();
}
function ChangeFormElements(value){
    var element = '';
    switch (value) {
        case 'date' :
            element = '<input class="field-date" type="text" style="width: 90px;">';
            break;
        case 'textarea' :
            element = '<textarea style="width: 300px; height: 100px"></textarea>';
            break;
        case 'radio' :
            element = '<div class="custom-field-item"><input type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'checkbox' :
            element = '<div class="custom-field-item"><input type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'single_select' :
            element = '<div class="custom-field-item"><input type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
        case 'multi_select' :
            element = '<div class="custom-field-item"><input type="text" style="width: 170px;"> <a href="#" onclick="addCustomField(); return false;">[+]</a> <a href="#" onclick="removeCustomField(this); return false;">[-]</a></div>';
            break;
            
        default:
            element = '<input type="text" style="width: 170px;">';
    }
    
    $('#clients-custom-fields .custom-field').remove();
    $('#clients-custom-fields').append('<tr class="custom-field"><td>Значение:</td><td class="custom-field-wrapper">'+element+'</td></tr>');
    $('.field-date').datepicker({
        dateFormat: 'dd.mm.yy'
    });
}
</script>