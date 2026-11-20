<?php
function icon(string $name): string {
    return '<i class="fa fa-' . h($name) . '" aria-hidden="true"></i>';
}
function brand(): string {
    return '<a class="brand" href="./"><img class="brand-mark" src="gambar/depan/11.png" alt="" width="46" height="46"><span>E-Arsip<small>SAMSAT BANDA ACEH</small></span></a>';
}
function head_assets(): void { ?>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/png" sizes="125x125" href="assets/img/logo/logo6.png"><link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/modern-style.css"><script defer src="assets/js/portal.js"></script>
<?php }

