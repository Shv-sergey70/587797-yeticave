<nav class="nav">
  <ul class="nav__list container">
    <?foreach ($menu_items as $value):?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$value['name']?></a>
      </li>
    <?endforeach;?>
  </ul>
</nav>
<form class="form form--add-lot container form--invalid" action="add.php" method="POST" enctype="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item <?=!empty($errors['NAME'])?'form__item--invalid':''?>">
      <label for="NAME">Наименование</label>
      <input id="NAME" type="text" name="NAME" placeholder="Введите наименование лота" value="<?=$lot['NAME'] ?? ''?>" required>
      <span class="form__error">Введите наименование лота</span>
    </div>
    <div class="form__item <?=!empty($errors['CATEGORY'])?'form__item--invalid':''?>">
      <label for="CATEGORY">Категория</label>
      <select id="CATEGORY" name="CATEGORY" required>
        <?foreach ($menu_items as $value):?>
          <option <?=(isset($lot['CATEGORY']) && $lot['CATEGORY'] === $value['name'])?'selected':''?> value="<?=$value['id']?>"><?=$value['name']?></option>
        <?endforeach;?>
      </select>
      <span class="form__error">Выберите категорию</span>
    </div>
  </div>
  <div class="form__item form__item--wide <?=!empty($errors['DESCRIPTION'])?'form__item--invalid':''?>">
    <label for="DESCRIPTION">Описание</label>
    <textarea id="DESCRIPTION" name="DESCRIPTION" placeholder="Напишите описание лота" required><?=$lot['DESCRIPTION'] ?? ''?></textarea>
    <span class="form__error">Напишите описание лота</span>
  </div>
  <div class="form__item form__item--file">
    <label>Изображение</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src='<?=$lot['IMAGE_URL']?>' width="113" height="113" alt="Изображение лота">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" name='IMAGE_URL' type="file" id="photo2" value="" required>
      <label for="photo2">
        <span>+ Добавить</span>
      </label>
    </div>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small <?=!empty($errors['START_PRICE'])?'form__item--invalid':''?>">
      <label for="START_PRICE">Начальная цена</label>
      <input id="START_PRICE" type="number" name="START_PRICE" placeholder="0" value="<?=$lot['START_PRICE'] ?? ''?>" required>
      <span class="form__error">Введите начальную цену</span>
    </div>
    <div class="form__item form__item--small <?=!empty($errors['PRICE_STEP'])?'form__item--invalid':''?>">
      <label for="PRICE_STEP">Шаг ставки</label>
      <input id="PRICE_STEP" class="<?=!empty($errors['PRICE_STEP'])?'form__item--invalid':''?>" type="number" name="PRICE_STEP" placeholder="0" value=<?=$lot['PRICE_STEP'] ?? ''?> required>
      <span class="form__error">Введите шаг ставки</span>
    </div>
    <div class="form__item <?=!empty($errors['FINISH_DATE'])?'form__item--invalid':''?>">
      <label for="FINISH_DATE">Дата окончания торгов</label>
      <input class="form__input-date" id="FINISH_DATE" type="date" name="FINISH_DATE" value='<?=$lot['FINISH_DATE'] ?? ''?>' required>
      <span class="form__error">Введите дату завершения торгов</span>
    </div>
  </div>
  <span class="form__error form__error--bottom">
    <?if (isset($errors) && count($errors) > 0):?>
      <p>Пожалуйста, исправьте ошибки в форме.</p>
      <ul>
        <?foreach ($errors as $error_name => $error_text):?>
          <li><b><?=$dict[$error_name]?>:</b> <?=$error_text?></li>
        <?endforeach;?>
      </ul>
    <?endif;?>
  </span>
  <button type="submit" class="button">Добавить лот</button>
</form>