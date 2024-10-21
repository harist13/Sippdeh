<div id="map" style="width: 500px;">
  <?xml version="1.0" encoding="utf-8"?>
  <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
    @include('admin.peta-kaltim.regions.berau', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-timur', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.penajam-paser-utara', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.paser', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.mahakam-ulu', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-barat', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-kartanegara', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.samarinda', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.bontang', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.balikpapan', ['color' => '#2259A8'])
  </svg>
</div>

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
    padding: 8px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    z-index: 10;
    display: none; 
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('map');
    const tooltip = document.getElementById('tooltip');
    
    if (!mapElement || !tooltip) {
      console.error("Map or tooltip element not found");
      return; 
    }

    const groups = document.querySelectorAll('#map g.region');
    groups.forEach(group => {
      group.addEventListener('mouseover', event => onMouseOver({ event, group }));
      group.addEventListener('mouseout', event => onMouseOut());
    });

    function onMouseOver({ event, group }) {
      const paths = group.querySelectorAll('path');
      paths.forEach(path => path.classList.add('selected-region'));

      const mapRect = mapElement.getBoundingClientRect();
      const tooltipRect = tooltip.getBoundingClientRect();

      // atur posisi tooltipnya
      let left = event.clientX - mapRect.left;
      let top = event.clientY - mapRect.top;

      // cek apakah tooltip keluar dari batas map
      if (left + tooltipRect.width > mapRect.width) {
        left = mapRect.width - tooltipRect.width;
      }
      if (top + tooltipRect.height > mapRect.height) {
        top = mapRect.height - tooltipRect.height;
      }
      if (left < 0) {
        left = 0;
      }
      if (top < 0) {
        top = 0;
      }

      tooltip.style.left = `${left}px`;
      tooltip.style.top = `${top}px`;
      tooltip.style.display = 'block'; // Make the tooltip visible
    }

    function onMouseOut() {
      const paths = document.querySelectorAll('.selected-region');
      paths.forEach(path => path.classList.remove('selected-region'));
      tooltip.style.display = 'none'; // Hide the tooltip
    }
  });
</script>
