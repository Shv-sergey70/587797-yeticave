<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?foreach($menu_items as $value):?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?=$value['name']?></a>
            </li>
        <?endforeach;?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?foreach($catalog_items as $value):?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($value['image_url'], ENT_QUOTES)?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($value['category_name'], ENT_QUOTES)?></span>
                    <h3 class="lot__title"><a class="text-link" href="<?=MAIN_DIR?>/lot.php?ID=<?=$value['ID']?>"><?=htmlspecialchars($value['lot_name'], ENT_QUOTES)?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=toPriceFormat($value['lot_start_price'])?></span>
                        </div>
                        <div class="lot__timer timer">
                          <?=getTimeToMidnight()?>
                        </div>
                    </div>
                </div>
            </li>
        <?endforeach;?>
    </ul>
</section>