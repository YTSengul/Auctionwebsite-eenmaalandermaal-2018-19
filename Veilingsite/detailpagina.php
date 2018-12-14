<?php
include_once "components/connect.php";
include_once "components/meta.php";

//Als er geen Voorwerpnummer wordt meegegeven in de header (wat meestal betekent dat de user zelf probeert om via de URL de pagina te bereiken) kan de pagina niet correct geladen worden en wordt de user teruggestuurd naar de homepage.
if (!isset($_GET['Voorwerpnummer'])) {
    header('location: index.php');
}

//Prepared statement voor de productinformatie
$details_veiling = $dbh->prepare("SELECT Titel, Beschrijving FROM Voorwerp WHERE Voorwerpnummer = ?");
$details_veiling->execute([$_GET['Voorwerpnummer']]);
$detail_veiling = $details_veiling->fetch();

$titel = $detail_veiling['Titel'];
$beschrijving = $detail_veiling['Beschrijving'];

//Prepared statement voor de images
$foto_veiling = $dbh->prepare("SELECT Filenaam FROM Bestand WHERE Voorwerp = ?");
$foto_veiling->execute([$_GET['Voorwerpnummer']]);
$veilingfoto = $foto_veiling->fetchAll(); //PDO::FETCH_NUM

//echo '<pre>', var_dump($veilingfoto), '</pre>';


//Functie die de hoofdfoto toont die bij een veiling hoort
function echoMainpicture($veilingfoto)
{
    $hoofdfotoArray = $veilingfoto[0];
    $hoofdfoto = $hoofdfotoArray[0];
    echo "<img class='detailfoto' src='http://iproject4.icasites.nl/pics/$hoofdfoto' alt='Foto van een product'>";
}

//Functie die de subfoto's toont die bij een veiling horen
function echoSubpictures($veilingfoto)
{
    foreach ($veilingfoto as $foto) {
        echo "<img class='detailsubfoto' src='http://iproject4.icasites.nl/pics/$foto[0]' alt='Subfoto van een product'>";
    }
}

?>

<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">
    <div class="grid-x grid-margin-x detailpagina">
        <div class="cell">
            <h2><?php echo $titel ?></h2>
        </div>
        <div class="cell large-7 productdetails flexColumn">
            <!--Note to self: Inladen foto testen op de server: replacement inladen bij error-->
            <?php echoMainpicture($veilingfoto) ?>
            <div class="spaceAround marginTopAuto">
                <?php echoSubpictures($veilingfoto) ?>
            </div>
        </div>
        <div class="cell large-5 detail-biedingen">
            <div class="spaceBetween">
                <h3>Doe een bod</h3>
                <h3>00:00</h3>
            </div>
            <hr>
            <div>
                <p>Hier kunt u bieden. Denk goed na over uw bod. Eenmaal geboden kunt u uw bod niet meer intrekken en
                    bent u verplicht te betalen als u het product wint.</p>
            </div>
            <div>
                <form class="spaceBetween">
                    <input type="number" placeholder="Vul bedrag in...">
                    <input class="button" type="submit" value="Bieden">
                </form>
            </div>
            <div class="detail-bedragen">
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username1</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username2</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username3</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username4</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username5</h5>
                </div>
                <hr>
            </div>
            <div class="detail-aantal">
                <h4>Aantal biedingen: 0</h4>
            </div>
        </div>
        <div class="cell detailpagina-omschrijving">
            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Omschrijving</a></li>
                <li class="tabs-title"><a href="#panel2">Feedback</a></li>
            </ul>
            <hr>
            <div class="tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="panel1">
                    <iframe id="contact" allowtransparency="true" frameborder="1" scrolling="yes" width="100%" height="900px">

                        <!DOCTYPE html>
                        <html class="html" lang="en-US" prefix="og: http://ogp.me/ns#" itemscope itemtype="http://schema.org/WebPage">
                        <head>
                            <meta charset="UTF-8">
                            <link rel="profile" href="http://gmpg.org/xfn/11">

                            <title>YTS Webdesign - Wilt u ook een professioneel website laten maken?</title>
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <!-- This site is optimized with the Yoast SEO plugin v9.2.1 - https://yoast.com/wordpress/plugins/seo/ -->
                            <meta name="description" content="YTS Webdesign realiseert professionele opdrachten waarbij het altijd een slimme en creatieve design biedt die perfect aansluit bij de wensen en eisen."/>
                            <link rel="canonical" href="https://www.ytswebdesign.nl/"/>
                            <meta property="og:locale" content="en_US"/>
                            <meta property="og:type" content="website"/>
                            <meta property="og:title" content="YTS Webdesign - Wilt u ook een professioneel website laten maken?"/>
                            <meta property="og:description" content="YTS Webdesign realiseert professionele opdrachten waarbij het altijd een slimme en creatieve design biedt die perfect aansluit bij de wensen en eisen."/>
                            <meta property="og:url" content="https://www.ytswebdesign.nl/"/>
                            <meta property="og:site_name" content="YTS Webdesign"/>
                            <meta name="twitter:card" content="summary_large_image"/>
                            <meta name="twitter:description" content="YTS Webdesign realiseert professionele opdrachten waarbij het altijd een slimme en creatieve design biedt die perfect aansluit bij de wensen en eisen."/>
                            <meta name="twitter:title" content="YTS Webdesign - Wilt u ook een professioneel website laten maken?"/>
                            <meta name="twitter:image" content="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/A2000-PS-768x408.jpg"/>
                            <script type='application/ld+json'>{"@context":"https:\/\/schema.org","@type":"WebSite","@id":"#website","url":"https:\/\/www.ytswebdesign.nl\/","name":"YTS Webdesign","potentialAction":{"@type":"SearchAction","target":"https:\/\/www.ytswebdesign.nl\/?s={search_term_string}","query-input":"required name=search_term_string"}}</script>
                            <script type='application/ld+json'>{"@context":"https:\/\/schema.org","@type":"Organization","url":"https:\/\/www.ytswebdesign.nl\/","sameAs":[],"@id":"https:\/\/www.ytswebdesign.nl\/#organization","name":"YTS Webdesign","logo":"https:\/\/www.ytswebdesign.nl\/wp-content\/uploads\/2018\/08\/icon-512-512.png"}</script>
                            <meta name="google-site-verification" content="WzHHT1wDXbtBo2eCcJlSDn1CCOp_qx7b1ivmUPBuv4g"/>
                            <meta name="yandex-verification" content="4ceeecb5e4fce571"/>
                            <!-- / Yoast SEO plugin. -->

                            <link rel='dns-prefetch' href='//fonts.googleapis.com'/>
                            <link rel='dns-prefetch' href='//s.w.org'/>
                            <link rel="alternate" type="application/rss+xml" title="YTS Webdesign &raquo; Feed" href="https://www.ytswebdesign.nl/feed/"/>
                            <link rel="alternate" type="application/rss+xml" title="YTS Webdesign &raquo; Comments Feed" href="https://www.ytswebdesign.nl/comments/feed/"/>
                            <script type="text/javascript">window._wpemojiSettings={"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/11\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/11\/svg\/","svgExt":".svg","source":{"concatemoji":"https:\/\/www.ytswebdesign.nl\/wp-includes\/js\/wp-emoji-release.min.js?ver=5.0.1"}};!function(a,b,c){function d(a,b){var c=String.fromCharCode;l.clearRect(0,0,k.width,k.height),l.fillText(c.apply(this,a),0,0);var d=k.toDataURL();l.clearRect(0,0,k.width,k.height),l.fillText(c.apply(this,b),0,0);var e=k.toDataURL();return d===e}function e(a){var b;if(!l||!l.fillText)return!1;switch(l.textBaseline="top",l.font="600 32px Arial",a){case"flag":return!(b=d([55356,56826,55356,56819],[55356,56826,8203,55356,56819]))&&(b=d([55356,57332,56128,56423,56128,56418,56128,56421,56128,56430,56128,56423,56128,56447],[55356,57332,8203,56128,56423,8203,56128,56418,8203,56128,56421,8203,56128,56430,8203,56128,56423,8203,56128,56447]),!b);case"emoji":return b=d([55358,56760,9792,65039],[55358,56760,8203,9792,65039]),!b}return!1}function f(a){var c=b.createElement("script");c.src=a,c.defer=c.type="text/javascript",b.getElementsByTagName("head")[0].appendChild(c)}var g,h,i,j,k=b.createElement("canvas"),l=k.getContext&&k.getContext("2d");for(j=Array("flag","emoji"),c.supports={everything:!0,everythingExceptFlag:!0},i=0;i<j.length;i++)c.supports[j[i]]=e(j[i]),c.supports.everything=c.supports.everything&&c.supports[j[i]],"flag"!==j[i]&&(c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&c.supports[j[i]]);c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&!c.supports.flag,c.DOMReady=!1,c.readyCallback=function(){c.DOMReady=!0},c.supports.everything||(h=function(){c.readyCallback()},b.addEventListener?(b.addEventListener("DOMContentLoaded",h,!1),a.addEventListener("load",h,!1)):(a.attachEvent("onload",h),b.attachEvent("onreadystatechange",function(){"complete"===b.readyState&&c.readyCallback()})),g=c.source||{},g.concatemoji?f(g.concatemoji):g.wpemoji&&g.twemoji&&(f(g.twemoji),f(g.wpemoji)))}(window,document,window._wpemojiSettings);</script>
                            <style type="text/css">img.wp-smiley,img.emoji{display:inline!important;border:none!important;box-shadow:none!important;height:1em!important;width:1em!important;margin:0 .07em!important;vertical-align:-.1em!important;background:none!important;padding:0!important}</style>
                            <style id='essential_addons_elementor-notice-css-css' media='all'>.eael-review-notice{padding:15px 15px 15px 0;background-color:#fff;border-radius:3px;margin:20px 20px 0 0;border-left:4px solid transparent}.eael-review-notice:after{content:'';display:table;clear:both}.eael-review-thumbnail{width:114px;float:left;line-height:80px;text-align:center;border-right:4px solid transparent}.eael-review-thumbnail img{width:72px;vertical-align:middle;opacity:.85;transition:all .3s}.eael-review-thumbnail img:hover{opacity:1}.eael-review-text{overflow:hidden}.eael-review-text h3{font-size:24px;margin:0 0 5px;font-weight:400;line-height:1.3}.eael-review-text p{font-size:13px;margin:0 0 5px}.eael-review-ul{margin:0;padding:0}.eael-review-ul li{display:inline-block;margin-right:15px}.eael-review-ul li a{display:inline-block;color:#10738b;text-decoration:none;padding-left:26px;position:relative}.eael-review-ul li a span{position:absolute;left:0;top:-2px}</style>
                            <link rel='stylesheet' id='wp-block-library-css' href='https://www.ytswebdesign.nl/wp-includes/css/dist/block-library/A.style.min.css,qver=5.0.1.pagespeed.cf.qpjlrh86ek.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='essential_addons_elementor-css-css' href='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/css/A.essential-addons-elementor.css,qver=5.0.1.pagespeed.cf.FhM7-h6B4M.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='wpforms-full-css' href='https://www.ytswebdesign.nl/wp-content/plugins/wpforms-lite/assets/css/A.wpforms-full.css,qver=1.5.0.3.pagespeed.cf.YA2pSbySp5.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='essential_addons_elementor-tooltipster-css' href='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/css/A.tooltipster.bundle.min.css,qver=5.0.1.pagespeed.cf.WjYXJgA9oL.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='font-awesome-css' href='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/css/third/A.font-awesome.min.css,qver=4.7.0.pagespeed.cf.iWLJeBK_-M.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='simple-line-icons-css' href='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/css/third/simple-line-icons.min.css?ver=2.4.0' type='text/css' media='all'/>
                            <link rel='stylesheet' id='magnific-popup-css' href='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/css/third/magnific-popup.min.css?ver=1.0.0' type='text/css' media='all'/>
                            <style id='slick-css' media='all'>.slick-list,.slick-slider,.slick-track{position:relative;display:block}.slick-loading .slick-slide,.slick-loading .slick-track{visibility:hidden}.slick-slider{box-sizing:border-box;-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-ms-touch-action:pan-y;touch-action:pan-y;-webkit-tap-highlight-color:transparent}.slick-list{overflow:hidden;margin:0;padding:0}.slick-list:focus{outline:0}.slick-list.dragging{cursor:pointer;cursor:hand}.slick-slider .slick-list,.slick-slider .slick-track{-webkit-transform:translate3d(0,0,0);-moz-transform:translate3d(0,0,0);-ms-transform:translate3d(0,0,0);-o-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}.slick-track{left:0;top:0}.slick-track:after,.slick-track:before{content:"";display:table}.slick-track:after{clear:both}.slick-slide{float:left;height:100%;min-height:1px;display:none}[dir=rtl] .slick-slide{float:right}.slick-slide img{display:block}.slick-slide.slick-loading img{display:none}.slick-slide.dragging img{pointer-events:none}.slick-initialized .slick-slide{display:block}.slick-vertical .slick-slide{display:block;height:auto;border:1px solid transparent}.slick-arrow.slick-hidden{display:none}@font-face{font-family:slick;src:url(wp-content/themes/oceanwp/assets/fonts/slick/slick.eot);src:url(wp-content/themes/oceanwp/assets/fonts/slick/slick.eot?#iefix) format("embedded-opentype"),url(wp-content/themes/oceanwp/assets/fonts/slick/slick.woff) format("woff"),url(wp-content/themes/oceanwp/assets/fonts/slick/slick.ttf) format("truetype"),url(wp-content/themes/oceanwp/assets/fonts/slick/slick.svg#slick) format("svg");font-weight:400;font-style:normal}</style>
                            <link rel='stylesheet' id='oceanwp-style-css' href='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/css/A.style.min.css,qver=1.5.32.pagespeed.cf.OQFjsC8Jku.css' type='text/css' media='all'/>
                            <style id='oceanwp-hamburgers-css' media='all'>.hamburger{display:inline-block;cursor:pointer;transition-property:opacity,filter;transition-duration:.15s;transition-timing-function:linear;font:inherit;color:inherit;text-transform:none;background-color:transparent;border:0;margin:0;overflow:visible}a:hover .hamburger{opacity:.7}.hamburger-box{width:20px;height:16px;display:inline-block;position:relative}.hamburger-inner{display:block;top:50%;margin-top:2px}.hamburger-inner,.hamburger-inner::after,.hamburger-inner::before{width:20px;height:2px;background-color:#000;border-radius:3px;position:absolute;transition-property:transform;transition-duration:.15s;transition-timing-function:ease}.hamburger-inner::after,.hamburger-inner::before{content:"";display:block}.hamburger-inner::before{top:-7px}.hamburger-inner::after{bottom:-7px}</style>
                            <style id='oceanwp-spring-r-css' media='all'>.hamburger--spring-r .hamburger-inner{top:auto;bottom:-3px;-webkit-transition-duration:.13s;transition-duration:.13s;-webkit-transition-delay:0s;transition-delay:0s;-webkit-transition-timing-function:cubic-bezier(.55,.055,.675,.19);transition-timing-function:cubic-bezier(.55,.055,.675,.19)}.hamburger--spring-r .hamburger-inner::after{top:-14px;-webkit-transition:top .2s .2s cubic-bezier(.33333,.66667,.66667,1) , opacity 0s linear;transition:top .2s .2s cubic-bezier(.33333,.66667,.66667,1) , opacity 0s linear}.hamburger--spring-r .hamburger-inner::before{-webkit-transition:top .1s .2s cubic-bezier(.33333,.66667,.66667,1) , -webkit-transform .13s cubic-bezier(.55,.055,.675,.19);transition:top .1s .2s cubic-bezier(.33333,.66667,.66667,1) , transform .13s cubic-bezier(.55,.055,.675,.19)}.hamburger--spring-r.is-active .hamburger-inner{-webkit-transform:translate3d(0,-7px,0) rotate(-45deg);transform:translate3d(0,-7px,0) rotate(-45deg);-webkit-transition-delay:.22s;transition-delay:.22s;-webkit-transition-timing-function:cubic-bezier(.215,.61,.355,1);transition-timing-function:cubic-bezier(.215,.61,.355,1)}.hamburger--spring-r.is-active .hamburger-inner::after{top:0;opacity:0;-webkit-transition:top .2s cubic-bezier(.33333,0,.66667,.33333) , opacity 0s .22s linear;transition:top .2s cubic-bezier(.33333,0,.66667,.33333) , opacity 0s .22s linear}.hamburger--spring-r.is-active .hamburger-inner::before{top:0;-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg);-webkit-transition:top .1s .15s cubic-bezier(.33333,0,.66667,.33333) , -webkit-transform .13s .22s cubic-bezier(.215,.61,.355,1);transition:top .1s .15s cubic-bezier(.33333,0,.66667,.33333) , transform .13s .22s cubic-bezier(.215,.61,.355,1)}</style>
                            <link crossorigin="anonymous" rel='stylesheet' id='oceanwp-google-font-source-sans-pro-css' href='//fonts.googleapis.com/css?family=Source+Sans+Pro%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900%2C100i%2C200i%2C300i%2C400i%2C500i%2C600i%2C700i%2C800i%2C900i&#038;subset=latin&#038;ver=5.0.1' type='text/css' media='all'/>
                            <link crossorigin="anonymous" rel='stylesheet' id='oceanwp-google-font-aldrich-css' href='//fonts.googleapis.com/css?family=Aldrich%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900%2C100i%2C200i%2C300i%2C400i%2C500i%2C600i%2C700i%2C800i%2C900i&#038;subset=latin&#038;ver=5.0.1' type='text/css' media='all'/>
                            <link rel='stylesheet' id='elementor-icons-css' href='https://www.ytswebdesign.nl/wp-content/plugins/elementor/assets/lib/eicons/css/A.elementor-icons.min.css,qver=4.0.0.pagespeed.cf.nvc3Qzdi66.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='elementor-animations-css' href='https://www.ytswebdesign.nl/wp-content/plugins/elementor/assets/lib/animations/animations.min.css,qver=2.3.5.pagespeed.ce.RgG6VQREE3.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='elementor-frontend-css' href='https://www.ytswebdesign.nl/wp-content/plugins/elementor/assets/css/frontend.min.css,qver=2.3.5.pagespeed.ce.4ABXGbL_4V.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='elementor-global-css' href='https://www.ytswebdesign.nl/wp-content/uploads/elementor/css/A.global.css,qver=1544570375.pagespeed.cf.ugm-PJ0cBZ.css' type='text/css' media='all'/>
                            <link rel='stylesheet' id='elementor-post-6-css' href='https://www.ytswebdesign.nl/wp-content/uploads/elementor/css/A.post-6.css,qver=1544570375.pagespeed.cf.JM9jyiIP1G.css' type='text/css' media='all'/>
                            <link crossorigin="anonymous" rel='stylesheet' id='google-fonts-1-css' href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CSource+Sans+Pro%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=5.0.1' type='text/css' media='all'/>
                            <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-includes/js/jquery/jquery.js,qver=1.12.4.pagespeed.jm.pPCPAKkkss.js'></script>
                            <script src="https://www.ytswebdesign.nl/wp-includes,_js,_jquery,_jquery-migrate.min.js,qver==1.4.1+wp-content,_plugins,_sticky-menu-or-anything-on-scroll,_assets,_js,_jq-sticky-anything.min.js,qver==2.1.1.pagespeed.jc.kea9IWdiuA.js"></script><script>eval(mod_pagespeed_mUhmJEi5Tr);</script>
                            <script>eval(mod_pagespeed_ER1PPMIFqH);</script>
                            <link rel='https://api.w.org/' href='https://www.ytswebdesign.nl/wp-json/'/>
                            <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://www.ytswebdesign.nl/xmlrpc.php?rsd"/>
                            <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://www.ytswebdesign.nl/wp-includes/wlwmanifest.xml"/>
                            <meta name="generator" content="WordPress 5.0.1"/>
                            <link rel='shortlink' href='https://www.ytswebdesign.nl/'/>
                            <link rel="alternate" type="application/json+oembed" href="https://www.ytswebdesign.nl/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fwww.ytswebdesign.nl%2F"/>
                            <link rel="alternate" type="text/xml+oembed" href="https://www.ytswebdesign.nl/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fwww.ytswebdesign.nl%2F&#038;format=xml"/>
                            <link rel="icon" href="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xcropped-icon-512-512-2-32x32.png.pagespeed.ic.eTnlNimWF9.png" sizes="32x32"/>
                            <link rel="icon" href="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xcropped-icon-512-512-2-192x192.png.pagespeed.ic.jP4GMZxwHW.png" sizes="192x192"/>
                            <link rel="apple-touch-icon-precomposed" href="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xcropped-icon-512-512-2-180x180.png.pagespeed.ic.Bn8Mr9brur.png"/>
                            <meta name="msapplication-TileImage" content="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/cropped-icon-512-512-2-270x270.png"/>

                            <!-- BEGIN ExactMetrics v5.3.7 Universal Analytics - https://exactmetrics.com/ -->
                            <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create','UA-124911736-1','auto');ga('send','pageview');</script>
                            <!-- END ExactMetrics Universal Analytics -->
                            <style type="text/css" id="wp-custom-css">#site-header{box-shadow:0 10px 5px #27272780;-webkit-box-shadow:0 5px 8px #27272780;z-index:10}#site-logo #site-logo-inner a:hover img{opacity:1}</style>
                            <!-- OceanWP CSS -->
                            <style type="text/css">@media only screen and (min-width:960px){.widget-area,.content-left-sidebar .widget-area{width:29%}}#main #content-wrap,.separate-layout #main #content-wrap{padding-top:0;padding-bottom:0}label,body div.wpforms-container-full .wpforms-form .wpforms-field-label{color:#fff}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea{font-size:16px}body div.wpforms-container-full .wpforms-form input[type="date"],body div.wpforms-container-full .wpforms-form input[type="datetime"],body div.wpforms-container-full .wpforms-form input[type="datetime-local"],body div.wpforms-container-full .wpforms-form input[type="email"],body div.wpforms-container-full .wpforms-form input[type="month"],body div.wpforms-container-full .wpforms-form input[type="number"],body div.wpforms-container-full .wpforms-form input[type="password"],body div.wpforms-container-full .wpforms-form input[type="range"],body div.wpforms-container-full .wpforms-form input[type="search"],body div.wpforms-container-full .wpforms-form input[type="tel"],body div.wpforms-container-full .wpforms-form input[type="text"],body div.wpforms-container-full .wpforms-form input[type="time"],body div.wpforms-container-full .wpforms-form input[type="url"],body div.wpforms-container-full .wpforms-form input[type="week"],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{font-size:16px}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea,.select2-container .select2-choice,.woocommerce .woocommerce-checkout .select2-container--default .select2-selection--single{border-color:#000}body div.wpforms-container-full .wpforms-form input[type="date"],body div.wpforms-container-full .wpforms-form input[type="datetime"],body div.wpforms-container-full .wpforms-form input[type="datetime-local"],body div.wpforms-container-full .wpforms-form input[type="email"],body div.wpforms-container-full .wpforms-form input[type="month"],body div.wpforms-container-full .wpforms-form input[type="number"],body div.wpforms-container-full .wpforms-form input[type="password"],body div.wpforms-container-full .wpforms-form input[type="range"],body div.wpforms-container-full .wpforms-form input[type="search"],body div.wpforms-container-full .wpforms-form input[type="tel"],body div.wpforms-container-full .wpforms-form input[type="text"],body div.wpforms-container-full .wpforms-form input[type="time"],body div.wpforms-container-full .wpforms-form input[type="url"],body div.wpforms-container-full .wpforms-form input[type="week"],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{border-color:#000}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea,.woocommerce .woocommerce-checkout .select2-container--default .select2-selection--single{background-color:#333}body div.wpforms-container-full .wpforms-form input[type="date"],body div.wpforms-container-full .wpforms-form input[type="datetime"],body div.wpforms-container-full .wpforms-form input[type="datetime-local"],body div.wpforms-container-full .wpforms-form input[type="email"],body div.wpforms-container-full .wpforms-form input[type="month"],body div.wpforms-container-full .wpforms-form input[type="number"],body div.wpforms-container-full .wpforms-form input[type="password"],body div.wpforms-container-full .wpforms-form input[type="range"],body div.wpforms-container-full .wpforms-form input[type="search"],body div.wpforms-container-full .wpforms-form input[type="tel"],body div.wpforms-container-full .wpforms-form input[type="text"],body div.wpforms-container-full .wpforms-form input[type="time"],body div.wpforms-container-full .wpforms-form input[type="url"],body div.wpforms-container-full .wpforms-form input[type="week"],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{background-color:#333}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea{color:#fff}body div.wpforms-container-full .wpforms-form input[type="date"],body div.wpforms-container-full .wpforms-form input[type="datetime"],body div.wpforms-container-full .wpforms-form input[type="datetime-local"],body div.wpforms-container-full .wpforms-form input[type="email"],body div.wpforms-container-full .wpforms-form input[type="month"],body div.wpforms-container-full .wpforms-form input[type="number"],body div.wpforms-container-full .wpforms-form input[type="password"],body div.wpforms-container-full .wpforms-form input[type="range"],body div.wpforms-container-full .wpforms-form input[type="search"],body div.wpforms-container-full .wpforms-form input[type="tel"],body div.wpforms-container-full .wpforms-form input[type="text"],body div.wpforms-container-full .wpforms-form input[type="time"],body div.wpforms-container-full .wpforms-form input[type="url"],body div.wpforms-container-full .wpforms-form input[type="week"],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{color:#fff}#site-logo #site-logo-inner,.oceanwp-social-menu .social-menu-inner,#site-header.full_screen-header .menu-bar-inner,.after-header-content .after-header-content-inner{height:66px}#site-navigation-wrap .dropdown-menu>li>a,.oceanwp-mobile-menu-icon a,.after-header-content-inner>a{line-height:66px}#site-header,.has-transparent-header .is-sticky #site-header,.has-vh-transparent .is-sticky #site-header.vertical-header,#searchform-header-replace{background-color:#444}#site-header{border-color:#000}#site-header.has-header-media .overlay-header-media{background-color:rgba(221,51,51,.11)}#site-logo #site-logo-inner a img,#site-header.center-header #site-navigation-wrap .middle-site-logo a img{max-width:200px}#site-logo a.site-logo-text{color:#fff}#site-logo a.site-logo-text:hover{color:#006dc1}#site-navigation-wrap .dropdown-menu>li>a{padding:0 23px}#site-navigation-wrap .dropdown-menu>li>a,.oceanwp-mobile-menu-icon a,#searchform-header-replace-close{color:#fefefe}#site-navigation-wrap .dropdown-menu>li>a:hover,.oceanwp-mobile-menu-icon a:hover,#searchform-header-replace-close:hover{color:#006dc1}#site-navigation-wrap .dropdown-menu>.current-menu-item>a,#site-navigation-wrap .dropdown-menu>.current-menu-ancestor>a,#site-navigation-wrap .dropdown-menu>.current-menu-item>a:hover,#site-navigation-wrap .dropdown-menu>.current-menu-ancestor>a:hover{color:#006dc1}#site-navigation-wrap .dropdown-menu>li>a{background-color:rgba(57,57,57,0)}.dropdown-menu .sub-menu,#searchform-dropdown,.current-shop-items-dropdown{background-color:#444}.dropdown-menu .sub-menu,#searchform-dropdown,.current-shop-items-dropdown{border-color:#006dc1}.dropdown-menu ul li a.menu-link{color:#fff}.dropdown-menu ul li a.menu-link:hover{color:#006dc1}.dropdown-menu ul li a.menu-link:hover{background-color:rgba(57,57,57,0)}.dropdown-menu ul>.current-menu-item>a.menu-link{color:#006dc1}.dropdown-menu ul>.current-menu-item>a.menu-link{background-color:#444}.navigation li.mega-cat .mega-cat-title{color:#444}.navigation li.mega-cat ul li .mega-post-title a{color:#444}.navigation li.mega-cat ul li .mega-post-title a:hover{color:#444}.mobile-menu .hamburger-inner,.mobile-menu .hamburger-inner::before,.mobile-menu .hamburger-inner::after{background-color:#fff}#mobile-dropdown{max-height:460px}#sidr,#mobile-dropdown{background-color:#393939}#sidr li,#sidr ul,#mobile-dropdown ul li,#mobile-dropdown ul li ul{border-color:#efefef}body .sidr a,body .sidr-class-dropdown-toggle,#mobile-dropdown ul li a,#mobile-dropdown ul li a .dropdown-toggle,#mobile-fullscreen ul li a,#mobile-fullscreen .oceanwp-social-menu.simple-social ul li a{color:#fff}#mobile-fullscreen a.close .close-icon-inner,#mobile-fullscreen a.close .close-icon-inner::after{background-color:#fff}body .sidr a:hover,body .sidr-class-dropdown-toggle:hover,body .sidr-class-dropdown-toggle .fa,body .sidr-class-menu-item-has-children.active>a,body .sidr-class-menu-item-has-children.active>a>.sidr-class-dropdown-toggle,#mobile-dropdown ul li a:hover,#mobile-dropdown ul li a .dropdown-toggle:hover,#mobile-dropdown .menu-item-has-children.active>a,#mobile-dropdown .menu-item-has-children.active>a>.dropdown-toggle,#mobile-fullscreen ul li a:hover,#mobile-fullscreen .oceanwp-social-menu.simple-social ul li a:hover{color:#006dc1}#mobile-fullscreen a.close:hover .close-icon-inner,#mobile-fullscreen a.close:hover .close-icon-inner::after{background-color:#006dc1}.sidr-class-dropdown-menu ul,#mobile-dropdown ul li ul,#mobile-fullscreen ul ul.sub-menu{background-color:#474747}#footer-bottom{background-color:#393939}body{font-family:Source\ Sans\ Pro;font-weight:700;font-size:16px;color:#fff}#site-logo a.site-logo-text{font-family:Aldrich}@media (max-width:480px){#site-logo a.site-logo-text{font-size:20px}}#site-navigation-wrap .dropdown-menu>li>a,#site-header.full_screen-header .fs-dropdown-menu>li>a,#site-header.top-header #site-navigation-wrap .dropdown-menu>li>a,#site-header.center-header #site-navigation-wrap .dropdown-menu>li>a,#site-header.medium-header #site-navigation-wrap .dropdown-menu>li>a,.oceanwp-mobile-menu-icon a{font-family:Source\ Sans\ Pro;font-weight:600;font-size:23px}.dropdown-menu ul li a.menu-link,#site-header.full_screen-header .fs-dropdown-menu ul.sub-menu li a{font-family:Source\ Sans\ Pro;font-weight:600;font-size:16px}.sidr-class-dropdown-menu li a,a.sidr-class-toggle-sidr-close,#mobile-dropdown ul li a,body #mobile-fullscreen ul li a{font-family:Source\ Sans\ Pro;font-weight:600;font-size:16px}@media (max-width:480px){.sidr-class-dropdown-menu li a,a.sidr-class-toggle-sidr-close,#mobile-dropdown ul li a,body #mobile-fullscreen ul li a{font-size:16px;line-height:1.4}}.site-breadcrumbs{font-family:Source\ Sans\ Pro}.sidebar-box .widget-title{font-family:Source\ Sans\ Pro;font-weight:600}.sidebar-box,.footer-box{font-family:Source\ Sans\ Pro}</style></head>

                        <body class="home page-template-default page page-id-6 wp-custom-logo oceanwp-theme dropdown-mobile default-breakpoint content-full-width content-max-width page-header-disabled has-breadcrumbs elementor-default elementor-page elementor-page-6">


                        <div id="outer-wrap" class="site clr">


                            <div id="wrap" class="clr">



                                <header id="site-header" class="minimal-header clr" data-height="66" itemscope="itemscope" itemtype="http://schema.org/WPHeader">




                                    <div id="site-header-inner" class="clr container">




                                        <div id="site-logo" class="clr" itemscope itemtype="http://schema.org/Brand">


                                            <div id="site-logo-inner" class="clr">

                                                <a href="https://www.ytswebdesign.nl/" class="custom-logo-link" rel="home" itemprop="url"><script data-pagespeed-no-defer>//<![CDATA[
                                                        (function(){for(var g="function"==typeof Object.defineProperties?Object.defineProperty:function(b,c,a){if(a.get||a.set)throw new TypeError("ES3 does not support getters and setters.");b!=Array.prototype&&b!=Object.prototype&&(b[c]=a.value)},h="undefined"!=typeof window&&window===this?this:"undefined"!=typeof global&&null!=global?global:this,k=["String","prototype","repeat"],l=0;l<k.length-1;l++){var m=k[l];m in h||(h[m]={});h=h[m]}var n=k[k.length-1],p=h[n],q=p?p:function(b){var c;if(null==this)throw new TypeError("The 'this' value for String.prototype.repeat must not be null or undefined");c=this+"";if(0>b||1342177279<b)throw new RangeError("Invalid count value");b|=0;for(var a="";b;)if(b&1&&(a+=c),b>>>=1)c+=c;return a};q!=p&&null!=q&&g(h,n,{configurable:!0,writable:!0,value:q});var t=this;function u(b,c){var a=b.split("."),d=t;a[0]in d||!d.execScript||d.execScript("var "+a[0]);for(var e;a.length&&(e=a.shift());)a.length||void 0===c?d[e]?d=d[e]:d=d[e]={}:d[e]=c};function v(b){var c=b.length;if(0<c){for(var a=Array(c),d=0;d<c;d++)a[d]=b[d];return a}return[]};function w(b){var c=window;if(c.addEventListener)c.addEventListener("load",b,!1);else if(c.attachEvent)c.attachEvent("onload",b);else{var a=c.onload;c.onload=function(){b.call(this);a&&a.call(this)}}};var x;function y(b,c,a,d,e){this.h=b;this.j=c;this.l=a;this.f=e;this.g={height:window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight,width:window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth};this.i=d;this.b={};this.a=[];this.c={}}function z(b,c){var a,d,e=c.getAttribute("data-pagespeed-url-hash");if(a=e&&!(e in b.c))if(0>=c.offsetWidth&&0>=c.offsetHeight)a=!1;else{d=c.getBoundingClientRect();var f=document.body;a=d.top+("pageYOffset"in window?window.pageYOffset:(document.documentElement||f.parentNode||f).scrollTop);d=d.left+("pageXOffset"in window?window.pageXOffset:(document.documentElement||f.parentNode||f).scrollLeft);f=a.toString()+","+d;b.b.hasOwnProperty(f)?a=!1:(b.b[f]=!0,a=a<=b.g.height&&d<=b.g.width)}a&&(b.a.push(e),b.c[e]=!0)}y.prototype.checkImageForCriticality=function(b){b.getBoundingClientRect&&z(this,b)};u("pagespeed.CriticalImages.checkImageForCriticality",function(b){x.checkImageForCriticality(b)});u("pagespeed.CriticalImages.checkCriticalImages",function(){A(x)});function A(b){b.b={};for(var c=["IMG","INPUT"],a=[],d=0;d<c.length;++d)a=a.concat(v(document.getElementsByTagName(c[d])));if(a.length&&a[0].getBoundingClientRect){for(d=0;c=a[d];++d)z(b,c);a="oh="+b.l;b.f&&(a+="&n="+b.f);if(c=!!b.a.length)for(a+="&ci="+encodeURIComponent(b.a[0]),d=1;d<b.a.length;++d){var e=","+encodeURIComponent(b.a[d]);131072>=a.length+e.length&&(a+=e)}b.i&&(e="&rd="+encodeURIComponent(JSON.stringify(B())),131072>=a.length+e.length&&(a+=e),c=!0);C=a;if(c){d=b.h;b=b.j;var f;if(window.XMLHttpRequest)f=new XMLHttpRequest;else if(window.ActiveXObject)try{f=new ActiveXObject("Msxml2.XMLHTTP")}catch(r){try{f=new ActiveXObject("Microsoft.XMLHTTP")}catch(D){}}f&&(f.open("POST",d+(-1==d.indexOf("?")?"?":"&")+"url="+encodeURIComponent(b)),f.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),f.send(a))}}}function B(){var b={},c;c=document.getElementsByTagName("IMG");if(!c.length)return{};var a=c[0];if(!("naturalWidth"in a&&"naturalHeight"in a))return{};for(var d=0;a=c[d];++d){var e=a.getAttribute("data-pagespeed-url-hash");e&&(!(e in b)&&0<a.width&&0<a.height&&0<a.naturalWidth&&0<a.naturalHeight||e in b&&a.width>=b[e].o&&a.height>=b[e].m)&&(b[e]={rw:a.width,rh:a.height,ow:a.naturalWidth,oh:a.naturalHeight})}return b}var C="";u("pagespeed.CriticalImages.getBeaconData",function(){return C});u("pagespeed.CriticalImages.Run",function(b,c,a,d,e,f){var r=new y(b,c,a,e,f);x=r;d&&w(function(){window.setTimeout(function(){A(r)},0)})});})();pagespeed.CriticalImages.Run('/mod_pagespeed_beacon','https://www.ytswebdesign.nl/','2L-ZMDIrHf',true,false,'Ru9cuwYHSy4');
                                                        //]]></script><img width="5096" height="1266" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xwit-B-B-compressed.png.pagespeed.ic.q0j5QkgqAb.png" class="custom-logo" alt="YTS Webdesign logo" itemprop="logo" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xwit-B-B-compressed.png.pagespeed.ic.q0j5QkgqAb.png 5096w, https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xwit-B-B-compressed-300x75.png.pagespeed.ic.zIRmXvkG-9.png 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xwit-B-B-compressed-768x191.png.pagespeed.ic.nmzhL_61sy.png 768w, https://www.ytswebdesign.nl/wp-content/uploads/2018/08/xwit-B-B-compressed-1024x254.png.pagespeed.ic.kTYyv5_qMk.png 1024w" sizes="(max-width: 5096px) 100vw, 5096px" data-pagespeed-url-hash="2830681830" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/></a>
                                            </div><!-- #site-logo-inner -->



                                        </div><!-- #site-logo -->

                                        <div id="site-navigation-wrap" class="clr">



                                            <nav id="site-navigation" class="navigation main-navigation clr" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">

                                                <ul id="menu-navigatie" class="main-menu dropdown-menu sf-menu"><li id="menu-item-22" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-6 current_page_item menu-item-22"><a href="https://www.ytswebdesign.nl/" class="menu-link"><span class="text-wrap">Home</span></a></li><li id="menu-item-23" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children dropdown menu-item-23"><a href="https://www.ytswebdesign.nl/diensten/" class="menu-link"><span class="text-wrap">Diensten <span class="nav-arrow fa fa-angle-down"></span></span></a>
                                                        <ul class="sub-menu">
                                                            <li id="menu-item-201" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-201"><a href="https://www.ytswebdesign.nl/diensten/webdesign/" class="menu-link"><span class="text-wrap">Webdesign</span></a></li>	<li id="menu-item-198" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-198"><a href="https://www.ytswebdesign.nl/diensten/website-onderhoud/" class="menu-link"><span class="text-wrap">Website onderhoud</span></a></li>	<li id="menu-item-199" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-199"><a href="https://www.ytswebdesign.nl/diensten/responsive-design/" class="menu-link"><span class="text-wrap">Responsive webdesign</span></a></li>	<li id="menu-item-200" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-200"><a href="https://www.ytswebdesign.nl/diensten/hosting/" class="menu-link"><span class="text-wrap">Hosting</span></a></li>	<li id="menu-item-202" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-202"><a href="https://www.ytswebdesign.nl/diensten/zoekmachine-optimalisatie-seo/" class="menu-link"><span class="text-wrap">Zoekmachine optimalisatie (SEO)</span></a></li>	<li id="menu-item-197" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-197"><a href="https://www.ytswebdesign.nl/diensten/ssl-certificaat/" class="menu-link"><span class="text-wrap">SSL Certificaat</span></a></li></ul>
                                                    </li><li id="menu-item-571" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-571"><a href="https://www.ytswebdesign.nl/tarieven/" class="menu-link"><span class="text-wrap">Tarieven</span></a></li><li id="menu-item-24" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-24"><a href="https://www.ytswebdesign.nl/contact/" class="menu-link"><span class="text-wrap">Contact</span></a></li></ul>
                                            </nav><!-- #site-navigation -->



                                        </div><!-- #site-navigation-wrap -->




                                        <div class="oceanwp-mobile-menu-icon clr mobile-right">




                                            <a href="#" class="mobile-menu">
                                                <div class="hamburger hamburger--spring-r">
                                                    <div class="hamburger-box">
                                                        <div class="hamburger-inner"></div>
                                                    </div>
                                                </div>
                                            </a>




                                        </div><!-- #oceanwp-mobile-menu-navbar -->


                                    </div><!-- #site-header-inner -->


                                    <div id="mobile-dropdown" class="clr">

                                        <nav class="clr" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">

                                            <ul id="menu-navigatie-1" class="menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-6 current_page_item menu-item-22"><a href="https://www.ytswebdesign.nl/">Home</a></li>
                                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-23"><a href="https://www.ytswebdesign.nl/diensten/">Diensten</a>
                                                    <ul class="sub-menu">
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-201"><a href="https://www.ytswebdesign.nl/diensten/webdesign/">Webdesign</a></li>
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-198"><a href="https://www.ytswebdesign.nl/diensten/website-onderhoud/">Website onderhoud</a></li>
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-199"><a href="https://www.ytswebdesign.nl/diensten/responsive-design/">Responsive webdesign</a></li>
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-200"><a href="https://www.ytswebdesign.nl/diensten/hosting/">Hosting</a></li>
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-202"><a href="https://www.ytswebdesign.nl/diensten/zoekmachine-optimalisatie-seo/">Zoekmachine optimalisatie (SEO)</a></li>
                                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-197"><a href="https://www.ytswebdesign.nl/diensten/ssl-certificaat/">SSL Certificaat</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-571"><a href="https://www.ytswebdesign.nl/tarieven/">Tarieven</a></li>
                                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-24"><a href="https://www.ytswebdesign.nl/contact/">Contact</a></li>
                                            </ul>
                                        </nav>

                                    </div>




                                </header><!-- #site-header -->



                                <main id="main" class="site-main clr">



                                    <div id="content-wrap" class="container clr">


                                        <div id="primary" class="content-area clr">


                                            <div id="content" class="site-content clr">



                                                <article class="single-page-article clr">


                                                    <div class="entry clr" itemprop="text">
                                                        <div class="elementor elementor-6">
                                                            <div class="elementor-inner">
                                                                <div class="elementor-section-wrap">
                                                                    <section data-id="59bb773" class="elementor-element elementor-element-59bb773 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="61f501c" class="elementor-element elementor-element-61f501c elementor-column elementor-col-100 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="2a58faf" class="elementor-element elementor-element-2a58faf elementor-widget elementor-widget-spacer" data-element_type="spacer.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-spacer">
                                                                                                        <div class="elementor-spacer-inner"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div data-id="8649520" class="elementor-element elementor-element-8649520 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h1 class="elementor-heading-title elementor-size-default">YTS Webdesign - Wilt u ook een professioneel website?</h1>		</div>
                                                                                            </div>
                                                                                            <div data-id="baf473e" class="elementor-element elementor-element-baf473e elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>YTS Webdesign realiseert professionele opdrachten waarbij het altijd een slimme en creatieve design biedt die perfect aansluit bij de wensen en eisen van de opdrachtgever.</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div data-id="3454623" class="elementor-element elementor-element-3454623 elementor-widget elementor-widget-spacer" data-element_type="spacer.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-spacer">
                                                                                                        <div class="elementor-spacer-inner"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="0a2c4dc" class="elementor-element elementor-element-0a2c4dc elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}" data-element_type="section">
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="015f923" class="elementor-element elementor-element-015f923 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="1b0861c" class="elementor-element elementor-element-1b0861c elementor-hidden-tablet elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-image">
                                                                                                        <img width="768" height="408" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xA2000-PS-768x408.jpg.pagespeed.ic.Zw-6Xoc9lu.jpg" class="attachment-medium_large size-medium_large" alt="Responsive Autohandel2000" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xA2000-PS-768x408.jpg.pagespeed.ic.Zw-6Xoc9lu.jpg 768w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/A2000-PS-300x159.jpg 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xA2000-PS-1024x544.jpg.pagespeed.ic.0wel5pObPJ.jpg 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/A2000-PS.jpg 1404w" sizes="(max-width: 768px) 100vw, 768px" data-pagespeed-url-hash="4150593556" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>											</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div data-id="4900ea4" class="elementor-element elementor-element-4900ea4 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="175688d" class="elementor-element elementor-element-175688d elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h2 class="elementor-heading-title elementor-size-default">Waarom kiezen voor een webdesigner?</h2>		</div>
                                                                                            </div>
                                                                                            <div data-id="b1751e6" class="elementor-element elementor-element-b1751e6 elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>Zoals u wel weet is de eerste indruk die iemand op u maakt van groot belang. Zo is het ook met uw website. Maakt uw website op het eerste gezicht geen goede en overzichtelijke indruk dan is men al vaak geneigd om verder te “klikken”. De website wordt UW visitekaartje op het internet, deze vertegenwoordigd uw bedrijf. De eerste indruk die een potentiële klant of bezoeker van uw site krijgt is daarom van groot belang. U dient zich daarom ook altijd de vraag te stellen, wat wil ik met mijn website. Moet deze b.v. informatief, zakelijk, speels of een combinatie hiervan worden. Zolang een bezoeker op uw site aanwezig is, is hij niet bij de concurrentie. Blijf uw bezoeker boeien!</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="54d2f56" class="elementor-element elementor-element-54d2f56 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-background-overlay"></div>
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="cbf83a3" class="elementor-element elementor-element-cbf83a3 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="5d27bc1" class="elementor-element elementor-element-5d27bc1 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h2 class="elementor-heading-title elementor-size-default">Wat kost een website laten maken eigenlijk?</h2>		</div>
                                                                                            </div>
                                                                                            <div data-id="ec9a07f" class="elementor-element elementor-element-ec9a07f elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>Je zult zien dat er grote verschillen zijn in tarieven voor het bouwen van websites. Mijn tarief ligt onder het gemiddelde. Hoe kan dat? Dit komt doordat ik nog op het HBO studeer voor mijn ICT diploma, daarnaast heb ik ook een bijbaan. Dit betekent dat ik niet hoef te leven van het maken van websites, en er hoeft geen duur kantoor van betaald te worden. Op deze manier kan ik dus werken tegen lagere tarieven. Wil je dus een website laten maken tegen een aantrekkelijk tarief?</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div data-id="c1fb6d6" class="elementor-element elementor-element-c1fb6d6 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="53ab578" class="elementor-element elementor-element-53ab578 eael-pricing-content-align-center eael-pricing-button-align-center elementor-widget elementor-widget-eael-pricing-table" data-element_type="eael-pricing-table.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="eael-pricing style-1">
                                                                                                        <div class="eael-pricing-item ">
                                                                                                            <div class="header">
                                                                                                                <h2 class="title">Website laten maken</h2>
                                                                                                            </div>
                                                                                                            <div class="eael-pricing-tag">
                                                                                                                <span class="price-tag"><span class="price-currency">€</span>295</span>
                                                                                                                <span class="price-period">/ ex BTW</span>
                                                                                                            </div>
                                                                                                            <div class="body">
                                                                                                                <ul>
                                                                                                                    <li>
                                                                                                                        <span class="li-icon" style="color:#00C853"><i class=""></i></span>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                            </div>
                                                                                                            <div class="footer">
                                                                                                                <a href="https://ytswebdesign.nl/tarieven/" class="eael-pricing-button">
                                                                                                                    <i class=" fa-icon-left"></i>
                                                                                                                    Lees verder							    	</a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="1619671" class="elementor-element elementor-element-1619671 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-background-overlay"></div>
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="ef1746f" class="elementor-element elementor-element-ef1746f elementor-column elementor-col-100 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="ea72973" class="elementor-element elementor-element-ea72973 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h2 class="elementor-heading-title elementor-size-default">Uw website in een paar eenvoudige stappen</h2>		</div>
                                                                                            </div>
                                                                                            <div data-id="e3e6100" class="elementor-element elementor-element-e3e6100 elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>Het oriëntatiegesprek vindt normaliter plaats door middel van een persoonlijk gesprek. YTS Webdesign wil graag meer van uw onderneming te weten komen. In het gesprek worden de wensen en de eisen besproken. Na het gesprek heb ik een goed beeld hoe de website er uit moet komen te zien en welke functionaliteiten de website moet hebben.</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="1067fac" class="elementor-element elementor-element-1067fac elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-background-overlay"></div>
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="ada9cb5" class="elementor-element elementor-element-ada9cb5 elementor-column elementor-col-33 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="6fec653" class="elementor-element elementor-element-6fec653 elementor-view-default elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-element_type="icon-box.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-icon-box-wrapper">
                                                                                                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon elementor-animation-">
				<i class="fa fa-star" aria-hidden="true"></i>
				</span>
                                                                                                        </div>
                                                                                                        <div class="elementor-icon-box-content">
                                                                                                            <h3 class="elementor-icon-box-title">
                                                                                                                <span>YTS Webdesign​</span>
                                                                                                            </h3>
                                                                                                            <p class="elementor-icon-box-description">YTS Webdesign is gespecialiseerd in o.a. webdesign, responsive webdesign (smartphone en tablet vriendelijk), ontwikkeling van professionele websites met CMS op maat, landingspagina’s, en zoekmachine optimalisatie / seo.</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div data-id="6bf3b49" class="elementor-element elementor-element-6bf3b49 elementor-column elementor-col-33 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="4006f70" class="elementor-element elementor-element-4006f70 elementor-view-default elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-element_type="icon-box.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-icon-box-wrapper">
                                                                                                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon elementor-animation-">
				<i class="fa fa-laptop" aria-hidden="true"></i>
				</span>
                                                                                                        </div>
                                                                                                        <div class="elementor-icon-box-content">
                                                                                                            <h3 class="elementor-icon-box-title">
                                                                                                                <span>Website op maat</span>
                                                                                                            </h3>
                                                                                                            <p class="elementor-icon-box-description">YTS Webdesign ontwikkelt uw website met CMS op maat met responsive webdesign. Hierdoor kunt u uw website snel en gemakkelijk aanpassen en beheren inclusief aangepaste weergave voor op een smartphone! Dankzij de CMS is het mogelijk om updates binnen te halen zodat uw website technisch altijd up-to-date blijft.</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div data-id="ea64423" class="elementor-element elementor-element-ea64423 elementor-column elementor-col-33 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="ca42710" class="elementor-element elementor-element-ca42710 elementor-view-default elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-element_type="icon-box.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-icon-box-wrapper">
                                                                                                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon elementor-animation-">
				<i class="fa fa-question-circle-o" aria-hidden="true"></i>
				</span>
                                                                                                        </div>
                                                                                                        <div class="elementor-icon-box-content">
                                                                                                            <h3 class="elementor-icon-box-title">
                                                                                                                <span>Waarom YTS Webdesign?</span>
                                                                                                            </h3>
                                                                                                            <p class="elementor-icon-box-description">U bent bij ons geen nummer, maar een individu met eigen wensen en ideeën. Wij zullen uw wensen en ideeën samenvoegen met onze eigen creativiteit om tot een fascinerend eindproduct te komen.</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="e9d5098" class="elementor-element elementor-element-e9d5098 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-background-overlay"></div>
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="2244580" class="elementor-element elementor-element-2244580 elementor-column elementor-col-100 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="f9ee2ae" class="elementor-element elementor-element-f9ee2ae elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h2 class="elementor-heading-title elementor-size-default">Diensten die wij voor u kunnen uitvoeren</h2>		</div>
                                                                                            </div>
                                                                                            <section data-id="3932163" class="elementor-element elementor-element-3932163 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section" data-element_type="section">
                                                                                                <div class="elementor-container elementor-column-gap-default">
                                                                                                    <div class="elementor-row">
                                                                                                        <div data-id="ff07674" class="elementor-element elementor-element-ff07674 elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="653fe38" class="elementor-element elementor-element-653fe38 elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/webdesign/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="1024" height="682" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xonline-3461400_1920-1024x682.png.pagespeed.ic.LHvcr6bOSF.png" class="elementor-animation-float attachment-large size-large" alt="Webdesign" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xonline-3461400_1920-1024x682.png.pagespeed.ic.LHvcr6bOSF.png 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xonline-3461400_1920-300x200.png.pagespeed.ic.Gj8X9W9Sfy.png 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xonline-3461400_1920-768x512.png.pagespeed.ic.KF47L8CIZ1.png 768w" sizes="(max-width: 1024px) 100vw, 1024px" data-pagespeed-url-hash="3645995658" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="fbedc17" class="elementor-element elementor-element-fbedc17 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/webdesign/">Webdesign</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div data-id="136c6c5" class="elementor-element elementor-element-136c6c5 elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="80fd869" class="elementor-element elementor-element-80fd869 elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/responsive-design/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="960" height="640" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xresponsive-1622825_1280.png.pagespeed.ic.9gqozODx3E.png" class="elementor-animation-float attachment-large size-large" alt="Responsive webdesign" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xresponsive-1622825_1280.png.pagespeed.ic.9gqozODx3E.png 960w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xresponsive-1622825_1280-300x200.png.pagespeed.ic.titdX1xbF-.png 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xresponsive-1622825_1280-768x512.png.pagespeed.ic.t8X5oq22VG.png 768w" sizes="(max-width: 960px) 100vw, 960px" data-pagespeed-url-hash="2591227222" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="42528c5" class="elementor-element elementor-element-42528c5 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/responsive-webdesign/">Responsive webdesign</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div data-id="5859b0b" class="elementor-element elementor-element-5859b0b elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="62500fd" class="elementor-element elementor-element-62500fd elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/hosting/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="1024" height="683" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/cloud-3406627_1920-1024x683.jpg" class="elementor-animation-float attachment-large size-large" alt="Webhosting hosting" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/cloud-3406627_1920-1024x683.jpg 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/cloud-3406627_1920-300x200.jpg 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/cloud-3406627_1920-768x512.jpg 768w" sizes="(max-width: 1024px) 100vw, 1024px" data-pagespeed-url-hash="2702942941" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="711d979" class="elementor-element elementor-element-711d979 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/hosting/">Hosting</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </section>
                                                                                            <section data-id="a0ebdf0" class="elementor-element elementor-element-a0ebdf0 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section" data-element_type="section">
                                                                                                <div class="elementor-container elementor-column-gap-default">
                                                                                                    <div class="elementor-row">
                                                                                                        <div data-id="be1c44c" class="elementor-element elementor-element-be1c44c elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="eec0434" class="elementor-element elementor-element-eec0434 elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/website-onderhoud/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="1024" height="683" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/webd-repair-1024x683.jpg" class="elementor-animation-float attachment-large size-large" alt="Website onderhoud" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/webd-repair-1024x683.jpg 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/webd-repair-300x200.jpg 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/webd-repair-768x512.jpg 768w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/webd-repair.jpg 1248w" sizes="(max-width: 1024px) 100vw, 1024px" data-pagespeed-url-hash="1842976452" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="8efc714" class="elementor-element elementor-element-8efc714 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/website-onderhoud/">Website onderhoud</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div data-id="c645e7c" class="elementor-element elementor-element-c645e7c elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="edd5913" class="elementor-element elementor-element-edd5913 elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/zoekmachine-optimalisatie-seo/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="1024" height="683" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xSEO-1024x683.png.pagespeed.ic.4PgYUi-KOw.png" class="elementor-animation-float attachment-large size-large" alt="SEO Zoekoptimalisatie" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xSEO-1024x683.png.pagespeed.ic.4PgYUi-KOw.png 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xSEO-300x200.png.pagespeed.ic.rGCsjSXn5z.png 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/xSEO-768x512.png.pagespeed.ic.9L6hA7RtH1.png 768w" sizes="(max-width: 1024px) 100vw, 1024px" data-pagespeed-url-hash="2794863865" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="1bad329" class="elementor-element elementor-element-1bad329 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/zoekmachine-optimalisatie-seo/">Zoekmachine optimalisatie (SEO)</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div data-id="7f92a7e" class="elementor-element elementor-element-7f92a7e elementor-column elementor-col-33 elementor-inner-column" data-element_type="column">
                                                                                                            <div class="elementor-column-wrap elementor-element-populated">
                                                                                                                <div class="elementor-widget-wrap">
                                                                                                                    <div data-id="f27709e" class="elementor-element elementor-element-f27709e elementor-widget elementor-widget-image" data-element_type="image.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <div class="elementor-image">
                                                                                                                                <a href="https://www.ytswebdesign.nl/diensten/ssl-certificaat/" data-elementor-open-lightbox="">
                                                                                                                                    <img width="1024" height="683" src="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/security-3406723_1920-1024x683.jpg" class="elementor-animation-float attachment-large size-large" alt="SSL Certificaat" srcset="https://www.ytswebdesign.nl/wp-content/uploads/2018/09/security-3406723_1920-1024x683.jpg 1024w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/security-3406723_1920-300x200.jpg 300w, https://www.ytswebdesign.nl/wp-content/uploads/2018/09/security-3406723_1920-768x512.jpg 768w" sizes="(max-width: 1024px) 100vw, 1024px" data-pagespeed-url-hash="1232766313" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"/>								</a>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div data-id="8b12945" class="elementor-element elementor-element-8b12945 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                                        <div class="elementor-widget-container">
                                                                                                                            <h3 class="elementor-heading-title elementor-size-default"><a href="https://www.ytswebdesign.nl/diensten/ssl-certificaat/">SSL certificaat</a></h3>		</div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </section>
                                                                                            <div data-id="7dbb047" class="elementor-element elementor-element-7dbb047 elementor-align-right elementor-mobile-align-right elementor-widget elementor-widget-button" data-element_type="button.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-button-wrapper">
                                                                                                        <a href="diensten" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-text">Lees verder</span>
		</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section data-id="1f1d55b3" class="elementor-element elementor-element-1f1d55b3 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}" data-element_type="section">
                                                                        <div class="elementor-container elementor-column-gap-default">
                                                                            <div class="elementor-row">
                                                                                <div data-id="1d554498" class="elementor-element elementor-element-1d554498 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="3c06af93" class="elementor-element elementor-element-3c06af93 elementor-widget elementor-widget-heading" data-element_type="heading.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <h2 class="elementor-heading-title elementor-size-default">Contact</h2>		</div>
                                                                                            </div>
                                                                                            <div data-id="474e6287" class="elementor-element elementor-element-474e6287 elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>YTS Webdesign<br/>Nijenkamp 117<br/>6651HJ Druten</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div data-id="7c2eeb6f" class="elementor-element elementor-element-7c2eeb6f elementor-widget elementor-widget-icon-list" data-element_type="icon-list.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <ul class="elementor-icon-list-items">
                                                                                                        <li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<i class="fa fa-phone" aria-hidden="true"></i>
						</span>
                                                                                                            <span class="elementor-icon-list-text">0639465142</span>
                                                                                                        </li>
                                                                                                        <li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                                                                                                            <span class="elementor-icon-list-text">info@ytswebdesign.nl</span>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div data-id="4fed3e1b" class="elementor-element elementor-element-4fed3e1b elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-text-editor elementor-clearfix"><p>BTW-nummer: NL230122607B01<br/>KvK-nummer: 72323949</p></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div data-id="38d71b3d" class="elementor-element elementor-element-38d71b3d elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
                                                                                    <div class="elementor-column-wrap elementor-element-populated">
                                                                                        <div class="elementor-widget-wrap">
                                                                                            <div data-id="288fa89b" class="elementor-element elementor-element-288fa89b elementor-widget elementor-widget-shortcode" data-element_type="shortcode.default">
                                                                                                <div class="elementor-widget-container">
                                                                                                    <div class="elementor-shortcode"><style type="text/css">body #wpforms-251{}body #wpforms-251 .wpforms-head-container{border-width:0}body #wpforms-251 .wpforms-head-container .wpforms-title{}body #wpforms-251 .wpforms-head-container .wpforms-description{display:block}body #wpforms-251 .wpforms-submit-container .wpforms-submit{color:#393939;background-color:#fff;border-width:0}body #wpforms-251 .wpforms-submit-container .wpforms-submit:hover{background-color:#006dc1;color:#fff}body #wpforms-251 .wpforms-submit-container{text-align:right}body #wpforms-251 .wpforms-form .wpforms-field input[type="text"],body #wpforms-251 .wpforms-form .wpforms-field input[type="email"],body #wpforms-251 .wpforms-form .wpforms-field input[type="tel"],body #wpforms-251 .wpforms-form .wpforms-field input[type="url"],body #wpforms-251 .wpforms-form .wpforms-field input[type="password"] body #wpforms-251 .wpforms-form .wpforms-field input[type="number"]{border-width:1px}body #wpforms-251 .wpforms-form .wpforms-field textarea{border-width:1px}body #wpforms-251 .wpforms-form .wpforms-field.wpforms-field-select select{border-width:1px}body #wpforms-251 .wpforms-form .wpforms-field-radio li label{}body #wpforms-251 .wpforms-form .wpforms-field-checkbox li label{}body #wpforms-251 .wpforms-form .wpforms-field .wpforms-field-description{}body #wpforms-251 .wpforms-form .wpforms-field label.wpforms-field-label{}body #wpforms-251 .wpforms-form .gform_fields .gsection .gsection_title{}body #wpforms-251 .wpforms-form .gform_fields .gsection .gsection_description{}body #wpforms-251 .wpforms-form .gform_fields .gfield .ginput_container{}body #wpforms-confirmation-_251{border-width:1px}body #wpforms-251 label.wpforms-error{border-width:1px}</style>
                                                                                                        <div class="wpforms-container wpforms-container-full" id="wpforms-251"><form id="wpforms-form-251" class="wpforms-validate wpforms-form" data-formid="251" method="post" enctype="multipart/form-data" action="/"><div class="wpforms-field-container"><div id="wpforms-251-field_1-container" class="wpforms-field wpforms-field-text wpforms-one-half wpforms-first" data-field-id="1"><label class="wpforms-field-label wpforms-label-hide" for="wpforms-251-field_1">Naam <span class="wpforms-required-label">*</span></label><input type="text" id="wpforms-251-field_1" class="wpforms-field-large wpforms-field-required" name="wpforms[fields][1]" placeholder="Uw naam" required></div><div id="wpforms-251-field_2-container" class="wpforms-field wpforms-field-text wpforms-one-half" data-field-id="2"><label class="wpforms-field-label wpforms-label-hide" for="wpforms-251-field_2">Email <span class="wpforms-required-label">*</span></label><input type="text" id="wpforms-251-field_2" class="wpforms-field-large wpforms-field-required" name="wpforms[fields][2]" placeholder="Uw email" required></div><div id="wpforms-251-field_3-container" class="wpforms-field wpforms-field-textarea" data-field-id="3"><label class="wpforms-field-label wpforms-label-hide" for="wpforms-251-field_3">Bericht <span class="wpforms-required-label">*</span></label><textarea id="wpforms-251-field_3" class="wpforms-field-medium wpforms-field-required" name="wpforms[fields][3]" placeholder="Typ hier uw bericht . . ." required></textarea></div></div><div class="wpforms-field wpforms-field-hp"><label for="wpforms-field_hp" class="wpforms-field-label">Website</label><input type="text" name="wpforms[hp]" id="wpforms-field_hp" class="wpforms-field-medium"></div><div class="wpforms-submit-container"><input type="hidden" name="wpforms[id]" value="251"><input type="hidden" name="wpforms[author]" value="1"><input type="hidden" name="wpforms[post_id]" value="6"><button type="submit" name="wpforms[submit]" class="wpforms-submit " id="wpforms-submit-251" value="wpforms-submit" data-alt-text="Verzenden...">Verzenden</button></div></form></div></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </article>

                                            </div><!-- #content -->


                                        </div><!-- #primary -->



                                    </div><!-- #content-wrap -->



                                </main><!-- #main -->





                                <footer id="footer" class="site-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">


                                    <div id="footer-inner" class="clr">



                                        <div id="footer-bottom" class="clr no-footer-nav">


                                            <div id="footer-bottom-inner" class="container clr">



                                                <div id="copyright" class="clr" role="contentinfo">
                                                    Copyright YTS Webdesign			</div><!-- #copyright -->


                                            </div><!-- #footer-bottom-inner -->


                                        </div><!-- #footer-bottom -->


                                    </div><!-- #footer-inner -->


                                </footer><!-- #footer -->


                            </div><!-- #wrap -->


                        </div><!-- #outer-wrap -->






                        <script src="https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/js/eael-scripts.js,qver==1.0+fancy-text.js,qver==1.0+countdown.min.js,qver==1.0+masonry.min.js,qver==1.0.pagespeed.jc.4VnoSFm11K.js"></script><script>eval(mod_pagespeed_1wGRM7MQvd);</script>
                        <script>eval(mod_pagespeed_jlqcryfEa$);</script>
                        <script>eval(mod_pagespeed_WgkFyRrmE6);</script>
                        <script>eval(mod_pagespeed_oGkMBa9GPO);</script>
                        <script type='text/javascript'>//<![CDATA[
                            var eaelPostGrid={"ajaxurl":"https:\/\/www.ytswebdesign.nl\/wp-admin\/admin-ajax.php"};
                            //]]></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/js/load-more.js,qver=1.0.pagespeed.jm.cj64NlzNmd.js'></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/social-feeds/codebird.js,qver=1.0.pagespeed.jm.0mMSr5h299.js'></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/social-feeds/doT.min.js,qver=1.0.pagespeed.jm.eqKiKDSr2-.js'></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/social-feeds/moment.js,qver=1.0.pagespeed.jm.KnNC1sqJed.js'></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/social-feeds/jquery.socialfeed.js,qver=1.0.pagespeed.jm.aBreeD8Gtm.js'></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/js/mixitup.min.js,qver=1.0.pagespeed.jm.at62Mwksmt.js'></script>
                        <script src="https://www.ytswebdesign.nl/wp-content/plugins/essential-addons-for-elementor-lite/assets/js/jquery.magnific-popup.min.js,qver==1.0+tooltipster.bundle.min.js,qver==1.0+progress-bar.js,qver==1.0.pagespeed.jc.3TLBOCOEzz.js"></script><script>eval(mod_pagespeed_EKNtNLxgvx);</script>
                        <script>eval(mod_pagespeed_VMNiI603pg);</script>
                        <script>eval(mod_pagespeed_o9hmRBM$_H);</script>
                        <script type='text/javascript'>//<![CDATA[
                            var sticky_anything_engage={"element":"header","topspace":"0","minscreenwidth":"0","maxscreenwidth":"999999","zindex":"5","legacymode":"","dynamicmode":"","debugmode":"","pushup":"","adminbar":"1"};
                            //]]></script>
                        <script src="https://www.ytswebdesign.nl/wp-content,_plugins,_sticky-menu-or-anything-on-scroll,_assets,_js,_stickThis.js,qver==2.1.1+wp-includes,_js,_imagesloaded.min.js,qver==3.2.0+wp-content,_themes,_oceanwp,_assets,_js,_third,_magnific-popup.min.js,qver==1.5.32+wp-content,_themes,_oceanwp,_assets,_js,_third,_lightbox.min.js,qver==1.5.32.pagespeed.jc.HufKq3TJa_.js"></script><script>eval(mod_pagespeed_8PemtclOkv);</script>
                        <script>eval(mod_pagespeed_Che1xByOQs);</script>
                        <script>eval(mod_pagespeed_t6$a9JlfA$);</script>
                        <script>eval(mod_pagespeed_yx1GSVG1UU);</script>
                        <script type='text/javascript'>//<![CDATA[
                            var oceanwpLocalize={"isRTL":"","menuSearchStyle":"disabled","sidrSource":null,"sidrDisplace":"1","sidrSide":"left","sidrDropdownTarget":"icon","verticalHeaderTarget":"icon","customSelects":".woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select"};
                            //]]></script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/js/main.min.js,qver=1.5.32.pagespeed.ce.stkVe6U2CM.js'></script>
                        <script type='text/javascript'>//<![CDATA[
                            !function(a,b){"use strict";function c(){if(!e){e=!0;var a,c,d,f,g=-1!==navigator.appVersion.indexOf("MSIE 10"),h=!!navigator.userAgent.match(/Trident.*rv:11\./),i=b.querySelectorAll("iframe.wp-embedded-content");for(c=0;c<i.length;c++){if(d=i[c],!d.getAttribute("data-secret"))f=Math.random().toString(36).substr(2,10),d.src+="#?secret="+f,d.setAttribute("data-secret",f);if(g||h)a=d.cloneNode(!0),a.removeAttribute("security"),d.parentNode.replaceChild(a,d)}}}var d=!1,e=!1;if(b.querySelector)if(a.addEventListener)d=!0;if(a.wp=a.wp||{},!a.wp.receiveEmbedMessage)if(a.wp.receiveEmbedMessage=function(c){var d=c.data;if(d)if(d.secret||d.message||d.value)if(!/[^a-zA-Z0-9]/.test(d.secret)){var e,f,g,h,i,j=b.querySelectorAll('iframe[data-secret="'+d.secret+'"]'),k=b.querySelectorAll('blockquote[data-secret="'+d.secret+'"]');for(e=0;e<k.length;e++)k[e].style.display="none";for(e=0;e<j.length;e++)if(f=j[e],c.source===f.contentWindow){if(f.removeAttribute("style"),"height"===d.message){if(g=parseInt(d.value,10),g>1e3)g=1e3;else if(~~g<200)g=200;f.height=g}if("link"===d.message)if(h=b.createElement("a"),i=b.createElement("a"),h.href=f.getAttribute("src"),i.href=d.value,i.host===h.host)if(b.activeElement===f)a.top.location.href=d.value}else;}},d)a.addEventListener("message",a.wp.receiveEmbedMessage,!1),b.addEventListener("DOMContentLoaded",c,!1),a.addEventListener("load",c,!1)}(window,document);
                            //]]></script>
                        <!--[if lt IE 9]>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/themes/oceanwp/assets/js//third/html5.min.js?ver=1.5.32'></script>
                        <![endif]-->
                        <script src="https://www.ytswebdesign.nl/wp-includes,_js,_jquery,_ui,_position.min.js,qver==1.11.4+wp-content,_plugins,_elementor,_assets,_lib,_dialog,_dialog.min.js,qver==4.4.1+wp-content,_plugins,_elementor,_assets,_lib,_waypoints,_waypoints.min.js,qver==4.0.2.pagespeed.jc.UA9irlOT-1.js"></script><script>eval(mod_pagespeed_BaeQKFX3LB);</script>
                        <script>eval(mod_pagespeed_hD9GyLH5Nm);</script>
                        <script>eval(mod_pagespeed_BELEYttzmd);</script>
                        <script type='text/javascript' src='https://www.ytswebdesign.nl/wp-content/plugins/elementor/assets/lib/swiper/swiper.jquery.min.js,qver=4.4.3.pagespeed.jm.UqvvPkMNwo.js'></script>
                        <script type='text/javascript'>//<![CDATA[
                            var elementorFrontendConfig={"isEditMode":"","is_rtl":"","breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"2.3.5","urls":{"assets":"https:\/\/www.ytswebdesign.nl\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"general":{"elementor_global_image_lightbox":"yes","elementor_enable_lightbox_in_editor":"yes"}},"post":{"id":6,"title":"Home","excerpt":""}};
                            //]]></script>
                        <script src="https://www.ytswebdesign.nl/wp-content/plugins/elementor,_assets,_js,_frontend.min.js,qver==2.3.5+wpforms-lite,_assets,_js,_jquery.validate.min.js,qver==1.15.1+wpforms-lite,_assets,_js,_wpforms.js,qver==1.5.0.3.pagespeed.jc.IH3hr7HYuE.js"></script><script>eval(mod_pagespeed_sn1VFdeUT2);</script>
                        <script>eval(mod_pagespeed_IssRWUzwvL);</script>
                        <script>eval(mod_pagespeed_tQqktIkMv0);</script>
                        <script type='text/javascript'>//<![CDATA[
                            var wpforms_settings={"val_required":"This field is required.","val_url":"Please enter a valid URL.","val_email":"Please enter a valid email address.","val_number":"Please enter a valid number.","val_confirm":"Field values do not match.","val_fileextension":"File type is not allowed.","val_filesize":"File exceeds max size allowed.","val_time12h":"Please enter time in 12-hour AM\/PM format (eg 8:45 AM).","val_time24h":"Please enter time in 24-hour format (eg 22:45).","val_requiredpayment":"Payment is required.","val_creditcard":"Please enter a valid credit card number.","uuid_cookie":"","locale":"en"}
                            //]]></script>
                        </body>
                        </html>
                    </iframe>
                </div>
            </div>
            <div class="tabs-panel" id="panel2"> <!--Note: Iemand moet dit nog werkend maken-->
                <p>Yes, sir. I think those new droids are going to work out fine. In fact, I, uh, was also thinking
                    about
                    our agreement about my staying on another season. And if these new droids do work out, I want to
                    transmit my application to the Academy this year. You mean the next semester before harvest? Sure,
                    there're more than enough droids. Harvest is when I need you the most. Only one more season. This
                    year
                    we'll make enough on the harvest so I'll be able to hire some more hands. And then you can go to the
                    Academy next year. You must understand I need you here, Luke. But it's a whole 'nother year. Look,
                    it's
                    only one more season. Yeah, that's what you said last year when Biggs and Tank left. Where are you
                    going? It looks like I'm going nowhere. I have to finish cleaning those droids.
                </p>
            </div>
        </div>
        <?php include "components/scripts.html"; ?>
        <!--    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>-->
        <!--    <script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>-->
        <!--    <script>-->
        <!--        $(document).foundation();-->
        <!--    </script>-->
    </div>
</div>
</body>

</html>