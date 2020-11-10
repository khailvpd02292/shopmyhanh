<div id="shop_search_popup_wrapper" class="dialog animated dialog--close">
    <div class="dialog__overlay"></div>
    <div class="dialog__content">
        <div class="dialog-inner">
            <form  method="get" action="<?php echo esc_url(site_url()); ?>" class="search-popup-inner">
                <input type="text" name="s" placeholder="<?php esc_html_e( 'Search...', 'milo-bibo' ); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            <div><button class="action search-close-popup" data-dialog-close="close" type="button"><i class="fa fa-close"></i></button></div>
        </div>
    </div>
</div>