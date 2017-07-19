<?php
add_action( 'admin_menu', 'dnxasi_add_admin_menu' );
add_action( 'admin_init', 'dnxasi_settings_init' );

function dnxasi_add_admin_menu() { 
    $dnxasi_settings_page = add_options_page( 'danixland author signature', 'Author Signature', 'manage_options', 'dnxasi', 'dnxasi_options_page' );
    add_action( 'load-' . $dnxasi_settings_page, 'dnxasi_load_admin_scripts' );
}

function dnxasi_load_admin_scripts() {
    add_action( 'admin_enqueue_scripts', 'dnxasi_enqueue_styles' );
}

// enqueue our scripts
function dnxasi_enqueue_styles() {
    wp_enqueue_style( 'dnxasi_admin_style', plugins_url('/css/dnxasi_admin_style.css', dirname(__FILE__)), array(), '0.1' );
}


function dnxasi_settings_init() { 

    register_setting( 'dnxasi_options', 'dnxasi_settings' );

    add_settings_section(
        'dnxasi_pluginPage_section', 
        __( 'Global Signature positioning and sizing', 'dnxasi' ), 
        'dnxasi_settings_section_callback', 
        'dnxasi_options'
    );

    add_settings_field( 
        'dnxasi_signature_position', 
        __( 'Signature alignment?', 'dnxasi' ), 
        'dnxasi_signature_position_render', 
        'dnxasi_options', 
        'dnxasi_pluginPage_section' 
    );

    add_settings_field( 
        'dnxasi_signature_size', 
        __( 'signature width?', 'dnxasi' ), 
        'dnxasi_signature_size_render', 
        'dnxasi_options', 
        'dnxasi_pluginPage_section' 
    );

}


function dnxasi_signature_position_render(  ) { 

    $options = get_option( 'dnxasi_settings' );
    ?>
    <label class="description dnxasi_label">
        <input type='radio' name='dnxasi_settings[dnxasi_signature_position]' <?php checked( $options['dnxasi_signature_position'], 'left' ); ?> value='left'>
        <span>
            <img src="<?php echo plugins_url('/img/signature-left.png', dirname(__FILE__)); ?>" alt="">
            <?php _e('left', 'dnxasi'); ?>
        </span>
    </label>
    <label class="description dnxasi_label">
        <input type='radio' name='dnxasi_settings[dnxasi_signature_position]' <?php checked( $options['dnxasi_signature_position'], 'center' ); ?> value='center'>
        <span>
            <img src="<?php echo plugins_url('/img/signature-center.png', dirname(__FILE__)); ?>" alt="">
            <?php _e('center', 'dnxasi'); ?>
        </span>
    </label>
    <label class="description dnxasi_label">
        <input type='radio' name='dnxasi_settings[dnxasi_signature_position]' <?php checked( $options['dnxasi_signature_position'], 'right' ); ?> value='right'>
        <span>
            <img src="<?php echo plugins_url('/img/signature-right.png', dirname(__FILE__)); ?>" alt="">
            <?php _e('right <small>(default)</small>', 'dnxasi'); ?>
        </span>
    </label>
    <?php

}

function dnxasi_signature_size_render (  ) {
    $options = get_option( 'dnxasi_settings' );
?>
    <label class="description">
        <input type="number" name="dnxasi_settings[dnxasi_signature_size]" min="1" max="1600" placeholder="300" value="<?php echo $options['dnxasi_signature_size']; ?>"> <?php _e('pixels', 'dnxasi'); ?><br>
    </label>
    <p class="description"><?php _e('set the width in pixels for your signature image and it will be scaled accordingly. If you leave this field empty the default width of <strong>300px</strong> will be used.', 'dnxasi'); ?></p>
<?php
}

function dnxasi_settings_section_callback(  ) { 

    echo __( 'Choose how you want to position and scale the signature at the bottom of the article.', 'dnxasi' );

}


function dnxasi_options_page(  ) { 

    ?>
    <h2><?php _e('danixland Author Signature Settings', 'dnxasi'); ?></h2>

    <p class="description"><?php printf(
        __('To upload your signature image go to your <a href="%1$s">profile page</a>, here on this page you can set up the general look of the signature trough the website.', 'dnxasi'),
        admin_url('profile.php')
    ); ?></p>

    <form action='options.php' method='post'>
        
        <?php
        settings_fields( 'dnxasi_options' );
        do_settings_sections( 'dnxasi_options' );
        submit_button();
        ?>
        
    </form>
    <?php

}
