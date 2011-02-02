<div class="greybox" style="margin-top:0px;">
    <h3>Profile</h3>
    <?php if ($sf_user->isAuthenticated()) : ?>
        <img style="float:right;" alt="avatar" src="<?php echo $sf_user->getProfile()->getAvatarOrDefaultUrl() ?>"/>
        <?php echo __('Logged in as') ?> <b><?php echo $sf_user->getUsername() ?></b><br/>
        <a href="<?php echo url_for('@editmyprofile') ?>"><?php echo __('Edit profile') ?></a><br/>
        <a href="<?php echo url_for('@sf_guard_signout') ?>"><?php echo __('Logout') ?></a>
        <?php if ($sf_user->getGuardUser()->getIsSuperAdmin()) : ?>
            <br /><a href="/backend.php"><?php echo __('Go to admin') ?></a>
        <?php endif; ?>
    <?php else: ?>
        <?php echo __('You are not logged in.') ?> <br/>
        <a href="<?php echo url_for('@sf_guard_signin') ?>"><?php echo __('Login') ?></a> or <a href="<?php echo url_for('@register') ?>"><?php echo __('Register') ?></a>
    <?php endif; ?>
    <div style="clear:both;"></div>
</div>