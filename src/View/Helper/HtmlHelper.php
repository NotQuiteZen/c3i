<?php

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * Html helper
 * @method  h1(string $text, array $options)
 * @method  h2(string $text, array $options)
 * @method  h3(string $text, array $options)
 * @method  h4(string $text, array $options)
 * @method  h5(string $text, array $options)
 * @method  button(string $text, array $options)
 * @method  image(string $text, array $options)
 */
class HtmlHelper extends Helper\HtmlHelper {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'meta' => '<meta{{attrs}}/>',
            'metalink' => '<link href="{{url}}"{{attrs}}/>',
            'link' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'mailto' => '<a href="mailto:{{url}}"{{attrs}}>{{content}}</a>',
            'image' => '<img src="{{url}}"{{attrs}}/>',
            'tableheader' => '<th{{attrs}}>{{content}}</th>',
            'tableheaderrow' => '<tr{{attrs}}>{{content}}</tr>',
            'tablecell' => '<td{{attrs}}>{{content}}</td>',
            'tablerow' => '<tr{{attrs}}>{{content}}</tr>',
            'block' => '<div{{attrs}}>{{content}}</div>',
            'blockstart' => '<div{{attrs}}>',
            'blockend' => '</div>',
            'tag' => '<{{tag}}{{attrs}}>{{content}}</{{tag}}>',
            'tagstart' => '<{{tag}}{{attrs}}>',
            'tagend' => '</{{tag}}>',
            'tagselfclosing' => '<{{tag}}{{attrs}}/>',
            'para' => '<p{{attrs}}>{{content}}</p>',
            'parastart' => '<p{{attrs}}>',
            'css' => '<link rel="{{rel}}" href="{{url}}"{{attrs}}/>',
            'style' => '<style{{attrs}}>{{content}}</style>',
            'charset' => '<meta charset="{{charset}}"/>',
            'ul' => '<ul{{attrs}}>{{content}}</ul>',
            'ol' => '<ol{{attrs}}>{{content}}</ol>',
            'li' => '<li{{attrs}}>{{content}}</li>',
            'h1' => '<h1{{attrs}}>{{content}}</h1>',
            'h2' => '<h2{{attrs}}>{{content}}</h2>',
            'h3' => '<h3{{attrs}}>{{content}}</h3>',
            'h4' => '<h4{{attrs}}>{{content}}</h4>',
            'h5' => '<h5{{attrs}}>{{content}}</h5>',
            'javascriptblock' => '<script{{attrs}}>{{content}}</script>',
            'javascriptstart' => '<script>',
            'javascriptlink' => '<script src="{{url}}"{{attrs}}></script>',
            'javascriptend' => '</script>',
        ],
    ];

    public function __call($method, $params) {

        $text = Hash::get($params, 0);
        $options = Hash::get($params, 1);

        return $this->formatTemplate($method, [
            'attrs' => $this->templater()->formatAttributes($options),
            'content' => $text,
        ]);
    }
}
