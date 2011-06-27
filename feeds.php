<?php include('config.php');
include('head.php'); ?>

<br />

<div class="content">
<span class="page_title">feeds</span><br />

<table class="form_table" width="500">
    <tr><td><strong>rss</strong>
    <table class="rss_table">
        <tr>
            <td><em>index</em></td>
            <td><a href="<?php echo SITE_URL; ?>/rss" target="_blank"><?php echo SITE_URL; ?>/rss</a></td>
            <td><a href="http://fusion.google.com/add?source=atgs&feedurl=<?php echo urlencode(SITE_URL . "/rss"); ?>"><img src="http://buttons.googlesyndication.com/fusion/add.gif" border="0" alt="Add to Google"></a></td>
        </tr>
        
        <tr>
            <td><em>newest</em></td>
            <td><a href="<?php echo SITE_URL; ?>/rss/new" target="_blank"><?php echo SITE_URL; ?>/rss/new</a></td>
            <td><a href="http://fusion.google.com/add?source=atgs&feedurl=<?php echo urlencode(SITE_URL . "/rss/new"); ?>"><img src="http://buttons.googlesyndication.com/fusion/add.gif" border="0" alt="Add to Google"></a></td>
        </tr>

        <tr>
            <td><em>all-time</em></td>
            <td><a href="<?php echo SITE_URL; ?>/rss/all-time" target="_blank"><?php echo SITE_URL; ?>/rss/all-time</a></td>
            <td><a href="http://fusion.google.com/add?source=atgs&feedurl=<?php echo urlencode(SITE_URL . "/rss/all-time"); ?>"><img src="http://buttons.googlesyndication.com/fusion/add.gif" border="0" alt="Add to Google"></a></td>
        </tr>
    </table>
    </td></tr>
</table>

</div>

<br /><br />

<?php include('foot.php'); ?>