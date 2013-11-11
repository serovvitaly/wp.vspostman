<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=<?= $contact_id ?>" class="nav-tab">Общие</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard_mails&cid=<?= $contact_id ?>" class="nav-tab">Письма</a>
  <a href="#" class="nav-tab nav-tab-active">Комментарии</a>
</h2>

<div class="tab-container">

  <div style="margin: 0 0 20px; width: 500px;">
    <button id="clients-comment-toggle" onclick="displayCommentForm();" class="button button-small button-primary">Добавить комментарий</button>
    <table id="clients-comment-buttons" style="float: right; display: none;"><tr>
       <td><button onclick="sendCommentForm();" style="float: right;" class="button button-small button-primary">Сохранить</button></td>
       <td><a href="#" onclick="hideCommentForm(); return false;">отмена</a></td>
    </tr></table>
    
    <div id="clients-comment-form" style="margin: 10px 0 5px; display: none;">
      <strong><?= wp_get_current_user()->display_name ?></strong>
      <textarea cols="" rows="" style="height: 100px; width: 100%;" placeholder="Введите текст комментария"></textarea>
    </div>
  </div>

  <div id="clients-comments-list">
<?
    if (count($comments) > 0) {
        foreach ($comments AS $com) {
    ?>
  <div>
    <a href="#" class="clients-unsubscribe-contact" style="padding-right: 5px" title="Удалить" onclick="removeComment(<?= $com->id ?>); return false;">x</a><strong><?= $com->user_name ?></strong> <i><?= $com->created ?></i><br>
    <p style="margin: 5px 0 15px;"><?= $com->content ?></p>
  </div>
    <?
        }
    }
?>
  </div>

</div>

<script>

function displayCommentForm(){
    $('#clients-comment-form textarea').css('border-color', '');
    $('#clients-comment-toggle').hide();
    $('#clients-comment-buttons').show();
    $('#clients-comment-form').slideDown(100);
}

function hideCommentForm(){
    $('#clients-comment-toggle').show();
    $('#clients-comment-buttons').hide();
    $('#clients-comment-form').slideUp(100);
    $('#clients-comment-form textarea').val('');
}

function sendCommentForm(){
    var comment = $('#clients-comment-form textarea').val().trim();
    $('#clients-comment-form textarea').css('border-color', '');
    if (comment == '') {
        $('#clients-comment-form textarea').css('border-color', 'red');
        $('#clients-comment-form textarea').focus();
        return;
    }
    
    $.ajax({
        url: '/wp-content/plugins/vspostman/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            controller: 'clients',
            act: 'add_comment',
            comment: comment,
            contact_id: '<?= $contact_id ?>'
        },
        success: function(data){
            if (data.success === true) {
                $('#clients-comments-list').prepend('<div><strong><?= wp_get_current_user()->display_name ?></strong> <i>'+data.result.created+'</i><br><p style="margin: 5px 0 15px;">'+data.result.content+'</p></div>');
                
                hideCommentForm();
            }
        }
    });
}

</script>