<?php declare(strict_types=1);?>
<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<div class="container">
  <section class="lots">
    <h2>Все лоты в категории «<span><?=htmlspecialchars($category_name)?></span>»</h2>
    <ul class="lots__list">
      <?php if(count($items_result)):?>
        <?php foreach ($items_result as $value):?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=htmlspecialchars($value['IMAGE_URL'], ENT_QUOTES)?>" width="350" height="260" alt="<?=htmlspecialchars($value['NAME'], ENT_QUOTES)?>">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?=htmlspecialchars($value['CATEGORY_NAME'], ENT_QUOTES)?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?ID=<?=$value['ID']?>"><?=htmlspecialchars($value['NAME'], ENT_QUOTES)?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount"><?=(!empty($value['BETS_COUNT']))?plural_form((int)$value['BETS_COUNT'], ['ставка', 'ставки', 'ставок']):'Стартовая цена'?></span>
                <span class="lot__cost"><?=toPriceFormat((int)$value['PRICE'])?></span>
              </div>
              <div class="lot__timer timer <?=$value['IS_LESS_THAN_24_HOUR']?'timer--finishing':''?>">
                <?=getTimeDiff($value['FINISH_DATE'])?>
              </div>
            </div>
          </div>
        </li>
        <?php endforeach;?>
      <?php endif;?>
  </section>
    <?php if(isset($pagination['PAGES_COUNT']) && $pagination['PAGES_COUNT'] > 1):?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="<?='categories.php?cat_id='.$category_id.'&page='.$pagination['PREV_PAGE']?>">Назад</a></li>
        <?php foreach($pagination['PAGES'] as $value):?>
          <?php if($value === $pagination['CURRENT_PAGE']):?>
            <li class="pagination-item pagination-item-active">
              <a><?=$value?></a>
            </li>
          <?php else:?>
            <li class="pagination-item">
              <a href="categories.php?cat_id=<?=$category_id?>&page=<?=$value?>"><?=$value?></a>
            </li>
          <?php endif;?>
        <?php endforeach;?>
        <li class="pagination-item pagination-item-next"><a href="<?='categories.php?cat_id='.$category_id.'&page='.$pagination['NEXT_PAGE']?>">Вперед</a></li>
      </ul>
    <?php endif;?>
</div>