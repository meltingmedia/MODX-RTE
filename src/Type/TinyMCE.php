<?php namespace Melting\MODX\RTE\Type;

/**
 * TinyMCE support
 */
class TinyMCE extends TinyMCERTE
{
    /**
     * @inherit
     */
    public function getOptions()
    {
        $settings = [
            'custom_buttons1',
            'custom_buttons2',
            'custom_buttons3',
            'custom_buttons4',
            'custom_buttons5',
            'convert_fonts_to_spans',
            'convert_newlines_to_brs',
            //'css_path' => $this->context->getOption('editor_css_path','',$this->properties),
            //'directionality' => $this->context->getOption('manager_direction','ltr',$this->properties),
            'element_format',
            'entity_encoding',
            'fix_nesting',
            'fix_table_elements',
            'font_size_classes',
            'font_size_style_values',
            'forced_root_block',
            'indentation',
            'invalid_elements',
            //'language' => $this->context->getOption('manager_language',$this->context->getOption('cultureKey','en',$this->properties),$this->properties),
            'nowrap',
            'object_resizing',
            'path_options',
            'custom_plugins',
            'remove_linebreaks',
            'remove_redundant_brs',
            'removeformat_selector',
            'skin',
            'skin_variant',
            'table_inline_editing',
            'editor_theme',
            'theme_advanced_blockformats',
//            'theme_advanced_buttons1' => $this->context->getOption('tiny.custom_buttons1'),
//            'theme_advanced_buttons2' => $this->context->getOption('tiny.custom_buttons2'),
//            'theme_advanced_buttons3' => $this->context->getOption('tiny.custom_buttons3'),
//            'theme_advanced_buttons4' => $this->context->getOption('tiny.custom_buttons4'),
//            'theme_advanced_buttons5' => $this->context->getOption('tiny.custom_buttons5'),
            'theme_advanced_font_sizes',
            'css_selectors',
            //'use_browser' => $this->context->getOption('use_browser',true,$this->properties),

            'template_list',
            'template_selected_content_classes',
        ];
        foreach ($settings as $k) {
            $this->modx->setOption("tiny.{$k}", $this->getSetting($k));
        }

        return [
            'editor' => 'TinyMCE',
        ];

        // @TODO allow customization (those need to be passed to the OnRichTextEditorInit event
        return [
            // No system settings provided by the RTE
            'cleanup' => true,
            'cleanup_on_startup' => false,
            'compressor' => '',
            //'content_css' => $this->context->getOption('editor_css_path'),
            'element_list' => '',
            'entities' => '',
            'execcommand_callback' => 'Tiny.onExecCommand',
            'file_browser_callback' => 'Tiny.loadBrowser',
            'force_p_newlines' => true,
            'force_br_newlines' => false,
            'formats' => array(
                'alignleft' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyleft'),
                'alignright' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyright'),
                'alignfull' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyfull'),
            ),
            'frontend' => false,
            'height' => '400px',
            'plugin_insertdate_dateFormat' => '%Y-%m-%d',
            'plugin_insertdate_timeFormat' => '%H:%M:%S',
            'preformatted' => false,
            'resizable' => true,
            'relative_urls' => true,
            'remove_script_host' => true,
            //'resource_browser_path' => $this->modx->getOption('manager_url',null,MODX_MANAGER_URL).'controllers/browser/index.php?',
            //'template_external_list_url' => $this->config['assetsUrl'].'template.list.php',
            'theme_advanced_disable' => '',
            'theme_advanced_resizing' => true,
            'theme_advanced_resize_horizontal' => true,
            'theme_advanced_statusbar_location' => 'bottom',
            'theme_advanced_toolbar_align' => 'left',
            'theme_advanced_toolbar_location' => 'top',
            'width' => '100%',
        ];
    }
}
