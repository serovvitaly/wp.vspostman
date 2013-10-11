<h2 class="nav-tab-wrapper">
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard&cid=<?= $id ?>" class="nav-tab">Общие</a>
  <a href="/wp-admin/admin.php?page=vspostman-clients&act=clientcard_mails&cid=<?= $id ?>" class="nav-tab">Письма</a>
  <a href="#" class="nav-tab nav-tab-active">Комментарии</a>
</h2>

<div class="tab-container">

<?
    if (count($comments) > 0) {
        foreach ($comments AS $com) {
    ?>
  <div>
    <strong><?= $com->user_name ?></strong> <i><?= $com->created ?></i><br>
    <p style="margin: 5px 0 15px;"><?= $com->content ?></p>
  </div>
    <?
        }
    }
?>

</div>