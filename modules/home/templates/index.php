<!-- Заголовок раздела -->
<div class="titlebar">
    <div><h1><?= _s('Welcome') ?></h1></div>
</div>

<!-- Меню -->
<ul class="nav nav-pills nav-stacked">
    <li class="title"><?= _s('Information') ?></li>
    <li><a href="news/"><i class="rss fw lg"></i><?= _s('News Archive') ?></a></li>
    <li class="title"><?= _s('Communication') ?></li>
    <li><a href="guestbook/"><i class="comments fw lg"></i><?= _s('Guestbook') ?><span class="badge pull-right">0</span></a></li>
    <li><a href="forum/"><i class="comment lg fw"></i><?= _s('Forum') ?><span class="badge pull-right">0</span></a></li>
    <li class="title"><?= _s('Useful') ?></li>
    <li><a href="downloads/"><i class="download lg fw"></i><?= _s('Downloads') ?><span class="badge pull-right">0</span></a></li>
    <li><a href="#"><i class="books lg fw"></i><?= _s('Library') ?><span class="badge pull-right">0</span></a></li>
    <li class="title"><?= _s('Community') ?></li>
    <li><a href="community/"><i class="user lg fw"></i><?= _s('Users') ?><span class="badge pull-right"><?= $this->total_users ?></span></a></li>
    <li><a href="/photoalbum"><i class="picture lg fw"></i><?= _s('Photo Album') ?><span class="badge pull-right">0</span></a></li>
</ul>
