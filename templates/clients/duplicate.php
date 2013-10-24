

<div style="margin: 20px 0;">

<p style="color: gray; padding-bottom: 10px;">Укажите из какой воронки в какую вы хотите скопировать или перенести клиенты.</p>

<form id="contacta-duplicate-form" action="" method="POST">
  <table>
    <tr>
      <td style="text-align: right; line-height: 27px; padding-bottom: 20px;">Текущая воронка:</td>
      <td>
        <select name="op_from">
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
      </td>
    </tr>
    <tr>
      <td style="text-align: right; line-height: 27px; padding-bottom: 20px;">Операция:</td>
      <td>
        <select name="operation">
          <option value="copy">копировать</option>
          <option value="move">перенести</option>
        </select>
      </td>
    </tr>
    <tr>
      <td style="text-align: right; line-height: 27px; padding-bottom: 10px;">Желаемая воронка:</td>
      <td>
        <select name="op_to">
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
      </td>
    </tr>
  </table>
  
  <div class="info"></div>
  
  <div style="margin-top: 20px;">
    <input type="submit" class="button button-primary button-large" value="Выполнить">
  </div>
  
</form>  
</div>

<script>

function checkEmail(email){
    var reg = /([a-zA-Z0-9-_.]+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+/;
    return reg.test(email);
}

$(document).ready(function(){

    
    ajaxForm({
        id: 'contacta-duplicate-form',
        data: {
            controller: 'clients',
            act: 'duplicatesave'
        },
        beforeSubmit: function(formData, jqForm, options){
            var infoBox = $('#contacta-duplicate-form .info');
            infoBox.html('');
            var vars = {};
            for (var i = 0; i < formData.length; i++) {
                vars[formData[i].name] = formData[i].value;
            }
            
            if (vars.op_to == vars.op_from) {
                infoBox.html('<span style="color:red">Вы указали одинаковые воронки для операции, нужно указать разные воронки.</span>');
                return false;
            }
            
            infoBox.html('<i>Выполняется операция...</i>');
            
        },
        success: function(data){
            $('#contacta-duplicate-form .info').html(data.result);
        }
    });
     
});

</script>