

<div style="margin: 20px 0;">
<form id="contactadd-form" action="/wp-content/plugins/vspostman/ajax.php" method="POST">
    <input type="hidden" name="controller" value="clients">
    <input type="hidden" name="act" value="contactadd">
  <div style="margin-bottom: 20px">Воронка: 
    <select name="funnel_id">
      <option></option>
    <?
      if (count($funnels_list) > 0) {
          foreach ($funnels_list AS $funnel) {
      ?>
      <option value="<?= $funnel->id ?>"><?= $funnel->name ?></option>
      <?
          }
      }
    ?>
    </select>
  </div>
  <div style="margin-bottom: 20px">
    <input class="big-text" style="width: 50%;" type="text" name="first_name" size="130" value="" autocomplete="off" placeholder="Имя">
  </div>
  <div>
    <input class="big-text" style="width: 50%;" type="text" name="email" size="130" value="" autocomplete="off" placeholder="Email">
    <img class="email-check-ajax" style="vertical-align: sub; display: none;" src="/wp-content/plugins/vspostman/img/ajax-loader.gif" alt="">
    <img class="email-check-ok" style="vertical-align: sub; display: none;" src="/wp-content/plugins/vspostman/img/tick.png" alt="">
    <img class="email-check-fail" style="vertical-align: sub; display: none;" src="/wp-content/plugins/vspostman/img/cross.png" alt="">
  </div>    
  
  <div style="margin-top: 20px;">
    <input type="submit" class="button button-primary button-large" value="Сохранить">
  </div>
  
</form>  
</div>

<script>

function checkEmail(email){
    var reg = /([a-zA-Z0-9-_.]+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+/;
    return reg.test(email);
}

$(document).ready(function(){
    
    $('#contactadd-form input[name="email"]')
        .on('change', function(){
            if (!checkEmail($(this).val())) {
                $('#contactadd-form .email-check-ok').hide();
                $('#contactadd-form .email-check-fail').show();
                $('#contactadd-form .email-check-ajax').hide();
            }
        }).on('keyup', function(){
            var email = $(this).val();
            if (checkEmail(email)) {
                $('#contactadd-form .email-check-ajax').show();
                $('#contactadd-form .email-check-ok').hide();
                $('#contactadd-form .email-check-fail').hide();
                $.ajax({
                    url: '/wp-content/plugins/vspostman/ajax.php',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        controller: 'clients',
                        act: 'validemail',
                        email: email
                    },
                    success: function(data){
                        if (data.success === true && data.result == 'validemail-ok') {
                            $('#contactadd-form .email-check-ok').show();
                            $('#contactadd-form .email-check-fail').hide();
                            $('#contactadd-form .email-check-ajax').hide();
                        } else {
                            $('#contactadd-form .email-check-ok').hide();
                            $('#contactadd-form .email-check-fail').show();
                            $('#contactadd-form .email-check-ajax').hide();
                        }
                    }
                });
            }
        });
    
    $('#contactadd-form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function(formData, jqForm, options){
            var flag = true;
            for (var i = 0; i < formData.length; i++) {
                var field = formData[i];
                switch (field.name) {
                    case 'email':
                        if ( !checkEmail(field.value) ) {
                            jqForm.find('input[name="email"]').css('border-color', 'red');
                            flag = false;
                        } else {
                            jqForm.find('input[name="email"]').css('border-color', '#DFDFDF');
                        }
                        break;
                        
                    case 'first_name':
                        if (field.value.replace(' ','') == '') {
                            jqForm.find('input[name="name"]').css('border-color', 'red');
                            flag = false;
                        } else {
                            jqForm.find('input[name="name"]').css('border-color', '#DFDFDF');
                        }
                }
            }
            
            return flag;
        },
        success: function(data){
            console.log(data);
            if (data.success === true) {
                window.location = '/wp-admin/admin.php?page=vspostman-clients';
            }
        }
    });
     
});

</script>