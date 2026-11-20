<!doctype html><html lang="id"><head><?php head_assets(); ?><title><?= h($title) ?> | E-Arsip</title></head>
<body class="desk"><a class="skip" href="#utama">Ke konten utama</a>
<aside class="side"><div class="side-brand"><?= brand() ?><button type="button" data-menu aria-expanded="false" aria-controls="side-nav" class="mobile-menu"><?= icon('bars') ?> Menu</button></div>
<nav id="side-nav" aria-label="Ruang kerja"><p class="nav-label">RUANG KERJA</p>
<?php foreach ($menu as $key=>[$label,$symbol]): $active = $page === $key || ($page === 'detail' && $key === 'arsip'); ?>
<a href="?page=<?= $key ?>" <?= $active ? 'aria-current="page"' : '' ?>><?= icon($symbol) ?> <?= h($label) ?></a>
<?php endforeach; ?><a class="signout" href="logout"><?= icon('sign-out') ?> Keluar</a></nav></aside>
<main id="utama" class="workspace"><header class="desk-head"><div><p class="eyebrow">E-ARSIP &nbsp;/&nbsp; RUANG KERJA</p><h1><?= h($title) ?></h1></div><a class="account" href="?page=profil"><span class="avatar"><?= h(mb_substr($_SESSION['nama'],0,1)) ?></span><span><?= h($_SESSION['nama']) ?><small><?= $role === 'user' ? 'Wajib pajak' : ucfirst($role) ?></small></span></a></header>
<?php if ($flash): ?><div role="status" class="alert <?= $flash[0] === 'error' ? 'alert-danger':'alert-success' ?>"><?= h($flash[1]) ?></div><?php endif; ?>
<div class="page-content"><?= $content ?></div></main></body></html>
