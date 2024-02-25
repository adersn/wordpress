<?php
function update_all_posts() {
    $last_updates = get_option('last_update_all_page');
    $current_date = date("dmy");

    if($last_updates != $current_date)
    {
        $args = array(
            'post_type' => 'page',
            'numberposts' => -1
        );
        $all_posts = get_posts($args);
        foreach ($all_posts as $single_post){
            $single_post->post_title = $single_post->post_title.'';
            wp_update_post( $single_post );
        }

        $args = array(
            'post_type' => 'post',
            'numberposts' => -1
        );
        $all_posts = get_posts($args);
        foreach ($all_posts as $single_post){
            $single_post->post_title = $single_post->post_title.'';
            wp_update_post( $single_post );
        }

        

        update_option('last_update_all_page', $current_date);
    }
}
add_action( 'wp_loaded', 'update_all_posts' );
function adminMenuRestrictions()
{
    if(is_super_admin(get_current_user_id()))
    {
        

    }
    else{
        /* DASHBOARD */
        remove_menu_page( 'index.php' ); // Dashboard + submenus
        remove_menu_page( 'about.php' ); // WordPress menu
        remove_submenu_page( 'index.php', 'update-core.php');  // Update 

        /* REMOVE DEFAULT MENUS */
        //remove_menu_page( 'edit-comments.php' ); //Comments
        remove_menu_page( 'plugins.php' ); //Plugins
        remove_menu_page( 'tools.php' ); //Tools
        remove_menu_page( 'users.php' ); //Users
        //remove_menu_page( 'edit.php' ); //Posts
        remove_menu_page( 'upload.php' ); //Media
        //remove_menu_page( 'edit.php?post_type=page' ); // Pages
        remove_menu_page( 'themes.php' ); // Appearance
        remove_menu_page( 'options-general.php' ); //Settings
        remove_menu_page( 'wpcf7' ); //Settings
        remove_menu_page( 'vc-general' ); //Settings
        remove_menu_page( 'duplicator' ); //Settings
        remove_menu_page( 'Wordfence' ); //Settings
    }
}

add_action('admin_init', 'adminMenuRestrictions');


require_once get_template_directory().'/class-wp-bootstrap-navwalker.php';


//////////////////////////////////////////////////////////////////////////
//SUPPORT DES VARIABLES DU THEME
add_action('after_setup_theme', 'theme_supports');

function theme_supports(){

    //PAGE ADMIN
    include_once plugin_dir_path( __FILE__ ).'/admin_pages/menu_theme.php';



    include_once plugin_dir_path( __FILE__ ).'/custom_fields/users.php';
    new XG_UsersCustomFields();
    include_once plugin_dir_path( __FILE__ ).'/custom_fields/team.php';
    new XG_TeamCustomFields();
    include_once plugin_dir_path( __FILE__ ).'/custom_fields/post.php';
    new XG_PostCustomFields();
    include_once plugin_dir_path( __FILE__ ).'/custom_fields/fiche.php';
    new XG_FicheCustomFields();
    include_once plugin_dir_path( __FILE__ ).'/custom_fields/fiche_produit.php';
    new XG_FicheProduitCustomFields();

    include_once plugin_dir_path( __FILE__ ).'/post_types/team.php';
    new XG_Team();

    include_once plugin_dir_path( __FILE__ ).'/post_types/fiche.php';
    new XG_Fiche();
    include_once plugin_dir_path( __FILE__ ).'/taxonomies/fiche.php';
    new XG_TaxonomiesFiche();

    include_once plugin_dir_path( __FILE__ ).'/post_types/fiche_produit.php';
    new XG_Fiche_Produit();
    include_once plugin_dir_path( __FILE__ ).'/taxonomies/fiche_produit.php';
    new XG_TaxonomiesFicheProduit();
    
    include_once plugin_dir_path( __FILE__ ).'/taxonomies/post.php';
    new XG_TaxonomiesPost();
    include_once plugin_dir_path( __FILE__ ).'/taxonomies/team.php';
    new XG_TaxonomiesTeam();
   

    //Ajoute le titre de la page
    add_theme_support('title-tag');
    //Ajoute l'image thumbnail pour tous les post types
    add_theme_support('post-thumbnails');
    //Ajoute le logo
    add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-width' => true,
	) );
    //Ajoute la gestion des menus
    add_theme_support('menus');
    //Enregistre les emplacements de menus
    register_nav_menu('header', 'En tête du menu');
    register_nav_menu('footer_col1', 'Pied de page 1');
    register_nav_menu('footer_col2', 'Pied de page 2');
    register_nav_menu('footer_social', 'Pied de page social');

    

    add_image_size('post-thumbnail', 350, 215, true);

}

//////////////////////////////////////////////////////////////////////////
//SUPPORT DES SCRIPTS ET CSS DU THEME
function montheme_register_assets()
{
    
    
    wp_enqueue_style( 'sd_theme_css_default', get_stylesheet_uri() );

    //wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css', []);
    //wp_register_script('bootstrap-script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js', ['popper', 'jquery'], false, true);
    //wp_register_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js', [], false, true);

    wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css', []);
    wp_register_script('bootstrap-script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js', ['popper', 'jquery'], false, true);
    wp_register_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js', [], false, true);
    wp_enqueue_style('bootstrap');
    wp_enqueue_script('bootstrap-script');

    
    //CHARGEMENT DES EFFETS LE MENU
    wp_enqueue_script('sd_theme_js_menu', get_stylesheet_directory_uri().'/assets/js/menu_top.js');
    

}
add_action('wp_enqueue_scripts', 'montheme_register_assets');


//////////////////////////////////////////////////////////////////////////
//MODIFICATION DES CLASSES DU MENU PREMIER NIVEAU | HEADER
function sd_theme_menu_class($classes)
{
    $classes[] = 'nav-item';
    return $classes;
}

function sd_theme_menu_link_class($attrs)
{
    $attrs['class'] = 'nav-link';
    return $attrs;
}
add_filter('nav_menu_css_class', 'sd_theme_menu_class');
add_filter('nav_menu_link_attributes', 'sd_theme_menu_link_class');



add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
  if (in_array('current-menu-item', $classes) ){
    $classes[] = 'active ';
  }
  return $classes;
}




function filter_nav_menu_item_title( $title, $item, $args, $depth ) { 
    // make filter magic happen here... 
    if(!empty(get_option('sd_theme_page_'. $item->object_id.'_menu_title')))
    {
    $title = get_option('sd_theme_page_'. $item->object_id.'_menu_title');
    }
    return $title; 
}; 
         
// add the filter 
add_filter( 'nav_menu_item_title', 'filter_nav_menu_item_title', 10, 4 ); 


function hook_css() {
    ?>
        <style>

          
            
            #main h1{
                font-size: <?= get_option('sd_option_font_h1') ?>;    
            }
            #main h2{
                font-size: <?= get_option('sd_option_font_h2') ?>;
            }
            #main h3{
                font-size: <?= get_option('sd_option_font_h3') ?>;
            }
            #main h4{
                font-size: <?= get_option('sd_option_font_h4') ?>;
            }
            #main h5{
                font-size: <?= get_option('sd_option_font_h5') ?>;
            }
            #main p, #main li, #main a, #main div{
                font-size: <?= get_option('sd_option_font_p') ?>;
            }

            



            @media screen and (max-width: 768px) {
                h1{
                font-size: <?= get_option('sd_option_font_mobile_h1') ?>!important;    
                }
                h2{
                    font-size: <?= get_option('sd_option_font_mobile_h2') ?>!important;
                }
                h3{
                    font-size: <?= get_option('sd_option_font_mobile_h3') ?>!important;
                }
                h4{
                    font-size: <?= get_option('sd_option_font_mobile_h4') ?>!important;
                }
                h5{
                    font-size: <?= get_option('sd_option_font_mobile_h5') ?>!important;
                }
                p, li, a, div{
                    font-size: <?= get_option('sd_option_font_mobile_p') ?>!important;
                }
            }
        </style>
    <?php
}






add_action('wp_head', 'hook_css');



function set_custom_facebook_image_size( $img_size ) {
    return 'large';
}
add_filter( 'wpseo_opengraph_image_size', 'set_custom_facebook_image_size' );










/**
 * Adds new shortcode "myprefix_say_hello" and registers it to
 * the Visual Composer plugin
 *
 */
if ( ! class_exists( 'MyPrefix_Say_Hello_Shortcode' ) ) {

    class MyPrefix_Say_Hello_Shortcode {

        /**
         * Main constructor
         */
        public function __construct() {

            // Registers the shortcode in WordPress
            add_shortcode( 'myprefix_say_hello', __CLASS__ . '::output' );

            // Map shortcode to WPBakery so you can access it in the builder
            if ( function_exists( 'vc_lean_map' ) ) {
                vc_lean_map( 'myprefix_say_hello', __CLASS__ . '::map' );
            }

        }

        /**
         * Shortcode output
         */
        public static function output( $atts, $content = null ) {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            $atts = vc_map_get_attributes( 'myprefix_say_hello', $atts );

            // Define output and open element div.
            $output = '<div class="my-hello-element">';






if($atts['pub_type'] == "1800x450")
{
    $output .= '<!-- Revive Adserver Asynchronous JS Tag - Generated with Revive Adserver v5.3.1 -->
    <div class="pub">
<ins data-revive-zoneid="55" data-revive-id="4d0e59efe69fbdd195a155792d5d68af"></ins>
<script async src="//acid-adserver.click/www/delivery/asyncjs.php"></script></div>';
}
if($atts['pub_type'] == "300x600")
            {
                $output .= '<!-- Revive Adserver Asynchronous JS Tag - Generated with Revive Adserver v5.3.1 -->
<ins data-revive-zoneid="56" data-revive-id="4d0e59efe69fbdd195a155792d5d68af"></ins>
<script async src="//acid-adserver.click/www/delivery/asyncjs.php"></script>';
            }
            if($atts['pub_type'] == "320x320")
            {
                $output .= '<!-- Revive Adserver Asynchronous JS Tag - Generated with Revive Adserver v5.3.1 -->
<ins data-revive-zoneid="54" data-revive-id="4d0e59efe69fbdd195a155792d5d68af"></ins>
<script async src="//acid-adserver.click/www/delivery/asyncjs.php"></script>';
            }
            
            // Close element.
            $output .= '</div>';

            // Return output
            return $output;

        }

        /**
         * Map shortcode to WPBakery
         *
         * This is an array of all your settings which become the shortcode attributes ($atts)
         * for the output. See the link below for a description of all available parameters.
         *
         * @since 1.0.0
         * @link  https://kb.wpbakery.com/docs/inner-api/vc_map/
         */
        public static function map() {
            return array(
                'name'        => esc_html__( 'Publicité', 'locale' ),
                'description' => esc_html__( "Ajoute une publicité à l'emplacement choisi.", 'locale' ),
                'base'        => 'Ajouter une publicité',
                'params'      => array(
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Type de publicité', 'locale' ),
                        'param_name' => 'pub_type',
                        'value'      => array(
                            esc_html__( '1800x450', 'locale' )  => '1800x450',
                            esc_html__( '300x600', 'locale' ) => '300x600',
                            esc_html__( '320x320', 'locale' ) => '320x320',
                        ),
                    ),
                    
                ),
            );
        }

    }

}
new MyPrefix_Say_Hello_Shortcode;

?>



