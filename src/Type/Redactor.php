<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * Redactor support
 */
class Redactor extends BaseRTE
{
    protected $override = 'redactor-overrides.js';

    /**
     * @inherit
     */
    public function getOptions()
    {
        $settings = [
            'direction',
            'lang',
            'minHeight',
            'autoresize',
            'linkAnchor',
            'linkEmail',
            'placeholder',
            'visual',
            'buttons',
            'buttonSource',
            'linkNofollow',
            'formattingTags',
            'colors',
            'typewriter',
            'buttonsHideOnMobile',
            'toolbarOverflow',
            'toolbarFixed',
            //'toolbarFixedTarget',
            //'toolbarFixedTopOffset',
            'activeButtons',
            'focus',
            'focusEnd',
            'scrollTarget',
            'enterKey',
            'cleanStyleOnEnter',
            'linkTooltip',
            'imageLink',
            'imagePosition',
            'buttonsHide',
            'showDimensionsOnResize',
            'tabindex',
            'shortcuts',
            'linkProtocol',
            'prefetch_ttl',
            'tabAsSpaces',
            'tabifier',
            'allowedTags',
            'cleanup',
            'convertDivs',
            'convertLinks',
            'deniedTags',
            'linebreaks',
            'linkSize',
            'advAttrib',
            'cleanSpaces',
            'pastePlainText',
            'paragraphize',
            'removeComments',
            'predefinedLinks',
            'removeEmptyTags',
            'replaceTags',
            'replaceStyles',
            'removeDataAttr',
            'removeAttr',
            'allowedAttr',
            'replaceDivs',
            'preSpaces',
            'uploadFields',
            'autosave',
            'interval',
            'eurekaUpload',
            'marginFloatLeft',
            'marginFloatRight',
            'linkResource',
            'browse_files',
            'searchImages',
            'dragUpload',
            'convertImageLinks',
            'convertVideoLinks',
            'dragImageUpload',
            'dragFileUpload',
            'imageEditable',
            'imageResizable',
            'limiter',
            'textexpander',
            'speechVoice',
            'speechRate',
            'speechPitch',
            'speechVolume',
            'counterWPM',
            'activeButtonsStates',
            'clipsJson',
            'formattingAdd',
            'shortcutsAdd',
            'buttonFullScreen',
            'predefinedLinks',
            'textexpander',
            'wym',
            'plugin_uploadcare',
            'uploadcare_pub_key',
            'uploadcare_locale',
            'uploadcare_crop',
            'uploadcare_tabs',
            'plugin_filemanager', 'plugin_fontcolor', 'plugin_fontfamily', 'plugin_fontsize', 'plugin_table',
            'plugin_textdirection', 'plugin_video', 'plugin_limiter',
            'plugin_breadcrumb', 'plugin_clips', 'plugin_contrast', 'plugin_counter', 'plugin_download',
            'plugin_imagepx', 'plugin_norphan', 'plugin_replacer', 'plugin_speek', 'plugin_wym', 'plugin_zoom',
            'plugin_baseurls',
            'baseurls_mode',
            'plugin_eureka',
            'plugin_eureka_shivie9',
            'additionalPlugins',
        ];

        foreach ($settings as $k) {
            $this->modx->setOption("redactor.{$k}", $this->getSetting($k));
        }

        return [
            'editor' => 'Redactor',
        ];

        // @TODO
        if($this->loadCodeMirror) {
            $options['codemirror'] = true;
            $options['codemirrorJSON'] = $this->modx->toJSON($this->getCodeMirrorOptions());
            $options['plugin_files'] .= '<link rel="stylesheet" type="text/css" href="' . $this->assetsUrl . 'lib/codemirror/codemirror.imperavi.css' . '" />';
            $codemirrorTheme = $this->getOption('codemirror.theme', null, 'default');
            if(!empty($codemirrorTheme) && $codemirrorTheme !== 'default') $options['plugin_files'] .= '<link rel="stylesheet" type="text/css" href="' . $this->assetsUrl . "lib/codemirror/theme/$codemirrorTheme.css" . '" />';
            $pluginFiles[] = $this->assetsUrl . 'lib/codemirror/codemirror.imperavi.min.js'; // imperavi uses some sort of custom build or something
        }
        elseif($this->loadAce) {
            $plugins[] = 'syntax';
            $script = <<<HERE
<script>try {ace} catch(e) { document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.9/ace.js"><\/script>') }</script>
HERE;
            $this->modx->regClientStartupHTMLBlock($script);
            if(!$this->greedyPlugins) $pluginFiles[] = $this->assetsUrl . 'lib/syntax.min.js';
            $options['aceTheme'] = 'ace/theme/' . ($this->getOption('ace.theme', null, 'chrome'));
            $options['aceFontSize'] = ($this->getOption('ace.font_size', null, '13px'));
            $options['aceUseSoftTabs'] = ($this->getBooleanOption('ace.soft_tabs', null, true));
            $options['aceUseWrapMode'] = ($this->getBooleanOption('ace.word_wrap', null, false));
            $options['aceHighlightActiveLine'] = ($this->getBooleanOption('redactor.syntax_highlightActiveLine', null, true));
            $options['aceMode'] = ($this->getOption('redactor.syntax_aceMode', null, 'ace/mode/html'));
            $options['aceReadOnly'] = ($this->getBooleanOption('redactor.syntax_readOnly', null, false));
            $options['aceTabSize'] = ($this->getOption('ace.tab_size', null, 4));
            $options['aceOfflineSource'] = $this->assetsUrl . 'lib/ace/ace.min.js';
        }
    }
}
