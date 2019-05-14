<?php

/* @var $data array */

?>

<div class="col-md-3 col-xs-3 filter-type">
    <div class="form-group">
        <?php for ($i = 0; $i < count($data)/4; ++$i): ?>
            <input
                class = ""
                name = "address_point"
                type = "checkbox"
                value = "<?= $data[$i]['id'] ?>"
                <?= $data[$i]['checked'] ? "checked=checked" : "" ?>
                style="margin-top: -25px;"
            />
            <label><?= $data[$i]['name'] ?></label>
            <br/>
        <?php endfor; ?>
    </div>
</div>
<div class="col-md-3 col-xs-3 filter-type">
    <div class="form-group">
        <?php for ($i; $i < count($data)/2; ++$i): ?>
            <input 
                class=""
                name="address_point"
                type = "checkbox"
                value = "<?= $data[$i]['id'] ?>"
                <?= $data[$i]['checked'] ? "checked=checked" : "" ?>
                style="margin-top: -25px;"
            />
            <label><?= $data[$i]['name'] ?></label>
            <br/>
        <?php endfor; ?>
    </div>
</div>
<div class="col-md-3 col-xs-3 filter-type">
    <div class="form-group">
        <?php for (; $i < 3*count($data)/4; ++$i): ?>
            <input 
                class = ""
                name = "address_point"
                type = "checkbox"
                value = "<?= $data[$i]['id'] ?>"
                <?= $data[$i]['checked'] ? "checked=checked" : "" ?>
                style="margin-top: -25px;"
            />
            <label> <?= $data[$i]['name'] ?></label>
            <br/>
        <?php endfor; ?>
    </div>
</div>
<div class="col-md-3 col-xs-3 filter-type">
    <div class="form-group">
        <?php for (; $i < count($data); ++$i): ?>
            <input 
                class=""
                name="address_point"
                type = "checkbox"
                value = "<?= $data[$i]['id'] ?>"
                <?= $data[$i]['checked'] ? "checked=checked" : "" ?>
                style="margin-top: -25px;"
            />
            <label><?= $data[$i]['name'] ?></label>
            <br/>
        <?php endfor; ?>
    </div>
</div>