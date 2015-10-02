<?php

class CFMViewSubmissions_cfm {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;

  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display($form_id) {
    ?>
    <div style="clear: both; float: left; width: 99%;">
      <div style="float: left; font-size: 14px; font-weight: bold;">
        <br />
        <?php echo __("This section allows you to view form submissions.","contact_form_maker"); ?>
        <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-contact-form-builder-guide-7.html"><?php echo __("Read More in User Manual","contact_form_maker"); ?></a><br /><br />
        <span style="color: #FF0000;"><?php echo __("This feature is disabled for the non-commercial version.","contact_form_maker"); ?></span>
      </div>
      <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/files/fromContactFormBuilder.php">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_CFM_URL . '/images/wd_logo.png'; ?>" />
        </a>
      </div>
    </div>
    <div style="clear: both; float: left; width: 99%;">
      <img style="max-width: 100%;" src="<?php echo WD_CFM_URL . '/images/screenshots/sub.png'; ?>" />
    </div>
    <?php
  }
}

?>
