<nav class="nav">
  <ul class="nav__list container">
    <?foreach ($menu_items as $value):?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$value['name']?></a>
      </li>
    <?endforeach;?>
  </ul>
</nav>