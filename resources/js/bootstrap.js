import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;

Alpine.directive('tooltip', (el, { expression }) => {
    el.title = expression;
});
