<?=include_template('_main_menu.php', ['menu_items' => $menu_items]); //Подключение меню?>
<form class="form container <?=!empty($errors)?'form--invalid':''?>" method="POST" enctype="multipart/form-data"> <!-- form--invalid -->
  <h2>Регистрация нового аккаунта</h2>
  <div class="form__item <?=!empty($errors['EMAIL'])?'form__item--invalid':''?>">
    <label for="email">E-mail*</label>
    <input id="email" type="text" name="EMAIL" placeholder="Введите e-mail" value="<?=$account['EMAIL']??''?>" required>
    <span class="form__error">Введите e-mail</span>
  </div>
  <div class="form__item <?=!empty($errors['PASSWORD'])?'form__item--invalid':''?>">
    <label for="password">Пароль*</label>
    <input id="password" type="password" name="PASSWORD" placeholder="Введите пароль" required>
    <span class="form__error">Введите пароль</span>
  </div>
  <div class="form__item <?=!empty($errors['NAME'])?'form__item--invalid':''?>">
    <label for="name">Имя*</label>
    <input id="name" type="text" name="NAME" placeholder="Введите имя" value="<?=$account['NAME']??''?>" required>
    <span class="form__error">Введите имя</span>
  </div>
  <div class="form__item <?=!empty($errors['MESSAGE'])?'form__item--invalid':''?>">
    <label for="message">Контактные данные*</label>
    <textarea id="message" name="MESSAGE" placeholder="Напишите как с вами связаться" required><?=$account['MESSAGE']??''?></textarea>
    <span class="form__error">Напишите как с вами связаться</span>
  </div>
  <div class="form__item form__item--file form__item--last">
    <label>Аватар</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="photo2" name='AVATAR' value='' >
      <!-- accept='image/jpeg, image/png' -->
      <label for="photo2">
        <span>+ Добавить</span>
      </label>
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
  <button type="submit" class="button">Зарегистрироваться</button>
  <a class="text-link" href="#">Уже есть аккаунт</a>
</form>