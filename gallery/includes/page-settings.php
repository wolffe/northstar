<?php
function imagepress_admin_page() {
	?>
	<div class="wrap">
		<h2><b>Image</b>Press Settings</h2>

		<?php
		$t = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard_tab';
		if(isset($_GET['tab']))
			$t = $_GET['tab'];

        $i = 'poster';
		?>
		<h2 class="nav-tab-wrapper">
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=dashboard_tab" class="nav-tab <?php echo $t == 'dashboard_tab' ? 'nav-tab-active' : ''; ?>"><div class="dashicons dashicons-info"></div></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=install_tab" class="nav-tab <?php echo $t == 'install_tab' ? 'nav-tab-active' : ''; ?>">Installation</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=settings_tab" class="nav-tab <?php echo $t == 'settings_tab' ? 'nav-tab-active' : ''; ?>">Settings</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=configurator_tab" class="nav-tab <?php echo $t == 'configurator_tab' ? 'nav-tab-active' : ''; ?>">Configurator</a>

            <a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=collections_tab" class="nav-tab <?php echo $t == 'collections_tab' ? 'nav-tab-active' : ''; ?>">Collections</a>

			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=label_tab" class="nav-tab <?php echo $t == 'label_tab' ? 'nav-tab-active' : ''; ?>">Labels</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=notifications_tab" class="nav-tab <?php echo $t == 'notifications_tab' ? 'nav-tab-active' : ''; ?>">Feed Ads &amp; Notifications</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=upload_tab" class="nav-tab <?php echo $t == 'upload_tab' ? 'nav-tab-active' : ''; ?>">Upload</a>
            <?php if(get_option('cinnamon_mod_hub') == 0) { ?>
			    <a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=users_tab" class="nav-tab <?php echo $t == 'users_tab' ? 'nav-tab-active' : ''; ?>">Users</a>
            <?php } else { ?>
			    <a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=users_tab" class="nav-tab <?php echo $t == 'users_tab' ? 'nav-tab-active' : ''; ?>">Users (HUB)</a>
            <?php } ?>
            <a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=login_tab" class="nav-tab <?php echo $t == 'login_tab' ? 'nav-tab-active' : ''; ?>">Login</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=hooks_tab" class="nav-tab <?php echo $t == 'hooks_tab' ? 'nav-tab-active' : ''; ?>">Hooks</a>
		</h2>

		<?php if($t == 'dashboard_tab') {
            // Get the WP built-in version
            $wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver;
            $ipdata = get_plugin_data(IP_PLUGIN_FILE_PATH);

            echo '<div class="wrap">
				<h2><b>Image</b>Press</h2>

				<div id="poststuff" class="ui-sortable meta-box-sortables">
					<div class="postbox">
						<h3>Dashboard (Help and general usage)</h3>
						<div class="inside">
							<p>Thank you for using <b>Image</b>Press, a multi-purpose fully-featured and WordPress-integrated image gallery plugin.</p>
        					<p>
								<small>You are using <b>Image</b>Press plugin version <strong>' . $ipdata['Version'] . '</strong> with <a href="//fontawesome.io/" rel="external">FontAwesome</a> 4.5.0 and jQuery ' . $wp_jquery_ver . '.</small><br>
								<small>You are using PHP version ' . PHP_VERSION . ' and MySQL server version ' . mysqli_get_client_info() . '.</small><br>
							</p>

							<h4>Help with shortcodes</h4>
							<p>
								Use the shortcode tag <code>[imagepress-add]</code> in any post or page to show the submission form.<br>
								Use the shortcode tag <code>[imagepress-add category="landscapes"]</code> in any post or page to show the submission form with a fixed (hidden) category. Use this option for category-based contests. Use the category <b>slug</b>.<br>
                                Use the shortcode tag <code>[imagepress-add exclude="1,2,3"]</code> in any post or page to exclude certain categories from image submission.<br>
								Use the shortcode tag <code>[imagepress-add-bulk]</code> in any post or page to show the bulk submission form.<br>
								<br>
								Use the shortcode tag <code>[imagepress-search]</code> in any post or page to show the search form.<br>
								<br>
								Use the shortcode tag <code>[imagepress-show]</code> in any post or page to display all images.<br>
								Use the shortcode tag <code>[imagepress-show category="landscapes"]</code> in any post or page to display all images in a specific category. Use the category <b>slug</b>.<br>
								Use the shortcode tag <code>[imagepress-show sort="none"]</code> in any post or page to hide the category sorter/selector.<br>
								Use the shortcode tag <code>[imagepress-show author="yes"]</code> in any post or page to show the author sorter/selector.<br>

								Use the shortcode tag <code>[imagepress-show count="4"]</code> in any post or page to display a specific number of images.<br>
								Use the shortcode tag <code>[imagepress-show user="7"]</code> in any post or page to filter images by user ID.<br>
								<br>
								Use the shortcode tag <code>[imagepress type="top" mode="views" count="1"]</code> in any post or page to display the most viewed image.<br>
								Use the shortcode tag <code>[imagepress type="top" mode="likes" count="1"]</code> in any post or page to display the most voted image.<br>
								<br>
                                Use the shortcode tag <code>[imagepress mode="views"]</code> in a text widget to display most viewed images.<br>
                                Use the shortcode tag <code>[imagepress mode="likes"]</code> in a text widget to display most liked/voted images.<br>
                                Use the shortcode tag <code>[imagepress mode="likes/views" <b>count="10"</b>]</code> to adjust the number of displayed images.<br>
                                <br>
                                Use the shortcode tag <code>[imagepress-show sort="category" author="yes"]</code> to display category sort dropdown.<br>

								<br>
                                Use the shortcode tag <code>[notifications]</code> in any post or page to display the notifications (this feature is in beta status).<br>
								<br>
								Use the shortcode tag <code>[imagepress-collections count="X"]</code> in any post or page to display X collections.<br>
							</p>

							<h4>Help with styling</h4>
							<p>See <code>/documentation/single-image.php</code> for a sample single image template. Match it with your <code>/single.php</code> template structure and drop it in your active theme.</p>
							<p>Use the <code>.ip_box_img</code> class to activate lightboxes (based on element class).</p>

                            <h4>Profile Usage</h4>
                            <p>In order to view the user profile or portfolio, you need to create (or edit) the <code>author.php</code> file in your theme folder (<code>wp-content/themes/your-theme/author.php</code>) and add the following code:</p>

                            <p><textarea class="large-text code" rows="6">
&lt;?php
// BEGIN IMAGEPRESS AUTHOR CODE
if(function_exists("ip_author"))
	ip_author();
// END IMAGEPRESS AUTHOR CODE
?&gt;
                            </textarea></p>
                            <p>
                                If you want to show the profile on a custom page, such as <b>My Profile</b> or <b>View My Portfolio</b>, use the <code>[cinnamon-profile]</code> shortcode.<br>
                                If you want to show a certain user profile on a page, use the <code>[cinnamon-profile author=17]</code> shortcode, where <b>17</b> is the user ID.
                            </p>
                            <p>In order for the above to work, you need to edit your .htaccess file and add these lines at the end:</p>
                            <p><textarea class="large-text code" rows="6">
# BEGIN ImagePress Author Rewrite
RewriteCond %{HTTP_HOST} !^www\.domain.com
RewriteCond %{HTTP_HOST} ([^.]+)\.domain.com
RewriteRule ^(.*)$ ?author_name=%1
# END ImagePress Author Rewrite
                            </textarea></p>
						</div>
					</div>

                    <div class="postbox">
                        <div class="inside">
                            <p>For support, feature requests and bug reporting, please visit the <a href="//getbutterfly.com/" rel="external">official website</a>.</p>
                            <p>&copy;' . date('Y') . ' <a href="//getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="//getbutterfly.com/trac/" rel="external">Trac</a> &middot; <small>Code wrangling since 2005</small></p>
                        </div>
                    </div>
				</div>
			</div>';
		} ?>
		<?php if($t == 'install_tab') { ?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3>Installation</h3>
					<div class="inside">
                        <?php
            			if(isset($_POST['isResetSubmit'])) {
                            global $wpdb;
            				$ip_vote_meta = get_option('ip_vote_meta');
            
            				$wpdb->query("UPDATE " . $wpdb->prefix . "postmeta SET meta_value = '0' WHERE meta_key = '" . $ip_vote_meta . "'");
                            echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
            			}
            			if(isset($_POST['isUpgrade'])) {
            				delete_option('ip_presstrends');
            				delete_option('ip_default_category');
            				delete_option('ip_default_category_show');
            				delete_option('ip_author_filter');
            				delete_option('ip_box_styling');
            				delete_option('ip_box_hover');
            				delete_option('gs_title_colour');
            				delete_option('gs_text_colour');
            				delete_option('gs_cpt');
            				delete_option('ip_module_masonry');
            				delete_option('ip_width');
            				delete_option('ip_content_optional');
            				delete_option('ip_url_optional');
            				delete_option('ip_module_flip');
            				delete_option('cinnamon_post_type');
            
            				delete_option('cinnamon_colour');
            				delete_option('cinnamon_colour_step');
            				delete_option('cinnamon_hover_colour');
            				delete_option('act_settings');
            				delete_option('cinnamon_awards_more');
            				delete_option('cinnamon_mod_activity');
            				delete_option('cinnamon_style_pure');
            
            				delete_option('ip_timebeforerevote');
            			    delete_option('ip_module_slider');
            				delete_option('ip_lightbox');
            
            				delete_option('gs_category', 0);
            				delete_option('gs_slides', 5);
            				delete_option('gs_width', '100%');
            				delete_option('gs_autoplay', 0);
            				delete_option('gs_secondary_background', '#dd9933');
            				delete_option('gs_secondary_border', '#000000');
            				delete_option('gs_secondary_border_type', 'solid');
            				delete_option('gs_easing_style', 'easeOutQuint');
            				delete_option('gs_additional_levels', 1);
            
            				delete_option('cinnamon_text_colour');
            				delete_option('cinnamon_background_colour');
            				delete_option('ip_box_background');
            				delete_option('ip_text_colour');
            				delete_option('ip_cookie_expiration');
            				delete_option('cinnamon_show_progress');
            				delete_option('cinnamon_profile_title');
            				delete_option('ip_createusers');
            				delete_option('cinnamon_show_online');
            				delete_option('ip_disqus');
                            delete_option('ip_upload_redirection');
                            delete_option('ip_delete_redirection');
                            delete_option('ip_print_label');
                            delete_option('ip_tag_label');
                            delete_option('ip_allow_tags');
                            delete_option('cms_available_for_print');
                            delete_option('ip_behance_label');
                            delete_option('cinnamon_pt_social');
                            delete_option('cinnamon_show_comments');
                            delete_option('cinnamon_show_uploads');

                            delete_post_meta_by_key('imagepress_print');
                            delete_post_meta_by_key('imagepress_behance');

            				delete_metadata('user', 0, 'hub_gender', '', true);

                            $user_ID = get_current_user_id();
            				delete_user_meta($user_ID, 'cinnamon_action_time');
            
            				wp_clear_scheduled_hook('act_cron_daily');
            
            				global $wp_taxonomies;

                            $taxonomy = 'imagepress_image_property';
            				if(taxonomy_exists($taxonomy))
            					unset($wp_taxonomies[$taxonomy]);

                            $taxonomy = 'imagepress_image_tag';
            				if(taxonomy_exists($taxonomy))
            					unset($wp_taxonomies[$taxonomy]);

                            echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
            			}
            			?>

            			<hr>
    					<h2>Maintenance</h2>
						<form method="post" action="">
							<p>
								<input type="submit" name="isResetSubmit" value="Reset all likes" class="button-primary">
                                <br><small>This option resets all image likes to 0. This action is irreversible.</small>
							</p>
							<p>
								<input type="submit" name="isUpgrade" value="Clean up" class="button-primary">
                                <br><small>This option cleans up old/orphaned settings. This action is irreversible.</small>
							</p>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
		<?php if($t == 'configurator_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_ipp', $_POST['ip_ipp']);
				update_option('ip_app', $_POST['ip_app']);
				update_option('ip_padding', $_POST['ip_padding']);
				update_option('ip_order', $_POST['ip_order']);
				update_option('ip_orderby', $_POST['ip_orderby']);

				update_option('ip_image_size',          $_POST['ip_image_size']);
				update_option('ip_title_optional',      $_POST['ip_title_optional']);
				update_option('ip_meta_optional',       $_POST['ip_meta_optional']);
				update_option('ip_views_optional',      $_POST['ip_views_optional']);
				update_option('ip_comments',            $_POST['ip_comments']);
				update_option('ip_likes_optional',      $_POST['ip_likes_optional']);
				update_option('ip_author_optional',     $_POST['ip_author_optional']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<form method="post" action="">
				<h3 class="title">Grid Configurator</h3>
				<p>The <b>Grid configurator</b> allows you to select which information will be visible inside the image box.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label>Image box details</label></th>
			                <td>
                            <p>
                                <select name="ip_image_size" id="ip_image_size">
                                    <optgroup label="WordPress (Default)">
                                        <option value="thumbnail"<?php if(get_option('ip_image_size') == 'thumbnail') echo ' selected'; ?>>Thumbnail</option>
                                    </optgroup>

                                    <optgroup label="Small">
                                        <option value="imagepress_sq_sm"<?php if(get_option('ip_image_size') == 'imagepress_sq_sm') echo ' selected'; ?>>Small (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_sm"<?php if(get_option('ip_image_size') == 'imagepress_pt_sm') echo ' selected'; ?>>Small (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_sm"<?php if(get_option('ip_image_size') == 'imagepress_ls_sm') echo ' selected'; ?>>Small (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="Standard">
                                        <option value="imagepress_sq_std"<?php if(get_option('ip_image_size') == 'imagepress_sq_std') echo ' selected'; ?>>Standard (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_std"<?php if(get_option('ip_image_size') == 'imagepress_pt_std') echo ' selected'; ?>>Standard (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_std"<?php if(get_option('ip_image_size') == 'imagepress_ls_std') echo ' selected'; ?>>Standard (Landscape) (ImagePress)</option>
                                    </optgroup>
                                </select> <label for="ip_image_size"><b>Image box</b> thumbnail size</label>
                                <br><small>Use <b>thumbnail</b>, adjust the column size to match your thumbnail size and hide the description in order to have uniform sizes</small>
                            </p>
							<p>
								<select name="ip_title_optional" id="ip_title_optional">
									<option value="0"<?php if(get_option('ip_title_optional') == 0) echo ' selected'; ?>>Hide image title</option>
									<option value="1"<?php if(get_option('ip_title_optional') == 1) echo ' selected'; ?>>Show image title</option>
								</select>
								<label for="ip_title_optional">Show/hide image title</label>
							</p>
							<p>
								<select name="ip_meta_optional" id="ip_meta_optional">
									<option value="0"<?php if(get_option('ip_meta_optional') == 0) echo ' selected'; ?>>Hide image meta</option>
									<option value="1"<?php if(get_option('ip_meta_optional') == 1) echo ' selected'; ?>>Show image meta</option>
								</select>
								<label for="ip_meta_optional">Show/hide the image meta (category/taxonomy)</label>
							</p>
							<p>
								<select name="ip_views_optional" id="ip_views_optional">
									<option value="0"<?php if(get_option('ip_views_optional') == 0) echo ' selected'; ?>>Hide image views</option>
									<option value="1"<?php if(get_option('ip_views_optional') == 1) echo ' selected'; ?>>Show image views</option>
								</select>
								<label for="ip_views_optional">Show/hide the number of image views</label>
							</p>
							<p>
								<select name="ip_likes_optional" id="ip_likes_optional">
									<option value="0"<?php if(get_option('ip_likes_optional') == 0) echo ' selected'; ?>>Hide image likes</option>
									<option value="1"<?php if(get_option('ip_likes_optional') == 1) echo ' selected'; ?>>Show image likes</option>
								</select>
								<label for="ip_likes_optional">Show/hide the number of image likes</label>
							</p>
							<p>
								<select name="ip_comments" id="ip_comments">
									<option value="0"<?php if(get_option('ip_comments') == '0') echo ' selected'; ?>>Hide image comments</option>
									<option value="1"<?php if(get_option('ip_comments') == '1') echo ' selected'; ?>>Show image comments</option>
								</select>
								<label for="ip_comments">Show/hide the number of image comments</label>
							</p>
							<p>
								<select name="ip_author_optional" id="ip_author_optional">
									<option value="0"<?php if(get_option('ip_author_optional') == 0) echo ' selected'; ?>>Hide image author</option>
									<option value="1"<?php if(get_option('ip_author_optional') == 1) echo ' selected'; ?>>Show image author</option>
								</select>
								<label for="ip_author_optional">Show/hide the author name and link</label>
							</p>
							</td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">Grid Settings</h3>
				<p>These settings apply globally for the image grid.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label>Grid details</label></th>
			                <td>
    							<p>
    								<input type="number" name="ip_ipp" id="ip_ipp" min="1" max="9999" value="<?php echo get_option('ip_ipp'); ?>">
    								<label for="ip_ipp">Images per page</label>
    								<br><small>How many images per page you want to display.</small>
    							</p>
    							<p>
    								<input type="number" name="ip_app" id="ip_app" min="1" max="9999" value="<?php echo get_option('ip_app'); ?>">
    								<label for="ip_app">Authors per page</label>
    								<br><small>How many authors per page you want to display.</small>
    							</p>
    							<p>
    								<input type="number" name="ip_padding" id="ip_padding" min="0" max="9999" step="0.5" value="<?php echo get_option('ip_padding'); ?>">
    								<label for="ip_padding">Image padding</label>
    								<br><small>Gap between images (in pixels).</small>
    							</p>
    							<p>
    							    <label for="ip_order">Sort images</label>
    								<select name="ip_order" id="ip_order">
    									<option value="ASC"<?php if(get_option('ip_order') == 'ASC') echo ' selected'; ?>>ASC</option>
    									<option value="DESC"<?php if(get_option('ip_order') == 'DESC') echo ' selected'; ?>>DESC</option>
    								</select> <label for="ip_orderby">by</label> <select name="ip_orderby" id="ip_orderby">
    									<option value="none"<?php if(get_option('ip_orderby') == 'none') echo ' selected'; ?>>none</option>
    									<option value="ID"<?php if(get_option('ip_orderby') == 'ID') echo ' selected'; ?>>ID</option>
    									<option value="author"<?php if(get_option('ip_orderby') == 'author') echo ' selected'; ?>>author</option>
    									<option value="title"<?php if(get_option('ip_orderby') == 'title') echo ' selected'; ?>>title</option>
    									<option value="name"<?php if(get_option('ip_orderby') == 'name') echo ' selected'; ?>>name</option>
    									<option value="date"<?php if(get_option('ip_orderby') == 'date') echo ' selected'; ?>>date</option>
    									<option value="rand"<?php if(get_option('ip_orderby') == 'rand') echo ' selected'; ?>>rand</option>
    									<option value="comment_count"<?php if(get_option('ip_orderby') == 'comment_count') echo ' selected'; ?>>comment_count</option>
    								</select>
    							</p>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
            </form>
		<?php } ?>
		<?php if($t == 'collections_tab') { ?>
			<?php
            global $wpdb;
            $orphan_count = $wpdb->get_var("SELECT COUNT(*) FROM `" . $wpdb->prefix . "ip_collectionmeta` WHERE `image_ID` NOT IN (SELECT `ID` FROM `" . $wpdb->posts . "`)");

			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_collections_page', $_POST['ip_collections_page']);
				update_option('ip_collections_read_more_link', $_POST['ip_collections_read_more_link']);
				update_option('ip_collections_read_more', $_POST['ip_collections_read_more']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			if(isset($_POST['isCollectionCU'])) {
                $wpdb->query("DELETE FROM `" . $wpdb->prefix . "ip_collectionmeta` WHERE `image_ID` NOT IN (SELECT `ID` FROM `" . $wpdb->posts . "`)");

				echo '<div class="updated notice is-dismissible"><p>Collection images cleaned up successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3>Collections</h3>
					<div class="inside">
						<form method="post" action="">
                            <p>
								<?php
								wp_dropdown_pages([
									'name' => 'ip_collections_page',
									'echo' => 1,
									'show_option_none' => 'Select collections page...',
									'option_none_value' => '0',
									'selected' => get_option('ip_collections_page')
								]);
								?> <label for="ip_collections_page">Collections page</label>
                                <br><small>This page should contain the collections shortcode (i.e. [imagepress-show collection="1"]</code>)</small>
                            </p>
							<p>
                                <input name="ip_collections_read_more_link" id="ip_collections_read_more_link" type="url" class="regular-text" placeholder="http://" value="<?php echo get_option('ip_collections_read_more_link'); ?>"> <label for="ip_collections_read_more_link">"Read more" link</label>
                                <br><small>Add a link to your help page or a help document</small>
							</p>
							<p>
                                <input name="ip_collections_read_more" id="ip_collections_read_more" type="text" class="regular-text" placeholder="Read more" value="<?php echo get_option('ip_collections_read_more'); ?>"> <label for="ip_collections_read_more">"Read more" label</label>
                                <br><small>Label the link to your help page or a help document</small>
							</p>
                            <p>
                                <input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"> 
                                <input type="submit" name="isCollectionCU" value="Remove <?php echo $orphan_count; ?> missing image references" class="button-secondary alignright"> 
                            </p>
							<p>Use the shortcode tag <code>[imagepress-collections count="X"]</code> in any post or page to display X collections.</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'login_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_login_flat_mode', $_POST['ip_login_flat_mode']);
				update_option('ip_login_image', $_POST['ip_login_image']);
				update_option('ip_login_bg', $_POST['ip_login_bg']);
				update_option('ip_login_box_bg', $_POST['ip_login_box_bg']);
				update_option('ip_login_box_text', $_POST['ip_login_box_text']);
				update_option('ip_login_page_text', $_POST['ip_login_page_text']);
				update_option('ip_login_button_bg', $_POST['ip_login_button_bg']);
				update_option('ip_login_button_text', $_POST['ip_login_button_text']);
				update_option('ip_login_copyright', sanitize_text_field($_POST['ip_login_copyright']));

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<form method="post" action="">
				<h3 class="title">Login/Registration</h3>
    			<p>This section allows you to customize the native WordPress login/registration page (<code>/wp-login.php</code>) by adding/removing/renaming elements and changing default colours and background properties.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="ip_login_flat_mode">Flat mode</label></th>
			                <td>
                                <select name="ip_login_flat_mode" id="ip_login_flat_mode">
                                    <option value="1"<?php if(get_option('ip_login_flat_mode') == 1) echo ' selected'; ?>>Enable flat mode</option>
                                    <option value="0"<?php if(get_option('ip_login_flat_mode') == 0) echo ' selected'; ?>>Disable flat mode</option>
                                </select>
                                <br><small>Flat mode will remove the login box shadow, button styles and rounded borders.</small>
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_image">Page background image<br><small>(optional)</small></label></th>
			                <td>
                                <input type="url" name="ip_login_image" id="ip_login_image" class="regular-text" value="<?php echo get_option('ip_login_image'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_bg">Page background colour</label></th>
			                <td>
                                <input type="text" name="ip_login_bg" id="ip_login_bg" class="ip_colorpicker" data-default-color="#FEFEFE" value="<?php echo get_option('ip_login_bg'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_box_bg">Login box background colour</label></th>
			                <td>
                                <input type="text" name="ip_login_box_bg" id="ip_login_box_bg" class="ip_colorpicker" data-default-color="#FFFFFF" value="<?php echo get_option('ip_login_box_bg'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_button_bg">Login button background colour</label></th>
			                <td>
                                <input type="text" name="ip_login_button_bg" id="ip_login_button_bg" class="ip_colorpicker" data-default-color="#00A0D2" value="<?php echo get_option('ip_login_button_bg'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_button_text">Login button text colour</label></th>
			                <td>
                                <input type="text" name="ip_login_button_text" id="ip_login_button_text" class="ip_colorpicker" data-default-color="#FFFFFF" value="<?php echo get_option('ip_login_button_text'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_box_text">Text colour<br><small>(inside login box)</small></label></th>
			                <td>
                                <input type="text" name="ip_login_box_text" id="ip_login_box_text" class="ip_colorpicker" data-default-color="#000000" value="<?php echo get_option('ip_login_box_text'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_page_text">Text colour<br><small>(outside login box)</small></label></th>
			                <td>
                                <input type="text" name="ip_login_page_text" id="ip_login_page_text" class="ip_colorpicker" data-default-color="#000000" value="<?php echo get_option('ip_login_page_text'); ?>">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_login_copyright">Copyright line<br><small>(optional)</small></label></th>
			                <td>
                                <input type="text" name="ip_login_copyright" id="ip_login_copyright" class="regular-text" value="<?php echo get_option('ip_login_copyright'); ?>">
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
			</form>
		<?php } ?>
        <?php if($t == 'settings_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_moderate', $_POST['ip_moderate']);
				update_option('ip_registration', $_POST['ip_registration']);

				update_option('ip_cat_moderation_include', $_POST['ip_cat_moderation_include']);

                // modules
                update_option('cinnamon_mod_hub', $_POST['cinnamon_mod_hub']);

				update_option('ip_notification_email', $_POST['ip_notification_email']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<form method="post" action="">
				<h3 class="title">Modules</h3>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="cinnamon_mod_hub">User hub <sup><small>BETA|DEV</small></sup></label></th>
			                <td>
                                <select name="cinnamon_mod_hub" id="cinnamon_mod_hub">
                                    <option value="1"<?php if(get_option('cinnamon_mod_hub') == 1) echo ' selected'; ?>>Enable hub</option>
                                    <option value="0"<?php if(get_option('cinnamon_mod_hub') == 0) echo ' selected'; ?>>Disable hub</option>
                                </select>
                                <br><small>Enable a subdomain address for users (e.g. jack.yourdomain.com).</small>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">General Settings</h3>
				<p>These settings apply globally for all ImagePress users.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="ip_registration">User registration</label></th>
			                <td>
								<select name="ip_registration" id="ip_registration">
									<option value="0"<?php if(get_option('ip_registration') == '0') echo ' selected'; ?>>Require user registration (recommended)</option>
									<option value="1"<?php if(get_option('ip_registration') == '1') echo ' selected'; ?>>Do not require user registration</option>
								</select>
								<br><small>Require users to be registered and logged in to upload images (recommended).</small>
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_moderate">Image moderation</label></th>
			                <td>
								<select name="ip_moderate" id="ip_moderate">
									<option value="0"<?php if(get_option('ip_moderate') == '0') echo ' selected'; ?>>Moderate all images (recommended)</option>
									<option value="1"<?php if(get_option('ip_moderate') == '1') echo ' selected'; ?>>Do not moderate images</option>
								</select>
								<br><small>Moderate all submitted images (recommended).</small>
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_cat_moderation_include">Moderate entries in this category</label></th>
			                <td>
								<input type="text" class="regular-text" name="ip_cat_moderation_include" id="ip_cat_moderation_include" value="<?php echo get_option('ip_cat_moderation_include'); ?>">
								<br><small>Always moderate entries in this category (use category ID).</small>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">Email Settings</h3>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="ip_notification_email">Administrator email<br><small>(used for new image notification)</small></label></th>
			                <td>
								<input type="text" name="ip_notification_email" id="ip_notification_email" value="<?php echo get_option('ip_notification_email'); ?>" class="regular-text">
								<br><small>The administrator will receive an email notification each time a new image is uploaded</small>
								<br><small>Separate multiple addresses with comma</small>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
			</form>
		<?php } ?>
		<?php if($t == 'users_tab') { ?>
            <?php
            if(isset($_POST['cinnamon_submit'])) {
                update_option('cinnamon_label_index', $_POST['cinnamon_label_index']);
                update_option('cinnamon_label_portfolio', $_POST['cinnamon_label_portfolio']);
                update_option('cinnamon_label_about', $_POST['cinnamon_label_about']);
                update_option('cinnamon_label_hub', $_POST['cinnamon_label_hub']);
                update_option('cinnamon_hide', $_POST['cinnamon_hide']);
                update_option('cinnamon_image_size', $_POST['cinnamon_image_size']);
                update_option('ip_cards_per_author', $_POST['ip_cards_per_author']);
                update_option('ip_cards_image_size', $_POST['ip_cards_image_size']);

				update_option('ip_et_login', $_POST['ip_et_login']);

                update_option('cinnamon_show_awards', $_POST['cinnamon_show_awards']);
                update_option('cinnamon_show_followers', $_POST['cinnamon_show_followers']);
                update_option('cinnamon_show_following', $_POST['cinnamon_show_following']);

                update_option('cinnamon_account_page', $_POST['cinnamon_account_page']);
                update_option('cinnamon_edit_page', $_POST['cinnamon_edit_page']);

				update_option('cinnamon_show_likes', $_POST['cinnamon_show_likes']);

                update_option('cinnamon_software', $_POST['cinnamon_software']);
                update_option('cinnamon_skills', $_POST['cinnamon_skills']);

				update_option('approvednotification', $_POST['approvednotification']);
				update_option('declinednotification', $_POST['declinednotification']);
				update_option('ip_override_email_notification', $_POST['ip_override_email_notification']);

                echo '<div class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p></div>';
            }
            ?>
			<form method="post" action="">
				<h3 class="title">General Settings</h3>
				<p>These settings apply globally for all ImagePress users.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="cinnamon_account_page">Author account login page</label></th>
			                <td>
			                    <input type="url" name="cinnamon_account_page" id="cinnamon_account_page" value="<?php echo get_option('cinnamon_account_page'); ?>" class="regular-text" placeholder="http://">
			                    <br><small>Create a new page and add the <code>[cinnamon-login]</code> shortcode.</small>
			                    <br><small>This shortcode will display a login/registration tabbed section.</small>
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="cinnamon_edit_page">Author profile edit page URL</label></th>
			                <td>
                                <input type="url" name="cinnamon_edit_page" id="cinnamon_edit_page" value="<?php echo get_option('cinnamon_edit_page'); ?>" class="regular-text" placeholder="http://">
                                <br><small>Create a new page and add the <code>[cinnamon-profile-edit]</code> shortcode.</small>
                                <br><small>This shortcode will display all user fields in a tabbed section.</small>
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_et_login">WordPress login URL<br><small>(optional)</small></label></th>
			                <td>
								<input type="url" name="ip_et_login" id="ip_et_login" value="<?php echo get_option('ip_et_login'); ?>" class="regular-text">
								<br><small>Use this option to define a different login URL than <code>/wp-login.php</code>.</small>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">Author Cards</h3>
				<p>These settings apply to author cards. Use the <code>[cinnamon-card]</code> shortcode to display the cards.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label for="ip_cards_per_author">Number of images</label></th>
			                <td>
                                <input type="number" name="ip_cards_per_author" id="ip_cards_per_author" value="<?php echo get_option('ip_cards_per_author'); ?>" min="0" max="32">
			                </td>
			            </tr>
			            <tr>
			                <th scope="row"><label for="ip_cards_image_size">Image size</label></th>
			                <td>
                                <select name="ip_cards_image_size" id="ip_cards_image_size">
                                    <optgroup label="WordPress (Default)">
                                        <option value="thumbnail"<?php if(get_option('ip_cards_image_size') == 'thumbnail') echo ' selected'; ?>>Thumbnail</option>
                                    </optgroup>

                                    <optgroup label="Small">
                                        <option value="imagepress_sq_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_sq_sm') echo ' selected'; ?>>Small (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_pt_sm') echo ' selected'; ?>>Small (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_ls_sm') echo ' selected'; ?>>Small (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="Standard">
                                        <option value="imagepress_sq_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_sq_std') echo ' selected'; ?>>Standard (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_pt_std') echo ' selected'; ?>>Standard (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_ls_std') echo ' selected'; ?>>Standard (Landscape) (ImagePress)</option>
                                    </optgroup>
                                </select>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">Author Awards</h3>
				<p>Create a new page and add the <code>[cinnamon-awards]</code> shortcode. This shortcode will list all available awards and their description.</p>
                <p><span class="dashicons dashicons-awards"></span> <a href="<?php echo admin_url('edit-tags.php?taxonomy=award'); ?>" class="button button-secondary">Add/Edit Awards</a></p>

                <hr>
				<h3 class="title">Author Profile</h3>
				<p>These settings apply to author profiles.</p>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label>Profile Settings</label></th>
			                <td>
                                <p>
                                    <select name="cinnamon_show_awards" id="cinnamon_show_awards">
                                        <option value="1"<?php if(get_option('cinnamon_show_awards') == 1) echo ' selected'; ?>>Show awards</option>
                                        <option value="0"<?php if(get_option('cinnamon_show_awards') == 0) echo ' selected'; ?>>Hide awards</option>
                                    </select>
                                </p>
                                <hr>
                                <p>
                                    <select name="cinnamon_show_followers" id="cinnamon_show_followers">
                                        <option value="1"<?php if(get_option('cinnamon_show_followers') == 1) echo ' selected'; ?>>Show followers</option>
                                        <option value="0"<?php if(get_option('cinnamon_show_followers') == 0) echo ' selected'; ?>>Hide followers</option>
                                    </select> 
                                    <select name="cinnamon_show_following" id="cinnamon_show_following">
                                        <option value="1"<?php if(get_option('cinnamon_show_following') == 1) echo ' selected'; ?>>Show following</option>
                                        <option value="0"<?php if(get_option('cinnamon_show_following') == 0) echo ' selected'; ?>>Hide following</option>
                                    </select> <label>Followers behaviour</label>
                                </p>
                                <hr>
                                <p>
                                    <select name="cinnamon_show_likes" id="cinnamon_show_likes">
                                        <option value="1"<?php if(get_option('cinnamon_show_likes') == 1) echo ' selected'; ?>>Show likes</option>
                                        <option value="0"<?php if(get_option('cinnamon_show_likes') == 0) echo ' selected'; ?>>Hide likes</option>
                                    </select>
                                </p>

                                <hr>
                                <p>
                                    <label for="cinnamon_software">Software proficiency list (comma separated values)</label>
                                    <textarea class="large-text" rows="8" name="cinnamon_software" id="cinnamon_software"><?php echo get_option('cinnamon_software'); ?></textarea>
                                </p>
                                <p>
                                    <label for="cinnamon_skills">Skills list (comma separated values)</label>
                                    <textarea class="large-text" rows="8" name="cinnamon_skills" id="cinnamon_skills"><?php echo get_option('cinnamon_skills'); ?></textarea>
                                </p>
			                </td>
			            </tr>
			        </tbody>
			    </table>

                <hr>
				<h3 class="title">Email Settings</h3>
			    <table class="form-table">
			        <tbody>
			            <tr>
			                <th scope="row"><label>Email Settings</label></th>
			                <td>
    							<p>
    								<input type="checkbox" id="approvednotification" name="approvednotification" value="yes" <?php if(get_option('approvednotification') == 'yes') echo 'checked'; ?>> <label for="approvednotification">Notify author when image is approved</label>
    								<br>
    								<input type="checkbox" id="declinednotification" name="declinednotification" value="yes" <?php if(get_option('declinednotification') == 'yes') echo 'checked'; ?>> <label for="declinednotification">Notify author when image is rejected</label>
    							</p>
                                <p>
                                    <select name="ip_override_email_notification" id="ip_override_email_notification">
                                        <option value="1"<?php if(get_option('ip_override_email_notification') == 1) echo ' selected'; ?>>Override WordPress email notification</option>
                                        <option value="0"<?php if(get_option('ip_override_email_notification') == 0) echo ' selected'; ?>>Do not override WordPress email notification</option>
                                    </select>
                                    <br><small>Override the default WordPress email notification. This will hide the default login/registration links and will redirect the user to the correct ImagePress links. Deactivate if using other member plugins or if you notice any conflict.</small>
                                </p>
                            </td>
			            </tr>
			        </tbody>
			    </table>

                <?php if(get_option('cinnamon_mod_hub') == 1) { ?>
                    <hr>
    				<h3 class="title">Hub Options <sup><small>DEVELOPERS ONLY</small></sup></h3>
    				<p>In order to enable the hub (portfolio) feature of ImagePress, check the <b>Dashboard</b> section and copy the required code inside your <code>author.php</code> template file and modify the <code>.htaccess</code> file. Requirements for portfolio subdomains (jack.domain.com) include active permalinks, wildcard subdomain support (contact your hosting server for more information) and FTP access to your template files.</p>
    			    <table class="form-table">
    			        <tbody>
    			            <tr>
    			                <th scope="row"><label for="cinnamon_label_index">Hub index icon label</label></th>
    			                <td>
                                    <p>
                                        <input type="text" name="cinnamon_label_index" id="cinnamon_label_index" value="<?php echo get_option('cinnamon_label_index'); ?>" class="regular-text">
                                        <br><small>Try <b>View all</b> or <b>Back to index view</b>.</small>
                                    </p>
                                </td>
                            </tr>
                            <tr>
    			                <th scope="row"><label for="cinnamon_label_hub">Hub view button label</label></th>
    			                <td>
                                    <p>
                                        <input type="text" name="cinnamon_label_hub" id="cinnamon_label_hub" value="<?php echo get_option('cinnamon_label_hub'); ?>" class="regular-text">
                                        <br><small>Try <b>View Portfolio</b>.</small>
                                    </p>
                                </td>
                            </tr>
                            <tr>
    			                <th scope="row"><label for="cinnamon_image_size">Profile image size</label></th>
    			                <td>
                                    <p>
                                        <input type="number" min="90" max="320" name="cinnamon_image_size" id="cinnamon_image_size" value="<?php echo get_option('cinnamon_image_size'); ?>">
                                        <br><small>Default is <b>150</b>px. Leave blank for default WordPress size.</small>
                                    </p>
                                </td>
                            </tr>
                            <tr>
    			                <th scope="row"><label>Hub tabs</label></th>
    			                <td>
                                    <p>
                                        <input type="text" name="cinnamon_label_portfolio" id="cinnamon_label_portfolio" value="<?php echo get_option('cinnamon_label_portfolio'); ?>" class="regular-text" placeholder="My Portfolio (tab title)">
                                        <br><small>Try <b>My Portfolio</b> or <b>My Images</b>.</small>
                                        <br>
                                        <input type="text" name="cinnamon_label_about" id="cinnamon_label_about" value="<?php echo get_option('cinnamon_label_about'); ?>" class="regular-text" placeholder="About (tab title)">
                                        <br><small>Try <b>About</b>.</small>
                                    </p>
                                </td>
                            </tr>
                            <tr>
    			                <th scope="row"><label for="cinnamon_hide">CSS selectors to hide when viewing the hub</label></th>
    			                <td>
                                    <p>
                                        <input type="text" name="cinnamon_hide" id="cinnamon_hide" value="<?php echo get_option('cinnamon_hide'); ?>" class="regular-text">
                                        <br><small>Try <b>header, nav, footer</b> or <b>.sidebar</b> or <b>#main-menu</b>.</small>
                                        <br><small>If your hub page flashes for a brief moment, consider moving the selectors in your <code>style.css</code> file (e.g. <code>header, nav, footer, .sidebar, #main-menu { display: none; }</code>.</small>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>

                <hr>
                <p><input name="cinnamon_submit" type="submit" class="button-primary" value="Save Changes"></p>
            </form>
		<?php } ?>
		<?php if($t == 'label_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_name_label', $_POST['ip_name_label']);
				update_option('ip_email_label', $_POST['ip_email_label']);

				update_option('ip_caption_label', $_POST['ip_caption_label']);
				update_option('ip_category_label', $_POST['ip_category_label']);
				update_option('ip_tag_label', $_POST['ip_tag_label']);
				update_option('ip_description_label', $_POST['ip_description_label']);
				update_option('ip_upload_label', $_POST['ip_upload_label']);
				update_option('ip_keywords_label', $_POST['ip_keywords_label']);
				update_option('ip_image_label', $_POST['ip_image_label']);
				update_option('ip_video_label', $_POST['ip_video_label']);
				update_option('ip_purchase_label', $_POST['ip_purchase_label']);
				update_option('ip_sticky_label', $_POST['ip_sticky_label']);

				//update_option('ip_wrb_link_label', $_POST['ip_wrb_link_label']);

				update_option('ip_author_find_title', $_POST['ip_author_find_title']);
				update_option('ip_author_find_placeholder', $_POST['ip_author_find_placeholder']);
				update_option('ip_image_find_title', $_POST['ip_image_find_title']);
				update_option('ip_image_find_placeholder', $_POST['ip_image_find_placeholder']);

				update_option('ip_notifications_mark', $_POST['ip_notifications_mark']);
				update_option('ip_notifications_all', $_POST['ip_notifications_all']);

                update_option('cms_title', $_POST['cms_title']);
                update_option('cms_featured_tooltip', $_POST['cms_featured_tooltip']);
                update_option('cms_verified_profile', $_POST['cms_verified_profile']);

				update_option('ip_upload_success_title', $_POST['ip_upload_success_title']);
				update_option('ip_upload_success', $_POST['ip_upload_success']);

                update_option('ip_likes', $_POST['ip_likes']);
                update_option('ip_vote_meta', $_POST['ip_vote_meta']);
                update_option('ip_vote_like', stripslashes_deep($_POST['ip_vote_like']));
                update_option('ip_vote_unlike', stripslashes_deep($_POST['ip_vote_unlike']));
                update_option('ip_vote_nobody', stripslashes_deep($_POST['ip_vote_nobody']));
                update_option('ip_vote_who', stripslashes_deep($_POST['ip_vote_who']));
                update_option('ip_vote_who_singular', stripslashes_deep($_POST['ip_vote_who_singular']));
                update_option('ip_vote_who_plural', stripslashes_deep($_POST['ip_vote_who_plural']));
                update_option('ip_vote_who_link', stripslashes_deep($_POST['ip_vote_who_link']));
                update_option('ip_vote_login', stripslashes_deep($_POST['ip_vote_login']));

                update_option('cinnamon_edit_label', $_POST['cinnamon_edit_label']);
				update_option('cinnamon_pt_account', $_POST['cinnamon_pt_account']);
				update_option('cinnamon_pt_author', $_POST['cinnamon_pt_author']);
				update_option('cinnamon_pt_profile', $_POST['cinnamon_pt_profile']);
				update_option('cinnamon_pt_portfolio', $_POST['cinnamon_pt_portfolio']);
				update_option('cinnamon_pt_collections', $_POST['cinnamon_pt_collections']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3>Label Settings</h3>
					<div class="inside">
						<form method="post" action="">
							<p>
								<input type="text" name="ip_name_label" id="ip_name_label" value="<?php echo get_option('ip_name_label'); ?>" class="regular-text">
								<label for="ip_name_label">Name label</label>
								<br>
								<input type="text" name="ip_email_label" id="ip_email_label" value="<?php echo get_option('ip_email_label'); ?>" class="regular-text">
								<label for="ip_email_label">Email address label</label>
							</p>
							<p>
								<input type="text" name="ip_caption_label" id="ip_caption_label" value="<?php echo get_option('ip_caption_label'); ?>" class="regular-text">
								<label for="ip_caption_label">Image caption label</label>
								<br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_category_label" id="ip_category_label" value="<?php echo get_option('ip_category_label'); ?>" class="regular-text">
								<label for="ip_category_label">Image category label (dropdown)</label>
							</p>
							<p>
								<input type="text" name="ip_tag_label" id="ip_tag_label" value="<?php echo get_option('ip_tag_label'); ?>" class="regular-text">
								<label for="ip_tag_label">Image tag label (dropdown)</label>
							</p>
							<p>
								<input type="text" name="ip_description_label" id="ip_description_label" value="<?php echo get_option('ip_description_label'); ?>" class="regular-text">
								<label for="ip_description_label">Image description label (textarea)</label>
								<br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_keywords_label" id="ip_keywords_label" value="<?php echo get_option('ip_keywords_label'); ?>" class="regular-text">
								<label for="ip_keywords_label">Image keywords label</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_upload_label" id="ip_upload_label" value="<?php echo get_option('ip_upload_label'); ?>" class="regular-text">
								<label for="ip_upload_label">Image upload button label (button)</label>
							</p>
							<p>
								<input type="text" name="ip_image_label" id="ip_image_label" value="<?php echo get_option('ip_image_label'); ?>" class="regular-text">
								<label for="ip_image_label">Image upload selection label (link)</label>
							</p>
							<p>
								<input type="text" name="ip_video_label" id="ip_video_label" value="<?php echo get_option('ip_video_label'); ?>" class="regular-text">
								<label for="ip_video_label">Image video link (Youtube/Vimeo)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_purchase_label" id="ip_purchase_label" value="<?php echo get_option('ip_purchase_label'); ?>" class="regular-text">
								<label for="ip_purchase_label">Image purchase link label (button)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_sticky_label" id="ip_sticky_label" value="<?php echo get_option('ip_sticky_label'); ?>" class="regular-text">
								<label for="ip_sticky_label">Sticky image label (checkbox)</label>
                                <br><small>Leave blank to disable</small>
							</p>
                            <?php /** ?>
							<p>
								<input type="text" name="ip_wrb_link_label" id="ip_wrb_link_label" value="<?php echo get_option('ip_wrb_link_label'); ?>" class="regular-text">
								<label for="ip_wrb_link_label">WRB label (checkbox)</label>
                                <br><small>Leave blank to disable</small>
							</p>
                            <?php /**/ ?>

                            <hr>
                            <h2>Author/Image Cards</h2>
							<p>
								<input type="text" name="ip_author_find_title" id="ip_author_find_title" value="<?php echo get_option('ip_author_find_title'); ?>" class="regular-text">
								<label for="ip_author_find_title">Author name/location sorter title</label>
								<br>
								<input type="text" name="ip_author_find_placeholder" id="ip_author_find_placeholder" value="<?php echo get_option('ip_author_find_placeholder'); ?>" class="regular-text">
								<label for="ip_author_find_placeholder">Author name/location sorter placeholder</label>
							</p>
							<p>
								<input type="text" name="ip_image_find_title" id="ip_image_find_title" value="<?php echo get_option('ip_image_find_title'); ?>" class="regular-text">
								<label for="ip_image_find_title">Image author/title/category sorter title</label>
								<br>
								<input type="text" name="ip_image_find_placeholder" id="ip_image_find_placeholder" value="<?php echo get_option('ip_image_find_placeholder'); ?>" class="regular-text">
								<label for="ip_image_find_placeholder">Image author/title/category sorter placeholder</label>
							</p>

                            <hr>
                            <h2>Notifications</h2>
							<p>
								<input type="text" name="ip_notifications_mark" id="ip_notifications_mark" value="<?php echo get_option('ip_notifications_mark'); ?>" class="regular-text">
								<label for="ip_notifications_mark">"Mark all as read" label</label>
								<br>
								<input type="text" name="ip_notifications_all" id="ip_notifications_all" value="<?php echo get_option('ip_notifications_all'); ?>" class="regular-text">
								<label for="ip_notifications_all">"View all notifications" label</label>
							</p>

                            <hr>
                            <h2>Tooltips</h2>
                            <p>
                                <input type="text" name="cms_title" id="cms_title" value="<?php echo get_option('cms_title'); ?>" class="regular-text"> <label for="cms_title">Site Title</label>
                                <br><small>This text will appear in various places all over the site.</small>
                            </p>
                            <p>
                                <input type="text" name="cms_featured_tooltip" id="cms_featured_tooltip" value="<?php echo get_option('cms_featured_tooltip'); ?>" class="regular-text"> <label for="cms_featured_tooltip">Featured item tooltip</label>
                                <br><small>This text will appear when the "featured" icon is hovered.</small>
                            </p>
                            <p>
                                <input type="text" name="cms_verified_profile" id="cms_verified_profile" value="<?php echo get_option('cms_verified_profile'); ?>" class="regular-text"> <label for="cms_verified_profile">Verified profile tooltip</label>
                                <br><small>This text will appear when the "verified" icon is hovered.</small>
                            </p>

                            <hr>
                            <h2>Image Upload</h2>
                            <p>
                                <input type="text" name="ip_upload_success_title" id="ip_upload_success_title" value="<?php echo get_option('ip_upload_success_title'); ?>" class="regular-text"> <label for="ip_upload_success_title">Upload success title</label>
                                <br><small>This text will appear when the image upload is successful.</small>
                                <br><small>Leave blank to disable</small>
                            </p>
                            <p>
                                <input type="text" name="ip_upload_success" id="ip_upload_success" value="<?php echo get_option('ip_upload_success'); ?>" class="regular-text"> <label for="ip_upload_success">Upload success</label>
                                <br><small>This text will appear when the image upload is successful.</small>
                                <br><small>Leave blank to disable</small>
                            </p>

							<h3>Users</h3>
                            <p>
                                <input type="text" name="cinnamon_edit_label" id="cinnamon_edit_label" value="<?php echo get_option('cinnamon_edit_label'); ?>" class="text"> <label for="cinnamon_edit_label">Author profile edit label (try <b>Edit profile</b>)</label>
                            </p>
							<p>
								<!-- pt = profile tab -->
								<input type="text" name="cinnamon_pt_account" value="<?php echo get_option('cinnamon_pt_account'); ?>" size="16" placeholder="Account details"> 
								<input type="text" name="cinnamon_pt_author" value="<?php echo get_option('cinnamon_pt_author'); ?>" size="16" placeholder="Author details"> 
								<input type="text" name="cinnamon_pt_profile" value="<?php echo get_option('cinnamon_pt_profile'); ?>" size="16" placeholder="Profile details"> 
								<input type="text" name="cinnamon_pt_portfolio" value="<?php echo get_option('cinnamon_pt_portfolio'); ?>" size="16" placeholder="Portfolio editor"> 
								<input type="text" name="cinnamon_pt_collections" value="<?php echo get_option('cinnamon_pt_collections'); ?>" size="16" placeholder="Collections">
								<label>Profile edit tab labels</label>
							</p>

							<h3>Like/Unlike</h3>
							<p>
								<input type="text" name="ip_likes" id="ip_likes" value="<?php echo get_option('ip_likes'); ?>">
								<label for="ip_likes">General action name (plural)</label>
								<br><small>The name of the vote action ("like", "love", "appreciate", "vote"). Use plural.</small>
							</p>
							<p>
								<input type="text" name="ip_vote_meta" id="ip_vote_meta" value="<?php echo get_option('ip_vote_meta'); ?>">
								<label for="ip_vote_meta">Vote meta name</label>
								<br><small>The name of the vote meta field. Use this to migrate your old count.</small>
							</p>
							<p>
								<input type="text" name="ip_vote_like" id="ip_vote_like" value="<?php echo get_option('ip_vote_like'); ?>" placeholder="I like this image" class="regular-text">
								<label for="ip_vote_like">Vote "like" label</label>
								<br>
								<input type="text" name="ip_vote_unlike" id="ip_vote_unlike" value="<?php echo get_option('ip_vote_unlike'); ?>" placeholder="Oops! I don't like this" class="regular-text">
								<label for="ip_vote_unlike">Vote "unlike" label</label>
								<br>
								<input type="text" name="ip_vote_nobody" id="ip_vote_nobody" value="<?php echo get_option('ip_vote_nobody'); ?>" placeholder="Nobody likes this yet" class="regular-text">
								<label for="ip_vote_nobody">"No likes" label</label>
								<br>
								<input type="text" name="ip_vote_who" id="ip_vote_who" value="<?php echo get_option('ip_vote_who'); ?>" placeholder="Users that like this image:" class="regular-text">
								<label for="ip_vote_who">"Who" label</label>
							</p>
							<p>
								<input type="text" name="ip_vote_who_singular" id="ip_vote_who_singular" value="<?php echo get_option('ip_vote_who_singular'); ?>" placeholder="user likes this" class="regular-text">
								<label for="ip_vote_who_singular">Singular "who" label</label>
								<br>
								<input type="text" name="ip_vote_who_plural" id="ip_vote_who_plural" value="<?php echo get_option('ip_vote_who_plural'); ?>" placeholder="users like this" class="regular-text">
								<label for="ip_vote_who_plural">Plural "who" label</label>
								<br>
								<input type="text" name="ip_vote_who_link" id="ip_vote_who_link" value="<?php echo get_option('ip_vote_who_link'); ?>" placeholder="who?" class="regular-text">
								<label for="ip_vote_who_link">"Who" link label</label>
								<br>
								<input type="text" name="ip_vote_login" id="ip_vote_login" value="<?php echo get_option('ip_vote_login'); ?>" placeholder="You need to be logged in to like this" class="regular-text">
								<label for="ip_vote_login">"Logged in" notice</label>
								<br>
							</p>

							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'upload_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_request_user_details', $_POST['ip_request_user_details']);
				update_option('ip_upload_secondary', $_POST['ip_upload_secondary']);
				update_option('ip_require_description', $_POST['ip_require_description']);

				update_option('ip_upload_size', $_POST['ip_upload_size']);
				update_option('ip_cat_exclude', $_POST['ip_cat_exclude']);

				update_option('ip_resize', $_POST['ip_resize']);
				update_option('ip_max_width', $_POST['ip_max_width']);
				update_option('ip_max_quality', $_POST['ip_max_quality']);

                update_option('ip_dropbox_enable', $_POST['ip_dropbox_enable']);
				update_option('ip_dropbox_key', $_POST['ip_dropbox_key']);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<form method="post" action="">
    			<h3 class="title">Upload Settings</h3>
    		    <table class="form-table">
    		        <tbody>
    		            <tr>
    		                <th scope="row"><label for="ip_resize">Image resize</label></th>
    		                <td>
                                <select name="ip_resize" id="ip_resize">
                                    <option value="1"<?php if(get_option('ip_resize') == 1) echo ' selected'; ?>>Enable image resizing</option>
                                    <option value="0"<?php if(get_option('ip_resize') == 0) echo ' selected'; ?>>Disable image resizing</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label for="ip_max_width">Maximum image width</label></th>
    		                <td>
                                <input name="ip_max_width" id="ip_max_width" type="number" value="<?php echo get_option('ip_max_width')?>" min="1">
                                <br><small>Set maximum image width (will be resized if larger).</small>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label for="ip_max_quality">Resized image quality</label></th>
    		                <td>
                                <input name="ip_max_quality" id="ip_max_quality" type="number" value="<?php echo get_option('ip_max_quality')?>" min="0" max="100">
                                <br><small>Set image quality when resizing image.</small>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label for="ip_upload_size">Maximum image upload size<br><small>(in kilobytes)</small></label></th>
    		                <td>
                                <input type="number" name="ip_upload_size" id="ip_upload_size" min="0" max="<?php echo (ini_get('upload_max_filesize') * 1024); ?>" step="1024" value="<?php echo get_option('ip_upload_size'); ?>">
                                <br><small>Try 2048 for most configurations (your server allows a maximum of <?php echo ini_get('upload_max_filesize'); ?>).</small>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label for="ip_cat_exclude">Exclude categories</label></th>
    		                <td>
                                <input type="text" name="ip_cat_exclude" id="ip_cat_exclude" value="<?php echo get_option('ip_cat_exclude'); ?>">
                                <br><small>Exclude these categories from the upload form (separate IDs with comma).</small>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label>Upload details</label></th>
    		                <td>
                                <select name="ip_request_user_details" id="ip_request_user_details">
                                    <option value="1"<?php if(get_option('ip_request_user_details') == 1) echo ' selected'; ?>>Request user name and email</option>
                                    <option value="0"<?php if(get_option('ip_request_user_details') == 0) echo ' selected'; ?>>Do not request user name and email</option>
                                </select>
                                <br>
                                <select name="ip_require_description" id="ip_require_description">
                                    <option value="1"<?php if(get_option('ip_require_description') == 1) echo ' selected'; ?>>Require description</option>
                                    <option value="0"<?php if(get_option('ip_require_description') == 0) echo ' selected'; ?>>Do not require description</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
    		                <th scope="row"><label>Upload features</label></th>
    		                <td>
                                <select name="ip_upload_secondary" id="ip_upload_secondary">
                                    <option value="1"<?php if(get_option('ip_upload_secondary') == 1) echo ' selected'; ?>>Enable secondary upload button</option>
                                    <option value="0"<?php if(get_option('ip_upload_secondary') == 0) echo ' selected'; ?>>Disable secondary upload button</option>
                                </select> <label for="ip_upload_secondary">Enable/disable additional images (variants, progress shots, making of, etc.)</label>
                                <br>
                                <select name="ip_allow_tags" id="ip_allow_tags">
                                    <option value="1"<?php if(get_option('ip_allow_tags') == 1) echo ' selected'; ?>>Enable tags</option>
                                    <option value="0"<?php if(get_option('ip_allow_tags') == 0) echo ' selected'; ?>>Disable tags</option>
                                </select> <label for="ip_allow_tags">Enable/disable image tags dropdown.</label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h3 class="title">Integrations</h3>
                <p>Allow third-party modules to hook into the upload functions.</p>
                <table class="form-table">
                    <tbody>
    		            <tr>
    		                <th scope="row"><label><i class="fa fa-dropbox"></i> Dropbox</label></th>
    		                <td>
                                <p>
                                    <input type="checkbox" name="ip_dropbox_enable" value="1" <?php if(get_option('ip_dropbox_enable') === '1') echo 'checked'; ?>> <label>Enable Dropbox upload</label>
                                </p>
                                <p>
                                    <input name="ip_dropbox_key" id="ip_dropbox_key" type="text" class="regular-text" value="<?php echo get_option('ip_dropbox_key'); ?>"> <label for="ip_dropbox_key">Dropbox API Key</label>
                                    <br><small>Allow users to upload images from their Dropbox accounts. Requires an <a href="https://www.dropbox.com/developers/dropins/chooser/js" rel="external">API key</a>. <a href="https://www.dropbox.com/developers/apps/create?app_type_checked=dropins" rel="external">Create new Dropbox app.</a></small>
                                </p>
    		                </td>
    		            </tr>
                    </tbody>
                </table>

				<p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
			</form>
		<?php } ?>
		<?php if($t == 'notifications_tab') { ?>
			<?php
			if (isset($_POST['notification_add'])) {
				global $wpdb;

				$notification_type_custom = $_POST['notification_type_custom'];
				$notification_icon_custom = $_POST['notification_icon_custom'];
				$notification_link_custom = $_POST['notification_link_custom'];
				$notification_user = $_POST['notification_user'];
				$when = date('Y-m-d H:i:s');

				if(!empty($notification_link_custom))
					$notification_type = '<a href="' . $notification_link_custom . '">' . $notification_type_custom . '</a>';
				else
					$notification_type = '' . $notification_type_custom . '';

				$sql = "INSERT INTO " . $wpdb->prefix . "notifications (`userID`, `postID`, `actionType`, `actionIcon`, `actionTime`) VALUES (0, '$notification_user', '$notification_type', '$notification_icon_custom', '$when')";
				$wpdb->query($sql);

				echo '<div class="updated notice is-dismissible"><p>Notification added successfully!</p></div>';
			}

            if (isset($_POST['feed_ad_add'])) {
                global $wpdb;

                $notification_feed_ad = (int) $_POST['notification_feed_ad'];
                $when = date('Y-m-d H:i:s');

                $sql = "INSERT INTO " . $wpdb->prefix . "notifications (`userID`, `postID`, `actionType`, `actionTime`) VALUES (0, '$notification_feed_ad', 'ad', '$when')";
                $wpdb->query($sql);

                echo '<div class="updated notice is-dismissible"><p>Feed add added successfully!</p></div>';
			}
			?>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
			<script>
			jQuery(document).ready(function($) {
				$('.ajax_trash').click(function(e){
					var data = {
						action: 'ajax_trash_action',
						odvm_post: $(this).attr('data-post'),
					};

					$.post(ajaxurl, data, function(response) {
						alert('' + response);
					});
					fade_vote = $(this).attr('data-post');
					$('#notification-' + fade_vote).fadeOut('slow', function(){});
					e.preventDefault();
				});
			});
			</script>

            <h2>Feed Ads &amp; Notifications</h2>

            <div class="postbox">
                <div class="inside">
                    <form method="post">
                        <h2>Add new feed ad</h2>
                        <p>Add a new (sticky or non-sticky) ad to poster feed.</p>
                        <p>
                            <?php
                            $post_type = 'feed-ad';
                            $post_type_object = get_post_type_object($post_type);
                            $label = $post_type_object->label;
                            $posts = get_posts(
                                [
                                    'post_type' => $post_type,
                                    'post_status'=> 'publish',
                                    'suppress_filters' => false,
                                    'posts_per_page' => -1,
                                ]
                            );

                            echo '<select name="notification_feed_ad" id="notification_feed_ad">
                                <option value="0">Select feed ad...</option>';
                                foreach ($posts as $post) {
                                    echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';
                                }
                            echo '</select>';
                            ?>
                            <label>See all your ads <a href="<?php echo admin_url('edit.php?post_type=feed-ad'); ?>">here</a> or <a href="<?php echo admin_url('post-new.php?post_type=feed-ad'); ?>">post a new one</a>.</label>
                            <br><small>This is the ad to be posted to feed.</small>
                        </p>
                        <p>
                            <input type="submit" name="feed_ad_add" value="Add feed ad" class="button button-primary">
                        </p>
                    </form>
                </div>
            </div>

            <hr>

            <form method="post">
                <h3>Add new notification</h3>
                <p>
                    <input type="text" name="notification_icon_custom" id="notification_icon_custom" class="regular-text" placeholder="fa-bicycle"> 
                    <label for="notification_icon_custom">Notification icon (FontAwesome)</label>
                    <br>
                    <input type="text" name="notification_type_custom" id="notification_type_custom" class="regular-text"> 
                    <label for="notification_type_custom">Notification type (custom)</label>
                    <br><small>This is the notification body text (e.g. <em>Check out this great new feature!</em> or <em>You have been verified!</em>).</small>
                </p>
                <p>
                    <input type="url" name="notification_link_custom" id="notification_link_custom" class="regular-text" placeholder="http://"> 
                    <label for="notification_link_custom">Notification link (custom)</label>
                    <br><small>This is the URL link the text above will point to.</small>
                </p>
                <p>
                    <?php
                    $args = [
                        'name' => 'notification_user',
                        'show_option_none' => 'Show to this user only (optional, leave blank to show to all users)...'
                    ];
                    wp_dropdown_users($args); ?>
                </p>
                <p>
                    <input type="submit" name="notification_add" value="Add custom notification" class="button button-secondary">
                </p>
            </form>

            <hr>
            <h3>All notifications</h3>
            <?php
            global $wpdb;

            $sql = "SELECT * FROM " . $wpdb->prefix . "notifications ORDER BY ID DESC LIMIT 500";
            $results = $wpdb->get_results($sql);
            foreach ($results as $result) { ?>
                <div id="notification-<?php echo $result->ID; ?>">
                    <a href="#" class="ajax_trash" data-post="<?php echo $result->ID; ?>"><i class="fa fa-fw fa-trash"></i></a>&nbsp;
                    <?php
                    $display = '';
                    $id = $result->ID;
                    $action = $result->actionType;
                    $nickname = get_the_author_meta('nickname', $result->userID);
                    $time = human_time_diff(strtotime($result->actionTime), current_time('timestamp')) . ' ago';
                    $ip_collections_page_id = get_option('ip_collections_page');

                    if ($result->status == 0) {
                        $status = '<i class="fa fa-fw fa-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
                    } else if ($result->status == 1) {
                        $status = '<i class="fa fa-fw fa-check-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
                    }

                    $display .= $status;

                    $display .= ' [' . $result->ID . '] ';

                    if ($action == 'loved')
                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-heart"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' a poster <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';
                    else if($action == 'collected')
                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-folder"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' a poster <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> into a <a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $result->postKeyID . '">collection</a> <time>' . $time . '</time>';
                    else if($action == 'added')
                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-arrow-circle-up"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';
                    else if($action == 'followed')
                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-plus-circle"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $result->actionType . ' you <time>' . $time . '</time>';
                    else if($action == 'commented on')
                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' a poster <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';
                    else if($action == 'replied to a comment on') {
                        $comment_id = get_comment($result->postID);
                        $comment_post_ID = $comment_id->comment_post_ID;
                        $b = $comment_id->user_id;

                        $display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> replied to a comment on <a href="' . get_permalink($comment_post_ID) . '">' . get_the_title($comment_post_ID) . '</a> <time>' . $time . '</time>';
                    }
                    else if($action == 'featured')
									$display .= '' . get_the_post_thumbnail($result->postID, [16,16]) . ' <i class="fa fa-fw fa-star"></i> <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> poster was ' . $action . ' <time>' . $time . '</time>';
                    // custom
                    else if(0 == $result->postID || '-1' == $result->postID) {
                        $attachment_id = 202;
                        $image_attributes = wp_get_attachment_image_src($attachment_id, [16,16]);

                        $display .= '<img src="' .  $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '"> <i class="fa fa-fw ' . $result->actionIcon . '"></i> ' . $result->actionType . ' <time>' . $time . '</time>';
                    }
                    else {}

                    echo $display;
                    ?>
                </div>
            <?php } ?>
		<?php } ?>
		<?php if($t == 'hooks_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('hook_upload_success', stripslashes_deep($_POST['hook_upload_success']));

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3>Hooks</h3>
					<div class="inside">
						<form method="post" action="">
                            <p>
								<label for="hook_upload_success">Upload Success (below)</label><br>
								<textarea class="large-text code" rows="4" name="hook_upload_success" id="hook_upload_success"><?php echo get_option('hook_upload_success'); ?></textarea>
                                <br><small>Example: a sharing shortcode. Use <code>#url#</code> to get the current permalink.</small>
                            </p>
							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
}
