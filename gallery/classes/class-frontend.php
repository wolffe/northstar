<?php
if (!defined('ABSPATH')) exit;

class Cinnamon_Frontend_User_Manager {
	public function __construct() {
		add_action('wp_ajax_cinnamon_ajax_login', [$this, 'cinnamon_ajax_login']);
		add_action('wp_ajax_nopriv_cinnamon_ajax_login', [$this, 'cinnamon_ajax_login']);
		add_action('wp_ajax_cinnamon_process_registration', [$this, 'cinnamon_process_registration']);
		add_action('wp_ajax_nopriv_cinnamon_process_registration', [$this, 'cinnamon_process_registration']);
		add_action('wp_ajax_cinnamon_process_psw_recovery', [$this, 'cinnamon_process_psw_recovery']);
		add_action('wp_ajax_nopriv_cinnamon_process_psw_recovery', [$this, 'cinnamon_process_psw_recovery']);

		add_shortcode('cinnamon-login', [$this,'cinnamon_user_frontend_shortcode']);
	}

	public function cinnamon_login_form() { ?>
        <div class="ip-tab">
            <ul class="ip-tabs active">
                <li class="current"><a href="#"><i class="fas fa-sign-in-alt"></i> Log in</a></li>
                <li class=""><a href="#"><i class="fas fa-user"></i> Sign up</a></li>
                <li class=""><a href="#"><i class="fas fa-question-circle"></i> Lost password</a></li>
            </ul>
            <div class="tab_content">
                <div class="ip-tabs-item" style="display: block;">
                    <?php if (!is_user_logged_in()) :
                        $login_arguments = [
                            'echo'           => true,
                            'remember'       => true,
                            'redirect'       => 'https://posterspy.com/',
                            'form_id'        => 'loginform',
                            'id_username'    => 'user_login',
                            'id_password'    => 'user_pass',
                            'id_remember'    => 'rememberme',
                            'id_submit'      => 'wp-submit',
                            'label_username' => 'Username',
                            'label_password' => 'Password',
                            'label_remember' => 'Remember Me',
                            'label_log_in'   => 'Log In',
                            'value_username' => '',
                            'value_remember' => true
                        ];
                        wp_login_form($login_arguments); ?>

                        <?php /** ?>
                        <form action="login" method="post" id="form" name="loginform">
                            <h2>Log in</h2>
                            <p><input type="text" name="log" id="login_user" value="<?php if(isset($user_login)) echo esc_attr($user_login); ?>" size="32" placeholder="Username"></p>
                            <p><input type="password" name="pwd" id="login_pass" value="" size="32" placeholder="Password"></p>
                            <p><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="true" checked> Remember me</label></p>
                            <p><input type="submit" name="wp-sumbit" id="wp-submit" value="Log in"></p>
                            <input type="hidden" name="login" value="true">
                            <?php wp_nonce_field('ajax_form_nonce', 'security'); ?>
                        </form>
                        <?php /**/ ?>
                    <?php else : ?>
                        <p>You are already logged in.</p>
                    <?php endif; ?>
                </div>
                <div class="ip-tabs-item">
                    <?php if (!is_user_logged_in()) : ?>
                        <form action="register" method="post" id="regform" name="registrationform">
                            <h2>Sign up</h2>
                            <p><input type="text" name="user_login" id="reg_user" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="Username"></p>
                            <p><input type="email" name="user_email" id="reg_email" value="<?php if (isset($user_email)) echo esc_attr(stripslashes($user_email)); ?>" size="32" placeholder="Email address"></p>
                            <p>A password will be emailed to you.</p>
                            <p><input type="submit" name="user-sumbit" id="user-submit" value="Sign up"></p>
                            <input type="hidden" name="register" value="true">
                            <?php wp_nonce_field('ajax_form_nonce', 'security'); ?>
                        </form>
                    <?php else : ?>
                        <p>You are already logged in.</p>
                    <?php endif; ?>
                </div>
                <div class="ip-tabs-item">
                    <form action="resetpsw" method="post" id="pswform" name="passwordform">
                        <h2>Lost your password?</h2>
                        <p><input type="text" name="forgot_login" id="forgot_login" class="input" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="Username or email address"></p>
                        <p><input type="submit" name="fum-psw-sumbit" id="fum-psw-submit" value="Reset password"></p>
                        <input type="hidden" name="forgotten" value="true">
                        <?php wp_nonce_field('ajax_form_nonce', 'security'); ?>
                    </form>
                </div>
            </div>
        </div>
    <?php
	}

    public function cinnamon_process_registration() {
        check_ajax_referer('ajax_form_nonce', 'security');

        $user_login = $_REQUEST['user_login'];
        $user_email = $_REQUEST['user_email'];

        $errors = register_new_user($user_login, $user_email);

        if (is_wp_error($errors)) {
            $registration_error_messages = $errors->errors;
            $display_errors = '<ul>';
                foreach ($registration_error_messages as $error) {
                    $display_errors .= '<li>' . $error[0] . '</li>';
                }
            $display_errors .= '</ul>';

            echo json_encode([
                'registered' => false,
                'message' => sprintf('Something was wrong:</br> %s', $display_errors)
            ]);
        } else {
            echo json_encode([
                'registered' => true,
                'message' => 'Registration was successful!'
            ]);

            $user_id = $errors;

            // Multisite fix
            add_user_to_blog(1, $user_id, 'author');
        }

        die();
	}

	public function cinnamon_process_psw_recovery() {
		check_ajax_referer('ajax_form_nonce', 'security');

		if (is_email($_REQUEST['username'])) {
			$username = sanitize_email($_REQUEST['username']);
		} else {
			$username = sanitize_user($_REQUEST['username']);
		}

		$user_forgotten = $this->cinnamon_retrieve_password($username);

		if (is_wp_error($user_forgotten)) {
			echo json_encode([
				'reset' => false,
				'message' => $user_forgotten->get_error_message(),
			]);
		}
        else {
			echo json_encode([
				'reset' => true,
				'message' => 'Password reset. Please check your email.',
			]);
		}

		die();
	}

	public function cinnamon_retrieve_password($user_data) {
		global $wpdb, $current_site;

		$errors = new WP_Error();
		if (empty($user_data)) {
			$errors->add('empty_username', 'Please enter a username or email address.');
		} else if(strpos($user_data, '@')) {
			$user_data = get_user_by('email', trim($user_data));
			if (empty($user_data)) {
				$errors->add('invalid_email', 'There is no user registered with that email address.');
			}
		} else {
			$login = trim($user_data);
			$user_data = get_user_by('login', $login);
		}

        if ($errors->get_error_code())
			return $errors;
		if (!$user_data) {
			$errors->add('invalidcombo', 'Invalid username or email address.');
			return $errors;
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if (!$allow) {
			return new WP_Error('no_password_reset', 'Password reset is not allowed for this user');
		} else if(is_wp_error($allow)) {
			return $allow;
		}

        $user_id = $user_data->ID;
        $password = wp_generate_password();
        wp_set_password($password, $user_id);

		$message = 'Someone requested that your password be reset for the following account: ' . $key . "\r\n\r\n";
		$message .= network_home_url('/') . "\r\n\r\n";
		$message .= sprintf('Username: %s', $user_login) . "\r\n\r\n";
		$message .= 'Your new password is ' . $password . "\r\n\r\n";

		if (is_multisite()) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else {
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		}

		$title = sprintf('[%s] Password reset', $blogname);
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);

		if ($message && ! wp_mail($user_email, $title, $message)) {
			$errors->add('noemail', 'The email could not be sent. Possible reason: your host may have disabled the mail() function.');

            return $errors;
            wp_die();
        }
        return true;
    }

	public function cinnamon_user_frontend_shortcode($atts, $content = null) {
        extract(shortcode_atts([
            'form' => '',
        ], $atts));
        ob_start();
        $this->cinnamon_login_form();

        return ob_get_clean();
    }
}

$cinnamon_frontend_user_manager = new Cinnamon_Frontend_User_Manager();
