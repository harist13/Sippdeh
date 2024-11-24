<div class="p-4">
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-white p-4 rounded-lg text-center">
            <h4 class="font-semibold text-gray-600">Total Suara Masuk</h4>
            <p class="text-2xl font-bold text-[#3560A0]">{{ $dptAbstainData['total_suara_masuk'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg text-center">
            <h4 class="font-semibold text-gray-600">Total Abstain</h4>
            <p class="text-2xl font-bold text-[#1E3A8A]">{{ $dptAbstainData['total_abstain'] }}</p>
        </div>
    </div>
    <div class="mb-4 relative">
        <canvas id="participationChart"></canvas>
        <!-- Legend di pojok kiri bawah -->
        <div class="absolute mt-5 left-2 bg-white p-2 rounded-lg shadow">
            <div class="flex flex-col">
                <div class="flex items-center mb-1">
                    <div class="w-4 h-4 bg-[#66AFFF] mr-2"></div>
                    <span class="text-sm">Suara Masuk</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-[#004999] mr-2"></div>
                    <span class="text-sm">Abstain</span>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
@endassets

@script
<script>
    console.log('pie');
    function initParticipationChart() {
        const pieData = @json($dptAbstainData);
        const colors = ['#66AFFF', '#004999'];

        const ctx = document.getElementById('participationChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pieData.labels,
                datasets: [{
                    data: pieData.percentages,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Initialize chart when component loads
    setTimeout(() => initParticipationChart(), 0);
</script>
@endscript