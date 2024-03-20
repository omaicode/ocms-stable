<?php
namespace App\Form\Fields;

use App\Form\Fields\Traits\HasPlainInput;
use Illuminate\Support\Str;

class CodeMirror extends BaseField
{
    use HasPlainInput;

    protected $view = 'admin.form.fields.code-mirror';
    protected static $css = [
        '/vendor/codemirror/codemirror.css',
        '/vendor/codemirror/theme/base16-light.css',
    ];

    protected static $js = [
        '/vendor/codemirror/codemirror.js',
        '/vendor/codemirror/addon/selection/active-line.js',
        '/vendor/codemirror/addon/edit/matchbrackets.js',
        '/vendor/codemirror/addon/fold/foldcode.js',
        '/vendor/codemirror/addon/fold/foldgutter.js',
        '/vendor/codemirror/addon/fold/brace-fold.js',
        '/vendor/codemirror/addon/fold/xml-fold.js',
        '/vendor/codemirror/addon/fold/indent-fold.js',
        '/vendor/codemirror/addon/fold/markdown-fold.js',
        '/vendor/codemirror/addon/fold/comment-fold.js',
        '/vendor/codemirror/addon/mode/overlay.js',
        '/vendor/codemirror/mode/xml/xml.js',
        '/vendor/codemirror/mode/javascript/javascript.js',
        '/vendor/codemirror/mode/css/css.js',
        '/vendor/codemirror/mode/htmlmixed/htmlmixed.js',
    ];

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->id = $this->id . '_' . substr(Str::uuid(), 0, 8);
        $this->setScript($this->script());
        $this->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render();
    }

    protected function script()
    {
        $id = $this->id;

        return <<<SCRIPT
        CodeMirror.defineMode('shortcode', function (config, parserConfig) {
            return  CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "htmlmixed"), {
                startState: function () {
                    return {
                        tokenizeContent: null,
                        insideShortcode: false
                    };
                },
                token: function (stream, state) {
                    if (stream.match('[partial')) {
                        state.insideShortcode = true;
                        state.tokenizeContent = tokenizeAttributes('partial');
                        return 'shortcode';
                    } else if (stream.match('[sub-partial')) {
                        state.insideShortcode = true;
                        state.tokenizeContent = tokenizeAttributes('sub-partial');
                        return 'shortcode';
                    } else if (stream.match('[/partial]') || stream.match('[/sub-partial]')) {
                        state.insideShortcode = false;
                        return 'shortcode';
                    } else if (state.insideShortcode) {
                        return state.tokenizeContent(stream, state);
                    } else {
                        stream.next();
                        return null;
                    }
                }
            });

            function tokenizeAttributes(tag) {
                return function (stream, state) {
                    while (!stream.eol()) {
                        if (stream.match(']')) {
                            state.tokenizeContent = tokenizeContent(tag);
                            return 'shortcode';
                        }

                        stream.eatSpace();

                        if (stream.match(/\w+/)) {
                            return 'shortcode';
                        } else if (stream.match(/".*?"/)) {
                            return 'string';
                        } else if (stream.match(/'.*?'/)) {
                            return 'string';
                        } else {
                            stream.next();
                        }
                    }
                    return null;
                };
            }

            function tokenizeContent(tag) {
                return function (stream, state) {
                    while (!stream.eol()) {
                        if (stream.match('[' + (tag === 'partial' ? '/' : '') + tag + ']')) {
                            state.tokenizeContent = null;
                            state.insideShortcode = false;
                            return 'shortcode';
                        } else {
                            stream.next();
                        }
                    }
                    return null;
                };
            }
        });


        var codeMirror = CodeMirror.fromTextArea(document.getElementById("{$id}"), {
            mode: 'shortcode',
            lineNumbers: true,
            lineWrapping: true,
            styleActiveLine: true,
            matchBrackets: true,
            extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            matchTags: {bothTags: true}
        });

        codeMirror.setOption("theme", 'base16-light');
        codeMirror.on('change', function() {
            codeMirror.save();
        });
        SCRIPT;
    }
}
