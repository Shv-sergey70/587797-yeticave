<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<form class="form container <?=!empty($errors)?'form--invalid':''?>" method="POST">
  <h2>Вход</h2>
  <?php if(isset($errors['WRONG'])):?>
    <span style='display: inline;' class="form__error"><?=$errors['WRONG']?></span>
  <?php endif;?>
  <div class="form__item <?=!empty($errors['EMAIL'])?'form__item--invalid':''?>">
    <label for="email">E-mail*</label>
    <input id="email" type="text" name="EMAIL" placeholder="Введите e-mail" value="<?=$login['EMAIL']??''?>" required>
    <span class="form__error"><?=$errors['EMAIL']??''?></span>
  </div>
  <div class="form__item form__item--last <?=!empty($errors['PASSWORD'])?'form__item--invalid':''?>">
    <label for="password">Пароль*</label>
    <input id="password" type="password" name="PASSWORD" placeholder="Введите пароль" value="<?=$login['PASSWORD']??''?>" required>
    <span class="form__error"><?=$errors['PASSWORD']??''?></span>
  </div>
  <button type="submit" class="button">Войти</button>
</form>