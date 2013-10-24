<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=import" class="nav-tab">Копировать & вставить</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importfile" class="nav-tab nav-tab-active">Загрузить файл</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importservices" class="nav-tab">Другие сервисы</a>
</h2>

<div class="tab-container">
<form id="contacts-importfile-form" action="">
  <div style="vertical-align: top; display: inline-block; width: 200px; margin-right: 10px;">
    <strong style="vertical-align: top;">Выберите файл с контактами:</strong>
    <p>Можно использовать файл формата CSV с полями разделенными ";".</p>
  </div>
  <div style="display: inline-block;">
     <input name="contacts_file" type="file">
      <div class="progresser">
        <div class="inner"></div>
      </div>
  </div>

  <div class="info" style="padding: 0 0 0 214px;"></div>
  <div style="padding: 10px 0 0 214px;">
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