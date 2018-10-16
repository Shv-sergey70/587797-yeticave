<?php declare(strict_types=1);?>
<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach($my_bets as $value):?>
      <?php switch ($value['LOT_STATUS']) {
        case 'IS_MORE_THAN_24_HOUR':
          print("<tr class='rates__item'>");
          break;
        case 'IS_LESS_THAN_24_HOUR':
          print("<tr class='rates__item'>");
          break;
        case 'IS_FINISHED':
          print("<tr class='rates__item rates__item--end'>");
          break;
        case 'IS_WIN':
          print("<tr class='rates__item rates__item--win'>");
          break;
      }?>
        <td class="rates__info">
          <div class="rates__img">
            <img src="<?=htmlspecialchars($value['IMAGE_URL'], ENT_QUOTES)?>" width="54" height="40" alt="<?=htmlspecialchars($value['NAME'], ENT_QUOTES)?>">
          </div>
          <div>
            <h3 class="rates__title"><a href="/lot.php?ID=<?=$value['ID']?>"><?=htmlspecialchars($value['NAME'], ENT_QUOTES)?></a></h3>
            <?php if($value['LOT_STATUS'] === 'IS_WIN'):?>
              <p><?=$value['CONTACTS']?></p>
            <?php endif;?>
          </div>
        </td>
        <td class="rates__category">
          <?=htmlspecialchars($value['CATEGORY_NAME'], ENT_QUOTES)?>
        </td>
        <td class="rates__timer">
          <?php switch ($value['LOT_STATUS']) {
            case 'IS_MORE_THAN_24_HOUR':
              print("<div class='timer'>".getTimeDiff($value['FINISH_DATE'])."</div>");
              break;
            case 'IS_LESS_THAN_24_HOUR':
              print("<div class='timer timer--finishing'>".getTimeDiff($value['FINISH_DATE'])."</div>");
              break;
            case 'IS_FINISHED':
              print('<div class="timer timer--end">Торги окончены</div>');
              break;
            case 'IS_WIN':
              print('<div class="timer timer--win">Ставка выиграла</div>');
              break;
          }?>
        </td>
        <td class="rates__price">
          <?=toPriceFormat((int)$value['BET_PRICE'])?>
        </td>
        <td class="rates__time">
          <?php if(abs(strtotime($value['BET_DATE_CREATE'])-time()) > 86400):?>
          <?=showDate(strtotime($value['BET_DATE_CREATE'])).' в '.date('H:i', strtotime($value['BET_DATE_CREATE']))?>
          <?php else:?>
          <?=showDate(strtotime($value['BET_DATE_CREATE']))?>
          <?php endif;?>
        </td>
      </tr>
    <?php endforeach;?>
  </table>
</section>