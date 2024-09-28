<?php
$title='Profile';

ob_start();
?>
    <div class="row">
        <div class="col my-4 text-end h4">
            Profile Page
        </div>
    </div>
    <div class="row">
        <div class="col"></div>
    </div>
<?php
$content = ob_get_contents();;
$content = ob_get_clean();
include('layouts.php');


