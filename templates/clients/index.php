<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients" class="nav-tab nav-tab-active">Поиск контактов</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=filterlist" class="nav-tab">Список фильтров</a>
</h2>

<div class="tab-container">
  <label>Фильтры: 
    <select style="width: 200px;">
      <option value=""></option>
      <option value="">Парвый</option>
      <option value="">Второй</option>
    </select>
    <input type="submit" class="button action" value="Загрузить фильтр">
  </label>
  
  <div style="padding: 0; border-top: 1px solid #C9C9C9; margin-top: 20px;">
  <form action="/wp-admin/admin.php?page=vspostman-clients&act=filtersave" method="POST">
    
    <input type="hidden" name="id" value="">
    
    <div class="filter-items"></div>
  
  </form>
  </div>

</div>


<script>

$(document).ready(function(){
    addConditionsGroup(); 
});

function addConditionsGroup(){
    $.tmpl( $('#tpl-filter-item').html().trim(), {} ).appendTo('.filter-items');
}

function removeConditionsGroup(el){
    $(el).parent('.filter-item').remove();
}

</script>


<script id="tpl-filter-item" type="text/x-jquery-tmpl">
    <div class="filter-item">
  
        <table class="filter-params">
          <tr>
            <td style="width: 80px;">Контакты:</td>
            <td>
            <select style="width: 200px;">
              <option value="">Все</option>
              <option value="">Получающие рассылку</option>
              <option value="">Не получающие рассылку</option>
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
                <li><label><input type="checkbox" value="<?= $funnel->id ?>"> - <?= $funnel->name ?></label></li>  
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
            <select type="text" name="basicTermsRange_3_1" id="basicTermsRange_3_1" class="selWithOtherEl basicTermsRange" onfocus="javascript:$(this).parents('.fieldLine').find('input:radio').attr(&quot;checked&quot;,true);">
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
              <input type="text" style="width: 68px;"> - 
              <input type="text" style="width: 68px;">
            </td>
          </tr>
        </table>
  
  
        <div>
        
        </div>
        
        <a class="button" href="#" onclick="addConditionsGroup(); return false;">Добавить группу условий</a>
        <a class="remove-button" href="#" onclick="removeConditionsGroup(this); return false;">удалить</a>
  
    </div>
</script>