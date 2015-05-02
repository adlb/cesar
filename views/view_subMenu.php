<?php if (!isset($obj['sons']) || count($obj['sons']) == 0) { ?>
    <li id="row_<?php echo $obj['id'] ?>">
        <a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $obj['id']))?>" <?php echo $obj['status'] == 'hide' ? 'class="cx_adminonly"' : '' ?>>
            <?php t($obj['titleKey']) ?>
        </a>
    </li>
<?php } else { ?>
    <li class="dropdown" id="row_<?php echo $obj['id'] ?>">
        <a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $obj['id']))?>" class="dropdown-toggle <?php echo $obj['status'] == 'hide' ? 'cx_adminonly' : '' ?>" data-toggle="dropdown" role="button">
            <?php t($obj['titleKey']) ?>&nbsp;
            <span class="caret"></span>
        </a>

        <ul class="dropdown-menu draggableMenuItem" role="menu" data-moveHandler="<?php echo url(array('controller' => 'builder', 'action' => 'moveEntry')) ?>">
            <?php foreach($obj['sons'] as $son) { ?>
                <li id="row_<?php echo $son['id'] ?>">
                    <a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $son['id']))?>" <?php echo $son['status'] == 'hide' ? 'class="cx_adminonly"' : '' ?>>
                        <?php t($son['titleKey'])?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>