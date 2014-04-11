<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="description" content="Codiad is an open source, web-based, cloud IDE and code editor with minimal footprint and requirements"
    <link href='https://fonts.googleapis.com/css?family=Chivo:900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="http://codiad.com/stylesheets/stylesheet.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="http://codiad.com/stylesheets/pygment_trac.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="http://codiad.com/stylesheets/print.css" media="print" />
    <!--<link rel="stylesheet" type="text/css" href="http://codiad.com/stylesheets/icons.css" media="screen" />-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title>Plugins - Codiad Web Based IDE by Fluidbyte</title>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-34692757-1']);
      _gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    
    </script>
  </head>
<body style="height:100%">  

    <a href="http://demo.codiad.com" id="demo_drop-in" target="_blank" onClick="_gaq.push(['_trackEvent', 'Demo', 'Demo', 'Load Demo']);">
        <span>Click Banner to Try a Live Demo</span>
    </a>
    <div id="banner_shadow"></div>
  
    <div id="container">
      <div class="inner">

        <header>
          <a id="logo" href="http://www.codiad.com"><img src="http://www.codiad.com/logo.jpg"></a>
          <h1>Codiad<span class="version">v.1.0</span></h1>
          <h2>Web Based, Cloud IDE</h2>
        </header>

        <section id="downloads" class="clearfix">
          <a href="https://github.com/Codiad/Codiad/releases" id="download-zip" class="button" onClick="_gaq.push(['_trackEvent', 'Download', 'Releases', 'Download Releases']);"><span>Download</span></a>
          <a href="http://demo.codiad.com" target="_blank" id="open-demo" class="button" onClick="_gaq.push(['_trackEvent', 'Demo', 'Demo', 'Load Demo']);"><span>Live Demo</span></a>
          <a href="http://market.codiad.com" id="plugins" class="button"><span>Plugins</span></a>
          <a href="https://github.com/Codiad/Codiad" id="view-on-github" class="button" onClick="_gaq.push(['_trackEvent', 'GitHub', 'ViewSource', 'View Source on GitHub']);"><span>GitHub</span></a>
        </section>

        <hr>

        <section id="main_content">
        
            <h1>Codiad Plugins</h1>
            
            <br>
            
            <p>Below are plugins tested and approved by the Codiad team. To install simply download and place the plugin's folder in (or git-clone into) the /plugins directory in Codiad. Then in the right-hand bar 
                use the Plugins tool to enable the plugin. You can also easily <a href="https://github.com/Codiad/Codiad-Plugin-Template" target="_blank">write your own</a>.
            </p>
            <table class="datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
            <?php
                
                
                $plugins = json_decode(file_get_contents('http://codiad.com/plugins.json'),true);
                $data = array();
                foreach($plugins as $plugin) {
                    if(!isset($plugin['category'])) {
                        $plugin['category'] = 'Common';
                    } 
                    if (!isset($data[$plugin['category']])) {
                        $data[$plugin['category']] = array();
                    } 
                    array_push($data[$plugin['category']], $plugin);
                }
                ksort($data);
                //$themes = json_decode(file_get_contents('http://codiad.com/themes.json'),true);
            
                foreach ($data as $category=>$plugins) {
                    echo '<tr>';
                    echo '<th colspan="4"><strong>'.$category.'</strong></th>';
                    echo '</tr>';
                    usort($plugins, 'sort_name');
                    foreach($plugins as $plugin) {
                        echo '<tr>';
                        echo '<td>'.$plugin['name'].'</td>';
                        echo '<td>'.$plugin['description'].'</td>';
                        echo '<td><a target="_blank" href="http://github.com/'.$plugin['author'].'">'.$plugin['author'].'</a></td>';
                        echo '<td class="center"><a target="_blank" href="'.$plugin['url'].'/archive/master.zip" class="icon-download-alt icon-large" alt="Download Zip"></a>';
                        echo '<span class="splitter">|</span><a target="_blank" href="'.$plugin['url'].'" class="icon-github icon-large" alt="GitHub Repo"></a></td>';
                        echo '</tr>';
                    }
                
                }
                
                function sort_name($a, $b) { return strnatcmp($a['name'], $b['name']); }
                
            ?>
            </tbody>
            </table>
<br>
            
            <p><em>Please note: While we test plugins before adding them to this list we cannot garuntee quality or provide any warranty. If you have any issues please address them directly with the plugin author.</em></p>

            <hr>
            
            <h2>Submit a Plugin / Theme</h2>
            
            <p>If you would like to submit a plugin or theme, please email the GitHub repository and description to <a href="mailto:dev@codiad.com">dev[at]codiad.com</a> or open an issue on <a href="https://github.com/Codiad/Codiad/issues/">Github</a> (New Issue).</p>
            
            <p>Not sure how to get started? Check out the <a href="https://github.com/Codiad/Codiad-Plugin-Template" target="_blank">Codiad Plugin Template</a>.</p>
            
            <hr>
            
            <h2>Support the Codiad Project</h2>

<p>A <i>LOT</i> of time and energy has been put into the system, and there are a lot of new features in the works. If you 
like the system and would like to say thanks, here&apos;s your chance!</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="background: none; text-align: center; padding: 5px;">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
        </section>

        <footer>
          Codiad is maintained by <a href="https://github.com/Fluidbyte">Fluidbyte</a><br>
        </footer>

        <script src="http://www.codiad.com/javascripts/jquery.js"></script>
        <script src="http://www.codiad.com/javascripts/main.js"></script>

      </div>
    </div>
  </body>
</html>
