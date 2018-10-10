<nav class="nav">
<ul class="nav__list container">
  <?foreach ($menu_items as $value):?>
    <li class="nav__item">
      <a href="all-lots.html"><?=$value['name']?></a>
    </li>
  <?endforeach;?>
</ul>
</nav>
<form class="form container <?=!empty($errors)?'form--invalid':''?>" method="POST">
<h2>Вход</h2>
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