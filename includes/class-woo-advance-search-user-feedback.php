<?php

/**
 * Plugin review class
 *
 * Prompts users to give a review of the plugin on WordPress.org after a period of usage.
 * https://github.com/coblocks/coblocks/blob/master/includes/admin/class-
 *
 * @since      1.0.0
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/includes
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Main Feedback Notice Class
 */
class Woo_Advance_Search_User_Feedback
{
    /**
     * Slug.
     *
     * @var string $slug
     */
    private  $slug ;
    /**
     * Name.
     *
     * @var string $name
     */
    private  $name ;
    /**
     * Time limit.
     *
     * @var string $time_limit
     */
    private  $time_limit ;
    /**
     * No Bug Option.
     *
     * @var string $nobug_option
     */
    public  $nobug_option ;
    /**
     * Activation Date Option.
     *
     * @var string $date_option
     */
    public  $date_option ;
    /**
     * Class constructor.
     *
     * @param string $args Arguments.
     */
    public function __construct( $args )
    {
        $this->slug = $args['slug'];
        $this->name = $args['name'];
        $this->date_option = $this->slug . '_activation_date';
        $this->nobug_option = $this->slug . '_no_bug';
        
        if ( isset( $args['time_limit'] ) ) {
            $this->time_limit = $args['time_limit'];
        } else {
            $this->time_limit = WEEK_IN_SECONDS;
        }
        
        // Add actions.
        add_action( 'admin_init', array( $this, 'check_installation_date' ) );
        add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
    }
    
    /**
     * Seconds to words.
     *
     * @param string $seconds Seconds in time.
     */
    public function seconds_to_words( $seconds )
    {
        // Get the years.
        $years = intval( $seconds ) / YEAR_IN_SECONDS % 100;
        
        if ( $years > 1 ) {
            /* translators: Number of years */
            return sprintf( __( '%s years', 'woo-advance-search' ), $years );
        } elseif ( $years > 0 ) {
            return __( 'a year', 'woo-advance-search' );
        }
        
        // Get the weeks.
        $weeks = intval( $seconds ) / WEEK_IN_SECONDS % 52;
        
        if ( $weeks > 1 ) {
            /* translators: Number of weeks */
            return sprintf( __( '%s weeks', 'woo-advance-search' ), $weeks );
        } elseif ( $weeks > 0 ) {
            return __( 'a week', 'woo-advance-search' );
        }
        
        // Get the days.
        $days = intval( $seconds ) / DAY_IN_SECONDS % 7;
        
        if ( $days > 1 ) {
            /* translators: Number of days */
            return sprintf( __( '%s days', 'woo-advance-search' ), $days );
        } elseif ( $days > 0 ) {
            return __( 'a day', 'woo-advance-search' );
        }
        
        // Get the hours.
        $hours = intval( $seconds ) / HOUR_IN_SECONDS % 24;
        
        if ( $hours > 1 ) {
            /* translators: Number of hours */
            return sprintf( __( '%s hours', 'woo-advance-search' ), $hours );
        } elseif ( $hours > 0 ) {
            return __( 'an hour', 'woo-advance-search' );
        }
        
        // Get the minutes.
        $minutes = intval( $seconds ) / MINUTE_IN_SECONDS % 60;
        
        if ( $minutes > 1 ) {
            /* translators: Number of minutes */
            return sprintf( __( '%s minutes', 'woo-advance-search' ), $minutes );
        } elseif ( $minutes > 0 ) {
            return __( 'a minute', 'woo-advance-search' );
        }
        
        // Get the seconds.
        $seconds = intval( $seconds ) % 60;
        
        if ( $seconds > 1 ) {
            /* translators: Number of seconds */
            return sprintf( __( '%s seconds', 'woo-advance-search' ), $seconds );
        } elseif ( $seconds > 0 ) {
            return __( 'a second', 'woo-advance-search' );
        }
    
    }
    
    /**
     * Check date on admin initiation and add to admin notice if it was more than the time limit.
     */
    public function check_installation_date()
    {
        
        if ( !get_site_option( $this->nobug_option ) || false === get_site_option( $this->nobug_option ) ) {
            add_site_option( $this->date_option, time() );
            // Retrieve the activation date.
            $install_date = get_site_option( $this->date_option );
            // If difference between install date and now is greater than time limit, then display notice.
            if ( time() - $install_date > $this->time_limit ) {
                add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
            }
        }
    
    }
    
    /**
     * Display the admin notice.
     */
    public function display_admin_notice()
    {
        $screen = get_current_screen();
        
        if ( isset( $screen->base ) && ('plugins' === $screen->base || 'woocommerce_page_advance-search' === $screen->id) ) {
            $no_bug_url = wp_nonce_url( admin_url( 'plugins.php?' . $this->nobug_option . '=true' ), 'editorsAdvance-search-feedback-nounce' );
            $time = $this->seconds_to_words( time() - get_site_option( $this->date_option ) );
            ?>

			<style>
			.notice.editorWooAdvanceSearch-notice {
				border-left-color: #272c51 !important;
				padding: 20px;
			}
			.rtl .notice.editorWooAdvanceSearch-notice {
				border-right-color: #272c51 !important;
			}
			.notice.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner {
				display: table;
				width: 100%;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-notice-icon,
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-notice-content,
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-install-now {
				display: table-cell;
				vertical-align: middle;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-icon {
				color: #509ed2;
				font-size: 13px;
				width: 60px;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-icon img {
				width: 64px;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-content {
				padding: 0 40px 0 20px;
			}
			.notice.editorWooAdvanceSearch-notice p {
				padding: 0;
				margin: 0;
			}
			.notice.editorWooAdvanceSearch-notice h3 {
				margin: 0 0 5px;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-install-now {
				text-align: center;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-install-now .editorWooAdvanceSearch-install-button {
				padding: 6px 50px;
				height: auto;
				line-height: 20px;
				background: #32396a;
				border-color: #272c51 #0f153e #040823;
				box-shadow: 0 1px 0 #0d1f82;
				text-shadow: 0 -1px 1px #272c51, 1px 0 1px #171b3e, 0 1px 1px #0a1035, -1px 0 1px #040721;
			}
			.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-install-now .editorWooAdvanceSearch-install-button:hover {
				background: #272c51;
			}
			.notice.editorWooAdvanceSearch-notice a.no-thanks {
				display: block;
				margin-top: 10px;
				color: #72777c;
				text-decoration: none;
			}

			.notice.editorWooAdvanceSearch-notice a.no-thanks:hover {
				color: #444;
			}

			@media (max-width: 767px) {

				.notice.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner {
					display: block;
				}
				.notice.editorWooAdvanceSearch-notice {
					padding: 20px !important;
				}
				.notice.editorWooAdvanceSearch-noticee .editorWooAdvanceSearch-notice-inner {
					display: block;
				}
				.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-notice-content {
					display: block;
					padding: 0;
				}
				.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-notice-icon {
					display: none;
				}

				.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .editorWooAdvanceSearch-install-now {
					margin-top: 20px;
					display: block;
					text-align: left;
				}

				.notice.editorWooAdvanceSearch-notice .editorWooAdvanceSearch-notice-inner .no-thanks {
					display: inline-block;
					margin-left: 15px;
				}
			}
			</style>
			<div class="notice updated editorWooAdvanceSearch-notice">
				<div class="editorWooAdvanceSearch-notice-inner">
					<div class="editorWooAdvanceSearch-notice-icon">
						<?php 
            /* translators: 1. Name */
            ?>
						<img src="<?php 
            echo  esc_url( plugins_url( 'admin/images/woo-advance-search.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            printf( esc_attr__( '%s WordPress Plugin', 'woo-advance-search' ), esc_attr( $this->name ) );
            ?>" />
					</div>
					<div class="editorWooAdvanceSearch-notice-content">
						<?php 
            /* translators: 1. Name */
            ?>
						<h3><?php 
            printf( esc_html__( 'Are you enjoying %s Plugin?', 'woo-advance-search' ), esc_html( $this->name ) );
            ?></h3>
						<p>
							<?php 
            /* translators: 1. Name, 2. Time */
            ?>
							<?php 
            printf( esc_html__( 'You have been using %1$s for %2$s now. Mind leaving a review to let us know know what you think? We\'d really appreciate it!', 'woo-advance-search' ), esc_html( $this->name ), esc_html( $time ) );
            ?>
						</p>
					</div>
					<div class="editorWooAdvanceSearch-install-now">
						<?php 
            $review_url = '';
            $review_url = esc_url( 'https://wordpress.org/plugins/woo-advance-search/#reviews' );
            printf( '<a href="%1$s" class="button button-primary editorWooAdvanceSearch-install-button" target="_blank">%2$s</a>', esc_url( $review_url ), esc_html__( 'Leave a Review', 'woo-advance-search' ) );
            ?>
						<a href="<?php 
            echo  esc_url( $no_bug_url ) ;
            ?>" class="no-thanks"><?php 
            echo  esc_html__( 'No thanks / I already have', 'woo-advance-search' ) ;
            ?></a>
					</div>
				</div>
			</div>
			<?php 
        }
    
    }
    
    /**
     * Set the plugin to no longer bug users if user asks not to be.
     */
    public function set_no_bug()
    {
        // Bail out if not on correct page.
        // phpcs:ignore
        if ( !isset( $_GET['_wpnonce'] ) || (!wp_verify_nonce( $_GET['_wpnonce'], 'editorsAdvance-search-feedback-nounce' ) || !is_admin() || !isset( $_GET[$this->nobug_option] ) || !current_user_can( 'manage_options' )) ) {
            return;
        }
        add_site_option( $this->nobug_option, true );
    }

}
/*
* Instantiate the Woo_Advance_Search_User_Feedback     class.
*/
new Woo_Advance_Search_User_Feedback( array(
    'slug'       => 'editors_woo_advance_search_plugin_feedback',
    'name'       => __( 'Advance Search for WooCommerce', 'woo-advance-search' ),
    'time_limit' => WEEK_IN_SECONDS,
) );