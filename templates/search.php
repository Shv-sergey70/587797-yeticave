<?php declare(strict_types=1);?>
<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<?
// echo "<pre>";
//   var_dump(count($search_result));
//   var_dump(isset($empty));
// echo "</pre>";
?>
<div class="container">
  <section class="lots">
    <?if(isset($error)):?>
      <h2><?=$error?></h2>
    <?elseif(!count($search_result)):?>
      <h2>Ничего не найдено по вашему запросу</h2>
    <?else:?>
      <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search_query)?></span>»</h2>
    <ul class="lots__list">
      <?if(count($search_result)):?>
      <?foreach ($search_result as $value):?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?=htmlspecialchars($value['IMAGE_URL'], ENT_QUOTES)?>" width="350" height="260" alt="<?=htmlspecialchars($value['NAME'], ENT_QUOTES)?>">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?=htmlspecialchars($value['CATEGORY_NAME'], ENT_QUOTES)?></span>
          <h3 class="lot__title"><a class="text-link" href="lot.html"><?=htmlspecialchars($value['NAME'], ENT_QUOTES)?></a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount">Стартовая цена</span>
              <span class="lot__cost">10 999<b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer">
              <?=getTimeDiff($value['FINISH_DATE'])?>
            </div>
          </div>
        </div>
      </li>
      <?endforeach;?>
      <?endif;?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="img/lot-2.jpg" width="350" height="260" alt="Сноуборд">
        </div>
        <div class="lot__info">
          <span class="lot__category">Доски и лыжи</span>
          <h3 class="lot__title"><a class="text-link" href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount">12 ставок</span>
              <span class="lot__cost">15 999<b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer timer--finishing">
              00:54:12
            </div>
          </div>
        </div>
      </li>
  </section>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <li class="pagination-item pagination-item-active"><a>1</a></li>
    <li class="pagination-item"><a href="#">2</a></li>
    <li class="pagination-item"><a href="#">3</a></li>
    <li class="pagination-item"><a href="#">4</a></li>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
  </ul>
  <?endif;?>
</div>