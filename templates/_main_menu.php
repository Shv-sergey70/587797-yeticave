<?php declare(strict_types=1);?>
<nav class="nav">
  <ul class="nav__list container">
    <?foreach ($menu_items as $value):?>
      <li class="nav__item">
        <a href="/categories.php?cat_id=<?=$value['id']?>"><?=$value['name']?></a>
      </li>
    <?endforeach;?>
  </ul>
</nav>