<?php $replays = $pager->getResults() ?>

<h3><?php echo __('Replay result list') ?></h3>

<?php if (count($replays)) : ?>
    <div class="stdtable_border">
    <table class="stdtable replay_table" style="width:100%;">
        <tr>
            <th><?php echo __('Type') ?></th>
            <th><?php echo __('Players') ?></th>
            <th><?php echo __('Average APM') ?></th>
            <th><?php echo __('Map'); ?></th>
            <th><?php echo __('Category') ?></th>
            <th><?php echo __('Upload time') ?></th>
            <th></th>
        </tr>
        <?php foreach($replays as $replay) : ?>
        <tr>
            <td>
                <?php echo $replay->getReplayGameType()->getName() ?>
            </td>
            <td>
                <?php echo $replay->getPlayers() ?>
            </td>
            <td><?php echo $replay->getAvgApm() ?></td>
            <td><?php echo $replay->getMapName() ?></td>
            <td><?php echo $replay->getReplayCategory() ?></td>
            <td><?php echo $replay->getCreatedAt(sfConfig::get('app_date_format')) ?></td>
            <td><a href="<?php echo url_for('@viewreplay?id='.$replay->getId().'&name='.$replay) ?>"><?php echo __('Details') ?></a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <?php if ($pager->haveToPaginate()): ?>
        <div style="text-align:center;">
        <?php $links = $pager->getLinks() ?>
        <?php $currentPage = $pager->getPage() ?>
        <?php $nextPage = $pager->getNextPage() ?>
        <?php $previousPage = $pager->getPreviousPage() ?>

        <?php if (!$pager->isFirstPage()) : ?>
            <a href="?page=<?php echo $pager->getFirstPage() ?>"><?php echo __('First page') ?></a>
            | <a href="?page=<?php echo $previousPage ?>"><?php echo __('Previous Page') ?></a> |
        <?php endif; ?>

        <?php foreach($links as $page) :?>
            <?php if ($page != $currentPage) : ?>
                <a href="?page=<?php echo $page ?>"><?php echo $page ?></a>
            <?php else : ?>
                <?php echo $page ?>
            <?php endif; ?>
        <?php endforeach; ?>
                
        <?php if (!$pager->isLastPage()) : ?>
            | <a href="?page=<?php echo $nextPage ?>"><?php echo __('Next Page') ?></a>
            | <a href="?page=<?php echo $pager->getLastPage() ?>"><?php echo __('Last page') ?></a>
        <?php endif; ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <?php echo __('There is no replay in the system that is in the searching criteria.') ?>
<?php endif; ?>