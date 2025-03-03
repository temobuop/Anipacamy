<div class="container">
    <div class="anif-blocks">
        <div class="row">
            <?php
            if (!empty($data['topAiringAnimes'])) {
                $data['topAiringAnimes'] = array_slice($data['topAiringAnimes'], 0, 5);
            ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="anif-block anif-block-01">
                    <div class="anif-block-header">Top Airing</div>
                    <div class="anif-block-ul">
                        <ul class="ulclear">
                            <?php foreach ($data['topAiringAnimes'] as $index => $anime): ?>
                            <li>
                                <div class="film-poster item-qtip" data-id="<?php echo $anime['id']; ?>">
                                <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>">
                                        <img data-src="<?php echo htmlspecialchars($anime['poster']); ?>" 
                                             class="film-poster-img lazyload"
                                             src="<?= $websiteUrl ?>/public/images/no_poster.jpg"
                                             alt="<?php echo htmlspecialchars($anime['name']); ?>">
                                    </a>
                                </div>
                                <div class="film-detail">
                                    <h3 class="film-name">
                                        <a href="/details/<?php echo $anime['id']; ?>"
                                           title="<?php echo htmlspecialchars($anime['name']); ?>"
                                           class="dynamic-name anime-toggle"
                                           data-en="<?php echo htmlspecialchars($anime['name']); ?>"
                                           data-jp="<?php echo htmlspecialchars($anime['jname']); ?>"
                                           style="opacity: 1;">
                                            <?php echo htmlspecialchars($anime['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="fd-infor">
                                        <div class="tick">
                                            <?php if(isset($anime['episodes']['sub'])): ?>
                                            <div class="tick-item tick-sub">
                                                <i class="fas fa-closed-captioning mr-1"></i><?php echo $anime['episodes']['sub']; ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if(isset($anime['episodes']['dub'])): ?>
                                            <div class="tick-item tick-dub">
                                                <i class="fas fa-microphone mr-1"></i><?php echo $anime['episodes']['dub']; ?>
                                            </div>
                                            <?php endif; ?>

                                            <div>
                                                <span class="dot"></span><?php echo $anime['type']; ?>
                                                <?php if(isset($anime['duration'])): ?>
                                                <span class="fdi-item"><?php echo $anime['duration']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="more"><a href="/anime/top-airing">View more <i class="fas fa-angle-right ml-2"></i></a></div>
                </div>
            </div>
            <?php } ?>
            <?php
            if (!empty($data['mostPopularAnimes'])) {
            ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="anif-block anif-block-04">
                    <div class="anif-block-header">Most Popular</div>
                    <div class="anif-block-ul">
                        <ul class="ulclear">
                            <?php foreach($data['mostPopularAnimes'] as $anime): ?>
                            <li>
                                <div class="film-poster item-qtip" data-id="<?php echo htmlspecialchars($anime['id']); ?>">
                                <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>">
                                        <img data-src="<?php echo htmlspecialchars($anime['poster']); ?>" 
                                             class="film-poster-img lazyload"
                                             src="<?= $websiteUrl ?>/public/images/no_poster.jpg"
                                             alt="<?php echo htmlspecialchars($anime['name']); ?>">
                                    </a>
                                </div>
                                <div class="film-detail">
                                    <h3 class="film-name">
                                        <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>"
                                           title="<?php echo htmlspecialchars($anime['name']); ?>"
                                           class="dynamic-name anime-toggle"
                                           data-en="<?php echo htmlspecialchars($anime['name']); ?>"
                                           data-jp="<?php echo htmlspecialchars($anime['jname']); ?>"
                                           style="opacity: 1;">
                                            <?php echo htmlspecialchars($anime['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="fd-infor">
                                        <div class="tick">
                                            <?php if(isset($anime['episodes']['sub'])): ?>
                                            <div class="tick-item tick-sub">
                                                <i class="fas fa-closed-captioning mr-1"></i><?php echo htmlspecialchars($anime['episodes']['sub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if(isset($anime['episodes']['dub'])): ?>
                                            <div class="tick-item tick-dub">
                                                <i class="fas fa-microphone mr-1"></i><?php echo htmlspecialchars($anime['episodes']['dub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <div>
                                                <span class="dot"></span><?php echo htmlspecialchars($anime['type']); ?>
                                                <?php if(isset($anime['duration'])): ?>
                                                <span class="fdi-item"><?php echo htmlspecialchars($anime['duration']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="more"><a href="/anime/most-popular">View more <i class="fas fa-angle-right ml-2"></i></a></div>
                </div>
            </div>
            <?php } ?>
            <?php
            if (!empty($data['mostFavoriteAnimes'])) {
            ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="anif-block anif-block-04">
                    <div class="anif-block-header">Most Favorite</div>
                    <div class="anif-block-ul">
                        <ul class="ulclear">
                            <?php foreach($data['mostFavoriteAnimes'] as $anime): ?>
                            <li>
                                <div class="film-poster item-qtip" data-id="<?php echo htmlspecialchars($anime['id']); ?>">
                                <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>">
                                        <img data-src="<?php echo htmlspecialchars($anime['poster']); ?>" 
                                             class="film-poster-img lazyload"
                                             src="<?= $websiteUrl ?>/public/images/no_poster.jpg"
                                             alt="<?php echo htmlspecialchars($anime['name']); ?>">
                                    </a>
                                </div>
                                <div class="film-detail">
                                    <h3 class="film-name">
                                        <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>"
                                           title="<?php echo htmlspecialchars($anime['name']); ?>"
                                           class="dynamic-name anime-toggle"
                                           data-en="<?php echo htmlspecialchars($anime['name']); ?>"
                                           data-jp="<?php echo htmlspecialchars($anime['jname']); ?>"
                                           style="opacity: 1;">
                                            <?php echo htmlspecialchars($anime['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="fd-infor">
                                        <div class="tick">
                                            <?php if(isset($anime['episodes']['sub'])): ?>
                                            <div class="tick-item tick-sub">
                                                <i class="fas fa-closed-captioning mr-1"></i><?php echo htmlspecialchars($anime['episodes']['sub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if(isset($anime['episodes']['dub'])): ?>
                                            <div class="tick-item tick-dub">
                                                <i class="fas fa-microphone mr-1"></i><?php echo htmlspecialchars($anime['episodes']['dub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            
                                            
                                            <span class="dot"></span><?php echo htmlspecialchars($anime['type']); ?>
                                            <?php if(isset($anime['duration'])): ?>
                                            <span class="fdi-item"><?php echo htmlspecialchars($anime['duration']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="more">
                        <a href="/anime/most-favorite">View more <i class="fas fa-angle-right ml-2"></i></a>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php
            if (!empty($data['latestCompletedAnimes'])) {
            ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="anif-block anif-block-01">
                    <div class="anif-block-header">Latest Completed</div>
                    <div class="anif-block-ul">
                        <ul class="ulclear">
                            <?php foreach($data['latestCompletedAnimes'] as $anime): ?>
                            <li>
                                <div class="film-poster item-qtip" data-id="<?php echo htmlspecialchars($anime['id']); ?>">
                                    <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>">
                                        <img data-src="<?php echo htmlspecialchars($anime['poster']); ?>" 
                                             class="film-poster-img lazyload"
                                             src="<?= $websiteUrl ?>/public/images/no_poster.jpg"
                                             alt="<?php echo htmlspecialchars($anime['name']); ?>">
                                    </a>
                                </div>
                                <div class="film-detail">
                                    <h3 class="film-name">
                                        <a href="/details/<?php echo htmlspecialchars($anime['id']); ?>"
                                           title="<?php echo htmlspecialchars($anime['name']); ?>"
                                           class="dynamic-name anime-toggle"
                                           data-en="<?php echo htmlspecialchars($anime['name']); ?>"
                                           data-jp="<?php echo htmlspecialchars($anime['jname']); ?>"
                                           style="opacity: 1;">
                                            <?php echo htmlspecialchars($anime['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="fd-infor">
                                        <div class="tick">
                                            <?php if(isset($anime['episodes']['sub'])): ?>
                                            <div class="tick-item tick-sub">
                                                <i class="fas fa-closed-captioning mr-1"></i><?php echo htmlspecialchars($anime['episodes']['sub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if(isset($anime['episodes']['dub'])): ?>
                                            <div class="tick-item tick-dub">
                                                <i class="fas fa-microphone mr-1"></i><?php echo htmlspecialchars($anime['episodes']['dub']); ?>
                                            </div>
                                            <?php endif; ?>

                                            
                                            
                                            <span class="dot"></span><?php echo htmlspecialchars($anime['type']); ?>
                                            <?php if(isset($anime['duration'])): ?>
                                            <span class="fdi-item"><?php echo htmlspecialchars($anime['duration']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="more">
                        <a href="/anime/completed">View more <i class="fas fa-angle-right ml-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php } ?>
</div>
