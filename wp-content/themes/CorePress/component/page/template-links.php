<?php
$links_cat_arry = get_terms('link_category');
?>
<?php
foreach ($links_cat_arry as $links_cat_item) {
    $link_list_arr = get_bookmarks(array(
        'category' => $links_cat_item->term_id,
        'categorize' => 1
    ));
    //link_description

    echo "<h2>{$links_cat_item->name}</h2>";
    echo '<div class="links-plane">';
    foreach ($link_list_arr as $link_item) {
        $url = $link_item->link_url;
        $link_name = $link_item->link_name;
        $description = $link_item->link_description;

        if ($link_item->link_image == null) {
            $link_imgurl = file_get_img_url('chrome.png');
        } else {
            $link_imgurl = $link_item->link_image;
        }
        //print_r($link_list_arr);
        ?>
        <div class="links-item">
            <a href="<?php echo $url ?>" target="_blank">
                <div>
                    <img src="<?php echo $link_imgurl ?>" class="link-icon" alt="">
                </div>
                <div class="link-info-plane">
                    <div class="link-title"><?php echo $link_name ?></div>
                    <div class="link-description"><?php echo $description ?></div>
                </div>

            </a>
        </div>
        <?php
    }
    echo '</div>';
}
?>
