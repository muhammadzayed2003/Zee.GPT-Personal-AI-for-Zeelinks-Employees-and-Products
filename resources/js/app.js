import Alpine from 'alpinejs';
import { marked } from 'marked';
import mermaid from 'mermaid';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.marked = marked;
window.mermaid = mermaid;
window.Chart = Chart;

mermaid.initialize({
    startOnLoad: false,
    theme: 'dark',
});

Alpine.start();