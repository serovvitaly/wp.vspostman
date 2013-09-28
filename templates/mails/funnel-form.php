<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/wp-content/plugins/vspostman/libs/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/vspostman/libs/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css">

<style>
.draggable{
    width: 150px;
    height: 50px;
    background: #808080;
    text-align: center;
    color: #FFF;
    float: left;
    cursor: initial;
}
.level-sending{
    height: 50px;
    padding: 10px;
    border-bottom: 1px dotted #ACACAC;
}
.level-sending .meter{
    font-size: 33px;
    color: #E1E1E1;
    position: relative;
    float: left;
}
.level-sending.empty{
    display: none;
}
.buttons-grp{
    float: right;
    margin-top: 10px;
}
.buttons-grp .button{
    margin-right: 5px;
}
.buttons-grp a.delete{
    color: red;
}
.buttons-grp a.delete:hover{
    color: black;
}
</style>

<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
  <form name="post" action="" method="post">
    <input type="hidden" name="uid" value="<?= $item->id ?>">
    <input type="hidden" name="act" value="save">
  
    <div class="buttons-grp">
      <input type="submit" value="Сохранить" class="button button-primary">
      <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="button">Отмена</a>
      <? if ($item->id > 0) { ?><a href="/wp-admin/admin.php?page=vspostman-mails&act=delete&uid=<?= $item->id ?>" class="delete" onclick="if (!confirm('Точно удалить?')) return false;">Удалить</a><? } ?>
      
    </div>
    
    <h2><?= $item->title ?></h2>
    
    <div id="titlediv">
      <div id="titlewrap">
        <input type="text" name="name" size="30" value="<?= $item->name ?>" id="title" autocomplete="off" placeholder="Введите название воронки">
      </div>
    </div>
    
  </form>
  
  <div id="myholder" style="border: 1px solid #DFDFDF; height: 600px; overflow: scroll;">
  </div>
  
</div>

<script>
$(function() {
    
    var levels_content = '';
    for (var lev=0; lev<=100; lev++) {
        levels_content += '<div id="slevel-'+lev+'" class="level-sending empty"><div class="meter">'+lev+'</div></div>';
    }
    $('#myholder').html(levels_content);
    
    $('#slevel-0').removeClass('empty');
    $('#slevel-0').append('<div class="draggable"><p>Первое письмо</p></div>');
    
    $('#slevel-1').removeClass('empty');
    $('#slevel-1').append('<div class="draggable"><p>Второе письмо</p></div>');
    $('#slevel-1').append('<div class="draggable"><p>Третье письмо</p></div>');
    
    $('#slevel-4').removeClass('empty');
    $('#slevel-4').append('<div class="draggable"><p>Второе письмо</p></div>');
    $('#slevel-4').append('<div class="draggable"><p>Третье письмо</p></div>');
    $('#slevel-4').append('<div class="draggable"><p>Третье письмо</p></div>');
    
    $( ".draggable" ).draggable({
        axis: "x",
        grid: [ 50, 50 ],
        containment: 'myholder'
    });
});
</script>