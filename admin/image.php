<?php
include("lib/db.php");
include("lib/function.php");
include("lib/head.php");

$func = new Functions;
$AdminWeb = "http://localhost:80/FYP-Project";
$ProID = 1;
$ImageExp = explode("#", "1#1200#900");

$sql = "SELECT ImageID, ImageName, ImageExt FROM product_image WHERE ProID=?";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("i", $ProID);
$stmt->execute();
$query = $stmt->get_result();
$image_num = mysqli_num_rows($query);
?>

<style>
/* CSS styles as in the original code */
</style>

<tr class="simple-expand" data-rel="3">
    <td colspan="4" class="specaltcorner">IMAGES <span class="ui-icon-triangle-1-s ui-icon float-right"></span></td>
</tr>
<tbody id="box-3" class="hide">
    <tr>
        <td colspan="4" class="border-left-dedede">
            <strong>Preferred Size:</strong> <?= htmlspecialchars($ImageExp[1] . "px x " . $ImageExp[2] . "px") ?><br />
            <strong>Supported Format:</strong> jpg/jpeg, gif, or png<br />
            <strong>Max.:</strong> 20 images<br />
            <strong>Change Sequence:</strong> drag and drop the images<br /><br />

            <div id="ProImageNew" class="ImageRow">
                <?php
                $no = 1;
                $max = 3;

                if ($image_num > 0) {
                    while ($image_row = mysqli_fetch_assoc($query)) {
                        $ImageID = htmlspecialchars($image_row["ImageID"]);
                        $ImageName = htmlspecialchars($image_row["ImageName"]);
                        $ImageExt = htmlspecialchars($image_row["ImageExt"]);

                        if (!empty($ImageName)) {
                            echo "<div class=\"ImageBox\">
                                    <a data-fancybox=\"images\" href=\"$AdminWeb/upload/product/$ProID/$ImageName.$ImageExt\" class=\"tooltip\" title=\"Enlarge\">
                                        <img src=\"$AdminWeb/upload/product/$ProID/$ImageName.$ImageExt\">
                                    </a>
                                    <div class=\"ImageDelete\">
                                        <input type=\"checkbox\" name=\"ImageDelete$ImageID\" id=\"ImageDelete$ImageID\" value=\"1\">
                                        <label for=\"ImageDelete$ImageID\">Delete</label>
                                        <input type=\"hidden\" name=\"ImageList[]\" value=\"$ImageID\">
                                    </div>
                                </div>";
                            $no++;
                        }
                    }
                    $max = $no;
                }

                if ($max <= 20) {        
                    for ($no = $no; $no <= $max; $no++) {
                        echo "<div class=\"ImageBox\">
                                " . $func->customUploadFile("ProImage$no", "Click to upload image $no...") . "
                                <input type=\"hidden\" name=\"ImageList[]\" value=\"new_$no\">
                              </div>";
                    }
                }
                ?>
            </div>
            <input type='hidden' name="ProImageNo" id="ProImageNo" value="<?= ($no - 1) ?>">
            <div id="ProImageMore" class="add-row<?= ($no > 20 ? " hide" : "") ?>">
                <a class="add-more-box" data-type="ProImage"><span class="ui-icon ui-icon-plus"></span> Add More Image</a>
            </div>
        </td>
    </tr>
</tbody>

<script>
$(document).ready(function() {
    $(document).on('click', '.add-more-box', function() {
        var html = "";
        var type = $(this).attr("data-type");
        var newtotal = parseInt($('#' + type + 'No').val()) + 1;

        $("#overlay-bg").fadeIn();
        if (type == "ProImage") {
            html = '<div class="ImageBox">' +
                        '<div class="file_box" data-field="' + type + newtotal + '">' +
                            '<div class="file_text" id="' + type + newtotal + '_text">Click to upload image ' + newtotal + '...</div>' +
                            '<div class="file_btn"><input type="button" value="Browse..." alt="Browse..." title="Browse..."></div>' +
                        '</div>' +
                        '<div class="file_hidden"><input type="file" name="' + type + newtotal + '" id="' + type + newtotal + '" class="btn_upload"></div>' +
                        '<input type="hidden" name="ImageList[]" value="new_' + newtotal + '">' +
                    '</div>';
            
            if (newtotal >= 20) {
                $("#" + type + "More").hide();
            }
        }
        $('#'+type+'New').append(html);
        $('#'+type+'No').val(newtotal);

        $("#overlay-bg").fadeOut();
    });
});
</script>
