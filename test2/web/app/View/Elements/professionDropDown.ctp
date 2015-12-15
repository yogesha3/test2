<?php $profession = isset($professionId) ? $professionId : 'NULL'; ?>

<?php if (!empty($professions)) { ?>
<select name="data[BusinessOwner][profession_id]" id="profession" class="form-control filter">
    <option value="">Select Profession</option>
    <?php foreach($professions as $key => $val): ?>
        <option value="<?php echo $key;?>" <?php if($key == $profession) { echo 'selected';}?>><?php echo $val;?></option>
    <?php endforeach; ?>
</select>
<?php } else { ?>
<select name="data[BusinessOwner][profession_id]" id="profession" class="form-control filter">
    <option value="">No Profession</option>
</select>
<?php } ?>
