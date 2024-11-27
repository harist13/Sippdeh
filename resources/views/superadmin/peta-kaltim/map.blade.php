<div id="map" style="width: 500px;">
    <?xml version="1.0" encoding="utf-8"?>
    <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
        @include('Superadmin.peta-kaltim.regions.berau', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.kutai-timur', ['color' => '#F9D926'])
        @include('Superadmin.peta-kaltim.regions.penajam-paser-utara', ['color' => '#F9D926'])
        @include('Superadmin.peta-kaltim.regions.paser', ['color' => '#F9D926'])
        @include('Superadmin.peta-kaltim.regions.mahakam-ulu', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.kutai-barat', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.kutai-kartanegara', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.samarinda', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.bontang', ['color' => '#2259A8'])
        @include('Superadmin.peta-kaltim.regions.balikpapan', ['color' => '#2259A8'])
    </svg>
</div>

@push('styles')
	<style>
		.selected-region {
			opacity: 0.8;
		}

		path {
			transition: opacity 0.1s;
		}

		#tooltip {
			position: absolute;
			background-color: white;
			padding: 12px;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
			z-index: 10;
			display: none;
			min-width: 200px;
			pointer-events: none;
		}

		#tooltip .kabupaten-title {
			font-weight: 600;
			font-size: 1rem;
			margin-bottom: 8px;
			color: #1a1a1a;
			border-bottom: 1px solid #e5e7eb;
			padding-bottom: 4px;
		}

		#tooltip .info-grid {
			display: grid;
			grid-template-columns: auto 1fr;
			gap: 8px;
			font-size: 0.9rem;
		}

		#tooltip .label {
			color: #4b5563;
		}

		#tooltip .value {
			text-align: right;
			font-weight: 500;
			color: #1a1a1a;
		}
	</style>
@endpush

@push('scripts')
<script>
    const suaraPerKabupaten = @json($suaraPerKabupaten);
    const kabupatens = @json($kabupatens);
    const paslon = @json($calon);
	
	document.addEventListener('DOMContentLoaded', () => {
		const mapElement = document.getElementById('map');
		const tooltip = document.getElementById('tooltip');
		const kabupatenName = document.getElementById('kabupaten-name');
		const suaraPaslon1 = document.getElementById('suara-paslon1');
		const suaraPaslon2 = document.getElementById('suara-paslon2');

		if (!mapElement || !tooltip) {
			console.error("Map or tooltip element not found");
			return;
		}

		const groups = document.querySelectorAll('#map g.region');

		function calculateTooltipPosition(event) {
			const mapRect = mapElement.getBoundingClientRect();
			const tooltipRect = tooltip.getBoundingClientRect();

			let x = event.clientX - mapRect.left;
			let y = event.clientY - mapRect.top;

			const padding = 15;

			let left = x + padding;
			let top = y + padding;

			if (left + tooltipRect.width > mapRect.width) {
				left = x - tooltipRect.width - padding;
			}

			if (top + tooltipRect.height > mapRect.height) {
				top = y - tooltipRect.height - padding;
			}

			left = Math.max(0, Math.min(left, mapRect.width - tooltipRect.width));
			top = Math.max(0, Math.min(top, mapRect.height - tooltipRect.height));

			return { left, top };
		}

		function onMouseMove({ event }) {
			if (tooltip.style.display === 'block') {
				const { left, top } = calculateTooltipPosition(event);
				tooltip.style.left = `${left}px`;
				tooltip.style.top = `${top}px`;
			}
		}

		function onMouseOver({ event, group }) {
			const paths = group.querySelectorAll('path');
			paths.forEach(path => path.classList.add('selected-region'));

			const kabupatenId = group.getAttribute('data-kabupaten-id');
			const kabupaten = kabupatens.find(k => k.id == kabupatenId);

			if (kabupaten && suaraPerKabupaten[kabupatenId]) {
				kabupatenName.textContent = kabupaten.nama;

				const suaraPaslon1Value = suaraPerKabupaten[kabupatenId][paslon[0]?.id] || 0;
				const suaraPaslon2Value = suaraPerKabupaten[kabupatenId][paslon[1]?.id] || 0;

				// Display percentage of votes
				suaraPaslon1.textContent = `${suaraPaslon1Value}%`;

				if (suaraPaslon2) {
					suaraPaslon2.textContent = `${suaraPaslon2Value}%`;
				}
			}

			const { left, top } = calculateTooltipPosition(event);
			tooltip.style.left = `${left}px`;
			tooltip.style.top = `${top}px`;
			tooltip.style.display = 'block';
		}

		function onMouseOut() {
			const paths = document.querySelectorAll('.selected-region');
			paths.forEach(path => path.classList.remove('selected-region'));
			tooltip.style.display = 'none';
		}

		// Color constants
		const warnaAbuAbu = '#cccccc'; // Default color if no votes
		const warnaPaslon1 = '#3560A0'; // Color for candidate 1
		const warnaPaslon2 = '#F9D926'; // Color for candidate 2

		// Function to set region color based on vote count
		function setWarnaWilayah(group, kabupatenId) {
			const suaraPaslon1 = suaraPerKabupaten[kabupatenId]?.[paslon[0]?.id] || 0;
			const suaraPaslon2 = suaraPerKabupaten[kabupatenId]?.[paslon[1]?.id] || 0;

			let color;
			if (suaraPaslon1 === 0 && suaraPaslon2 === 0) {
				color = warnaAbuAbu; // No votes, use grey
			} else if (suaraPaslon1 > suaraPaslon2) {
				color = warnaPaslon1; // Candidate 1 leading, blue color
			} else if (suaraPaslon2 > suaraPaslon1) {
				color = warnaPaslon2; // Candidate 2 leading, yellow color
			} else {
				color = warnaAbuAbu; // If votes are equal, remain grey
			}

			// Set color on SVG element
			group.querySelectorAll('path').forEach(path => path.setAttribute('fill', color));
		}

		// Apply colors based on votes for each region
		groups.forEach(group => {
			const kabupatenId = group.getAttribute('data-kabupaten-id');
			setWarnaWilayah(group, kabupatenId);
		});

		// Event listeners for interaction
		groups.forEach(group => {
			group.addEventListener('mouseover', event => onMouseOver({ event, group }));
			group.addEventListener('mouseout', event => onMouseOut());
			group.addEventListener('mousemove', event => onMouseMove({ event }));
		});
	});
</script>
@endpush