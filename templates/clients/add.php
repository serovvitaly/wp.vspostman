

<div style="margin: 20px 0;">
  <div style="margin-bottom: 20px">Воронка: 
    <select>
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
  <div style="margin-bottom: 20px"><input class="big-text" style="width: 50%;" type="text" name="name" size="130" value="" autocomplete="off" placeholder="Имя"></div>
  <div><input class="big-text" style="width: 50%;" type="text" name="email" size="130" value="" autocomplete="off" placeholder="Email"></div>    
</div>

<input type="submit" class="button button-primary button-large" value="Сохранить">


