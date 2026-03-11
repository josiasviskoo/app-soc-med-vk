<?php
/*
Plugin Name: Viskoo Approval
Plugin URI: https://viskoo.com.br
Description: Aplicação de aprovação da conteúdo para clientes exclusivos da Viskoo.
Version: 1.0.0
Author: Josias Viskoo
Author URI: https://viskoo.com.br
Text Domain: viskoo-approval
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// define constants
if ( ! defined( 'VISKOO_APPROVAL_PLUGIN_DIR' ) ) {
    define( 'VISKOO_APPROVAL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'VISKOO_APPROVAL_PLUGIN_URL' ) ) {
    define( 'VISKOO_APPROVAL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
// slugs for pages used by activation
if ( ! defined( 'VISKOO_APPROVAL_PAGE_SLUG' ) ) {
    define( 'VISKOO_APPROVAL_PAGE_SLUG', 'dashboard' );
}
if ( ! defined( 'VISKOO_LOGIN_PAGE_SLUG' ) ) {
    define( 'VISKOO_LOGIN_PAGE_SLUG', 'login-media' );
}

class Viskoo_Approval {
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_post_statuses' ) );
        add_action( 'init', array( $this, 'register_roles' ) );
        add_action( 'admin_init', array( $this, 'block_dashboard_admin_from_backend' ) );
        add_filter( 'show_admin_bar', array( $this, 'hide_admin_bar_for_dashboard_admin' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta' ) );
        add_shortcode( 'viskoo_dashboard', array( $this, 'render_dashboard_shortcode' ) );
        add_shortcode( 'viskoo_login', array( $this, 'render_login_shortcode' ) );
        add_action( 'template_redirect', array( $this, 'maybe_redirect_login_page' ) );
        add_action( 'wp_ajax_viskoo_toggle_status', array( $this, 'ajax_toggle_status' ) );
        add_action( 'wp_ajax_viskoo_save_comment', array( $this, 'ajax_save_comment' ) );
    }

    /**
     * Register custom post types for clients, headlines and contents.
     */
    public function register_post_types() {
        // Client CPT
        $labels = array(
            'name'               => __( 'Clientes', 'viskoo-approval' ),
            'singular_name'      => __( 'Cliente', 'viskoo-approval' ),
            'menu_name'          => __( 'Clientes', 'viskoo-approval' ),
            'name_admin_bar'     => __( 'Cliente', 'viskoo-approval' ),
            'add_new'            => __( 'Adicionar Novo', 'viskoo-approval' ),
            'add_new_item'       => __( 'Adicionar Novo Cliente', 'viskoo-approval' ),
            'new_item'           => __( 'Novo Cliente', 'viskoo-approval' ),
            'edit_item'          => __( 'Editar Cliente', 'viskoo-approval' ),
            'view_item'          => __( 'Ver Cliente', 'viskoo-approval' ),
            'all_items'          => __( 'Todos os Clientes', 'viskoo-approval' ),
            'search_items'       => __( 'Procurar Clientes', 'viskoo-approval' ),
            'not_found'          => __( 'Nenhum cliente encontrado.', 'viskoo-approval' ),
            'not_found_in_trash' => __( 'Nenhum cliente na lixeira.', 'viskoo-approval' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'supports'           => array( 'title', 'editor' ),
            'has_archive'        => false,
            'capability_type'    => 'post',
            'rewrite'            => false,
        );
        register_post_type( 'viskoo_client', $args );

        // Headline CPT
        $labels = array(
            'name'               => __( 'Headlines', 'viskoo-approval' ),
            'singular_name'      => __( 'Headline', 'viskoo-approval' ),
            'menu_name'          => __( 'Headlines', 'viskoo-approval' ),
            'name_admin_bar'     => __( 'Headline', 'viskoo-approval' ),
            'add_new'            => __( 'Adicionar Novo', 'viskoo-approval' ),
            'add_new_item'       => __( 'Adicionar Nova Headline', 'viskoo-approval' ),
            'new_item'           => __( 'Nova Headline', 'viskoo-approval' ),
            'edit_item'          => __( 'Editar Headline', 'viskoo-approval' ),
            'view_item'          => __( 'Ver Headline', 'viskoo-approval' ),
            'all_items'          => __( 'Todas as Headlines', 'viskoo-approval' ),
            'search_items'       => __( 'Procurar Headlines', 'viskoo-approval' ),
            'not_found'          => __( 'Nenhuma headline encontrada.', 'viskoo-approval' ),
            'not_found_in_trash' => __( 'Nenhuma headline na lixeira.', 'viskoo-approval' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'supports'           => array( 'title', 'editor', 'author' ),
            'has_archive'        => false,
            'capability_type'    => 'post',
            'rewrite'            => false,
        );
        register_post_type( 'viskoo_headline', $args );

        // Content CPT
        $labels = array(
            'name'               => __( 'Conteúdos', 'viskoo-approval' ),
            'singular_name'      => __( 'Conteúdo', 'viskoo-approval' ),
            'menu_name'          => __( 'Conteúdos', 'viskoo-approval' ),
            'name_admin_bar'     => __( 'Conteúdo', 'viskoo-approval' ),
            'add_new'            => __( 'Adicionar Novo', 'viskoo-approval' ),
            'add_new_item'       => __( 'Adicionar Novo Conteúdo', 'viskoo-approval' ),
            'new_item'           => __( 'Novo Conteúdo', 'viskoo-approval' ),
            'edit_item'          => __( 'Editar Conteúdo', 'viskoo-approval' ),
            'view_item'          => __( 'Ver Conteúdo', 'viskoo-approval' ),
            'all_items'          => __( 'Todos os Conteúdos', 'viskoo-approval' ),
            'search_items'       => __( 'Procurar Conteúdos', 'viskoo-approval' ),
            'not_found'          => __( 'Nenhum conteúdo encontrado.', 'viskoo-approval' ),
            'not_found_in_trash' => __( 'Nenhum conteúdo na lixeira.', 'viskoo-approval' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
            'has_archive'        => false,
            'capability_type'    => 'post',
            'rewrite'            => false,
        );
        register_post_type( 'viskoo_content', $args );
    }

    /**
     * Register additional post statuses used by the workflow.
     */
    public function register_post_statuses() {
        register_post_status( 'pending', array(
            'label'                     => _x( 'Pendente', 'post status', 'viskoo-approval' ),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Pendente <span class="count">(%s)</span>', 'Pendentes <span class="count">(%s)</span>', 'viskoo-approval' ),
        ) );
        register_post_status( 'approved', array(
            'label'                     => _x( 'Aprovado', 'post status', 'viskoo-approval' ),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Aprovado <span class="count">(%s)</span>', 'Aprovados <span class="count">(%s)</span>', 'viskoo-approval' ),
        ) );
        register_post_status( 'rejected', array(
            'label'                     => _x( 'Revisão', 'post status', 'viskoo-approval' ),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Revisão <span class="count">(%s)</span>', 'Revisões <span class="count">(%s)</span>', 'viskoo-approval' ),
        ) );
    }

    /**
     * Create a custom role for dashboard administrators.
     */
    public function register_roles() {
        add_role( 'viskoo_dashboard_admin', 'Administrador da Dashboard', array(
            'read' => true,
            'edit_posts' => false,
            'upload_files' => false,
            'level_0' => true,
        ) );
    }

    public function block_dashboard_admin_from_backend() {
        if ( current_user_can( 'viskoo_dashboard_admin' ) && is_admin() && ! defined( 'DOING_AJAX' ) ) {
            wp_redirect( home_url( '/login-media' ) );
            exit;
        }
    }

    public function hide_admin_bar_for_dashboard_admin( $show ) {
        if ( current_user_can( 'viskoo_dashboard_admin' ) ) {
            return false;
        }
        return $show;
    }

    /**
     * Enqueue frontend assets (css/js) based on dashboard.html.
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'viskoo-dashboard-style', VISKOO_APPROVAL_PLUGIN_URL . 'assets/css/dashboard.css' );
        wp_enqueue_script( 'viskoo-dashboard-js', VISKOO_APPROVAL_PLUGIN_URL . 'assets/js/dashboard.js', array( 'jquery' ), '1.0.0', true );
        wp_localize_script( 'viskoo-dashboard-js', 'viskoo_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'viskoo_nonce' ),
        ) );
    }

    /**
     * Add custom meta boxes for headlines and contents.
     */
    public function add_meta_boxes() {
        add_meta_box( 'viskoo_meta_content', __( 'Detalhes de Conteúdo', 'viskoo-approval' ), array( $this, 'render_content_meta_box' ), 'viskoo_content', 'side', 'default' );
        add_meta_box( 'viskoo_meta_headline', __( 'Detalhes de Headline', 'viskoo-approval' ), array( $this, 'render_headline_meta_box' ), 'viskoo_headline', 'side', 'default' );
        add_meta_box( 'viskoo_meta_client', __( 'Informações do Cliente', 'viskoo-approval' ), array( $this, 'render_client_meta_box' ), 'viskoo_client', 'normal', 'default' );
    }

    public function render_content_meta_box( $post ) {
        wp_nonce_field( 'viskoo_save_meta', 'viskoo_meta_nonce' );
        $date   = get_post_meta( $post->ID, '_viskoo_suggested_date', true );
        $platform = get_post_meta( $post->ID, '_viskoo_platform', true );
        $format   = get_post_meta( $post->ID, '_viskoo_format', true );
        ?>
        <p><label><?php _e( 'Data sugerida', 'viskoo-approval' ); ?>
        <input type="date" name="viskoo_suggested_date" value="<?php echo esc_attr( $date ); ?>" /></label></p>
        <p><label><?php _e( 'Plataforma', 'viskoo-approval' ); ?>
        <select name="viskoo_platform">
            <option value="">—</option>
            <option value="instagram" <?php selected( $platform, 'instagram' ); ?>>Instagram</option>
            <option value="facebook" <?php selected( $platform, 'facebook' ); ?>>Facebook</option>
            <option value="both" <?php selected( $platform, 'both' ); ?>>IG/FB</option>
        </select></label></p>
        <p><label><?php _e( 'Formato', 'viskoo-approval' ); ?>
        <select name="viskoo_format">
            <option value="feed" <?php selected( $format, 'feed' ); ?>>Feed</option>
            <option value="stories" <?php selected( $format, 'stories' ); ?>>Stories</option>
            <option value="carousel" <?php selected( $format, 'carousel' ); ?>>Carrossel</option>
            <option value="reels" <?php selected( $format, 'reels' ); ?>>Reels</option>
        </select></label></p>
        <?php
    }

    public function render_headline_meta_box( $post ) {
        wp_nonce_field( 'viskoo_save_meta', 'viskoo_meta_nonce' );
        $date   = get_post_meta( $post->ID, '_viskoo_suggested_date', true );
        $platform = get_post_meta( $post->ID, '_viskoo_platform', true );
        ?>
        <p><label><?php _e( 'Data sugerida', 'viskoo-approval' ); ?>
        <input type="date" name="viskoo_suggested_date" value="<?php echo esc_attr( $date ); ?>" /></label></p>
        <p><label><?php _e( 'Plataforma', 'viskoo-approval' ); ?>
        <select name="viskoo_platform">
            <option value="">—</option>
            <option value="instagram" <?php selected( $platform, 'instagram' ); ?>>Instagram</option>
            <option value="facebook" <?php selected( $platform, 'facebook' ); ?>>Facebook</option>
            <option value="both" <?php selected( $platform, 'both' ); ?>>IG/FB</option>
        </select></label></p>
        <?php
    }

    public function render_client_meta_box( $post ) {
        wp_nonce_field( 'viskoo_save_meta', 'viskoo_meta_nonce' );
        $sub = get_post_meta( $post->ID, '_viskoo_subheadline', true );
        ?>
        <p><label><?php _e( 'Sub-headline', 'viskoo-approval' ); ?>
        <input type="text" name="viskoo_subheadline" value="<?php echo esc_attr( $sub ); ?>" class="widefat" /></label></p>
        <?php
    }

    public function save_meta( $post_id ) {
        // save client subheadline if present
        if ( isset( $_POST['viskoo_subheadline'] ) && get_post_type( $post_id ) === 'viskoo_client' ) {
            update_post_meta( $post_id, '_viskoo_subheadline', sanitize_text_field( $_POST['viskoo_subheadline'] ) );
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! isset( $_POST['viskoo_meta_nonce'] ) || ! wp_verify_nonce( $_POST['viskoo_meta_nonce'], 'viskoo_save_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        $fields = array( 'viskoo_suggested_date', 'viskoo_platform', 'viskoo_format' );
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }

        // if post is pending schedule auto approval after 24h
        $status = get_post_status( $post_id );
        if ( in_array( $status, array( 'pending' ), true ) ) {
            wp_schedule_single_event( time() + DAY_IN_SECONDS, 'viskoo_auto_approve', array( $post_id ) );
        }
    }

    /**
     * Activation routine called when plugin is activated.
     * Creates the dashboard & login pages with shortcodes.
     */
    public static function activate() {
        self::create_page( VISKOO_APPROVAL_PAGE_SLUG, 'Dashboard', '[viskoo_dashboard]' );
        self::create_page( VISKOO_LOGIN_PAGE_SLUG, 'Login Media', '[viskoo_login]' );
    }

    /**
     * Utility to create a WP page if not exists.
     */
    private static function create_page( $slug, $title, $content ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
        }
    }

    /**
     * Shortcode for rendering the dashboard.
     */
    public function render_dashboard_shortcode( $atts ) {
        if ( ! is_user_logged_in() || ! current_user_can( 'viskoo_dashboard_admin' ) ) {
            return '<p>Você precisa estar logado como administrador da dashboard para ver esta página.</p>';
        }

        // get client info - simple example: current user meta or first client
        $client = get_posts( array( 'post_type' => 'viskoo_client', 'numberposts' => 1 ) );
        $client = $client ? $client[0] : null;
        $client_name = $client ? $client->post_title : 'Cliente';
        $client_sub = $client ? get_post_meta( $client->ID, '_viskoo_subheadline', true ) : '';

        ob_start();
        // read the master HTML file and only keep the body content (avoids full document tags)
        $raw = file_get_contents( VISKOO_APPROVAL_PLUGIN_DIR . 'templates/dashboard.html' );
        if ( $raw !== false ) {
            // extract between <body> and </body>
            $start = stripos( $raw, '<body' );
            $end   = stripos( $raw, '</body>' );
            if ( $start !== false && $end !== false ) {
                $body = substr( $raw, strpos( $raw, '>', $start ) + 1, $end - strpos( $raw, '>', $start ) - 1 );
            } else {
                $body = $raw;
            }
            // remove any <style>...</style> and <script>...</script> blocks to avoid duplication; assets are enqueued separately
            $body = preg_replace('#<style[^>]*>.*?</style>#is', '', $body);
            $body = preg_replace('#<script[^>]*>.*?</script>#is', '', $body);

            // replace client placeholders
            $body = str_replace( 'Bella Cucina', esc_html( $client_name ), $body );
            $body = str_replace( 'Restaurante & Bar', esc_html( $client_sub ), $body );
            echo $body;

            // prepare JS data for headlines and contents from WP
            $wp_headlines = array();
            $posts = get_posts( array( 'post_type' => 'viskoo_headline', 'posts_per_page' => -1, 'post_status' => array('pending','approved','rejected') ) );
            foreach ( $posts as $p ) {
                $wp_headlines[] = array(
                    'id'       => $p->ID,
                    'date'     => get_post_meta( $p->ID, '_viskoo_suggested_date', true ),
                    'headline' => $p->post_title,
                    'platform' => get_post_meta( $p->ID, '_viskoo_platform', true ),
                    'status'   => $p->post_status,
                );
            }
            $wp_contents = array();
            $posts = get_posts( array( 'post_type' => 'viskoo_content', 'posts_per_page' => -1, 'post_status' => array('pending','approved','rejected') ) );
            foreach ( $posts as $p ) {
                $wp_contents[] = array(
                    'id'       => $p->ID,
                    'date'     => get_post_meta( $p->ID, '_viskoo_suggested_date', true ),
                    'caption'  => $p->post_content,
                    'mediaType'=> 'image',
                    'mediaSrc' => get_the_post_thumbnail_url( $p, 'full' ),
                    'platform' => get_post_meta( $p->ID, '_viskoo_platform', true ),
                    'status'   => $p->post_status,
                    'contentFormat' => get_post_meta( $p->ID, '_viskoo_format', true ),
                    'carouselImages' => array(),
                );
            }
            echo '<script>window.viskoo_headlines = ' . wp_json_encode( $wp_headlines ) . '; window.viskoo_contents = ' . wp_json_encode( $wp_contents ) . ';</script>';
        } else {
            echo '<p>Erro ao carregar o template da dashboard.</p>';
        }
        return ob_get_clean();
    }

    /**
     * Render custom login page for dashboard administrators.
     */
    public function render_login_shortcode( $atts ) {
        if ( is_user_logged_in() ) {
            if ( current_user_can( 'viskoo_dashboard_admin' ) ) {
                wp_safe_redirect( home_url( '/dashboard' ) );
                exit;
            } else {
                return '<p>Seu usuário não possui permissão para acessar o painel de aprovação.</p>';
            }
        }
        ob_start();
        $args = array(
            'redirect' => home_url( '/dashboard' ),
            'form_id'  => 'viskoo-login-form',
            'label_username' => __( 'Usuário', 'viskoo-approval' ),
            'label_password' => __( 'Senha', 'viskoo-approval' ),
            'label_log_in'   => __( 'Entrar', 'viskoo-approval' ),
            'remember'       => false,
            'id_username'    => 'user_login',
            'id_password'    => 'user_pass',
        );
        wp_login_form( $args );
        return ob_get_clean();
    }

    /**
     * Redirect /login-media to a page with login shortcode.
     */
    public function maybe_redirect_login_page() {
        global $pagenow;
        if ( ! is_admin() && trim( $_SERVER['REQUEST_URI'], '/' ) === 'login-media' ) {
            // render a simple page
            add_filter( 'the_content', function( $content ) {
                return do_shortcode( '[viskoo_login]' );
            } );
        }
    }

    /**
     * AJAX handler to toggle status.
     */
    public function ajax_toggle_status() {
        check_ajax_referer( 'viskoo_nonce', 'nonce' );
        if ( ! current_user_can( 'viskoo_dashboard_admin' ) ) {
            wp_send_json_error( 'unauthorized' );
        }
        $post_id = intval( $_POST['post_id'] );
        $status  = sanitize_text_field( $_POST['status'] );
        if ( in_array( $status, array( 'pending', 'approved', 'rejected' ), true ) ) {
            wp_update_post( array( 'ID' => $post_id, 'post_status' => $status ) );
            wp_send_json_success();
        } else {
            wp_send_json_error( 'invalid_status' );
        }
    }

    /**
     * AJAX handler to save comment when rejecting.
     */
    public function ajax_save_comment() {
        check_ajax_referer( 'viskoo_nonce', 'nonce' );
        if ( ! current_user_can( 'viskoo_dashboard_admin' ) ) {
            wp_send_json_error( 'unauthorized' );
        }
        $post_id = intval( $_POST['post_id'] );
        $comment = wp_kses_post( $_POST['comment'] );
        if ( $post_id && $comment !== '' ) {
            add_comment( array(
                'comment_post_ID'      => $post_id,
                'comment_author'       => wp_get_current_user()->display_name,
                'comment_content'      => $comment,
                'comment_type'         => 'viskoo_feedback',
                'user_id'              => get_current_user_id(),
                'comment_approved'     => 1,
            ) );
            wp_send_json_success();
        }
        wp_send_json_error( 'invalid' );
    }

    /**
     * Handler for auto approval cron.
     */
    public static function auto_approve_post( $post_id ) {
        if ( get_post_status( $post_id ) === 'pending' ) {
            wp_update_post( array( 'ID' => $post_id, 'post_status' => 'approved' ) );
        }
    }
}

// hook the cron event
add_action( 'viskoo_auto_approve', array( 'Viskoo_Approval', 'auto_approve_post' ) );

// activation tasks
register_activation_hook( __FILE__, array( 'Viskoo_Approval', 'activate' ) );

// bootstrap the plugin
new Viskoo_Approval();

// extend activation function inside main class
add_action( 'init', function() {
    if ( method_exists( 'Viskoo_Approval', 'activate' ) ) {
        // nothing here, activation hook already registered
    }
} );

// we can't easily modify class earlier; let's add activate method by editing class definition

