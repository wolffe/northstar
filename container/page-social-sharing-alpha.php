<?php  
/* 
Template Name: DEV: Social Sharing
*/

get_header(); ?>

<section id="content-wide" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if(function_exists('yoast_breadcrumb')) { yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); } ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <section class="entry-content">
                <?php the_content(); ?>

                <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=1436221290006088&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

                <script>
                // https://stackoverflow.com/questions/20200750/facebook-programmatically-post-on-a-facebook-page-with-a-big-photo#20204580
                /**
                FB.api(
                    'https://graph.facebook.com/1436221290006088/feed',
                    'post',
                    {
                        message: 'this is a grumpy cat',
                        description: "This cat has been lost for decades now, please call at 654321486",
                        picture: "http://laughingsquid.com/wp-content/uploads/grumpy-cat.jpg"
                    },
                    function (response) {
                        if (!response) {
                            alert('Error occurred.');
                        } else if (response.error) {
                            document.getElementById('result').innerHTML =
                                'Error: ' + response.error.message;
                        } else {
                            document.getElementById('result').innerHTML =
                                '<a href=\"https://www.facebook.com/' + response.id + '\">' +
                                'Story created.  ID is ' +
                                response.id + '</a>';
                        }
                    }
                );
                /**/
                window.fbAsyncInit = function() {
                    FB.init({
                        appId            : '1436221290006088',
                        autoLogAppEvents : true,
                        xfbml            : true,
                        version          : 'v2.12'
                    });
                };

                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));


                window.onload = function () {
                    /**
                    FB.login(function(){
                        // Note: The call will only work if you accept the permission request
                        FB.api('/me/feed', 'post', {message: 'Hello, world!'});
                    }, {scope: 'publish_actions'});
                    /**/
                };

                // https://developers.facebook.com/docs/javascript/examples
                // https://developers.facebook.com/docs/facebook-login/web/login-button
                // https://developers.facebook.com/docs/facebook-login/web#
                // http://www.krizna.com/demo/login-with-facebook-using-php/
                // https://tommcfarlin.com/add-custom-user-meta-during-registration/
                </script>

                <p>Link your PosterSpy account to any of the social networks below:</p>

                <div class="fb-login-button" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true" data-scope="public_profile, publish_actions"></div>

            </section>
        </article>
    <?php endwhile; endif; ?>
</section>

<?php get_footer();
