<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<section class="lot-item container">
  <h2><?=htmlspecialchars($lot_item['NAME'], ENT_QUOTES)?></h2>
  <div class="lot-item__content">
    <div class="lot-item__left">
      <div class="lot-item__image">
        <img src='<?=htmlspecialchars($lot_item['IMAGE_URL'], ENT_QUOTES)?>' width="730" height="548" alt='<?=htmlspecialchars($lot_item['NAME'], ENT_QUOTES)?>'>
      </div>
      <p class="lot-item__category">Категория: <span><?=$lot_item['CATEGORY_NAME']?></span></p>
      <p class="lot-item__description"><?=htmlspecialchars($lot_item['DESCRIPTION'], ENT_QUOTES)?></p>
    </div>
    <div class="lot-item__right">
      <?php if($can_create_bet):?>
        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=getTimeDiff($lot_item['FINISH_DATE'])?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=toPriceFormat($lot_item['PRICE'])?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=toPriceFormat($lot_item['MIN_BET'])?></span>
            </div>
          </div>
          <form class="lot-item__form" method="POST">
            <p class="lot-item__form-item">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="number" name="COST" placeholder="<?=number_format($lot_item['MIN_BET'], 0, '', ' ')?>" value="<?=$lot_item['MIN_BET']?>" required>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
          <?php if(!empty($errors)):?>
              <div style="color: red; font-size: 14px;"><?=$errors['COST']?></div>
            <?php endif;?>
        </div>
      <?endif;?>
      <div class="history">
        <h3>История ставок (<span><?=$lot_item['BETS_COUNT']?></span>)</h3>
        <table class="history__list">
          <?php foreach ($bets_list as $value):?>
            <tr class="history__item">
              <td class="history__name"><?=htmlspecialchars($value['USER_NAME'], ENT_QUOTES)?></td>
              <td class="history__price"><?=toPriceFormat($value['PRICE'])?></td>
              <td class="history__time"><?=showDate(strtotime($value['DATE_CREATE']))?></td>
            </tr>
          <?endforeach;?>
        </table>
      </div>
    </div>
  </div>
</section>