<div id="erm_list_menu_items">
    <i class="page-spin fa fa-refresh fa-spin" data-bind="visible: spin"></i>
    <div class="erm_menu_items" data-bind="sortable: {data:menu_items, options:{axis:'y', handle:'.icon-move'}, afterMove: aftermove}">
        <div class="erm_menu_item" data-bind="css: {'item-hidden': !visible(), 'product': is_product(), 'section': is_section()}">
            <table style="width: 100%;">
                <tr>
                    <td style="width:40px;"><i class="fa fa-bars icon-move"></i></td>
                    <td style="width:80px;"><a class="image-popup" href="#" data-bind="visible: hasImage, click: show_popup_image"><img class="src_thumb" data-bind="attr: {src: src_thumb}"></a></td>
                    <td style="position: relative;">
                        <span class="title" data-bind="text: title"></span>
                        <input style="width: 100%;" class="input-title" data-bind="value: title, css:{'visible': editing()}">
                    </td>
                    <td style="width:120px;">
                        <div class="edit-icons">
                            <i class="fa" data-bind="css: visible_css, click: toggle_visible"></i>
                            <i class="fa" data-bind="css: editing_css, click: toggle_editing"></i>
                            <i class="fa fa-remove" data-bind="click: $parent.removeitem"></i>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="edit-item" data-bind="slideVisible: editing, css: {'editing': editing() }">
                <table class="table-content">

                    <tr>
                        <td class="edit-image">
                            <div class="uploader-upload" data-bind="mediaUpload: {image_id: image_id, src_thumb: src_thumb, src_big: src_big, image_title: image_title, image_desc: image_desc}">
                                <div data-bind="visible: !hasImage()">
                                    <button class="button button-default"><?php _e('Select Image', 'erm'); ?></button>
                                </div>
                            </div>
                            <div class="uploader-image" data-bind="visible: hasImage">
                                <img data-bind="attr: {src: src_thumb}">
                                <i class="icon-delete fa fa-times" data-bind="click: removeimage"></i>
                            </div>
                        </td>
                        <td class="edit-content">
                            <div style="text-align: right; margin-bottom: 5px;"><a data-bind="attr:{href: link}">Edit POST</a></div>
                            <textarea class="menu_item_desc" data-bind="attr:{'id': editor_id}, tinyMCE: content" placeholder=""></textarea>
                            <div class="edit-prices" data-bind="sortable: {data:prices, options:{axis:'y', handle:'.icon-move-price'}}">
                                <div class="item-price">
                                    <i class="icon-move-price fa fa-bars"></i>
                                    <input placeholder="<?php _e('Title', 'erm'); ?>" data-bind="value: name">
                                    <input placeholder="<?php _e('Price', 'erm'); ?>" data-bind="value: value">
                                    <i class="icon-delete-price fa fa-times" data-bind="click: $parent.removeprice"></i>
                                </div>
                            </div>
                            <button class="add-price button button-default button-large" data-bind="click: newprice"><?php _e( 'Add price' , 'erm' ); ?></button>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <div data-bind="if: post_created()">
        <button class="button button-default button-large" data-bind="click: newitem_product"><?php _e('New Menu Item','erm')?></button>
        <button class="button button-default button-large" data-bind="click: newitem_section"><?php _e('New Title Section','erm')?></button>
        <!-- a class="button button-default button-large" data-bind="click: add_menuitem"><?php //_e('Add Menu Item','erm')?></a -->
    </div>
    <div data-bind="if: !post_created()">
        <h3><?php _e('Please, SAVE THIS POST to begin adding Menu Items','erm'); ?></h3>
    </div>

</div>

<div id="dialog-confirm" title="Empty the recycle bin?" style="display: none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
</div>

<div id="dialog-select-menuitem">

</div>