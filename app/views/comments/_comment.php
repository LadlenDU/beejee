<div class="row" data-id="<?php echo isset($item['id']) ? $item['id'] : 0 ?>">
    <?php if ($item->modified): ?>
    <div class="admin_modified">Изменен администратором</div>
    <?php endif; ?>
    <div class="image">
        <img width="<?php echo $item->image['width'] ?>" height="<?php echo $item->image['height'] ?>" src="<?php echo $item->image['src'] ?>"/>
    </div>
    <div class="name">
        <div class="capt">Имя:</div>
        <div class="value"><?php $item->name ?></div>
    </div>
    <div class="email">
        <div class="capt">Email:</div>
        <div class="value"><?php $item->email ?></div>
    </div>

    <input type="text" value="<?php echo htmlspecialchars($user->name, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']) ?>" class="name" maxlength="30" style="display:none"/><!--
              --><div class="name" title="<?php echo CLICK_TO_MOD ?>"><?php echo $user->name ? htmlspecialchars($user->name, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']) : '&nbsp;' ?></div><!--
              --><input type="text" value="<?php echo $user->age ?>" class="age" maxlength="3" style="display:none"/><!--
              --><div class="age" title="<?php echo CLICK_TO_MOD ?>"><?php echo htmlspecialchars($user->age, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']) ?></div><!--
              --><div class="city_id" title="<?php echo CLICK_TO_MOD ?>"><?php echo ($user->city_id == 0) ? 'Город не выбран' : $user->city_name ?></div><!--
              --><select style="display:none" class="city_id">
        <option value="0" <?php echo ($user->city_id == 0) ? 'selected="selected"' : ''; ?>>Город не выбран</option>
        <?php foreach($cities as $city): ?>
            <option value="<?php echo $city->id ?>" <?php
            if($user->city_id == $city->id)
            {
                echo 'selected="selected"';
            }
            ?>><?php echo htmlspecialchars($city->name, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']) ?></option>
        <?php endforeach ?>
    </select><!--
                <input type="text" value="<?php echo $user->city_id ?>" class="city" disabled="disabled"/>
              --><input type="button" value="Удалить" class="delete"/>
</div>