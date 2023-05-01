// assets/controllers/chart_controller.js
import { Controller } from 'stimulus';
import Chart  from '@symfony/ux-chartjs';
import 'chart.js/auto';
export default class extends Controller {
    static targets = ['canvas'];
    connect() {
        const ctx = this.canvasTarget.getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [
                    {
                        label: 'My Dataset',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                        ],
                    },
                ],
            },
        });
    }
}