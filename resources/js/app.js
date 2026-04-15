import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';
import { initFlowbite } from 'flowbite';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('livewire:navigated', () => {
    initFlowbite();
});