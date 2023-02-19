import '../vendor/highlight/monokai.min.css';
import '../vendor/highlight/highlightjs-copy.min.css';
import hljs from 'highlight.js';
import CopyButtonPlugin from '../vendor/highlight/highlight-js-copy';

hljs.addPlugin(new CopyButtonPlugin());

window.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('article pre code').forEach((element) => {
        hljs.highlightElement(element);
    });
});

