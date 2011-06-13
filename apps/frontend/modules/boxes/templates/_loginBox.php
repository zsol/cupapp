<?php use_helper('sfFacebookConnect'); ?>
<div class="greybox" style="margin-top:0px;">
    <h3><img alt="user" src="/images/icons/user.png"/> <?php echo __('Profile') ?></h3>
    <?php if ($sf_user->isAuthenticated()) : ?>
        <img style="float:right;" alt="avatar" src="<?php echo $sf_user->getProfile()->getAvatarOrDefaultUrl(AvatarHelper::SIZE_NORMAL) ?>"/>
        <?php echo __('Logged in as') ?> <b><?php echo $sf_user->getUsername() ?></b><br/>
        <?php 
          if (!sfFacebook::getGuardAdapter()->getUserFacebookUid($sf_user)) {
            echo facebook_connect_button();
            slot('fb_connect');
            include_facebook_connect_script();
            end_slot();
            echo "<br />";
          }
        ?>
        <a href="<?php echo url_for('@editmyprofile') ?>"><?php echo __('Edit profile') ?></a><br/>
        <a href="<?php echo url_for('@sf_guard_signout') ?>"><?php echo __('Log out') ?></a>
        <?php if ($sf_user->getGuardUser()->getIsSuperAdmin() || $sf_user->hasCredential(array('AdminAdmin', 'CommentAdmin', 'ReplayAdmin'), false /*OR*/)) : ?>
            <br /><a href="/backend.php"><?php echo __('Go to admin') ?></a>
        <?php endif; ?>
    <?php else: ?>
        <?php echo __('You are not logged in.') ?> <br/>
   <a href="<?php echo url_for('@sf_guard_signin') ?>"><?php echo __('Log in') ?></a> <?php echo __('or') ?> <a href="<?php echo url_for('@register') ?>"><?php echo __('Register') ?></a>
    <?php endif; ?>
     <?php include_component('language', 'language') ?>
    <div style="clear:both;"></div>
</div>