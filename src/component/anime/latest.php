<section class="block_area block_area_category lazy-component" data-component="category">
                  <div class="block_area-header">
                       <div class="float-left bah-heading mr-4">
                           <h2 class="cat-heading">
                                 Latest Episode </h2>
                                              </div>
                          <div class="float-right viewmore">
                           <a class="btn" href="/anime/recently-updated">View more<i class="fas fa-angle-right ml-2"></i></a>
                       </div>
                  <div class="clearfix"></div>
                 </div>

                                  <div class="tab-content">
                <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
                <div class="film_list-wrap">
                <?php 
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $itemsPerPage = 12;
                $offset = ($currentPage - 1) * $itemsPerPage;

                $json = json_decode(file_get_contents("$api/category/recently-updated"), true);
                if (isset($json['data']['animes']) && is_array($json['data']['animes'])) {
                    $animesToDisplay = array_slice($json['data']['animes'], $offset, $itemsPerPage);
                    foreach ($animesToDisplay as $anime) { ?>
                        <div class="flw-item">
                            <div class="film-poster">
                                <?php if ($anime['rating']) { ?>
                                    <div class="tick ltr" style="position: absolute; top: 10px; left: 10px;">
                                        <div class="tick-item tick-age amp-algn">18+</div>
                                    </div>
                                <?php } ?>
                                <div class="tick ltr" style="position: absolute; bottom: 10px; left: 10px;">
                                    <?php if (!empty($anime['episodes']['sub'])) { ?>
                                        <div class="tick-item tick-sub amp-algn" style="text-align: left;">
                                            <i class="fas fa-closed-captioning"></i> &nbsp;<?=$anime['episodes']['sub']?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($anime['episodes']['dub'])) { ?>
                                        <div class="tick-item tick-dub amp-algn" style="text-align: left;">
                                            <i class="fas fa-microphone"></i> &nbsp;<?=$anime['episodes']['dub']?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <img class="film-poster-img lazyload" data-src="<?=$anime['poster']?>" src="<?= $websiteUrl ?>/public/images/no_poster.jpg" alt="<?=$anime['name']?>">
                                <a class="film-poster-ahref" href="/details/<?=$anime['id']?>" title="<?=$anime['name']?>">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                            <div class="film-detail">
                                <h3 class="film-name"><a href="/details/<?=$anime['id']?>" title="<?=$anime['name']?>"><?=$anime['name']?></a></h3>
                                <div class="fd-infor">
                                    <span class="fdi-item"><?=$anime['type']?></span>
                                    <span class="dot"></span>
                                    <span class="fdi-item"><?=$anime['duration']?></span>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else {
                    echo "<p>No anime data available or invalid structure.</p>";
                }
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
     </div>
    </section>