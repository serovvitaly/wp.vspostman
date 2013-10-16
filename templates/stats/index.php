<? include("_header.php"); ?>

<div class="tab-container">

  <div class="wrapper1">
    <canvas id="stat-canva-1" width="300" height="300"></canvas>

    <table class="chart-table">
      <tr>
        <td>открыто</td>
        <td class="value stat-opened"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>щелкнуто</td>
        <td class="value stat-clicked"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>целей</td>
        <td class="value stat-targets"><span>0</span></td>
      </tr>
      <tr>
        <td>отказов</td>
        <td class="value stat-bounces"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>ошибок доставки</td>
        <td class="value stat-errors"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>жалоб</td>
        <td class="value stat-complaints"><span>0.00</span> %</td>
      </tr>
    </table>


    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" class="manage-column column-name" style="width: 50px;">Все</th>
            <th scope="col" class="manage-column column-name">Email</th>
            <th scope="col" class="manage-column column-name" style="width: 200px;">Действия</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th scope="col" class="manage-column column-name" style="width: 50px;">Все</th>
            <th scope="col" class="manage-column column-name">Email</th>
            <th scope="col" class="manage-column column-name" style="width: 200px;">Действия</th>
          </tr>
        </tfoot>

        <tbody id="the-list">
        <?
            if (count($list) > 0) {
                foreach ($list AS $item) {
            ?>
            <tr>
              <td><?= $item->first_name ?></td>
              <td><?= $item->email ?></td>
              <td><?= $item->removal_reason ?></td>
            </tr>
            <?
                }
            }
        ?>
        </tbody>
    </table>  
  </div>
    
  <div class="wrapper2">
    <canvas id="stat-canva-2" width="300" height="300"></canvas>

    <table class="chart-table">
      <tr>
        <td>открыто</td>
        <td class="value stat-opened"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>щелкнуто</td>
        <td class="value stat-clicked"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>целей</td>
        <td class="value stat-targets"><span>0</span></td>
      </tr>
      <tr>
        <td>отказов</td>
        <td class="value stat-bounces"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>ошибок доставки</td>
        <td class="value stat-errors"><span>0.00</span> %</td>
      </tr>
      <tr>
        <td>жалоб</td>
        <td class="value stat-complaints"><span>0.00</span> %</td>
      </tr>
    </table>


    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" class="manage-column column-name" style="width: 50px;">Все</th>
            <th scope="col" class="manage-column column-name">Email</th>
            <th scope="col" class="manage-column column-name" style="width: 200px;">Действия</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th scope="col" class="manage-column column-name" style="width: 50px;">Все</th>
            <th scope="col" class="manage-column column-name">Email</th>
            <th scope="col" class="manage-column column-name" style="width: 200px;">Действия</th>
          </tr>
        </tfoot>

        <tbody id="the-list">
        <?
            if (count($list) > 0) {
                foreach ($list AS $item) {
            ?>
            <tr>
              <td><?= $item->first_name ?></td>
              <td><?= $item->email ?></td>
              <td><?= $item->removal_reason ?></td>
              <td><?= $item->removal_at ?></td>
            </tr>
            <?
                }
            }
        ?>
        </tbody>
    </table>  
  </div>      
         


         
</div>

<script>
function renderMailSelection(data, coll){
    this.ctx = $("#stat-canva-"+coll).get(0).getContext("2d");
    
    $('.wrapper'+coll+' td.value span').html('0');
    $.each(data, function(index, item){
        $('.wrapper'+coll+' td.stat-'+index+' span').html(item);
    });
    
    this.data = [
        {
            value: data.opened,
            color:"#800000"
        },
        {
            value: data.clicked,
            color:"#FF8080"
        },
        {
            value: data.bounces,
            color:"#00FF00"
        },
        {
            value: data.errors,
            color:"#8080FF"
        },
        {
            value: data.complaints,
            color:"#F38630"
        },
    ];
    
    new Chart(ctx).Pie(this.data);
}
</script>
