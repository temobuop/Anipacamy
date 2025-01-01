<div id="anime-trending">
    <div class="container">
        <section class="block_area block_area_trending">
            <div class="block_area-header">
                <div class="bah-heading">
                    <h2 class="cat-heading">Trending</h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="block_area-content">
                <div class="trending-list" id="trending-home">
                    <div class="trending-container swiper-container">
                        <div class="swiper-wrapper">
                            <?php if (!empty($data['trendingAnimes'])): ?>
                                <?php foreach ($data['trendingAnimes'] as $index => $anime): ?>
                                    <div class="swiper-slide item-qtip" data-id="<?= htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') ?>">
                                        <div class="item">
                                            <div class="number">
                                                <span><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                                <div class="film-title dynamic-name" data-jname="<?= htmlspecialchars($anime['name'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($anime['name'], ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            </div>
                                            <a href="/details/<?= htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') ?>" class="film-poster">
                                            <img src="<?= htmlspecialchars($anime['poster'], ENT_QUOTES, 'UTF-8') ?>" class="film-poster-img lazyload" title="<?= htmlspecialchars($anime['name'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($anime['name'], ENT_QUOTES, 'UTF-8') ?>">
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
                        <div class="navi-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false" style="margin-bottom: 10px;">
                         
                        </div>
                        <div class="navi-prev swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-disabled="true">
                        
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.js"></script>

<script>
    var trendingSlider = new Swiper('.trending-container', {
        slidesPerView: 4,
        breakpoints: {
            768: {
                slidesPerView: 6
            }
        },
        navigation: {
            nextEl: '.navi-next',
            prevEl: '.navi-prev',
        },
    });
</script>
