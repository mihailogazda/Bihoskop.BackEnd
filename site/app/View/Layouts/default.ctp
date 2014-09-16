<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>		
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css('generic');
        echo $this->Html->css('fonts');
        echo $this->Html->css('boxes');
        //echo $this->Html->css('http://fonts.googleapis.com/css?family=Open+Sans');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-27767426-2']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>   
        <meta property="og:title" content="Bihoskop" />
        <meta property="og:type" content="product" />
        <meta property="og:url" content="http://www.bihoskop.com" />
        <meta property="og:image" content="http://lh6.ggpht.com/VyPNBP30b-kCEaAIDXRirGGCQKHW3y3pnfuyU9OBgFM8gNYwf2SQmi90az2GLntCj7E" />
        <meta property="og:site_name" content="Bihoskop" />
        <meta property="fb:admins" content="100004192482206" />        
    </head>
    <body>
        <div id="container">
            <div id="content">
                <center>
                    <table id="mainTable">
                        <tbody>
                            <tr>
                                <!-- HEADER -->
                                <td id="header" colspan="2">                                    
                                    <h1><?php echo $this->Html->image("bihoskop.png", array("url" => "home")); ?></h1>
                                    <div class="whiteBox">
                                        <center>
                                            <b>
                                                <?php echo $this->Html->link("Home", array("controller" => "pages", "action" => "home")); ?>
                                                <?php echo $this->Html->link("Spisak bioskopa", array("controller" => "pages", "action" => "bioskopi")); ?>
                                                <?php echo $this->Html->link("O nama", array("controller" => "pages", "action" => "about"), array("class" => "lastLink")); ?>
                                            </b>
                                        </center>
                                    </div>				
                                </td>
                            </tr>
                            <tr>
                                <td id="columnLeft" valign="top">
                                    <div id="content_spacer"> </div>
                                    <?php echo $this->Session->flash(); ?>
                                    <?php echo $this->fetch('content'); ?>
                                </td>
                                <td id="columnRight" valign="top">
                                    <center>
                                        <p> Skinite Bihoskop besplatno! </p>
                                        <?php
                                        echo $this->Html->image("get_it_on_play_logo_large.png", array("url" => "https://play.google.com/store/apps/details?id=com.bojandevic.bihoskop", "width" => "260px"));
                                        ?>
                                        <br /><br />
                                        <?php echo $this->Html->image("button_appstore.png", array("url" => "http://itunes.apple.com/us/app/bihoskop/id555952124?ls=1&mt=8")); ?>
                                        <br />
                                        <div id="rbox1" class="infoBox">
                                            <p> Podrška za iOS i Android sisteme </p>
                                        </div>
                                        <div id="rbox2" class="infoBox">
                                            <p> iOS 5+ / Android 2.1+ ready </p>
                                        </div>
                                        <div id="rbox3" class="infoBox">
                                            <p> Podrška za ekrane visoke rezolucije </p>
                                        </div>
                                        <br />
                                        <div id="socialBox">   
                                            <center>                                                
                                                <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.bihoskop.com&amp;send=false&amp;layout=button_count&amp;width=130&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=193979514066336" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;" allowTransparency="true"></iframe>
                                                <a href="https://twitter.com/share" class="twitter-share-button" data-text="Probajte ovu aplikaciju!" data-hashtags="bihoskop">Tweet</a>
                                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                                            
                                            </center>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </center>
            </div>
            <div class="push"></div>
        </div>
        <div id="footer">
            <center>                
                <div id="footer_content">
                    <?php echo $this->Html->link("Home", "home"); ?>
                    <?php echo $this->Html->link("Spisak bioskopa", "bioskopi"); ?>
                    <?php echo $this->Html->link("O nama", "about", array("class" => "lastLink")); ?>													
                    <br />
                    <br />
                    <span> &copy; Bojan Dević / Bojan Ćup 2012 </span>
                </div>            
            </center>
        </div>	

    </body>
</html>
