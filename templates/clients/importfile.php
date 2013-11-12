<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=import" class="nav-tab">Копировать & вставить</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importfile" class="nav-tab nav-tab-active">Загрузить файл</a>
  <!--a href="/wp-admin/admin.php?page=vspostman-clients&act=importservices" class="nav-tab">Другие сервисы</a-->
</h2>

<div class="tab-container">
<form id="contacts-importfile-form" action="">
  <div style="vertical-align: top; display: inline-block; width: 300px; margin-right: 10px;">
    
    <strong style="vertical-align: top;">Выберите файл с клиентами:</strong>

    <p>Вы можете импортировать CSV файл. В нем через запятую должны быть указаны значения трех полей: ФИО, Телефон, Email каждого клиента. Порядок следования полей не изменяйте. Если какое-то поле неизвестно, не указывайте его, но запятая после него все-равно должна быть. Клиенты с отсутствующим Email не импортируются. Данные каждого клиента должны быть указаны с новой строки.</p> 

    Пример:<br>
    Иван Королев,8-936-987-7645,ivan@mail.ru<br>
    ,+7 916 761 6752, semen@mail.ru<br>
    ,,vladimir@yandex.ru<br>

    <p>Одиночные контакты можно добавлять с помощью сервиса "<a href="/wp-admin/admin.php?page=vspostman-clients&act=add">Добавить клиента</a>".</p>
    
  </div>
  <div style="display: inline-block;">
     <input name="contacts_file" type="file">
      <div class="progresser">
        <div class="inner"></div>
      </div>
  </div>

  <div class="info" style="padding: 0 0 0 314px;"></div>
  <div style="padding: 10px 0 0 314px;">
    <input type="submit" class="button button-primary button-large" value="Импортировать клиентов">
  </div>
</form>
</div>

<script>
$(document).ready(function(){
    ajaxForm({
        id: 'contacts-importfile-form',
        data: {
            controller: 'clients',
            act: 'importfilesave'
        },
        beforeSubmit: function(formData, jqForm, options){
            $('#contacts-importfile-form .info').html('Обработка данных...');   
        },
        success: function(data){
            $('#contacts-importfile-form .info').html(data.result);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            $('#contacts-importfile-form .progresser .inner').width(percentVal);
        },
    });
});
</script>