jQuery(document).ready(function(){
    jQuery("#wp_menu_customizer_menu_list").sortable({
        cursor:'move'
        });
    jQuery("#wp_menu_customizer_menu_list").disableSelection();

    jQuery("#wpcus_user_permissions .user-roles-title").click(function() {
        var key = jQuery(this).data('key');

        jQuery(".user-roles-body[data-key='"+key+"']").toggle();
    });
});