<nav class="nav">
  <ul class="nav__list container">
    <?foreach($menu_items as $value):?>
      <li class="nav__item">
          <a href="all-lots.html"><?=$value['name']?></a>
      </li>
    <?endforeach;?>
  </ul>
</nav>
<section class="lot-item container">
  <h2><?=htmlspecialchars($lot_item['NAME'], ENT_QUOTES)?></h2>
  <div class="lot-item__content">
    <div class="lot-item__left">
      <div class="lot-item__image">
        <img src=<?=htmlspecialchars($lot_item['IMAGE_URL'], ENT_QUOTES)?> width="730" height="548" alt="Сноуборд">
      </div>
      <p class="lot-item__category">Категория: <span><?=$lot_item['CATEGORY_NAME']?></span></p>
      <p class="lot-item__description"><?=htmlspecialchars($lot_item['DESCRIPTION'], ENT_QUOTES)?></p>
    </div>
    <div class="lot-item__right">
      <div class="lot-item__state">
        <div class="lot-item__timer timer">
          <?=getTimeDiff($lot_item['FINISH_DATE'])?>
        </div>
        <div class="lot-item__cost-state">
          <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=!empty($lot_item['MAX_BET_PRICE'])?toPriceFormat($lot_item['MAX_BET_PRICE']):toPriceFormat($lot_item['START_PRICE'])?></span>
          </div>
          <div class="lot-item__min-cost">
            Мин. ставка <span>12 000 р</span>
          </div>
        </div>
        <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
          <p class="lot-item__form-item">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="number" name="cost" placeholder="12 000">
          </p>
          <button type="submit" class="button">Сделать ставку</button>
        </form>
      </div>
      <div class="history">
        <h3>История ставок (<span><?=count($bets_list)?></span>)</h3>
        <table class="history__list">
          <?php foreach ($bets_list as $value):?>
            <tr class="history__item">
              <td class="history__name"><?=htmlspecialchars($value['USER_NAME'], ENT_QUOTES)?></td>
              <td class="history__price"><?=toPriceFormat($value['PRICE'])?></td>
              <td class="history__time"><?=$value['DATE_CREATE']?></td>
            </tr>
          <?endforeach;?>
        </table>
      </div>
    </div>
  </div>
</section>