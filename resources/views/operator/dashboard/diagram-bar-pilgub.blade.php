<div class="chart-container">
    <div class="canvas-wrapper">
        <canvas id="voteCountChart" width="800" height="300"></canvas>
    </div>
</div>

@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
@endassets

@script
	<script>
        console.log('Init');

		function initDiagramBar() {
            setTimeout(() => {
                window.gubernurData = @json($data);
    
                const ctx = document.getElementById('voteCountChart');
                let isHovering = false;
    
                const chartData = {
                    title: "Jumlah Perolehan Suara Gubernur Per Kabupaten/Kota",
                    data: window.gubernurData
                };
    
                const MAX_VALUE = chartData.data.maxRange;
                const STEP_SIZE = MAX_VALUE / 5;
    
                let chart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData.data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 9 },
                                    maxRotation: 0,
                                    minRotation: 0,
                                    autoSkip: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                max: MAX_VALUE,
                                ticks: {
                                    stepSize: STEP_SIZE,
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                },
                                grid: { color: '#E0E0E0' }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const index = context.dataIndex;
                                        const totalSuarahSah = chartData.data.totalSuarahSah[index];
                                        const percentage = totalSuarahSah > 0 ? ((value / totalSuarahSah) * 100).toFixed(1) : 0;
                                        return `${context.dataset.label}: ${value.toLocaleString()} suara (${percentage}%)`;
                                    }
                                }
                            },
                            legend: {
                                display: true,
                                position: 'bottom',
                                align: 'center',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15,
                                    font: { size: 12 }
                                }
                            }
                        },
                        layout: {
                            padding: { left: 10, right: 10, top: 10, bottom: 10 }
                        },
                        onHover: (event, activeElements) => {
                            const previousState = isHovering;
                            isHovering = activeElements.length > 0;
                            
                            if (previousState !== isHovering) {
                                chart.update('none');
                            }
                        },
                        animation: {
                            duration: 1,
                            onComplete: function(animation) {
                                if (isHovering) return;
    
                                const chartInstance = animation.chart;
                                const ctx = chartInstance.ctx;
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.font = 'bold 14px Arial';
                                
                                chartInstance.data.datasets.forEach((dataset, datasetIndex) => {
                                    const meta = chartInstance.getDatasetMeta(datasetIndex);
                                    
                                    meta.data.forEach((bar, index) => {
                                        const data = dataset.data[index];
                                        const totalSuarahSah = chartData.data.totalSuarahSah[index];
                                        let percentage;
                                        
                                        // Hitung persentase berdasarkan total suara sah
                                        if (totalSuarahSah > 0) {
                                            percentage = ((data / totalSuarahSah) * 100).toFixed(1);
                                        } else {
                                            percentage = 0;
                                        }
                                        
                                        const barWidth = bar.width;
                                        const barHeight = bar.height;
                                        const barX = bar.x;
                                        const barY = bar.y;
                                        
                                        if (barHeight > 30) {
                                            ctx.save();
                                            ctx.translate(barX, barY + barHeight/2);
                                            ctx.rotate(-Math.PI / 2);
                                            ctx.fillStyle = '#FFFFFF';
                                            
                                            const percentageText = `${percentage}%`;
                                            
                                            ctx.fillText(percentageText, 0, 0);
                                            ctx.restore();
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
            }, 0);
        }

        initDiagramBar();
	</script>
@endscript