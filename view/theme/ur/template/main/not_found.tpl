<!DOCTYPE html><html class="no-js" lang="en"><head><?= $scripts ?></head><body><?= isset($header) ? $header : "" ?>
<?= isset($header_form) ? $header_form : "" ?><section class="page-wrapper mt-50" id="page-content"><!-- SHOP SECTION START--><div class="shop-section mb-80"><div class="container"><? if(isset($flash)): ?><div class="row"><div class="alert alert-danger"><?= $flash['content']; ?></div></div><? endif; ?><div class="row text-center"><div class="col-md-12 col-sm-12 col-xs-12"> <h1>Not found</h1></div></div><div class="row text-center mt-100"><div class="col-md-12 col-sm-12 col-xs-12"><p>The page you've request can't be found. Please proceed to the <a href="/">main page.</a></p></div></div></div></div><?= $footer ?>  </section></body></html>