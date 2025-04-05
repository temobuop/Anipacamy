<div id="anime-trending">
    <div class="container">
        <section class="block_area block_area_trending">
            <div class="block_area-header">
                <div class="bah-heading">
                    <h2 class="cat-heading">Trending Anime</h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="block_area-content">
                <div class="trending-list" id="trending-home">
                    <div class="trending-container-anime swiper-container">
                        <div class="swiper-wrapper">
                            <?php if (!empty($data['trending'])): ?>
                                <?php foreach ($data['trending'] as $index => $anime): ?>
                                    <div class="swiper-slide item-qtip" data-id="<?= htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') ?>">
                                        <div class="item">
                                            <div class="number">
                                                <span><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                                <div class="film-title dynamic-name" data-jname="<?= htmlspecialchars($anime['japanese_title'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($anime['title'], ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            </div>
                                            <a href="/details/<?= htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') ?>" class="film-poster">
                                            <img src="<?= htmlspecialchars($anime['poster'], ENT_QUOTES, 'UTF-8') ?>" class="film-poster-img lazyload" title="<?= htmlspecialchars($anime['title'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($anime['title'], ENT_QUOTES, 'UTF-8') ?>">
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No trending animes available at the moment.</p>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                        </div>
                    <div class="trending-navi" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="navi-next navi-next-anime" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"><i class="fas fa-angle-right"></i></div>
                        <div class="navi-prev swiper-button-disabled navi-prev-anime" tabindex="-1" role="button" aria-label="Previous slide" aria-disabled="true"><i class="fas fa-angle-left"></i></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="<?= $websiteUrl ?>/src/assets/js/swiper-bundle.min.js"></script>

<script>
    var trendingSlider = new Swiper('.trending-container-anime', {
        slidesPerView: 3,
        breakpoints: {
            768: {
                slidesPerView: 6
            }
        },
        navigation: {
            nextEl: '.navi-next-anime',
            prevEl: '.navi-prev-anime',
        },
    });
</script>