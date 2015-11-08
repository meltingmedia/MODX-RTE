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
        return [
            // RTE options without system setting
            //'accessibility_warnings' => false,
            'width' => '100%',
            'height' => '100px',

            // RTE settings with system settings to allow overrides
            'buttons1' => $this->getSetting('custom_buttons1'),
            'buttons2' => $this->getSetting('custom_buttons2'),
            'buttons3' => $this->getSetting('custom_buttons3'),
            'buttons4' => $this->getSetting('custom_buttons4'),
            'buttons5' => $this->getSetting('custom_buttons5'),
            'convert_fonts_to_spans' => $this->getSetting('convert_fonts_to_spans'),
            'convert_newlines_to_brs' => $this->getSetting('convert_newlines_to_brs'),
//            'css_path' => $this->context->getOption('editor_css_path','',$this->properties),
//            'directionality' => $this->context->getOption('manager_direction','ltr',$this->properties),
            'tiny.element_format' => $this->getSetting('element_format'),
            'tiny.fix_nesting' => $this->getSetting('fix_nesting'),
            'tiny.fix_table_elements' => $this->getSetting('fix_table_elements'),
            'tiny.font_size_classes' => $this->getSetting('font_size_classes'),
            'tiny.font_size_style_values' => $this->getSetting('font_size_style_values'),
            'tiny.forced_root_block' => $this->getSetting('forced_root_block'),
            'tiny.indentation' => $this->getSetting('indentation'),
            'tiny.invalid_elements' => $this->getSetting('invalid_elements'),
//            'language' => $this->context->getOption('manager_language',$this->context->getOption('cultureKey','en',$this->properties),$this->properties),
            'tiny.nowrap' => $this->getSetting('nowrap'),
            'tiny.object_resizing' => $this->getSetting('object_resizing'),
            'tiny.path_options' => $this->getSetting('path_options'),
            'tiny.custom_plugins' => $this->getSetting('custom_plugins'),
            'tiny.remove_linebreaks' => $this->getSetting('remove_linebreaks'),
            'tiny.remove_redundant_brs' => $this->getSetting('remove_redundant_brs'),
            'tiny.removeformat_selector' => $this->getSetting('removeformat_selector'),
            'tiny.skin' => $this->getSetting('skin'),
            'tiny.skin_variant' => $this->getSetting('skin_variant'),
            'tiny.table_inline_editing' => $this->getSetting('table_inline_editing'),
            'tiny.editor_theme' => $this->getSetting('editor_theme'),
            'tiny.theme_advanced_blockformats' => $this->getSetting('theme_advanced_blockformats'),
            'tiny.theme_advanced_font_sizes' => $this->getSetting('theme_advanced_font_sizes'),
            'tiny.css_selectors' => $this->getSetting('css_selectors'),
//            'use_browser' => $this->context->getOption('use_browser',true,$this->properties),
        ];
    }
}
