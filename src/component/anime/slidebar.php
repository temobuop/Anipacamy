<div class="deslide-wrap">
    <div class="container">
        <div id="slider" class="slider-container swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($data['spotlightAnimes'] as $index => $anime): ?>
                    <div class="swiper-slide">
                        <div class="deslide-item">
                            <div class="deslide-cover">
                                <div class="deslide-cover-img">
                                    <img class="film-poster-img lazyload" src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
                                </div>
                            </div>
                            <div class="deslide-item-content">
                                <div class="desi-sub-text">#<?= $index + 1 ?> Spotlight</div>
                                <div class="desi-head-title dynamic-name" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></div>
                                <div class="sc-detail">
                                    <div class="scd-item"><i class="fas fa-play-circle mr-1"></i>&nbsp;<?= htmlspecialchars($anime['otherInfo'][0]) ?></div>
                                    <div class="scd-item"><i class="fas fa-clock mr-1"></i>&nbsp;<?= htmlspecialchars($anime['otherInfo'][1]) ?></div>
                                    <div class="scd-item m-hide"><i class="fas fa-calendar mr-1"></i>&nbsp;<?= htmlspecialchars($anime['otherInfo'][2]) ?></div>
                                    <div class="scd-item mr-1"><span class="quality">&nbsp;<?= htmlspecialchars($anime['otherInfo'][3]) ?></span> </div>
                                     <div class="scd-item">
                                        <div class="tick">
                                         <div class="tick-item tick-sub"><i class="fas fa-closed-captioning mr-1"></i> <?= htmlspecialchars($anime['episodes']['sub']) ?></div>
                                          <?php if (!empty($anime['episodes']['dub'])): ?>
                                              <div class="tick-item tick-dub"><i class="fas fa-microphone mr-1"></i> <?= htmlspecialchars($anime['episodes']['dub']) ?></div>
                                          <?php endif; ?>
                                          <div class="tick-item tick-eps"><?= htmlspecialchars($anime['episodes']['sub']) ?></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                               </div>
                                <div class="desi-description"><?= htmlspecialchars($anime['description']) ?></div>
                                <div class="desi-buttons">
                                    <a href="/play/<?= htmlspecialchars($anime['id']) ?>?ep=1" class="btn btn-primary btn-radius mr-2"><i class="fas fa-play-circle mr-2"></i>Watch Now</a>
                                    <a href="/details/<?= htmlspecialchars($anime['id']) ?>" class="btn btn-secondary btn-radius">Detail<i class="fas fa-angle-right ml-2"></i></a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"></div>
            <div class="swiper-navigation">
                <div class="swiper-button swiper-button-next"><i class="fas fa-angle-right"></i></div>
                <div class="swiper-button swiper-button-prev"><i class="fas fa-angle-left"></i></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
         
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.js"></script>
    <script>
    var mainSlider = new Swiper('.slider-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
           
        });
    </script>
