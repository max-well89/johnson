<?php
    $page = round($offset / $limit) + 1;
    $pages = ceil($rows / $limit);
    $url = $this->makeUrl();
    $limits = array(10, 25, 50, 100, 200, 500);
?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="pull-left">
                <label class="control-label input-sm">
                    <?php echo Context::getInstance()->translate('pager_cnt_rows') ?>: <?php echo $rows; ?>, <?php echo Context::getInstance()->translate('pager_cnt_pages') ?>: <?php echo $pages; ?>
                </label>
            </div>
            
            <div class="pull-right">
                <label class="col-xs-7 control-label text-right input-sm" for="page_limit"><?php echo Context::getInstance()->translate('pager_cnt_rows_in_page') ?>:</label>
                <div class="col-xs-5">
                    <select class="form-control input-sm" id="page_limit" onChange="location.href='<?php echo "{$url}/limit/"; ?>' + this.value;">
                        <?php foreach($limits as $lmt) : ?>
                            <option value="<?php echo $lmt; ?>"<?php echo $lmt == $limit ? ' selected="selected"' : ''; ?>><?php echo $lmt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="text-center">
                <ul class="pagination pagination-sm">
                <?php if ($pages > 1) : ?>
                <li<?php echo $page == 1 ? ' class="disabled"' : ''; ?>><a href="<?php echo "{$url}/page/1"; ?>">&laquo</a></li>
                <?php for ($i = max(1, $page - 4); $i < min(max(1, $page - 4) + 10, $pages + 1); $i++) : ?>
                <li<?php echo $i == $page ? ' class="active"' : '' ?>><a href="<?php echo "{$url}/page/$i"; ?>"><?php echo $i ?></a>
                <?php endfor; ?>
                <li<?php echo $page == 1 ? ' class="disabled"' : ''; ?>><a href="<?php echo "{$url}/page/{$pages}"; ?>">&raquo;</a></li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
