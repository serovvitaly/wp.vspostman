<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients" class="nav-tab nav-tab-active">Поиск контактов</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=filterlist" class="nav-tab">Список фильтров</a>
</h2>

<div class="tab-container">
  <form id="filter-form" action="/wp-content/plugins/vspostman/ajax.php" method="POST">
  
    <input type="hidden" name="controller" value="clients">
    <input type="hidden" name="act" value="filtersave">
    <input type="hidden" name="id" value="0">
  
  <label>Фильтры: 
    <select id="clients-filters-list" style="width: 200px;">
      <option value="0"></option>
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
  <a href="#" class="button" onclick="loadFilter(); return false;">Загрузить фильтр</a>
  

  <div style="float: right;">
      <a href="#" class="button button-primary" onclick="addNewFilter(); return false;">Добавить новый фильтр</a>
  
      <label style="padding-left: 30px;">Сахранить фильтр как:
        <input name="filter_name" type="text" style="width: 200px;">
        
      </label>
      <input type="submit" class="button" value="Сохранить">
      
  </div>
  
  <div style="padding: 0; border-top: 1px solid #C9C9C9; margin-top: 20px;">
    
    <div class="filter-items"></div>
  
  
  <div style="text-align: center; margin: 20px 0;">
    <a style="padding: 0 20px" class="button button-primary button-large" href="#" onclick="goSearch(); return false;">ПОИСК</a>
  </div>
  </div>
  </form>
  
  <div id="clients-search-result" style="display: none;">
    <div style="text-align: right;">
      <a href="#" onclick="backToSearch(); return false;">Вернуться к поиску</a>
    </div>
    <table class="wp-list-table widefat" cellspacing="0" style="margin-top: 20px;">
    
    <thead>
      <tr>
        <th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th>
        <th scope="col" class="manage-column column-name" style="width: 300px;">Имя</th>
        <th scope="col" class="manage-column column-name" style="width: 300px;">Email</th>
        <th scope="col" class="manage-column column-name" style="width: 150px;">Добавлен</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th scope="col" class="manage-column column-cb check-column" style=""><input id="cb-select-all-1" type="checkbox"></th>
        <th scope="col" class="manage-column column-name">Имя</th>
        <th scope="col" class="manage-column column-name">Email</th>
        <th scope="col" class="manage-column column-name">Добавлен</th>
      </tr>
    </tfoot>
    
      <tbody></tbody>
    </table>
  </div>  
  
</div>






<script>

function backToSearch(){
    $('#clients-search-result').slideUp(null, function(){
        $('#clients-search-result table tbody').html('');
    });
    
    $('#filter-form').slideDown();
}

function loadFilter(fid){
    
    if (fid && fid > 0) {
        $('#clients-filters-list').val(fid);
    }
    
    var filter_id = $('#clients-filters-list').val();
    
    if (filter_id < 1) return false;
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'loadfilter',
            filter_id: filter_id
        },
        success: function(data){
            if (data.success === true) {
                $('.filter-items').html('');
                $('#filter-form input[name="id"]').val(data.result.id);
                $('#filter-form input[name="filter_name"]').val(data.result.name);
                $.each(data.result.data, function(index, item){
                    addConditionsGroup({uid: index, mix: item});                    
                });
            }
        }
    });
}

function addNewFilter(){
    $('.filter-items').html('');
    $('#clients-filters-list').val(0);
    $('#filter-form input[name="id"]').val(0);
    $('#filter-form input[name="filter_name"]').val('');
    addConditionsGroup();
}

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

function addConditionsGroup(data){
    var mix = $.extend({
        uid: getUniqueId(),
        mix: {
            dates_range: 'custom'
        }
    }, data);
    var tpl = $.tmpl( $('#tpl-filter-item').html().trim(), mix );
    tpl.appendTo('.filter-items');
    tpl.find('.datepicker').datepicker({
        dateFormat: 'dd.mm.yy'
    });
    tpl.slideDown(200);
    if (mix.mix && mix.mix.fields) {
        $.each(data.mix.fields, function(index, item){
            mix.fid = index;
            mix.mix = item;
            addConditionsGroupField(null, tpl, mix);
        });
    } else {
        addConditionsGroupField(null, tpl, mix);
    }
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

function addConditionsGroupField(el, parent, data){
    var mix = $.extend({
        fid: getUniqueId()
    }, data);
    var tpl = $.tmpl( $('#tpl-filter-item-field').html().trim(), mix );    
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
    $('#filter-form input[name="act"]').val('search');
    $('#filter-form').ajaxSubmit({
        dataType: 'json',
        beforeSubmit: function(formData, jqForm, options){
            $('#clients-search-result table tbody').html('');
        },
        success: function(data, statusText, xhr, $form){
            
            $('#filter-form').slideUp();
            
            if (data.success === true) {
                if (data.result && data.result.length > 0) {
                    for(var i = 0; i < data.result.length; i++){
                        $.tmpl( $('#tpl-result-item').html().trim(), data.result[i] ).appendTo('#clients-search-result table tbody');
                    }
                }
            }
            
            $('#clients-search-result').slideDown();
        }
    });
}

$(document).ready(function(){
    
    <? if ($current_filter > 0) { ?>loadFilter(<?= $current_filter ?>);<? } else { ?>addConditionsGroup();<? } ?>
    
    $('#filter-form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function(formData, jqForm, options){
            $('#filter-form input[name="act"]').val('filtersave');
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
        success: function(data, statusText, xhr, $form){
            if (data.success === true) {
                window.location = '/wp-admin/admin.php?page=vspostman-clients&act=filterlist';
            }
        }
    });
     
});
</script>


<script id="tpl-filter-item" type="text/x-jquery-tmpl">
    <div id="filter-item-${uid}" class="filter-item">
  
        <table class="filter-params">
          <tr>
            <td style="width: 80px;">Контакты:</td>
            <td>
            <select name="contacts_type[${uid}]" style="width: 200px;">
              <option{{if mix.contacts_type == 'all'}} selected="selected"{{/if}} value="all">Все</option>
              <option{{if mix.contacts_type == 'rec'}} selected="selected"{{/if}} value="rec">Получающие рассылку</option>
              <option{{if mix.contacts_type == 'no_rec'}} selected="selected"{{/if}} value="no_rec">Не получающие рассылку</option>
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
                <li><label><input{{if mix.funnels && mix.funnels[<?= $funnel->id ?>] && mix.funnels[<?= $funnel->id ?>] == 1}} checked="checked"{{/if}} type="checkbox" name="funnels[${uid}][<?= $funnel->id ?>]" value="1"> - <?= $funnel->name ?></label></li>  
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
              <option{{if mix.dates_range == 'today'}} selected="selected"{{/if}} value="today">Сегодня</option>
              <option{{if mix.dates_range == 'yesterday'}} selected="selected"{{/if}} value="yesterday">Вчера</option>
              <option{{if mix.dates_range == 'this_week'}} selected="selected"{{/if}} value="this_week">На этой неделе</option>
              <option{{if mix.dates_range == 'last_week'}} selected="selected"{{/if}} value="last_week">На прошлой неделе</option>
              <option{{if mix.dates_range == 'last_7_days'}} selected="selected"{{/if}} value="last_7_days">Последние 7 дней</option>
              <option{{if mix.dates_range == 'last_30_days'}} selected="selected"{{/if}} value="last_30_days">Последние 30 дней</option>
              <option{{if mix.dates_range == 'this_month'}} selected="selected"{{/if}} value="this_month">В этом месяце</option>
              <option{{if mix.dates_range == 'last_month'}} selected="selected"{{/if}} value="last_month">В прошлом месяце</option>
              <option{{if mix.dates_range == 'last_2_months'}} selected="selected"{{/if}} value="last_2_months">Последние 2 месяца</option>
              <option{{if mix.dates_range == 'all_time'}} selected="selected"{{/if}} value="all_time">Все время</option>
              <option{{if mix.dates_range == 'custom'}} selected="selected"{{/if}} value="custom">Свой диапазон</option>
            </select>       
            </td>
          </tr>
          <tr>
            <td>Свой диапазон дат:</td>
            <td>
              <input class="datepicker" name="date_start[${uid}]"{{if mix.dates_range == 'custom'}} value="${mix.date_start}"{{/if}} type="text" style="width: 70px;"> - 
              <input class="datepicker" name="date_end[${uid}]"{{if mix.dates_range == 'custom'}} value="${mix.date_end}"{{/if}} type="text" style="width: 70px;">
            </td>
          </tr>
        </table>
         
        <table class="filter-params">
          <tr>
            <td style="width: 130px;">Соответствует</td>
            <td>
            <select name="match[${uid}]">
              <option{{if mix.match == 'and'}} selected="selected"{{/if}} value="and">Всем</option>
              <option{{if mix.match == 'or'}} selected="selected"{{/if}} value="or">Одному из</option>
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
    <select name="fields[${uid}][${fid}][name]">
      <option{{if mix.name == 'first_name'}} selected="selected"{{/if}} value="first_name">ФИО</option>
      <option{{if mix.name == 'email'}} selected="selected"{{/if}} value="email">Email</option>
      <option{{if mix.name == 'country'}} selected="selected"{{/if}} value="country">Страна</option>
      <option{{if mix.name == 'city'}} selected="selected"{{/if}} value="city">Город</option>
      <option{{if mix.name == 'address'}} selected="selected"{{/if}} value="address">Адрес доставки</option>
      <option{{if mix.name == 'phone'}} selected="selected"{{/if}} value="phone">Телефон</option>
      <option{{if mix.name == 'skype'}} selected="selected"{{/if}} value="skype">Skype</option>
      <option{{if mix.name == 'icq'}} selected="selected"{{/if}} value="icq">ICQ</option>
      <option{{if mix.name == 'facebook'}} selected="selected"{{/if}} value="facebook">Facebook</option>
      <option{{if mix.name == 'vk'}} selected="selected"{{/if}} value="vk">Вконтакте</option>
      <option{{if mix.name == 'google'}} selected="selected"{{/if}} value="google">Google+</option>
      <option{{if mix.name == 'web'}} selected="selected"{{/if}} value="web">Веб-сайт</option>
      <option{{if mix.name == 'birthdate'}} selected="selected"{{/if}} value="birthdate">Дата рождения</option>
      <option{{if mix.name == 'information'}} selected="selected"{{/if}} value="information">Доп. информация</option>
    </select>
  </td>
  <td>
    <select name="fields[${uid}][${fid}][exp]">
      <option{{if mix.exp == 'eq'}} selected="selected"{{/if}} value="eq" selected="selected">равно</option>
      <option{{if mix.exp == 'not_eq'}} selected="selected"{{/if}} value="not_eq">НЕ равно</option>
      <option{{if mix.exp == 'co'}} selected="selected"{{/if}} value="co">содержит</option>
      <option{{if mix.exp == 'not_co'}} selected="selected"{{/if}} value="not_co">НЕ содержит</option>
      <option{{if mix.exp == 'start'}} selected="selected"{{/if}} value="start">начинается с</option>
      <option{{if mix.exp == 'end'}} selected="selected"{{/if}} value="end">заканчивается на</option>
      <option{{if mix.exp == 'not_start'}} selected="selected"{{/if}} value="not_start">НЕ начинается с</option>
      <option{{if mix.exp == 'not_end'}} selected="selected"{{/if}} value="not_end">НЕ заканчивается на</option>
    </select>
  </td>
  <td>
    <input name="fields[${uid}][${fid}][value]" type="text" style="width:140px" value="${mix.value}">
  </td>
  <td>
    <a class="button button-small button-primary" href="#" onclick="addConditionsGroupField(this, null, {uid:${uid}}); return false;">+</a>
    <a class="button button-small field-remove-button" href="#" onclick="removeConditionsGroupField(this); return false;">-</a>
  </td>
</tr>
</script>

<script id="tpl-result-item" type="text/x-jquery-tmpl">
<tr class="result-item">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="${id}"></th>
  <td><a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=${id}">${first_name}</a></td>
  <td><a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=${id}">${email}</a></td>
  <td>${created}</td>
</tr>
</script>