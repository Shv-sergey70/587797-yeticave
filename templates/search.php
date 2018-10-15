<?php declare(strict_types=1);?>
<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<div class="container">
  <section class="lots">
    <?php if(isset($error)):?>
      <h2 style="color: red"><?=$error?></h2>
    <?php elseif(!count($search_result)):?>
      <h2>Ничего не найдено по вашему запросу</h2>
    <?php else:?>
      <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search_query, ENT_QUOTES)?></span>»</h2>
    <ul class="lots__list">
      <?php if(count($search_result)):?>
        <?php foreach ($search_result as $value):?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=htmlspecialchars($value['IMAGE_URL'], ENT_QUOTES)?>" width="350" height="260" alt="<?=htmlspecialchars($value['NAME'], ENT_QUOTES)?>">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?=htmlspecialchars($value['CATEGORY_NAME'], ENT_QUOTES)?></span>
            <h3 class="lot__title"><a class="text-link" href="/lot.php?ID=<?=$value['ID']?>"><?=htmlspecialchars($value['NAME'], ENT_QUOTES)?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount"><?=(!empty($value['BETS_COUNT']))?plural_form((int)$value['BETS_COUNT'], ['ставка', 'ставки', 'ставок']):'Стартовая цена'?></span>
                <span class="lot__cost">10 999<b class="rub">р</b></span>
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
        <li class="pagination-item pagination-item-prev"><a href="<?='/search.php?search='.htmlspecialchars($search_query, ENT_QUOTES).'&page='.$pagination['PREV_PAGE']?>">Назад</a></li>
        <?php foreach($pagination['PAGES'] as $value):?>
          <?php if($value === $pagination['CURRENT_PAGE']):?>
            <li class="pagination-item pagination-item-active">
              <a><?=$value?></a>
            </li>
          <?php else:?>
            <li class="pagination-item">
              <a href="/search.php?search=<?=htmlspecialchars($search_query, ENT_QUOTES)?>&page=<?=$value?>"><?=$value?></a>
            </li>
          <?php endif;?>
        <?php endforeach;?>
        <li class="pagination-item pagination-item-next"><a href="<?='/search.php?search='.htmlspecialchars($search_query, ENT_QUOTES).'&page='.$pagination['NEXT_PAGE']?>">Вперед</a></li>
      </ul>
    <?php endif;?>
  <?php endif;?>
</div>