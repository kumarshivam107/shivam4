/* Auto Highight Current Link On Header */
//Get Current Browser Location
$CURRENT_URL = window.location.href.split('#')[0];

//Try to locate the anchor url on header
$HEADER_LINK = $('#sidebar');
$ACTIVE_LINK = $HEADER_LINK.find('a[href="' + $CURRENT_URL + '"]');
if ($ACTIVE_LINK) {
    $PARENT_LI = $ACTIVE_LINK.closest('li');
    if (!$PARENT_LI.hasClass('active')) {
        $PARENT_LI.addClass('active');
    }

    $ACTIVE_LINK.append('<span class="selected"></span>');

    $PARENT_LI_LI = $PARENT_LI.parent().parent();
    if (!$PARENT_LI_LI.hasClass('active')) {
        $PARENT_LI_LI.addClass('active');
    }
    if (!$PARENT_LI_LI.hasClass('menu-open')) {
        $PARENT_LI_LI.addClass('menu-open');
    }
}