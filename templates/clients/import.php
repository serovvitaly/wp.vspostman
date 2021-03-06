<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=import" class="nav-tab nav-tab-active">Копировать & вставить</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importfile" class="nav-tab">Загрузить файл</a>
  <!--a href="/wp-admin/admin.php?page=vspostman-clients&act=importservices" class="nav-tab">Другие сервисы</a-->
</h2>

<div class="tab-container">
<form id="contacts-import-form" action="" method="POST">

  <div style="vertical-align: top; display: inline-block; width: 300px; margin-right: 10px;">

    <strong style="vertical-align: top;">Введите ваши контакты:</strong>

    <p>Для каждого клиента через запятую укажите значения трех полей: ФИО, Телефон, Email. Порядок следования полей не изменяйте. Если какое-то поле неизвестно, не указывайте его, но запятую после него все-равно поставьте. Клиенты с отсутствующим Email не импортируются. Данные каждого клиента указывайте с новой строки.</p> 

    Пример:<br>
    Иван Королев,8-936-987-7645,ivan@mail.ru<br>
    ,+7 916 761 6752, semen@mail.ru<br>
    ,,vladimir@yandex.ru<br>
     
    <p>Одиночные контакты можно добавлять с помощью сервиса "<a href="/wp-admin/admin.php?page=vspostman-clients&act=add">Добавить клиента</a>".</p>
        
  </div>
  <textarea cols="" rows="" name="contacts_list" style="width: 400px; height: 400px;"></textarea>
  <div class="info" style="padding: 0 0 0 314px;"></div>
  <div style="padding: 10px 0 0 314px;">
    <input type="submit" class="button button-primary button-large" value="Импортировать клиентов">
  </div>
  
</form>
</div>

<script>
$(document).ready(function(){
    ajaxForm({
        id: 'contacts-import-form',
        data: {
            controller: 'clients',
            act: 'importsave'
        },
        beforeSubmit: function(formData, jqForm, options){
            $('#contacts-import-form .info').html('');
            if (formData[0].value.replace(' ', '') == '') {
                $('#contacts-import-form .info').html('<span style="color:red">Укажите хотябы один контакт.</span>');
                return false;
            }    
        },
        success: function(data){
            $('#contacts-import-form .info').html(data.result);
        }
    });
});
</script>
