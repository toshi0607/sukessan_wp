<div id="topnews">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <dl class="clearfix">
    <dt> <a href="<?php the_permalink() ?>" >
      <?php if ( has_post_thumbnail() ): // サムネイルを持っているときの処理 ?>
      <?php the_post_thumbnail( 'thumb150' ); ?>
      <?php else: // サムネイルを持っていないときの処理 ?>
      <img src="<?php echo get_template_directory_uri(); ?>/images/no-img.png" alt="no image" title="no image" width="100" height="100" />
      <?php endif; ?>
      </a> </dt>
    <dd>
      <h3><a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
        </a></h3>
      <div class="blog_info">
        <p><i class="fa fa-clock-o"></i>&nbsp;
          <?php the_time('Y/m/d') ?>
          &nbsp;<span class="pcone"><i class="fa fa-tags"></i>&nbsp;
          <?php the_category(', ') ?>
          <?php the_tags('', ', '); ?>
          </span></p>
      </div>
      <div class="smanone">
        <?php the_excerpt(); //スマートフォンには表示しない抜粋文 ?>
      </div>
    </dd>
  </dl>
  <?php endwhile; else: ?>
  <p>記事がありません</p>
  <?php endif; ?>
</div>
