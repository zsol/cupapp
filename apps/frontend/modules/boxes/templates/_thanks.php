<div class="greybox">
    <h3><img alt="heart" src="/images/icons/heart.png"/> <?php echo __('Thanks') ?></h3>
    <ul style="text-align:center;">
        <li><a href="http://github.com/zsol/cupapp"><img src="/images/misc/github.png"/></a></li>
        <li><a href="http://www.symfony-project.org/"><img src="/images/misc/symfony.gif"/></a></li>
        <?php if($sf_user->getCulture() == 'hu') : ?>
        <li>
            <a href="http://starcraft2.hu">
                <div style="-moz-border-radius: 15px;-webkit-border-radius: 15px;margin:0px auto;width:125px;height:125px;background:url(http://starcraft2.hu/common/images/banner/banner125125.jpg);"></div>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>