<?php ob_start(); ?>
<div class="well pgui-login">
    <div class="page-header">
        <h3><?php echo $this->_tpl_vars['Captions']->GetMessageString('PasswordRecovery'); ?>
</h3>
    </div>
    <div class="js-form-container">
        <div class="js-form-collection">
            <form id="recoveringPasswordForm" method="post">
                <div class="alert alert-info">
                    <?php echo $this->_tpl_vars['Captions']->GetMessageString('RecoveringPasswordInfo'); ?>

                </div>

                <div class="form-group">
                    <input required="true" placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('UsernameOrEmail'); ?>
" type="text" name="account-name" class="form-control" id="account-name"
                           data-validation="required" data-required-error-message="Username is required">
                </div>

                <div class="form-error-container">
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary js-save" data-action="open" data-url="login.php"><?php echo $this->_tpl_vars['Captions']->GetMessageString('SendPasswordResetLink'); ?>
</button>
                    &nbsp;<a href="login.php" class="btn btn-default"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Cancel'); ?>
</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('ContentBlock', ob_get_contents());ob_end_clean(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['layoutTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>