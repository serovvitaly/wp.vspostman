<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients" class="nav-tab nav-tab-active">Поиск контактов</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=filterlist" class="nav-tab">Список фильтров</a>
</h2>

<div class="tab-container">
  <form id="filter-form" action="/wp-content/plugins/vspostman/ajax.php" method="POST">
  
  <label>Фильтры: 
    <select style="width: 200px;">
      <option value=""></option>
      <?
        if (count($filters) > 0) {
            foreach ($filters AS $filter) {
        ?>
        <option value="<?= $filter->id ?>"><?= $filter->name ?></option>
        <?
            }
        }
      ?>
    </select>
  </label>
  <a href="#" class="button" onclick="return false;">Загрузить фильтр</a>
  

  <div style="float: right;">
      <input type="submit" class="button button-primary" value="Добавить новый фильтр">
  
      <label style="padding-left: 30px;">Сахранить фильтр как:
        <input name="filter_name" type="text" style="width: 200px;">
        
      </label>
      <input type="submit" class="button" value="Сохранить">
      
  </div>
  
  <div style="padding: 0; border-top: 1px solid #C9C9C9; margin-top: 20px;">
  
    
    <input type="hidden" name="controller" value="clients">
    <input type="hidden" name="act" value="filtersave">
    <input type="hidden" name="id" value="0">
    
    <div class="filter-items"></div>
  
  
  <div style="text-align: center; margin: 20px 0;">
    <a style="padding: 0 20px" class="button button-primary button-large" href="#" onclick="goSearch(); return false;">ПОИСК</a>
  </div>
  </div>
  </form>
</div>


<script>

$(document).ready(function(){
    addConditionsGroup();
    
    $('#filter-form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function(formData, jqForm, options){
            for (var i = 0; i < formData.length; i++) {
                var field = formData[i];
                switch (field.name) {
                    case 'filter_name':
                        if (field.value.replace(' ','') == '') {
                            jqForm.find('input[name="filter_name"]').css('border-color', 'red');
                            return false;
                        }
                        break;
                }
            }
        },
        success: function(data){
            console.log(data);
            if (data.success === true) {
                window.location = '/wp-admin/admin.php?page=vspostman-clients&act=filterlist';
            }
        }
    });
     
});

function getUniqueId(){
    var d = new Date();
    return d.valueOf() + '' + d.getUTCMilliseconds();
}

function checkConditionsGroupRemover(){
    var items = $('.filter-item');
    if (items.length > 1) {
        items.find('.remove-button').show();
        items.find('.conditions-list').show();
    } else {
        items.find('.remove-button').fadeOut(200);
        items.find('.conditions-list').fadeOut(200);
    }
}

function addConditionsGroup(){
    var tpl = $.tmpl( $('#tpl-filter-item').html().trim(), {uid: getUniqueId()} );
    tpl.appendTo('.filter-items');
    tpl.slideDown(200);
    addConditionsGroupField(null, tpl);
    checkConditionsGroupRemover(null, tpl);
}

function removeConditionsGroup(el){
    var tpl = $(el).parent('.filter-item');
    tpl.slideUp(200, function(){
        tpl.remove();
        checkConditionsGroupRemover(null, tpl);
    });
}

function checkConditionsGroupFieldRemover(el, parent){
    var fieldsCnt = parent ? parent.find('.filter-item-fields') : $(el).parent('.filter-item-fields');
    var items = fieldsCnt.find('.filter-item-field');
    if (items.length > 1) {
        items.find('.field-remove-button').show();
    } else {
        items.find('.field-remove-button').fadeOut(200);
    }
}

function addConditionsGroupField(el, parent){
    var tpl = $.tmpl( $('#tpl-filter-item-field').html().trim(), {} );    
    var fieldsCnt = parent ? parent.find('.filter-item-fields') : $(el).parents('.filter-item-fields');
    fieldsCnt.append(tpl);
    checkConditionsGroupFieldRemover(el, parent);
}

function removeConditionsGroupField(el){
    var tpl = $(el).parents('.filter-item-field');
    tpl.fadeOut(200, function(){
        tpl.remove();
        checkConditionsGroupFieldRemover(el);
    });
}

function getFilterData(){
    var data = $('#filter-form').serializeArray();
    
    return data;
}


function goSearch(){
    getFilterData();
}

</script>


<script id="tpl-filter-item" type="text/x-jquery-tmpl">
    <div data-uid="${uid}" class="filter-item">
  
        <table class="filter-params">
          <tr>
            <td style="width: 80px;">Контакты:</td>
            <td>
            <select name="contacts_type[${uid}]" style="width: 200px;">
              <option value="all">Все</option>
              <option value="rec">Получающие рассылку</option>
              <option value="no_rec">Не получающие рассылку</option>
            </select>        
            </td>
          </tr>
          <tr>
            <td>Воронки:</td>
            <td>
              <ul style="margin: 0;">
              <?
                  if (count($funnels_list) > 0) {
                      foreach ($funnels_list AS $funnel) {
                  ?>
                <li><label><input type="checkbox" value="funnels[${uid}][<?= $funnel->id ?>]"> - <?= $funnel->name ?></label></li>  
                  <?
                      }
                  }
              ?>
              </ul>
            </td>
          </tr>
        </table> 
         
        <table class="filter-params">
          <tr>
            <td style="width: 130px;">Диапазон дат:</td>
            <td>
            <select type="text" name="dates_range[${uid}]" id="basicTermsRange_3_1" class="selWithOtherEl basicTermsRange" onfocus="javascript:$(this).parents('.fieldLine').find('input:radio').attr(&quot;checked&quot;,true);">
              <option value="today">Сегодня</option>
              <option value="yesterday">Вчера</option>
              <option value="this_week">На этой неделе</option>
              <option value="last_week">На прошлой неделе</option>
              <option value="last_7_days">Последние 7 дней</option>
              <option value="last_30_days">Последние 30 дней</option>
              <option value="this_month">В этом месяце</option>
              <option value="last_month">В прошлом месяце</option>
              <option value="last_2_months">Последние 2 месяца</option>
              <option value="all_time" selected="selected">Все время</option>
            </select>       
            </td>
          </tr>
          <tr>
            <td>Свой диапазон дат:</td>
            <td>
              <input name="date_start[${uid}]" type="text" style="width: 68px;"> - 
              <input name="date_end[${uid}]" type="text" style="width: 68px;">
            </td>
          </tr>
        </table>
         
        <table class="filter-params">
          <tr>
            <td style="width: 130px;">Соответствует</td>
            <td>
            <select value="match[${uid}]">
              <option value="and">Всем</option>
              <option value="or">Одному из</option>
            </select>
            из следующих:       
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <table class="filter-item-fields">
              
              </table>
            </td>
          </tr>
        </table>
  
  
        <div>
        
        </div>
        
        <a class="button" href="#" onclick="addConditionsGroup(); return false;">Добавить группу условий</a>
        <a class="remove-button" href="#" onclick="removeConditionsGroup(this); return false;">удалить</a>
  
        <label class="conditions-list" style="padding-left:200px">Выберите ИЛИ, И между оператором "Условия Групп":
          <select>
            <option value="and">И</option>
            <option value="or">ИЛИ</option>
          </select>
        </label>
        
    </div>
</script>

<script id="tpl-filter-item-field" type="text/x-jquery-tmpl">
<tr class="filter-item-field">
  <td>
    <select>
      <option value="name" selected="selected">Name</option>
      <option value="email">Email</option>
      <option value="geo">Geolocation</option>
      <option value="custom">Custom Field</option>
      <option value="goal">Goals</option>
      <option value="created_on">Subscription Date</option>
      <option value="origin">Subscription Method</option>
      <option value="last_followup">Last Autoresponder Date</option>
      <option value="last_broadcast">Last Newsletter Date</option>
      <option value="last_open">Last Open Date</option>
      <option value="last_click">Last Click Date</option>
      <option value="message_open">Message Opened</option>
      <option value="message_not_open">Message Not Opened</option>
      <option value="link_clicked">Link Clicked</option>
      <option value="link_not_clicked">Link Not Clicked</option>
    </select>
  </td>
  <td>
    <select>
      <option value="eq" selected="selected">is</option>
      <option value="not_eq">is not</option>
      <option value="co">contains</option>
      <option value="not_co">does not contain</option>
      <option value="start">start with</option>
      <option value="end">ends with</option>
      <option value="not_start">does not start with</option>
      <option value="not_end">does not end with</option>
    </select>
  </td>
  <td>
    <input type="text" style="width:140px">
  </td>
  <td>
    <a class="button button-small button-primary" href="#" onclick="addConditionsGroupField(this); return false;">+</a>
    <a class="button button-small field-remove-button" href="#" onclick="removeConditionsGroupField(this); return false;">-</a>
  </td>
</tr>
</script>