import './bootstrap';

import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import flatpickr from 'flatpickr';
import { Calendar } from '@fullcalendar/core';

import 'flatpickr/dist/flatpickr.min.css';

import './books-data';
import './report';
import './borrowers-data';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;
window.FullCalendar = Calendar;

Alpine.start();

// Components
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#mapOne')) {
        import('./components/map')
            .then(module => module.initMap());
    }

    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1')
            .then(module => module.initChartOne());
    }

    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2')
            .then(module => module.initChartTwo());
    }

    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3')
            .then(module => module.initChartThree());
    }

    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6')
            .then(module => module.initChartSix());
    }

    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8')
            .then(module => module.initChartEight());
    }

    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13')
            .then(module => module.initChartThirteen());
    }

    if (document.querySelector('#calendar')) {
        import('./components/calendar-init')
            .then(module => module.calendarInit());
    }
});