import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

// flatpickr

// FullCalendar
import { Calendar } from '@fullcalendar/core';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';


window.Alpine = Alpine;
window.ApexCharts = ApexCharts
window.FullCalendar = Calendar;
window.Swiper = Swiper;
Alpine.start();

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

    // Calendar init
    if (document.querySelector('#calendar')) {
        import('./components/calendar-init').then(module => module.calendarInit());
    }
    // Latest Marketing Assets
    if (document.querySelector('.mySwiper')) {
        new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: ".swiper-button-next-custom",
                 prevEl: ".swiper-button-prev-custom",
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 },
            },
        });
    }

    // Recommended Assets
    if (document.querySelector('.recommendSwiper')) {
        new Swiper(".recommendSwiper", {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: ".swiper-button-next-recommend",
                 prevEl: ".swiper-button-prev-recommend",

            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 },
            },
        });
    }
});
