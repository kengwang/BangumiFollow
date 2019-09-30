<?php
/*
Template Name: 追番列表
*/
include_once 'bangumi/apis.php';
$bgmlist=bilibili::getFormatList();
if ($_GET['dbg']=='fuck'){
echo "<pre>";
print_r($bgmlist);
echo "</pre>";
}
get_header();
?>
    <!-- 进度条-->
    <style>
        .skillbar{width:100%;padding:10px;margin:10px 0;position:relative;display:block;overflow:hidden;height:30px;background-color:#9E9E9E;border-radius:5px 0 0 5px}
    </style>
    <script>
        !function(i){i.fn.skillbar=function(t){var e=i.extend({speed:1e3,bg:""},t),n=e.bg,d=i(this).find(".filled"),s=i(this).find(".title");return n?(d.css({"background-color":n}),s.css({"background-color":"#3F51B5"})):this.each(function(t){i(this).find(".filled").animate({width:i(this).find(".filled").data("width")},e.speed)}),this}}(jQuery);
        $(document).ready(function () {
            $('.skillbar').skillbar({
                speed: 3000,
            });
        });
    </script>
    <main id="main" class="site-main" role="main">

        <?php if ( true ) : ?>

            <!-- 番剧列表区 -->
            <section id="post-lists" class="clear-fix">
                <!-- 列表头广告 -->
                <?php echo stripslashes(mk_theme_option('list_head_ad')); ?>

                <?php if(mk_theme_option('list_style') == 'list'): ?>

                    <?php foreach($bgmlist as $bgm):
                       ?>
                        <section id="ss-<?php echo $bgm['ssid']; ?>">
                            <div class="post-item-list" style="height: auto;!important">

                                <a href="<?php echo $bgm['link']; ?>" class="post-item-img">
                                    <img class="anim-trans" style="width: 210px !important;height: 250px !important;" src="<?php echo $bgm['img'] ?>" alt="<?php echo $bgm['name']; ?>" title="<?php echo $bgm['name']; ?>">
                                </a>

                                <header class="entry-header">
                                    <h2 class="entry-title">
                                        <a href="<?php echo $bgm['link'];  ?>" class="anim-trans">
                                            <?php if ( $bgm['followst']==2 ) { echo '<span title="看完" class="article-ontop">看完</span> · ';}?>
                                            <?php echo $bgm['name']; ?>
                                        </a>
                                    </h2>

                                    <div class="archive-content">
                                        <?php
                                        echo  $bgm['des'];
                                        ?>
                                    </div>
                                    <div class="skillbar">
                                        <div class="prog_filled" style="width:<?php echo $bgm['progress'];?>%" data-width="100%"></div>
                                        <span class="prog_title">追番进度</span>
                                        <span class="prog_percent"><?php echo $bgm['watched']."/".$bgm['all'] ?> 话</span>
                                    </div>
<br><br><br>
                                    <span class="entry-meta">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <?php echo $bgm['progress']; ?>%

                                            <i class="fa fa-th" aria-hidden="true"></i>
                                         <?php echo $bgm['new']['title']; ?>

                    <i class="fa fa-star" aria-hidden="true"></i>
                    评分 <?php echo $bgm['score'];?> |
                    硬币 <?php echo $bgm['coin']; ?>
                                    </span>

                                </header><!-- .entry-header -->

                            </div>  <!-- #post-item-list -->
                        </section><!-- #post -->
                    <?php endforeach; ?>

                <?php else: ?>

                    <?php foreach($bgmlist['data']['list'] as $bgm): ?>
                        <article id="post-<?php the_ID(); ?>">
                            <div class="post-item-card">

                                <div class="post-item-card-body">
                                    <a href="<?php the_permalink(); ?>" class="item-thumb">
                                        <figure class="thumbnail" style="background-image:url(<?php echo mk_auto_post_thumbnail(270, 180, false);?>);"></figure>

                                        <div class="archive-content">
                                            <?php
                                            if (has_excerpt()){     // 有摘要
                                                echo wp_trim_words( get_the_excerpt(), 80, '...' );
                                            } elseif (post_password_required()) {   // 密码保护
                                                echo '本文已被密码保护';
                                            } else {
                                                echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 100, '...');
                                            }
                                            ?>
                                        </div>
                                    </a>

                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php if ( is_sticky() ) { echo '<span title="首页置顶" class="article-ontop">置顶</span> · ';}?>
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>

                                        <span class="entry-meta">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <?php echo timeago(get_gmt_from_date(get_the_time('Y-m-d G:i:s'))) ?>

                                            <?php if( function_exists( 'the_views' ) ) { ?>
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <?php if( !post_password_required() ) {the_views();} else { echo '-'; } ;?>
                                            <?php } ?>

                    <i class="fa fa-comments" aria-hidden="true"></i>
                    <?php if( !post_password_required() && comments_open() ) { comments_popup_link( '0', '1', '%', '', '-' ); } else { echo '-'; } ?>
                </span>

                                    </header><!-- .entry-header -->

                                </div>  <!-- #post-item-card-body -->
                            </div>  <!-- #post-item-card -->
                        </article><!-- #post -->
                    <?php endforeach; ?>

                <?php endif; ?>

                <!-- 列表尾广告 -->
                <?php echo stripslashes(mk_theme_option('list_foot_ad')); ?>
            </section>  <!-- .post-lists -->

            <!-- 页码 -->
            <?php mk_pagenavi(); ?>

        <?php else : ?>

            <style>
                .notfound {
                    margin: 50px auto 100px;
                    padding: 30px;
                    text-align: center;
                    background: rgba(255, 255, 255, 0.56);
                    max-width: 500px;
                    border-radius: 10px;
                }
                .notfound-ooops {
                    color: #666;
                    font-size: 24px;
                    margin-bottom: 25px;
                }
                .notfound-face {
                    font-size: 40px;
                }
            </style>




        <?php endif;
        if ($bgmlist['code']!=0){
            echo "获取出错,错误信息:".$bgmlist['message']."(".$bgmlist['code'].")";
        }
        ?>


    </main><!-- .site-main -->

<?php get_footer(); ?>