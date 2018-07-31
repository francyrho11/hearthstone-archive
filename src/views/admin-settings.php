<div class="wrap">
    <h1>Hearthstone Archive</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('hearthstone-archive');
        do_settings_sections('hearthstone-archive');
        submit_button();
        ?>
    </form>
</div>