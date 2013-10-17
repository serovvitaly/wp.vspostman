<script>
var CLIENT_ID = '333945996610.apps.googleusercontent.com';
var SCOPES = 'https://www.googleapis.com/auth/drive.readonly';

function checkAuth(handler) {
    gapi.auth.authorize(
        {'client_id': CLIENT_ID, 'scope': SCOPES, 'trashed': false},
        handler);
}
function importFromGoogleDrive(){
    checkAuth(function(authResult){
        if (authResult && !authResult.error) {
            gapi.client.request({
              path: 'drive/v2/files',
              params: {
                  maxResults: 1000,
                  q: "mimeType = 'application/vnd.google-apps.spreadsheet'"
                  //q: "mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'"
              },
              callback: function(data){
                  if (data.items && data.items.length > 0) {
                      var content = '<option>-- выберите файл из списка --</option>';
                      for (var i = 0; i < data.items.length; i++) {
                          var item = data.items[i];
                          content += '<option value="'+item.id+'">'+item.title+'</option>';
                      }
                      $('#clients-importservices-results .files-list').html(content);
                      $('#clients-importservices-results .files-list').on('change', function(){
                          console.log( $(this).val() );
                          
                          gapi.client.request({
                              path: 'drive/v2/files',
                              params: {
                                  maxResults: 1000,
                                  q: "mimeType = 'application/vnd.google-apps.spreadsheet'"
                                  //q: "mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'"
                              },
                              callback: function(data){
                                  if (data.items && data.items.length > 0) {
                                      var content = '<option>-- выберите файл из списка --</option>';
                                      for (var i = 0; i < data.items.length; i++) {
                                          var item = data.items[i];
                                          content += '<option value="'+item.id+'">'+item.title+'</option>';
                                      }
                                      $('#clients-importservices-results .files-list').html(content);
                                      $('#clients-importservices-results .files-list').on('change', function(){
                                          console.log( $(this).val() );
                                      });
                                  } else {
                                      //
                                  }
                                  
                                  $('#clients-importservices-links').slideUp();
                                  $('#clients-importservices-results').slideDown();
                              }
                          });
                          
                      });
                  } else {
                      //
                  }
                  
                  $('#clients-importservices-links').slideUp();
                  $('#clients-importservices-results').slideDown();
              }
            });           
        } else {
            alert('Ошибка авторизации Google, обратитесь к администратору.');
        }
    });
}

</script>
<script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>

<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=import" class="nav-tab">Копировать & вставить</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importfile" class="nav-tab">Загрузить файл</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=importservices" class="nav-tab nav-tab-active">Другие сервисы</a>
</h2>

<div class="tab-container">
  <h1 style="color:red">Сервис в разработке.</h1>
  <div style="vertical-align: top; display: inline-block; width: 200px; margin-right: 10px;">
    <strong style="vertical-align: top;">Импортировать контакты из аккаунта:</strong>
  </div>
  <div id="clients-importservices-links" style="display: inline-block;">
    <img style="cursor: pointer;" onclick="importFromGoogleDrive();" src="/wp-content/plugins/vspostman/img/google-docs.png" alt="">
    <img style="cursor: pointer;" onclick="importFromGoogleContacts();" src="/wp-content/plugins/vspostman/img/google-contacts.png" alt="">
  </div>
  
  
  <div id="clients-importservices-results" style="padding: 10px 0 0 214px; display: none;">
    <div style="margin: 0 0 30px;">
      <select class="files-list"></select>
    </div>
    <input type="submit" class="button button-primary button-large" value="Импорт контактов">
  </div>

</div>