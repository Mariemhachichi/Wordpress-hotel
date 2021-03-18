<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bussiness_agency
 */

?>
<footer id="footer">
    <?php $copyright = ours_restaurant_get_option('ours_restaurant_copyright');
    if( is_active_sidebar('footer1') || is_active_sidebar('footer2') || is_active_sidebar('footer3') || is_active_sidebar('footer4')|| is_active_sidebar('footerinfo'))
    {
        ?>

        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <?php if( is_active_sidebar('footer1')){ ?>
                        <div class="col-lg-3 col-md-6 footer-info">
                            <?php dynamic_sidebar('footer1'); ?>
                        </div>
                    <?php } if( is_active_sidebar('footer2')){?>

                        <div class="col-lg-3 col-md-6 footer-info">
                            <?php dynamic_sidebar('footer2'); ?>
                        </div>
                    <?php } if( is_active_sidebar('footer3')){?>
                        <div class="col-lg-3 col-md-6 footer-info">
                            <?php dynamic_sidebar('footer3'); ?>
                        </div>
                    <?php } if( is_active_sidebar('footer4')){?>
                        <div class="col-lg-3 col-md-6 footer-info">
                            <?php dynamic_sidebar('footer4'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class="container">
        <div class="copyright">
            <?php echo wp_kses_post($copyright); ?>
        </div>
        <div class="credits">
            <a href="<?php echo esc_url( __( 'https://www.amplethemes.com/', 'ours-restaurant' ) ); ?>"
            ><?php
                /* translators: %s: CMS name, i.e. WordPress. */
                printf( esc_html__( ' Design & develop by AmpleThemes %s', 'ours-restaurant' ), '' );
                ?></a>
        </div>
    </div>
</footer><!-- #footer -->

<a href="#" class="back-to-top"><i class="fas fa-chevron-up"></i></a>
<?php wp_footer(); ?>

</body>
</html>
