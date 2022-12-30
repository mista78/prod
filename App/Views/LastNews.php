<a href="<?= $urlActu ?>" class="hero-full__article-plus" aria-label="<?= "more" ?>">
    <i class="icon"></i>
</a>
<div class="hero-full__article-wrap">
    <div class="hero-full__article-head">
        <h2 class="hero-full__article-title"><?= $labelActu ?></h2>
        <span class="hero-full__article-date"><?= $dateActu ?></span>
    </div>
    <a href="<?= $urlActu ?>" class="hero-full__article-image" aria-label="<?= $titleActu ?>">
        <img src="<?= $imageActu ?>" alt="<?= $titleActu ?>" />
    </a>
    <div class="hero-full__article-body">
        <a href="<?= $urlActu ?>">
            <h3 class="tt-4"><?= $titleActu ?></h3>
        </a>
        <div class="u-txt-size-14">
            <p class="textActu">
                <?= $textActu ?>
            </p>
        </div>
    </div>
</div>